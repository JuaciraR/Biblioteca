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

class BookRequestButton extends Component
{
    public Book $book;
    
    public bool $isAvailable = true; 
    public $maxPendingBooks = 3; 
    public $userPendingRequestsCount = 0; 
    public $canRequest = false; 

    protected $listeners = ['requestCreated' => 'checkAvailability']; 

    // CORREÇÃO: Recebe o ID do livro e resolve o objeto, garantindo que o bind funcione.
    public function mount($book)
    {
        // Se for um Model, usa-o. Se for um ID (que Livewire passa muitas vezes), resolve-o.
        $this->book = $book instanceof Book ? $book : Book::findOrFail($book); 
        $this->checkAvailability();
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
        
        $this->checkAvailability();
        
        if (!$this->canRequest) {
            $msg = $this->isAvailable ? 'Request limit of ' . $this->maxPendingBooks . ' books reached.' : 'The book is already in the request process.';
            $this->dispatch('show-error', $msg)->to(BookDetail::class);
            return; 
        }

        // --- LÓGICA DE CRIAÇÃO DA REQUISIÇÃO (5 dias & Dados Registrados) ---
        
        try {
            DB::beginTransaction();

            $lastRequest = Request::orderByDesc('request_number')->first();
            $nextRequestNumber = ($lastRequest ? $lastRequest->request_number : 0) + 1;

            $requestedAt = Carbon::now();
            $dueDate = $requestedAt->copy()->addDays(Book::LOAN_DAYS);
            
            $request = Request::create([
                'user_id' => Auth::id(), 
                'book_id' => $this->book->id,
                'status' => 'Pending',
                'requested_at' => $requestedAt, 
                'due_date' => $dueDate,        
                'request_number' => $nextRequestNumber,
            ]);

            DB::commit();
            
            // Disparo de E-mails
            if (class_exists(RequestConfirmationMail::class)) {
                Mail::to(Auth::user()->email)->send(new RequestConfirmationMail($request, false));
                Mail::to('juacira.rosa@gmail.com')->send(new RequestConfirmationMail($request, true)); 
            }

            $this->isAvailable = false;
            $successMsg = 'Request #' . $nextRequestNumber . ' submitted successfully! Due date: ' . $dueDate->format('Y-m-d') . '.';
            
            $this->dispatch('show-success', $successMsg)->to(BookDetail::class);
            $this->dispatch('requestCreated');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMsg = 'An error occurred while registering the request: ' . $e->getMessage();
            $this->dispatch('show-error', $errorMsg)->to(BookDetail::class);
        }
    }

    public function render()
    {
        return view('livewire.book-request-button');
    }
}