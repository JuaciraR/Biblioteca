<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

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
     * Submits the review (Creates or Updates)
     */
    public function submitReview()
    {
        $this->validate();

        try {
            // Uses of updateOrCreate to allow the user to edit/update the review
            Review::updateOrCreate(
                [
                    'book_id' => $this->bookId,
                    'user_id' => Auth::id(),
                ],
                [
                    'rating' => $this->rating,
                    'comment' => $this->comment,
                ]
            );

            session()->flash('review_success', 'Your review was submitted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('review_error', 'An error occurred while submitting the review. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}