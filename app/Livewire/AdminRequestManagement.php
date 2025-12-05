<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestConfirmationMail; 
use Carbon\Carbon;
use App\Mail\RequestApprovedMail; 
use App\Mail\RequestRejectedMail; 

class AdminRequestManagement extends Component
{
    // Variáveis de estado para mensagens de alerta
    public $message = null;
    public $messageType = null;
    public $pendingRequestsCount = 0;
    public $activeRequestsCount = 0;
    public $last30DaysRequestsCount = 0;
    public $deliveredTodayCount = 0;

    public function mount()
    {
        if (!Auth::check()) {
            abort(403, 'Acesso negado.');
        }
    }
    




   public function approveRequest(Request $request)
    {
        if ($request->status !== 'Pending') {
            $this->message = 'A requisição já foi processada.';
            $this->messageType = 'error';
            return;
        }

        try {
            $request->update(['status' => 'Approved']);
            
            // DISPARO DO E-MAIL ESPECÍFICO DE APROVAÇÃO
            Mail::to($request->user->email)->send(new RequestApprovedMail($request));

            $this->message = 'Requisição Nº ' . $request->request_number . ' APROVADA. E-mail de confirmação enviado para ' . $request->user->email . '.';
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message = 'Erro ao aprovar e enviar e-mail: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }


    /**
     * Rejeita uma requisição pendente. (REINTEGRADO)
     */
    public function rejectRequest(Request $request)
    {
        if ($request->status !== 'Pending') {
            $this->message = 'A requisição já foi processada.';
            $this->messageType = 'error';
            return;
        }

        try {
            $request->update(['status' => 'Rejected']);
            
            // DISPARO DO E-MAIL ESPECÍFICO DE REJEIÇÃO
            Mail::to($request->user->email)->send(new RequestRejectedMail($request));

            $this->message = 'Requisição Nº ' . $request->request_number . ' REJEITADA.';
            $this->messageType = 'success';
        } catch (\Exception $e) {
            $this->message = 'Erro ao rejeitar e enviar e-mail: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }
  
    public function confirmReceipt(Request $request)
    {
        if ($request->status !== 'Approved') {
            $this->message = 'O livro ainda não foi aprovado para empréstimo ou já foi devolvido.';
            $this->messageType = 'error';
            return;
        }

        try {
            $request->update([
                'status' => 'Received',
                'received_at' => Carbon::now(),
            ]);
            
            // Cálculo dos dias decorridos (Requisito)
            $daysElapsed = $request->requested_at ? $request->received_at->diffInDays($request->requested_at) : 0;
            
            $alertMessage = 'Devolução do livro ' . $request->book->title . ' (Nº ' . $request->request_number . ') CONFIRMADA. Dias de empréstimo: ' . $daysElapsed . '.';
            $this->message = $alertMessage;
            $this->messageType = 'success';

        } catch (\Exception $e) {
            $this->message = 'Erro ao confirmar a receção: ' . $e->getMessage();
            $this->messageType = 'error';
        }
    }


    // Método que calcula as métricas (usado pelo Admin)
    public function updateMetrics()
    {
        $requestsQuery = Request::with('user', 'book');
        $allRequests = $requestsQuery->get(); 

        // METRICS CALCULATION 
        $this->activeRequestsCount = $allRequests->whereIn('status', ['Pending', 'Approved'])->count();
        $this->last30DaysRequestsCount = $allRequests->where('requested_at', '>=', Carbon::now()->subDays(30))->count();
        $this->deliveredTodayCount = $allRequests->where('received_at', '>=', Carbon::today())->count();
        
        // Atualiza a contagem fixa de pendentes
        $this->pendingRequestsCount = $allRequests->where('status', 'Pending')->count(); 
    }
    
    // ... (approveRequest, rejectRequest, confirmReceipt omitidos) ...

    public function render()
    {
        // Chama o método de métricas aqui para que ele sempre atualize o estado no render
        $this->updateMetrics(); 
        
        $userRole = Auth::user()?->role;
        $isAdmin = $userRole === 'Admin';
        
        $requestsQuery = Request::with(['user', 'book'])
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected', 'Received')")
            ->orderByDesc('requested_at');

        // REQUISITO: Se for Cidadão, filtra apenas as dele
        if ($userRole === 'Cidadao') {
            $requestsQuery->where('user_id', Auth::id());
        }
        
        $requests = $requestsQuery->get();
            
        return view('livewire.admin-request-management', [
            'requests' => $requests,
            'isAdmin' => $isAdmin, // Passa a variável de controle para a view
            'isCitizen' => $userRole === 'Cidadao'
        ]);
    }
}