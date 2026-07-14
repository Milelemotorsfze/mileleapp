<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationEndingReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $salespersonName;
    public $items;
    public $daysLeft;
    public $ccList = ['sharjeel.arif@milele.com', 'team.salesupport@milele.com', 'aymen.bouderbala@milele.com'];

    public function __construct($poNumber, $salespersonName, $items, $daysLeft)
    {
        $this->poNumber = $poNumber;
        $this->salespersonName = $salespersonName;
        $this->items = $items;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        $mail = $this->subject('Reservation Ending Soon (' . $this->daysLeft . ' day(s) left) - ' . $this->poNumber)
            ->view('emails.reservation_ending_reminder')
            ->with([
                'poNumber' => $this->poNumber,
                'salespersonName' => $this->salespersonName,
                'items' => $this->items,
                'daysLeft' => $this->daysLeft,
            ]);

        if (!empty($this->ccList)) {
            $mail->cc($this->ccList);
        }

        return $mail;
    }
}
