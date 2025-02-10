<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SwiftCopyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pfi_number;
    public $total_amount;
    public $swift_copy_path;

    /**
     * Create a new message instance.
     */
    public function __construct($pfi_number, $total_amount, $swift_copy_path)
    {
        $this->pfi_number = $pfi_number;
        $this->total_amount = $total_amount;
        $this->swift_copy_path = $swift_copy_path;

    }
    /**
     * 
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Invoice no : ". $this->pfi_number. " | Milele Motors")
                    ->view('purchase-order.emails.swift-copy')
                    ->with([
                        'pfi_number' => $this->pfi_number,
                        'total_amount' => $this->total_amount
                    ])
                    ->attach($this->swift_copy_path);
    }
}
