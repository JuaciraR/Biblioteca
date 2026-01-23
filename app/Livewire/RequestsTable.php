<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use App\Models\AvailabilityAlert;
use App\Mail\BookAvailableMail; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Traits\Trackable;

class RequestsTable extends Component
{
    use Trackable;

    // Propriedades de métricas do Admin
    public $pendingRequestsCount = 0;
    public $activeRequestsCount = 0;
    public $last30DaysRequestsCount = 0;
    public $deliveredTodayCount = 0;

    // Propriedades para a view
    public $isAdmin = false;
    public $message;
    public $messageType;

    // Escuta o evento 'requestCreated' para atualizar métricas
    protected $listeners = ['requestCreated' => 'updateMetrics'];

    public function mount()
    {
        $this->isAdmin = Auth::user()?->role === 'Admin';
        $this->updateMetrics();

        // Inicializa mensagens para evitar Undefined variable
        $this->message = null;
        $this->messageType = null;
    }

    // --- ADMIN ACTIONS ---
    public function approve($id)
    {
        if (!$this->isAdmin) abort(403);

        $req = Request::findOrFail($id);

        if ($req->status !== 'Pending') {
            $this->setMessage('Only Pending requests can be Approved.', 'error');
            return;
        }

        $req->update(['status' => 'Approved']);
        $this->setMessage('Request #' . $req->request_number . ' approved.', 'success');

        $this->logAudit('Requests', $id, "Admin Approved loan request #{$req->request_number}");
        $this->updateMetrics();
    }

    public function reject($id)
    {
        if (!$this->isAdmin) abort(403);

        $req = Request::findOrFail($id);

        if ($req->status !== 'Pending') {
            $this->setMessage('Only Pending requests can be Rejected.', 'error');
            return;
        }

        $req->update(['status' => 'Rejected']);
        $this->setMessage('Request #' . $req->request_number . ' rejected.', 'success');

        $this->logAudit('Requests', $id, "Admin Rejected loan request #{$req->request_number}");
        $this->updateMetrics();
    }

    public function confirmReception($id)
    {
        if (!$this->isAdmin) abort(403);

        $req = Request::with('book')->findOrFail($id);

        if ($req->status !== 'Approved') {
            $this->setMessage('Reception can only be confirmed for Approved requests.', 'error');
            return;
        }

        try {
            $req->update([
                'status' => 'Received',
                'received_at' => Carbon::now(),
            ]);

            $this->logAudit('Requests', $id, "Admin confirmed reception of book for request #{$req->request_number}");

            // Alertas para usuários inscritos
            $alerts = AvailabilityAlert::where('book_id', $req->book_id)->with('user')->get();

            foreach ($alerts as $alert) {
                if ($alert->user?->email) {
                    Mail::to($alert->user->email)->send(new BookAvailableMail($req->book));
                }
                $alert->delete();
            }

            $msg = 'Request #' . $req->request_number . ' marked as received.';
            if ($alerts->count() > 0) {
                $msg .= ' ' . $alerts->count() . ' users were notified by email.';
            }

            $this->setMessage($msg, 'success');

        } catch (\Exception $e) {
            Log::error('Failed to process availability alerts: ' . $e->getMessage());
            $this->setMessage('Book received, but email notifications failed.', 'warning');
        }

        $this->updateMetrics();
    }

    // --- MÉTODOS AUXILIARES ---
    private function setMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->messageType = $type;
        session()->flash($type, $message); // Mantém compatibilidade com a view
    }

    public function updateMetrics()
    {
        $requestsQuery = Request::with('user', 'book');
        $allRequests = $requestsQuery->get();

        $this->pendingRequestsCount = $allRequests->where('status', 'Pending')->count();
        $this->activeRequestsCount = $allRequests->whereIn('status', ['Pending', 'Approved'])->count();
        $this->last30DaysRequestsCount = $allRequests->where('requested_at', '>=', Carbon::now()->subDays(30))->count();
        $this->deliveredTodayCount = $allRequests->where('received_at', '>=', Carbon::today())->count();
    }

    public function render()
    {
        $this->updateMetrics();

        $requestsQuery = Request::with(['user', 'book'])
            ->orderByRaw("CASE 
                WHEN status = 'Pending' THEN 1 
                WHEN status = 'Approved' THEN 2 
                WHEN status = 'Received' THEN 3 
                ELSE 4 END")
            ->orderByDesc('requested_at');

        if (! $this->isAdmin) {
            $requestsQuery->where('user_id', Auth::id());
        }

        return view('livewire.requests-table', [
            'requests' => $requestsQuery->get(),
            'isAdmin' => $this->isAdmin,
            'message' => $this->message,
            'messageType' => $this->messageType,
            'pendingRequestsCount' => $this->pendingRequestsCount,
            'activeRequestsCount' => $this->activeRequestsCount,
            'last30DaysRequestsCount' => $this->last30DaysRequestsCount,
            'deliveredTodayCount' => $this->deliveredTodayCount,
        ]);
    }
}
