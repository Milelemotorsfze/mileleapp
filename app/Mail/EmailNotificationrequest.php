<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailNotificationrequest extends Mailable
{
    use Queueable, SerializesModels;

    public $ponumber;
    public $pl_number;
    public $transaction_amount;
    public $totalcost;
    public $transactionCount;
    public $orderUrl;
    public $currency;

    public function __construct($ponumber, $pl_number, $transaction_amount, $totalcost, $transactionCount, $orderUrl, $currency)
    {
        $this->ponumber = $ponumber;
        $this->pl_number = $pl_number;
        $this->transaction_amount = $this->formatAmount($transaction_amount, $currency);
        $this->totalcost = $this->formatAmount($totalcost, $currency);
        $this->transactionCount = $transactionCount;
        $this->orderUrl = $orderUrl;
        $this->currency = $currency;
    }

    private function formatAmount($amount, $currency)
    {
        return $currency . ' ' . number_format($amount, 2, '.', ',');
    }

    public function build()
    {
        return $this->subject('PO # ' . $this->ponumber . ' Status Update')
                    ->view('emails.dp_request_notification')
                    ->with([
                        'ponumber' => $this->ponumber,
                        'pl_number' => $this->pl_number,
                        'transaction_amount' => $this->transaction_amount,
                        'totalcost' => $this->totalcost,
                        'transactionCount' => $this->transactionCount,
                        'orderUrl' => $this->orderUrl,
                    ]);
    }
}