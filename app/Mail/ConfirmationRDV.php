<?php
namespace App\Mail;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationRDV extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RendezVous $rendezvous) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Confirmation de votre Rendez-vous — Cabinet Médical'
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.confirmation_rdv');
    }
}