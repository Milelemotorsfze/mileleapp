<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendClaimSubmissionReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $vehicle;
    public $emailContent;
    public $label; // Add label as a property

    /**
     * Create a new message instance.
     *
     * @param $boe
     * @param $salesperson
     */
    public function __construct($vehicle, $emailContent, $label)
    {
        $this->vehicle = $vehicle;
        $this->emailContent = $emailContent;
        $this->label = $label; // Set the label property
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Claim Submission Reminder - {$this->label}")
                    ->view('work_order.emails.claim_submission_reminder')
                    ->with([
                        'data' => $this->vehicle, // Pass vehicle as 'data'
                        'emailContent' => $this->emailContent,
                        'details' => ['label' => $this->label],
                    ]);
    }
}
