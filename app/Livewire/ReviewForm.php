<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\User; //  To find administrators
use App\Mail\ReviewNotificationMail; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewForm extends Component
{
    // ID of the book being reviewed, injected from the view
    public $bookId; 
    
    // Form properties
    public $rating = 5; // Default value
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ];

    /**
     * Mounts the component, receiving the book ID and pre-filling if a review already exists.
     */
    public function mount($bookId)
    {
        $this->bookId = $bookId;
        
        // Attempts to find an existing review by the user and book
        $existingReview = Review::where('book_id', $this->bookId)
                                ->where('user_id', Auth::id())
                                ->first();

        // If it exists, loads the data for editing
        if ($existingReview) {
            $this->rating = $existingReview->rating;
            $this->comment = $existingReview->comment;
        }
    }

    /**
     * Submits the review (Creates or Updates) and notifies Admins
     */
    public function submitReview()
    {
        $this->validate();

        try {
            // 1. Save or Update the review in the database
            $review = Review::updateOrCreate(
                [
                    'book_id' => $this->bookId,
                    'user_id' => Auth::id(),
                ],
                [
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                ]
            );

            // 2. FIND ADMINISTRATORS: Search for all users with the role 'Admin'
            $admins = User::where('role', 'Admin')->get();
            
            // 3. SEND EMAIL: Loop through each admin and send the notification
            if ($admins->isNotEmpty()) {
                foreach ($admins as $admin) {
                    // This uses the ReviewNotificationMail file you created
                    Mail::to($admin->email)->send(new ReviewNotificationMail($review));
                }
            }

            session()->flash('review_success', 'Your review was submitted and administrators have been notified!');
            
        } catch (\Exception $e) {
            // If there's a mail error (like wrong SMTP config), it will show here
            session()->flash('review_error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}