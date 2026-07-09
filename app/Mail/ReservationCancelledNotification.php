<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCancelledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $salespersonName;
    public $items;

    public function __construct($poNumber, $salespersonName, $items)
    {
        $this->poNumber = $poNumber;
        $this->salespersonName = $salespersonName;
        $this->items = $items;
    }

    public function build()
    {
        $mail = $this->subject('Purchase Order Reservation Cancelled - ' . $this->poNumber)
            ->view('emails.reservation_cancelled')
            ->with([
                'poNumber' => $this->poNumber,
                'salespersonName' => $this->salespersonName,
                'items' => $this->items,
            ]);

        $mail->cc(['aymen.bouderbala@milele.com', 'Abdul@milele.com']);

        return $mail;
    }
}
