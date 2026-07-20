<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentReleasedProcurementNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ponumber;
    public $senderName;
    public $senderEmail;

    public function __construct($ponumber, $senderName, $senderEmail)
    {
        $this->ponumber = $ponumber;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
    }

    public function build()
    {
        return $this->subject('New Purchase_' . $this->ponumber)
                    ->from($this->senderEmail, $this->senderName)
                    ->replyTo($this->senderEmail, $this->senderName)
                    ->view('emails.payment_released_procurement_notification')
                    ->with([
                        'ponumber' => $this->ponumber,
                        'senderName' => $this->senderName,
                    ]);
    }
}
