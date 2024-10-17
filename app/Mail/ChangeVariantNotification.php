<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChangeVariantNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $plNumber;
    public $changedVariants;
    public $orderUrl;

    public function __construct($poNumber, $plNumber, $changedVariants, $orderUrl)
    {
        $this->poNumber = $poNumber;
        $this->plNumber = $plNumber;
        $this->changedVariants = $changedVariants;
        $this->orderUrl = $orderUrl;
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->poNumber . ' Status Update')
                    ->view('emails.dp_changevariant_notification')
                    ->with([
                        'poNumber' => $this->poNumber,
                'plNumber' => $this->plNumber,
                'changedVariants' => $this->changedVariants,
                'orderUrl' => $this->orderUrl,
                    ]);
    }
}