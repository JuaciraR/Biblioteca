<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Public property to be accessed in the view

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Do you need help with your cart?',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.abandoned-cart', // Path: resources/views/emails/abandoned-cart.blade.php
        );
    }
}