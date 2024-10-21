<!DOCTYPE html>
<html>
<head>
    <title>Reminder: Export Documents Expiry for {{ $boe->boe ?? $boe->workOrder->wo_number ?? 'Work Order' }}</title>
</head>
<body>

    <p>Dear,</p>

    <p>
        Kindly remind the customer that the export documents are about to expire for this work order, 
        and we will be canceling and then reprocessing these documents. The customer will be charged 
        1560 AED for the cancellation and reprocessing.
    </p>

    <p>
        Please note, if the customer has taken the vehicles out of DUCAMZ, kindly ask them to bring 
        the vehicles back for cancellation and reprocessing.
    </p>

    <p>Work Order: <strong>{{ $boe->workOrder->wo_number ?? '' }}</strong></p>
    <p>Customer Name:<strong> {{ $boe->workOrder->customer_name ?? 'Unknown Customer' }}</strong></p>
    <p>Type:<strong> {{ $boe->workOrder->type_name }}</strong></p>

    @if(($boe->workOrder->type == 'export_exw' || $boe->workOrder->type == 'export_cnf') && $boe->workOrder->is_batch == 1 && !empty($boe->workOrder->batch)) 
        <p>Batch:<strong> {{ $boe->workOrder->batch }}</strong></p>
    @elseif(($boe->workOrder->type == 'export_exw' || $boe->workOrder->type == 'export_cnf') && $boe->workOrder->is_batch == 0) 
        <p>Batch:<strong> Single Work Order</strong></p>
    @endif 

    <p>Sales Person:<strong> {{ $boe->workOrder->salesPerson->name ?? '' }}</strong></p>  
    <p>BOE Number: <strong>{{ $boe->boe ?? '' }}</strong></p>
    <p>Declaration Number: <strong>{{ $boe->declaration_number ?? '' }}</strong></p>

    @if($boe->declaration_date != '')
        <p>Declaration Date: <strong>{{ \Carbon\Carbon::parse($boe->declaration_date)->format('d M Y') }}</strong></p>
        
        {{-- Calculate the 29th day from the declaration date --}}
        <p>Deadline to avoid penalty of 200 AED/day : <strong>{{ \Carbon\Carbon::parse($boe->declaration_date)->addDays(28)->format('d M Y') }}</strong></p>
    @endif

    <p><strong>Vehicle Details:</strong></p>

    @if($boe->vehicles->isNotEmpty())
        <ul>
            @foreach($boe->vehicles as $vehicle)
                @if($vehicle->delivery_status !== 'Delivered')
                    <li>
                        <strong>VIN:</strong> {{ $vehicle->vin ?? 'Unknown VIN' }} - 
                        <strong>Status:</strong> {{ $vehicle->delivery_status ?? 'No Status' }}
                    </li>
                @endif
            @endforeach
        </ul>
    @else
        <p>No vehicles found for this BOE.</p>
    @endif

    <p>Regards,</p>
    <p>Milele Matrix</p>

</body>
</html>
