<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationReassignedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $poNumber;
    public $salespersonName;      // the previous (removing) salesperson
    public $newSalespersonName;   // the newly assigned salesperson
    public $items;
    public $ccList = ['sharjeel.arif@milele.com', 'team.salesupport@milele.com', 'aymen.bouderbala@milele.com'];

    public function __construct($poNumber, $salespersonName, $newSalespersonName, $items)
    {
        $this->poNumber = $poNumber;
        $this->salespersonName = $salespersonName;
        $this->newSalespersonName = $newSalespersonName;
        $this->items = $items;
    }

    public function build()
    {
        $mail = $this->subject('Purchase Order Reservation Reassigned - ' . $this->poNumber)
            ->view('emails.reservation_reassigned')
            ->with([
                'poNumber' => $this->poNumber,
                'salespersonName' => $this->salespersonName,
                'newSalespersonName' => $this->newSalespersonName,
                'items' => $this->items,
            ]);

        if (!empty($this->ccList)) {
            $mail->cc($this->ccList);
        }

        return $mail;
    }
}
