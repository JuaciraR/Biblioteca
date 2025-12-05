<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
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

        $req = Request::findOrFail($id);

        // Só pode confirmar se o estado estiver 'Approved' (livro foi levantado)
        if ($req->status !== 'Approved') {
            session()->flash('error', 'Reception can only be confirmed for Approved requests.');
            return;
        }

        $req->update([
            'status' => 'Received', // Novo estado final
            'received_at' => Carbon::now(),
        ]);
        
        // Regra de Negócio: calcular os dias decorridos (requested_at -> received_at)
        $daysElapsed = $req->requested_at ? $req->received_at->diffInDays($req->requested_at) : 0;
        
        session()->flash('success', 'Request #' . $req->request_number . ' marked as received. Days elapsed: ' . $daysElapsed . '.');
        $this->updateMetrics();
    }
    
    // --- METRICS & QUERY ---

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