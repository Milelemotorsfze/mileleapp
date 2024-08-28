<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QCUpdateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $plNumber;
    public $vehiclesVIN;
    public $brandName;
    public $modelLine;
    public $oldVariantName;
    public $newVariantName;
    public $orderUrl;

    public function __construct($poNumber, $plNumber, $vehiclesVIN, $brandName, $modelLine, $oldVariantName, $newVariantName, $orderUrl)
    {
        $this->poNumber = $poNumber;
        $this->plNumber = $plNumber;
        $this->vehiclesVIN = $vehiclesVIN;
        $this->brandName = $brandName;
        $this->modelLine = $modelLine;
        $this->oldVariantName = $oldVariantName;
        $this->newVariantName = $newVariantName;
        $this->orderUrl = $orderUrl;
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->poNumber . ' Status Update')
                    ->view('emails.dp_qc_update_notification')
                    ->with([
                        'poNumber' => $this->poNumber,
                        'plNumber' => $this->plNumber,
                        'vehiclesVIN' => $this->vehiclesVIN,
                        'brandName' => $this->brandName,
                        'modelLine' => $this->modelLine,
                        'oldVariantName' => $this->oldVariantName,
                        'newVariantName' => $this->newVariantName,
                        'orderUrl' => $this->orderUrl,
                    ]);
    }
}