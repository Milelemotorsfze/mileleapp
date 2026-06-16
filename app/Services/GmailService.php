<?php
namespace App\Services;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class GmailService
{
    protected $client;
    protected $gmail;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->client->addScope(Google_Service_Gmail::GMAIL_SEND);

        $this->client->refreshToken(config('services.google.refresh_token'));

        $this->gmail = new Google_Service_Gmail($this->client);
    }

    public function sendEmail($to, $subject, $body)
    {
        $message = new \Swift_Message();
        $message->setSubject($subject);
        $message->setFrom('your-email@gmail.com');
        $message->setTo($to);
        $message->setBody($body, 'text/html');

        $data = $this->buildMessage($message);
        try {
            $this->sendMessage('me', $data);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }

    private function buildMessage($swiftMessage)
    {
        $mimeMessage = new \Swift_Mime_SimpleMessage($swiftMessage);
        $data = $mimeMessage->toString();
        $data = base64_encode($data);
        return str_replace(['+', '/', '='], ['-', '_', ''], $data);
    }

    private function sendMessage($userId, $data)
    {
        $message = new Google_Service_Gmail_Message();
        $message->setRaw($data);
        $this->gmail->users_messages->send($userId, $message);
    }
}