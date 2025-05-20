<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicles;
use App\Models\UserActivities;
use App\Models\Document;
use App\Events\DataUpdatedEvent;
use App\Models\Documentlog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "View the Document Section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $status = $request->input('status');
            $searchValue = $request->input('search.value');
            if($status === "Incoming")
            {
            $data = Vehicles::select( [
                    'warehouse.name as location',
                    'vehicles.vin',
                    'vehicles.id',
                    DB::raw('GROUP_CONCAT(varaints.model_detail) as model_details'),
                    'purchasing_order.po_number',
                    DB::raw('COUNT(vehicles.id) as vehicle_count'),
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->whereNull('vehicles.movement_grn_id');
                $data = $data->groupBy('purchasing_order.id');
            } 
            if($status === "Pending")
            {
            $data = Vehicles::select( [
                    'warehouse.name as location',
                    'vehicles.vin',
                    'vehicles.id',
                    'varaints.model_detail',
                    'purchasing_order.po_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->whereNotNull('vehicles.movement_grn_id')
                ->whereNull('vehicles.documents_id');
                $data = $data->groupBy('vehicles.id');
            }
            if($status === "Instock")
            {
            $data = Vehicles::select( [
                    'warehouse.name as location',
                    'vehicles.vin',
                    'vehicles.id',
                    'varaints.model_detail',
                    'documents.import_type',
                    'documents.owership',
                    'documents.id as docid',
                    'documents.document_with',
                    'documents.export_ondemand',
                    'purchasing_order.po_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('documents', 'vehicles.documents_id', '=', 'documents.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->whereNotNull('vehicles.movement_grn_id')
                ->whereNotNull('vehicles.documents_id')
                ->whereNull('vehicles.so_id')
                ->whereNull('vehicles.gdn_id');
                $data = $data->groupBy('vehicles.id');
            } 
            if($status === "PendingBL")
            {
            $data = Vehicles::select( [
                    'warehouse.name as location',
                    'vehicles.vin',
                    'vehicles.id',
                    'varaints.model_detail',
                    'documents.import_type',
                    'documents.owership',
                    'documents.id as docid',
                    'documents.document_with',
                    'documents.export_ondemand',
                    'purchasing_order.po_number',
                    'so.so_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('documents', 'vehicles.documents_id', '=', 'documents.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->whereNotNull('vehicles.movement_grn_id')
                ->whereNotNull('vehicles.documents_id')
                ->whereNotNull('vehicles.so_id')
                ->whereNull('documents.bl_number');
                $data = $data->groupBy('vehicles.id');
            } 
            if($status === "Sold")
            {
            $data = Vehicles::select( [
                    'warehouse.name as location',
                    'vehicles.vin',
                    'vehicles.id',
                    'varaints.model_detail',
                    'documents.import_type',
                    'documents.owership',
                    'documents.document_with',
                    'documents.export_ondemand',
                    'documents.bl_number',
                    'purchasing_order.po_number',
                    'so.so_number',
                ])
                ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                ->leftJoin('documents', 'vehicles.documents_id', '=', 'documents.id')
                ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->whereNotNull('vehicles.movement_grn_id')
                ->whereNotNull('vehicles.documents_id')
                ->whereNotNull('vehicles.so_id')
                ->whereNotNull('documents.bl_number');
                $data = $data->groupBy('vehicles.id');
            } 
                return DataTables::of($data)
                ->toJson();
        }
        return view('logistics.index');
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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the Document";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleId = $request->input('vehicleId');
        $importDocumentType = $request->input('importDocumentType');
        $documentOwnership = $request->input('documentOwnership');
        $documentWith = $request->input('documentWith');
        $document = new Document;
        $document->import_type = $importDocumentType;
        $document->	owership = $documentOwnership;
        $document->document_with = $documentWith;
        $document->save();
        $vehicle = Vehicles::find($vehicleId);
        $vehicle->documents_id = $document->id;
        $vehicle->save();
        $documentlog = new Documentlog();
        $documentlog->time = $currentDateTime->toTimeString();
        $documentlog->date = $currentDateTime->toDateString();
        $documentlog->status = 'Update Import Document Values';
        $documentlog->documents_id = $document->id;
        $documentlog->field = 'import_type';
        $documentlog->new_value = $importDocumentType;
        $documentlog->created_by = auth()->user()->id;
        $documentlog->save();
        $documentlog = new Documentlog();
        $documentlog->time = $currentDateTime->toTimeString();
        $documentlog->date = $currentDateTime->toDateString();
        $documentlog->status = 'Update Document Ownership Values';
        $documentlog->documents_id = $document->id;
        $documentlog->field = 'owership';
        $documentlog->new_value = $documentOwnership;
        $documentlog->created_by = auth()->user()->id;
        $documentlog->save();
        $documentlog = new Documentlog();
        $documentlog->time = $currentDateTime->toTimeString();
        $documentlog->date = $currentDateTime->toDateString();
        $documentlog->status = 'Update Document With Values';
        $documentlog->documents_id = $document->id;
        $documentlog->field = 'document_with';
        $documentlog->new_value = $documentWith;
        $documentlog->created_by = auth()->user()->id;
        $documentlog->save();
        event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
        return response()->json(['message' => 'Data saved successfully']);
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
    public function updatedocbl(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the BL Number";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicleId = $request->input('vehicleId');
        $documentId = $request->input('documentId');
        $blnumber = $request->input('blnumber');
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $document = Document::find($documentId);
        $document->bl_number = $blnumber;
        $document->bl_dms_uploading = "Yes";
        $document->save();
        $documentlog = new Documentlog();
        $documentlog->time = $currentDateTime->toTimeString();
        $documentlog->date = $currentDateTime->toDateString();
        $documentlog->status = 'Update BL Document';
        $documentlog->documents_id = $document->id;
        $documentlog->field = 'BL NUmber';
        $documentlog->new_value = $blnumber;
        $documentlog->created_by = auth()->user()->id;
        $documentlog->save();
        event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
    }
    /**
     * Update the specified resource in storage.
     */
    public function updatedoc(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update the documents";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleId = $request->input('vehicleId');
        $documentId = $request->input('documentId');
        $importDocumentType = $request->input('importDocumentType');
        $documentOwnership = $request->input('documentOwnership');
        $documentWith = $request->input('documentWith');
        $document = Document::find($documentId);
    $logEntries = [];
    if ($document->import_type != $importDocumentType) {
        $logEntries[] = [
            'status' => 'Update Import Document Values',
            'field' => 'import_type',
            'old_value' => $document->import_type,
            'new_value' => $importDocumentType,
        ];
        $document->import_type = $importDocumentType;
    }
    if ($document->owership != $documentOwnership) {
        $logEntries[] = [
            'status' => 'Update Document Ownership Values',
            'field' => 'owership',
            'old_value' => $document->owership,
            'new_value' => $documentOwnership,
        ];
        $document->owership = $documentOwnership;
    }
    if ($document->document_with != $documentWith) {
        $logEntries[] = [
            'status' => 'Update Document With Values',
            'field' => 'document_with',
            'old_value' => $document->document_with,
            'new_value' => $documentWith,
        ];
        $document->document_with = $documentWith;
    }
        $document = Document::find($documentId);
        $document->import_type = $importDocumentType;
        $document->owership = $documentOwnership;
        $document->document_with = $documentWith;
        $document->save();
        if (!empty($logEntries)) {
            $document->save();
            foreach ($logEntries as $entry) {
                $documentlog = new Documentlog();
                $documentlog->time = $currentDateTime->toTimeString();
                $documentlog->date = $currentDateTime->toDateString();
                $documentlog->status = $entry['status'];
                $documentlog->documents_id = $document->id;
                $documentlog->field = $entry['field'];
                $documentlog->old_value = $entry['old_value'];
                $documentlog->new_value = $entry['new_value'];
                $documentlog->created_by = auth()->user()->id;
                $documentlog->save();
            }
        }
        event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => 'Data Update']));
        return response()->json(['message' => 'Data saved successfully']);
    }
    public function destroy(string $id)
    {
        //
    }
}
