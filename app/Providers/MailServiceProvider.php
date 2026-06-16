<?php
namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use App\Mail\Transport\GmailTransport;
use Google_Client;
use Google_Service_Gmail;
use Symfony\Component\Mailer\Transport\TransportInterface;

class MailServiceProvider extends ServiceProvider
{
    public function register()
    {
        // No need to register anything in the container here
    }

    public function boot()
    {
        Mail::extend('gmail', function () {
            $client = new Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->setRedirectUri(config('services.google.redirect'));
            $client->setAccessType('offline');
            $client->setPrompt('consent');
            $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
            $client->refreshToken(config('services.google.refresh_token'));

            return new GmailTransport($client);
        });
    }
}