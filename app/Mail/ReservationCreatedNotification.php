<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $salespersonName;
    public $items;

    /**
     * @param  string  $poNumber         Final PO number (e.g. PO-000123)
     * @param  string  $salespersonName  Reservation salesperson name
     * @param  \Illuminate\Support\Collection  $items  Rows of { variant, qty }
     */
    public function __construct($poNumber, $salespersonName, $items)
    {
        $this->poNumber = $poNumber;
        $this->salespersonName = $salespersonName;
        $this->items = $items;
    }

    public function build()
    {
        $mail = $this->subject('Purchase Order Reserved Under Your Name - ' . $this->poNumber)
            ->view('emails.reservation_created')
            ->with([
                'poNumber' => $this->poNumber,
                'salespersonName' => $this->salespersonName,
                'items' => $this->items,
            ]);

        $mail->cc(['aymen.bouderbala@milele.com', 'Abdul@milele.com']);

        return $mail;
    }
}
