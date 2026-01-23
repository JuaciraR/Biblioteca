<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\User;
use App\Mail\ReviewApprovedMail;
use App\Mail\ReviewRejectedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Traits\Trackable;

class AdminReviewManagement extends Component
{
    use WithPagination, Trackable;

    // State properties for rejection process
    public $rejectingId = null;
    public $rejectionReason = '';

    /**
     * Compatibility bridge to handle calls from the Blade view.
     * This ensures that clicking 'Reject' opens the justification field.
     */
    public function updateStatus($id, $status)
    {
        if ($status === 'active') {
            $this->approve($id);
        } else {
            // This activates the rejection UI for the specific review
            $this->startRejection($id);
        }
    }

    /**
     * Approves a review and notifies the user via email.
     * @param int $id
     */
    public function approve($id)
    {
        if (Auth::user()->role !== 'Admin') abort(403);

        $review = Review::with(['user', 'book'])->findOrFail($id);

        try {
            // Update the status to 'active' (Approved)
            $review->update([
                'status' => 'active',
                'rejection_reason' => null
            ]);

            $this->logAudit('Reviews', $review->id, "Admin APPROVED review for book: {$review->book->title}");

            // Notify the citizen
            Mail::to($review->user->email)->send(new ReviewApprovedMail($review));

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Review Approved! User has been notified by email.'
            ]);

        } catch (\Exception $e) {
            Log::error('Mail Error (Approval): ' . $e->getMessage());
            
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'Status updated to Approved, but email notification failed.'
            ]);
        }
    }

    /**
     * Starts the rejection process by identifying the review and clearing previous reasons.
     * @param int $id
     */
    public function startRejection($id)
    {
        $this->rejectingId = $id;
        $this->rejectionReason = '';
    }

    /**
     * Finalizes the rejection with a justification and notifies the user.
     */
      public function confirmRejection()
    {
        if (Auth::user()->role !== 'Admin') abort(403);

        $this->validate([
            'rejectionReason' => 'required|min:5|max:500'
        ]);

        $review = Review::with(['user', 'book'])->findOrFail($this->rejectingId);

        try {
            // 1. Atualizamos a base de dados
            $review->update([
                'status' => 'rejected',
                'rejection_reason' => $this->rejectionReason
            ]);

            $this->logAudit('Reviews', $review->id, "Admin REJECTED review. Reason: {$this->rejectionReason}");

            // 2. IMPORTANTE: Damos refresh no objeto para garantir que o e-mail lê a razão gravada
            $review->refresh();

            // 3. Enviamos o e-mail
            Mail::to($review->user->email)->send(new ReviewRejectedMail($review));

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Review Rejected. User has been notified.'
            ]);

            $this->cancelRejection();

        } catch (\Exception $e) {
            Log::error('Mail Error (Rejection): ' . $e->getMessage());
            
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => 'Status updated, but email notification failed.'
            ]);
            
            $this->cancelRejection();
        }
    }

    /**
     * Cancels the current rejection action and hides the form.
     */
    public function cancelRejection()
    {
        $this->rejectingId = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        return view('livewire.admin-review-management', [
            'reviews' => Review::with(['user', 'book'])
                ->latest()
                ->paginate(10)
        ]);
    }
}