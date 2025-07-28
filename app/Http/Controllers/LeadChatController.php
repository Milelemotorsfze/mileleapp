<?php

namespace App\Http\Controllers;
use App\Models\LeadChat;
use App\Models\LeadChatReply;
use App\Models\User;

use Illuminate\Http\Request;

class LeadChatController extends Controller
{
    public function store(Request $request, $leadId) {
        $request->validate([
            'message' => 'required'
        ]);

        $chat = LeadChat::create([
            'lead_id' => $leadId,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        $this->notifyTaggedUsers($chat->message);

        return response()->json($chat);
    }

    public function reply(Request $request, $chatId) {
        $request->validate([
            'message' => 'required'
        ]);

        $reply = LeadChatReply::create([
            'chat_id' => $chatId,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        $this->notifyTaggedUsers($reply->message);

        return response()->json($reply);
    }

    private function notifyTaggedUsers($message) {
        preg_match_all('/@([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $message, $matches);
        $emails = $matches[1];

        foreach ($emails as $email) {
            // Notify user via email, e.g., using Laravel's Mail facade
            $user = User::where('email', $email)->first();
            if ($user) {
                try {
                    Mail::to($user->email)->send(new TaggedInChatNotification($user, $message));
                } catch (\Exception $e) {
                    \Log::error($e);
                }
            }
        }
    }
}
