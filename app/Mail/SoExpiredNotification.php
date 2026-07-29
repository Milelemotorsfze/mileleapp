<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SoExpiredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $soNumber;
    public $salespersonName;
    public Carbon $soDate;
    public $vehicles;
    public $ccList = ['team.salesupport@milele.com'];

    public function __construct($soNumber, $salespersonName, Carbon $soDate, $vehicles)
    {
        $this->soNumber = $soNumber;
        $this->salespersonName = $salespersonName;
        $this->soDate = $soDate;
        $this->vehicles = $vehicles;
    }

    public function build()
    {
        $mail = $this->subject('Sales Order ' . $this->soNumber . ' expired - stock released to Available')
            ->view('emails.so_expired')
            ->with([
                'soNumber' => $this->soNumber,
                'salespersonName' => $this->salespersonName,
                'soDate' => $this->soDate,
                'vehicles' => $this->vehicles,
            ]);

        if (!empty($this->ccList)) {
            $mail->cc($this->ccList);
        }

        return $mail;
    }
}
