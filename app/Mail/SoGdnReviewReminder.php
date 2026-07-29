<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SoGdnReviewReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $soNumber;
    public $salespersonName;
    public Carbon $soDate;
    public Carbon $expiryDate;
    public $daysLeft;
    public $vehicles;
    public $ccList = ['team.salesupport@milele.com'];

    public function __construct($soNumber, $salespersonName, Carbon $soDate, Carbon $expiryDate, $daysLeft, $vehicles)
    {
        $this->soNumber = $soNumber;
        $this->salespersonName = $salespersonName;
        $this->soDate = $soDate;
        $this->expiryDate = $expiryDate;
        $this->daysLeft = $daysLeft;
        $this->vehicles = $vehicles;
    }

    public function build()
    {
        $mail = $this->subject('Action needed: Sales Order ' . $this->soNumber . ' expires in ' . $this->daysLeft . ' day' . ($this->daysLeft == 1 ? '' : 's') . ' (no GDN issued)')
            ->view('emails.so_gdn_review_reminder')
            ->with([
                'soNumber' => $this->soNumber,
                'salespersonName' => $this->salespersonName,
                'soDate' => $this->soDate,
                'expiryDate' => $this->expiryDate,
                'daysLeft' => $this->daysLeft,
                'vehicles' => $this->vehicles,
            ]);

        if (!empty($this->ccList)) {
            $mail->cc($this->ccList);
        }

        return $mail;
    }
}
