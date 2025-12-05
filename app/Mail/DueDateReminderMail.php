<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Request;
use Illuminate\Mail\Mailables\Address;

class DueDateReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookRequest;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(Request $request)
    {
        $this->bookRequest = $request;
    }

    /**
     * Obtém o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('emailemaileme@gmail.com', 'Biblioteca Digital'),
            subject: 'Lembrete: Devolução de Livro Amanhã!',
        );
    }

    /**
     * Obtém a definição do conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requests.reminder', 
            with: [
                'request' => $this->bookRequest,
                'user' => $this->bookRequest->user,
                'book' => $this->bookRequest->book,
                'due_date' => $this->bookRequest->due_date->format('Y-m-d'),
            ],
        );
    }
}