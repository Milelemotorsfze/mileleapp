<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $token = 'aqib';
        $openAiApi = 'sk-sdfsdfw34523sdf234234';
        $WhatsAppToken = 'EAAXP5TlZACRIBABgLZBNxH9ffB27XrGx6vtR4SVLi9Vmr6ZBs08oHQquzU2ekOyg1r5aHIJdq2Ryb6MEZB6wlwwQ9UK3oihd3cYKD9TK0HCltGNov8LZCf8JuKYvGSNa8bLSeab2TD7Bl6H9XZB0s4bNrkS3SdNGuijOOr5ZAcz0kqFObyr4Vg59MeHhtMH5DoI0GzYfvwaaCpC16gG7dZBL';
        $api_version = 'v16.0';

        $challenge = $request->input('hub_challenge');
        $verify_token = $request->input('hub_verify_token');

        if ($verify_token === $token) {
            return $challenge;
        }

        $payload = $request->getContent();

        if (empty($payload)) {
            $payload = '{"object":"whatsapp_business_account","entry":[{"id":"107036135722828","changes":[{"value":{"messaging_product":"whatsapp","metadata":{"display_phone_number":"923098993732","phone_number_id":"108047392287351"},"contacts":[{"profile":{"name":"Aqib Awan"},"wa_id":"923162292811"}],"messages":[{"from":"923162292811","id":"wamid.HBgMOTIzMTYyMjkyODExFQIAEhggNTZENjQwM0ZBMDg0RTQ3MEJEMTM3RUFDMzlENzk4ODAA","timestamp":"1683385994","text":{"body":"Hi, Who is Peekcoding on youtube?"},"type":"text"}]},"field":"messages"}]}';
        }

        $decode = json_decode($payload, true);
        $ownerno = $decode['entry'][0]['changes']['0']['value']['metadata']['display_phone_number'];
        $username = $decode['entry'][0]['changes']['0']['value']['contacts'][0]['profile']['name'];
        $userno = $decode['entry'][0]['changes']['0']['value']['messages'][0]['from'];
        $usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];

        // send message to openai
        $ar = [
            'prompt' => 'My name is ' . $username . ' and my question is ' . $usermessage,
            'model' => 'text-davinci-003',
            'temperature' => 0.6,
            'max_tokens' => 150,
            'top_p' => 1,
            'frequency_penalty' => 1,
            'presence_penalty' => 1,
        ];

        $data = json_encode($ar);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $headers = [
            'Content-Type: application/json',
            'Authorization:Bearer ' . $openAiApi,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        curl_close($ch);

        $openapirespose = json_decode($result, true);

        $finalresponse = $openapirespose['choices'][0]['text'];

        try {
            // sending message back to user
            $endpoint = "https://graph.facebook.com/{$api_version}/108047392287351/messages";
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $userno,
                'text' => [
                    'body' => $finalresponse,
                ],
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$WhatsAppToken}",
                "Content-Type: application/json",
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            // Output the response
            return $response;
        } catch (\Exception $e) {
            // Handle exception
            return $e->getMessage();
        }
    }
}
