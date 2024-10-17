<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceChangeNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $ponumber;
    public $orderUrl;
    public $orderCurrency;
    public $priceChanges;
    public $totalAmountOfChanges;
    public $totalVehiclesChanged;

    public function __construct($ponumber, $orderCurrency, $priceChanges, $totalAmountOfChanges, $totalVehiclesChanged, $orderUrl)
    {
        $this->ponumber = $ponumber;
        $this->orderUrl = $orderUrl;
        $this->orderCurrency = $orderCurrency;
        $this->priceChanges = $priceChanges;
        $this->totalAmountOfChanges = $totalAmountOfChanges;
        $this->totalVehiclesChanged = $totalVehiclesChanged;
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->ponumber . ' Status Update')
                    ->view('emails.price_change_notification')
                    ->with([
                        'ponumber' => $this->ponumber,
                        'orderCurrency' => $this->orderCurrency,
                        'priceChanges' => $this->priceChanges,
                        'orderUrl' => $this->orderUrl,
                        'totalAmountOfChanges' => $this->totalAmountOfChanges,
                        'totalVehiclesChanged' => $this->totalVehiclesChanged,
                    ]);
    }
}
