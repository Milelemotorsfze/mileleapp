<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VINEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $plNumber;
    public $orderUrl;
    public $vehicleCount;
    public $vehicles;

    public function __construct($poNumber, $plNumber, $orderUrl, $vehicleCount, $vehicles)
    {
        $this->poNumber = $poNumber;
        $this->plNumber = $plNumber;
        $this->orderUrl = $orderUrl;
        $this->vehicleCount = $vehicleCount;
        $this->vehicles = $vehicles;
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->poNumber . ' Status Update')
                    ->view('emails.dp_vin_notification')
                    ->with([
                        'poNumber' => $this->poNumber,
                        'plNumber' => $this->plNumber,
                        'orderUrl' => $this->orderUrl,
                        'vehicleCount' => $this->vehicleCount,
                        'vehicles' => $this->vehicles,
                    ]);
    }
}