<?php

namespace App\Http\Controllers;
use  App\Models\StockMessage;
use Illuminate\Support\Facades\DB;
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
        // info("new pouch");
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
    public function getVehicleDetailsdp(Request $request)
    {
        $variantId = $request->input('variant_id');

        // Fetch details from the database
        $details = DB::table('vehicles')
            ->select('int_colour', 'ex_colour', DB::raw('SUM(CASE WHEN so_id IS NULL THEN 1 ELSE 0 END) as free_stock'), DB::raw('COUNT(*) as total_stock'))
            ->where('varaints_id', $variantId)
            ->whereNull('gdn_id')
            ->whereNotNull('vin')
            ->where('vehicles.status', 'Approved')
            ->where(function ($query) {
                $query->whereNull('vehicles.reservation_end_date')
                      ->orWhere('vehicles.reservation_end_date', '<', now());
            })
            ->groupBy('int_colour', 'ex_colour')
            ->get();

        // Prepare the data to send back
        $data = [];
        foreach ($details as $detail) {
            $intColourName = DB::table('color_codes')->where('id', $detail->int_colour)->value('name');
            $exColourName = DB::table('color_codes')->where('id', $detail->ex_colour)->value('name');

            $data[] = [
                'intColourName' => $intColourName,
                'exColourName' => $exColourName,
                'freeStock' => $detail->free_stock,
                'totalStock' => $detail->total_stock,
            ];
        }

        // Return the data as JSON
        return response()->json(['details' => $data]);
    }
    public function getVehicleDetailsdpbelgium(Request $request)
    {
        $variantId = $request->input('variant_id');

        // Fetch details from the database
        $details = DB::table('vehicles')
            ->select('int_colour', 'ex_colour', DB::raw('SUM(CASE WHEN so_id IS NULL THEN 1 ELSE 0 END) as free_stock'), DB::raw('COUNT(*) as total_stock'))
            ->where('varaints_id', $variantId)
            ->whereNull('gdn_id')
            ->whereNotNull('vin')
            ->where('vehicles.status', 'Approved')
            ->where(function ($query) {
                $query->whereNull('vehicles.reservation_end_date')
                      ->orWhere('vehicles.reservation_end_date', '<', now());
            })
            ->groupBy('int_colour', 'ex_colour')
            ->get();

        // Prepare the data to send back
        $data = [];
        foreach ($details as $detail) {
            $intColourName = DB::table('color_codes')->where('id', $detail->int_colour)->value('name');
            $exColourName = DB::table('color_codes')->where('id', $detail->ex_colour)->value('name');

            $data[] = [
                'intColourName' => $intColourName,
                'exColourName' => $exColourName,
                'freeStock' => $detail->free_stock,
                'totalStock' => $detail->total_stock,
            ];
        }

        // Return the data as JSON
        return response()->json(['details' => $data]);
    }
    
}
