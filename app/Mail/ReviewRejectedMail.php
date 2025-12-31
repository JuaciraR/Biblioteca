<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Review;
use Illuminate\Mail\Mailables\Address;

class ReviewRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('emailemaileme@gmail.com', 'Digital Library'),
            subject: 'Update regarding your book review',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reviews.rejected',
        );
    }
}