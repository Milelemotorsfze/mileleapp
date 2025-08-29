<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstimationDateReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $vehicles;
    public $daysLeft;

    public function __construct($vehicles, $daysLeft)
    {
        $this->vehicles = $vehicles;
        $this->daysLeft = $daysLeft;
    }

    public function build()
    {
        return $this->subject('Vehicle Estimation Date Reminder - ' . $this->daysLeft . ' Days Left')
            ->view('emails.estimation_date_reminder')
            ->with([
                'vehicles' => $this->vehicles,
                'daysLeft' => $this->daysLeft,
            ]);
    }
}
