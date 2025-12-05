<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Request;
use Illuminate\Mail\Mailables\Address;

class RequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestData;
    public $isAdminNotification;

    /**
     * Cria uma nova instância da mensagem.
     */
    public function __construct(Request $request, bool $isAdminNotification = false)
    {
        $this->requestData = $request;
        $this->isAdminNotification = $isAdminNotification;
    }

    /**
     * Obtém o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isAdminNotification
            ? 'NOVA REQUISIÇÃO SUBMETIDA - Nº ' . $this->requestData->request_number 
            : 'Confirmação da Sua Requisição Nº ' . $this->requestData->request_number;
            
        return new Envelope(
            from: new Address('emailemaileme@gmail.com', 'Biblioteca Digital'),
            subject: $subject,
        );
    }

    /**
     * Obtém a definição do conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requests.confirmation', 
            with: [
                'request' => $this->requestData,
                'is_admin' => $this->isAdminNotification,
                'book' => $this->requestData->book, // Para acesso fácil à capa
            ]
        );
    }
}