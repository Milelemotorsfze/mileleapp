<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PDICompletionNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $vehicle;
    public $salesPerson;
    public $pdiDate;

    /**
     * Create a new message instance.
     */
    public function __construct($vehicle, $salesPerson, $pdiDate)
    {
        $this->vehicle = $vehicle;
        $this->salesPerson = $salesPerson;
        $this->pdiDate = $pdiDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PDI Completed - Vehicle Ready for Pickup - VIN: ' . $this->vehicle->vin,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pdi-completion-notification',
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
