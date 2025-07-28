<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Email;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $to = $request->input('to');
        $subject = $request->input('subject');
        $body = $request->input('body');

        $email = (new Email())
            ->from('your-email@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($body);

        try {
            Mail::mailer('gmail')->send($email);
        } catch (\Exception $e) {
            \Log::error($e);
        }

        return response()->json(['message' => 'Email sent successfully']);
    }
}