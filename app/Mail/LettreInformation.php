<?php

namespace App\Mail;

use App\Models\Abonne;
use App\Models\Lettre;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

/** Lettre d'information envoyée à un abonné, avec son lien de désabonnement. */
class LettreInformation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lettre $lettre, public Abonne $abonne) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->lettre->sujet);
    }

    public function headers(): Headers
    {
        return new Headers(text: ['List-Unsubscribe' => '<'.$this->abonne->lienDesabonnement().'>']);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.lettre');
    }
}
