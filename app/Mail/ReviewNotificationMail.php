<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Review;
use Illuminate\Mail\Mailables\Address;

class ReviewNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $review;

    /**
     * Recebe a instância da Review criada.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Configura o Assunto e o Remetente.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('emailemaileme@gmail.com', 'Biblioteca Digital'),
            subject: 'New submitted review - ' . $this->review->book->title,
        );
    }

    /**
     * Define o template e as variáveis enviadas para a view.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.reviews.notification',
            with: [
                'review' => $this->review,
                'user' => $this->review->user,
                'book' => $this->review->book,
                'url' => url('/books/' . $this->review->book_id),
            ],
        );
    }
}