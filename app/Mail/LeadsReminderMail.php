<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class LeadsReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $salesPerson;
    public $leadsData;
    public $leadType;
    public $contactedCount;
    public $workingCount;

    /**
     * Create a new message instance.
     */
    public function __construct(User $salesPerson, $leadsData, $leadType = 'new', $contactedCount = 0, $workingCount = 0)
    {
        $this->salesPerson = $salesPerson;
        $this->leadsData = $leadsData;
        $this->leadType = $leadType;
        $this->contactedCount = $contactedCount;
        $this->workingCount = $workingCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->leadType === 'new' 
            ? 'Daily Leads Reminder - ' . $this->leadsData->count() . ' New Leads Assigned'
            : 'Weekly Leads Follow-up - ' . $this->leadsData->count() . ' Leads Need Review';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.leads-reminder',
            with: [
                'salesPerson' => $this->salesPerson,
                'leadsData' => $this->leadsData,
                'totalLeads' => $this->leadsData->count(),
                'leadType' => $this->leadType,
                'contactedCount' => $this->contactedCount,
                'workingCount' => $this->workingCount
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
