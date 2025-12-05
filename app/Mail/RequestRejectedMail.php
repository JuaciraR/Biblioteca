<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Request;

class RequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookRequest;

    public function __construct(Request $request)
    {
        $this->bookRequest = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update on Your Book Request - Req. #' . $this->bookRequest->request_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.requests.rejected_decision', // Template a ser criado
            with: [
                'request' => $this->bookRequest,
                'user' => $this->bookRequest->user,
                'book' => $this->bookRequest->book,
            ],
        );
    }
}