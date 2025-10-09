<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyLeadsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;
    public $totalLeads;

    /**
     * Create a new message instance.
     */
    public function __construct($reportData, $totalLeads)
    {
        $this->reportData = $reportData;
        $this->totalLeads = $totalLeads;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Leads Report - ' . $this->totalLeads . ' Total Leads (' . count($this->reportData) . ' Sales Persons)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-leads-report',
            with: [
                'reportData' => $this->reportData,
                'totalLeads' => $this->totalLeads,
                'totalSalesPersons' => count($this->reportData)
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
