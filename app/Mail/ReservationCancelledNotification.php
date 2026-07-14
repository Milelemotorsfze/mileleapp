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
    public $ccList = ['sharjeel.arif@milele.com', 'team.salesupport@milele.com', 'aymen.bouderbala@milele.com'];

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

        if (!empty($this->ccList)) {
            $mail->cc($this->ccList);
        }

        return $mail;
    }
}
