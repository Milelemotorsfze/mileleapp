<?php

namespace App\Support;

use App\Models\Calls;
use App\Models\Quotation;
use Illuminate\Support\Facades\DB;

class QuotationLeadResolver
{
    /**
     * Resolve lead/customer for a quotation (matches SalesOrderController logic).
     * Falls back to client_leads when the calls row was deleted.
     */
    public static function resolve(?Quotation $quotation): ?Calls
    {
        if (!$quotation || !$quotation->calls_id) {
            return null;
        }

        $call = Calls::find($quotation->calls_id);
        if ($call) {
            return $call;
        }

        $call = new Calls();
        $call->id = $quotation->calls_id;
        $call->name = '';
        $call->company_name = '';
        $call->phone = '';
        $call->email = '';
        $call->address = '';

        $client = DB::table('client_leads')
            ->join('clients', 'client_leads.clients_id', '=', 'clients.id')
            ->where('client_leads.calls_id', $quotation->calls_id)
            ->select(
                'clients.name',
                'clients.phone',
                'clients.email',
                'clients.address',
                'clients.customertype'
            )
            ->first();

        if ($client) {
            $call->name = $client->name ?? '';
            $call->phone = $client->phone ?? '';
            $call->email = $client->email ?? '';
            $call->address = $client->address ?? '';
            if (($client->customertype ?? '') === 'Company') {
                $call->company_name = $client->name ?? '';
            }
        }

        return $call;
    }
}
