<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $plNumber;
    public $changedFields;
    public $orderUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($poNumber, $plNumber, $changedFields, $orderUrl)
    {
        $this->poNumber = $poNumber;
        $this->plNumber = $plNumber;
        $this->changedFields = $changedFields;
        $this->orderUrl = $orderUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PO # ' . $this->poNumber . ' Changes Update')
                    ->view('emails.purchase_order_updated')
                    ->with([
                        'poNumber' => $this->poNumber,
                        'orderUrl' => $this->orderUrl,
                        'plNumber' => $this->plNumber,
                        'changedFields' => $this->changedFields,
                    ]);
    }
}
