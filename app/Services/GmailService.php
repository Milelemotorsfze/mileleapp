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
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->client->addScope(Google_Service_Gmail::GMAIL_SEND);

        // Manually set the access token if it's already available
        $this->client->refreshToken(env('GOOGLE_REFRESH_TOKEN'));

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