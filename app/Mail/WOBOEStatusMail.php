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
        // Dynamically build the subject line using PHP
        $subject = 'Reminder: Export Documents Expiry for ' . ($this->boe->boe ?? $this->boe->workOrder->wo_number ?? 'Work Order BOE');

        // Pass the `boe` object and `salesperson` object to the view
        return $this->subject($subject)
                    ->view('work_order.emails.wo_boe_status')
                    ->with([
                        'boe' => $this->boe,
                        'salesperson' => $this->salesperson,
                    ]);
    }
}
