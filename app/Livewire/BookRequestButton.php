<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Book;
use App\Models\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestConfirmationMail; 
use App\Models\AvailabilityAlert;
use App\Traits\Trackable;

class BookRequestButton extends Component
{
    use Trackable;

    public Book $book;
     public $hasAlert = false;
    
    public bool $isAvailable = true; 
    public $maxPendingBooks = 3; 
    public $userPendingRequestsCount = 0; 
    public $canRequest = false; 

    protected $listeners = ['requestCreated' => 'checkAvailability']; 

    public function mount($book)
    {
        // Garante que o Model Book seja resolvido corretamente
        $this->book = $book instanceof Book ? $book : Book::findOrFail($book); 
        $this->checkAvailability();
          $this->checkAlertStatus();
    }
    
    public function checkAvailability()
    {
        if (!Auth::check()) {
            $this->canRequest = false;
            return;
        }

        $userRole = Auth::user()->role;
        
        $this->isAvailable = $this->book->isAvailableForRequest();

        if ($userRole === 'Cidadao') {
            $this->userPendingRequestsCount = Request::where('user_id', Auth::id())
                ->whereIn('status', ['Pending', 'Approved'])
                ->count();
            
            $canUserRequest = $this->userPendingRequestsCount < $this->maxPendingBooks;
            $this->canRequest = $canUserRequest && $this->isAvailable;
        } elseif ($userRole === 'Admin') {
            $this->canRequest = $this->isAvailable;
        } else {
             $this->canRequest = false;
        }
    }

     public function requestBook()
{
    if (!Auth::check()) abort(403);

    // Validação de Stock (Requisito 5 do PEST)
    if ($this->book->stock <= 0) {
        $this->dispatch('request-notification', message: 'No stock available', type: 'error');
        return;
    }
    
    $this->checkAvailability();
    
    if (!$this->canRequest) {
        $msg = '';
        $isCitizen = Auth::user()->role === 'Cidadao';

        if (!$this->isAvailable) {
            $msg = 'Request blocked. The book "' . $this->book->title . '" is currently in use.';
        } elseif ($isCitizen && $this->userPendingRequestsCount >= $this->maxPendingBooks) {
            $msg = 'Limit of ' . $this->maxPendingBooks . ' books reached.';
        }
        
        $this->dispatch('request-notification', message: $msg, type: 'error'); 
        return; 
    }

    try {
        DB::beginTransaction();

        $lastRequest = Request::orderByDesc('request_number')->first();
        $nextRequestNumber = ($lastRequest ? $lastRequest->request_number : 0) + 1;

        $request = Request::create([
            'user_id' => Auth::id(), 
            'book_id' => $this->book->id,
            'status' => 'Pending',
            'requested_at' => Carbon::now(), 
            'due_date' => Carbon::now()->addDays(Book::LOAN_DAYS),        
            'request_number' => $nextRequestNumber,
        ]);

        DB::commit();

        // --- GATILHO DE LOG (Exigência do Menu Logs) ---
       $this->logAudit(
                'Requests', 
                $request->id, 
                "Created book request #{$nextRequestNumber} for: {$this->book->title}"
            );

        // Envio de emails e notificações
        $this->isAvailable = false;
        $this->dispatch('request-notification', message: 'Submitted successfully!', type: 'success'); 
        $this->dispatch('requestCreated');

    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('request-notification', message: 'Error: ' . $e->getMessage(), type: 'error');
    }

    
}


       public function checkAlertStatus()
    {
        if (Auth::check()) {
            $this->hasAlert = AvailabilityAlert::where('user_id', Auth::id())
                ->where('book_id', $this->book->id)
                ->exists();
        }
    }
     public function subscribeToAlert()
    {
        if (!Auth::check()) return;

        AvailabilityAlert::firstOrCreate([
            'user_id' => Auth::id(),
            'book_id' => $this->book->id
        ]);

        $this->hasAlert = true;
        
        $this->dispatch('request-notification', [
            'message' => 'Alert activated! We will email you once it is available.',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        // Se a view BookRequestButton.blade.php não existir, Livewire não renderiza nada
        return view('livewire.book-request-button');
    }
}