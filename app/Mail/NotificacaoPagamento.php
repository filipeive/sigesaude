<?php

namespace App\Mail;

use App\Models\Pagamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacaoPagamento extends Mailable
{
    use Queueable, SerializesModels;

    public $pagamento;

    public function __construct(Pagamento $pagamento)
    {
        $this->pagamento = $pagamento;
    }

    public function build()
    {
        return $this->subject('Notificação de Pagamento')
                    ->view('emails.notificacao_pagamento')
                    ->with([
                        'pagamento' => $this->pagamento,
                    ]);
    }

    /**
     * Get the message sender address.
     *
    
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notificacao Pagamento',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notificacao_pagamento',
        );
    }


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
