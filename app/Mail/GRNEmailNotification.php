<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GRNEmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ponumber;
    public $pl_number;
    public $orderUrl;
    public $vehicleCount;
    public $grnDate;
    public $vins;
    public $vehicles;

    public function __construct($poNumber, $plNumber, $orderUrl, $vehicleCount, $grnDate, $vehicles)
    {
        $this->poNumber = $poNumber;
        $this->plNumber = $plNumber;
        $this->orderUrl = $orderUrl;
        $this->vehicleCount = $vehicleCount;
        $this->grnDate = $grnDate;
        $this->vehicles = $vehicles;
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->poNumber . ' Status Update')
                    ->view('emails.dp_grn_notification')
                    ->with([
                        'poNumber' => $this->poNumber,
                        'plNumber' => $this->plNumber,
                        'orderUrl' => $this->orderUrl,
                        'vehicleCount' => $this->vehicleCount,
                        'grnDate' => $this->grnDate,
                        'vehicles' => $this->vehicles,
                    ]);
    }
}