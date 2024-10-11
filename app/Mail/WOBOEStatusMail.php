<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WOBOEStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $boe;
    public $salesperson;

    /**
     * Create a new message instance.
     *
     * @param $boe
     * @param $salesperson
     */
    public function __construct($boe, $salesperson)
    {
        $this->boe = $boe;
        $this->salesperson = $salesperson;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reminder: Export Documents Expiry for Work Order BOE')
                    ->view('emails.wo_boe_status');
    }
}
