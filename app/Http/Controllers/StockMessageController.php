<?php

namespace App\Http\Controllers;
use  App\Models\StockMessage;
use  App\Models\StockReply;
use Illuminate\Http\Request;

class StockMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function stockgetMessages($vehicleId)
    {
        info("new pouch");
        $messages = StockMessage::where('vehicle_id', $vehicleId)
                    ->with('user', 'replies.user') // Load user and replies with user
                    ->get();

        return response()->json($messages);
    }

    // Function to send a new message
    public function stocksendMessage(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'message' => 'required|string|max:1000',
        ]);

        $message = new StockMessage();
        $message->vehicle_id = $request->vehicle_id;
        $message->user_id = auth()->id();
        $message->message = $request->message;
        $message->save();

        return response()->json($message->load('user'));
    }

    // Function to send a reply to a message
    public function stocksendReply(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer',
            'reply' => 'required|string|max:1000',
        ]);

        $reply = new StockReply();
        $reply->stock_message_id = $request->message_id;
        $reply->user_id = auth()->id();
        $reply->reply = $request->reply;
        $reply->save();

        return response()->json($reply->load('user'));
    }
}
