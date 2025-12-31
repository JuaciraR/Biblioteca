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

class RequestsTable extends Component
{
    // Propriedades para os indicadores/métricas de Admin
    public $activeRequestsCount = 0;
    public $last30DaysRequestsCount = 0;
    public $deliveredTodayCount = 0;

    // Escuta o evento 'requestCreated' para atualizar as métricas
    protected $listeners = ['requestCreated' => 'updateMetrics'];

    public function mount()
    {
        $this->updateMetrics();
    }

    // --- ADMIN ACTIONS ---
    
    /**
     * Aprova uma requisição pendente.
     */
    public function approve($id)
    {
        if (Auth::user()?->role !== 'Admin') abort(403);

        $req = Request::findOrFail($id);

        if ($req->status !== 'Pending') {
             session()->flash('error', 'Only Pending requests can be Approved.');
             return;
        }

        $req->update(['status' => 'Approved']);
        session()->flash('success', 'Request #' . $req->request_number . ' approved.');
        $this->updateMetrics();
    }

    /**
     * Rejeita uma requisição pendente.
     */
    public function reject($id)
    {
        if (Auth::user()?->role !== 'Admin') abort(403);

        $req = Request::findOrFail($id);

        if ($req->status !== 'Pending') {
             session()->flash('error', 'Only Pending requests can be Rejected.');
             return;
        }

        $req->update(['status' => 'Rejected']);
        session()->flash('success', 'Request #' . $req->request_number . ' rejected.');
        $this->updateMetrics();
    }

    /**
     * Confirma a boa receção (devolução) do livro pelo Cidadão.
     */
     public function confirmReception($id)
    {
        if (Auth::user()?->role !== 'Admin') abort(403);

        // We load the 'book' relation to ensure data is available for the email template
        $req = Request::with('book')->findOrFail($id);

        if ($req->status !== 'Approved') {
            session()->flash('error', 'Reception can only be confirmed for Approved requests.');
            return;
        }

        try {
            // 1. Update request status (This makes the book technically available in the logic)
            $req->update([
                'status' => 'Received',
                'received_at' => Carbon::now(),
            ]);

            // --- CHALLENGE 3 LOGIC (ALERTS) ---
            
            // 2. Find all users who subscribed to an alert for this specific book
            $alerts = AvailabilityAlert::where('book_id', $req->book_id)->with('user')->get();

            Log::info("Book {$req->book_id} returned. Alerts found: " . $alerts->count());

            foreach ($alerts as $alert) {
                // 3. Send the notification email if user and email exist
                if ($alert->user && $alert->user->email) {
                    Mail::to($alert->user->email)->send(new BookAvailableMail($req->book));
                    Log::info("Availability alert email sent to: " . $alert->user->email);
                }
                
                // 4. Remove the alert from database (one-time notification completed)
                $alert->delete();
            }

            // --- END OF CHALLENGE 3 LOGIC ---
            
            $daysElapsed = $req->requested_at ? $req->received_at->diffInDays($req->requested_at) : 0;
            
            $msg = 'Request #' . $req->request_number . ' marked as received.';
            if ($alerts->count() > 0) {
                $msg .= ' ' . $alerts->count() . ' users were notified by email.';
            }

            session()->flash('success', $msg);

        } catch (\Exception $e) {
            Log::error('Failed to process availability alerts: ' . $e->getMessage());
            session()->flash('warning', 'Book received, but email notifications failed.');
        }

        $this->updateMetrics();
    }
   

    /**
     * Atualiza os indicadores do Admin.
     */
    public function updateMetrics()
    {
        $requestsQuery = Request::with('user', 'book');
        
        // Cidadão não vê estes indicadores, mas o filtro de role é aplicado no render.
        $allRequests = $requestsQuery->get();

        // Cálculo dos Indicadores
        $this->activeRequestsCount = $allRequests->whereIn('status', ['Pending', 'Approved'])->count();
        $this->last30DaysRequestsCount = $allRequests->where('requested_at', '>=', Carbon::now()->subDays(30))->count();
        $this->deliveredTodayCount = $allRequests->where('received_at', '>=', Carbon::today())->count();
    }

    public function render()
    { 
         $this->updateMetrics();

        $requestsQuery = Request::with('user', 'book')->latest('id');

        // Regra de Negócio: Cidadão vê apenas as suas requisições.
        if (Auth::user()?->role === 'Cidadao') {
            $requestsQuery->where('user_id', Auth::id());
        }
        
        $requests = $requestsQuery->get();
        
        return view('livewire.requests-table', [
            'requests' => $requests,
        ]);
    }
}