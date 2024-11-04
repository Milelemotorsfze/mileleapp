<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WebhookController extends Controller
{
    public function sendMessage()
    {
        $client = new Client();
        $url = 'https://graph.facebook.com/v17.0/172819319256290/messages';
        $accessToken = 'EAAkfJDAbFEQBO6oGJCTtp6YcZAM8haur5B2oDHyY0vLZCyTmF1a6ALWfkyE0cO9kwKs35ZBfIVi3y5LP30dgkKJ0v6BoF19SoVWZAZAs2nI7sUwuZCXrshVqDeAPJ8W4KHOP4EtsZAtHunDoITSgzna4JTEcMWZA1Y4zXAZBxMwkQ3q61xLZCNeZCuMh3shzhLi7WMx78cCvZCit3KkG9iIf5AiNxwZDZD';
        $messageData = [
            'recipient' => [
                'id' => '971568660061', // User's WhatsApp ID
            ],
            'message' => [
                'text' => 'Hello, this is your chatbot message.',
            ],
            'messaging_product' => 'whatsapp',
        ];

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $messageData,
            'query' => [
                'access_token' => $accessToken,
            ],
        ]);
        $result = json_decode($response->getBody(), true);
        // Process $result if needed
        return response()->json($result);
    }
}
