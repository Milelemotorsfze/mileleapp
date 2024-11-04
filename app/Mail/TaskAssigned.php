<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $assigner;
    public $taskMessage;
    public $leadLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($assigner, $taskMessage, $leadLink)
    {
        $this->assigner = $assigner;
        $this->taskMessage = $taskMessage;
        $this->leadLink = $leadLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Task Assigned')
                    ->view('emails.task_assigned');
    }
}