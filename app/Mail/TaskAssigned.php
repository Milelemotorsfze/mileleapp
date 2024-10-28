<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $taskMessage;
    public $clientName;
    public $clientPhone;
    public $assignerName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($taskMessage, $clientName, $clientPhone, $assignerName)
    {
        $this->taskMessage = $taskMessage;
        $this->clientName = $clientName;
        $this->clientPhone = $clientPhone;
        $this->assignerName = $assignerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Task Assigned')
                    ->view('emails.task_assigned')
                    ->with([
                        'taskMessage' => $this->taskMessage,
                        'clientName' => $this->clientName,
                        'clientPhone' => $this->clientPhone,
                        'assignerName' => $this->assignerName,
                    ]);
    }
}
