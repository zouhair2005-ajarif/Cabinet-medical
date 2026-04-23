<?php
namespace App\Mail;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RDVAccepte extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RendezVous $rendezvous) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Votre RDV a été accepté — Cabinet Médical'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rdv_accepte');
    }
}