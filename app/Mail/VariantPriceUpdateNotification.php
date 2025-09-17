<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VariantPriceUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $vehicles;
    public $variant;
    public $field;
    public $oldValue;
    public $newValue;
    public $reason;
    public $updatedBy;

    /**
     * Create a new message instance.
     */
    public function __construct($vehicles, $variant, $field, $oldValue, $newValue, $reason, $updatedBy)
    {
        $this->vehicles = $vehicles;
        $this->variant = $variant;
        $this->field = $field;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
        $this->reason = $reason;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $brandName = $this->vehicles->first()->brand_name ?? 'Unknown Brand';
        $modelLine = $this->vehicles->first()->model_line ?? 'Unknown Model';
        return new Envelope(
            subject: 'Variant Price Update Notification - ' . $brandName . ' ' . $modelLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.variant-price-update',
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
