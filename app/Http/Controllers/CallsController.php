<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelHasRoles;
use App\Models\SalesPersonLaugauges;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Language;
use App\Models\LeadSource;
use App\Models\MasterModelLines;
use App\Models\Logs;
use App\Models\CallsRequirement;
use Carbon\Carbon;
use App\Models\Varaint;
use App\Models\AvailableColour;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Calls::where('STATUS', 'New')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->orderBy('created_at', 'desc')->get();    
        $convertedleads = Calls::where('status', 'Converted To Leads')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->get(); 
        $convertedso = Calls::where('status','Converted To SO')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->get(); 
        $convertedrejection = Calls::where('status','Rejection')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->get(); 
        return view('calls.index',compact('data','convertedleads', 'convertedso','convertedrejection'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $Language = Language::get();
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        return view('calls.create', compact('countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 
        $this->validate($request, [
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|required_without:phone|email',           
            'location' => 'required',
            'milelemotors' => 'required',
            'language' => 'required',
            'model_line_ids' => 'array',
            'model_line_ids.*' => 'distinct',
            'type' => 'required',
            'sales_person_id' => ($request->input('sales-option') == "manual-assign") ? 'required' : '',
        ]);      
        if ($request->input('sales-option') == "auto-assign") {
        $email = $request->input('email');
        $phone = $request->input('phone');
        $language = $request->input('language');
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        $sales_person_id = null;
        $existing_email_count = null;
        $existing_phone_count = null;
        $existing_language_count = null;
        foreach ($sales_persons as $sales_person) {
            if ($language == "English") {
                $existing_email_count = Calls::where('email', $email)
                    ->where('sales_person', $sales_person->model_id)
                    ->whereNotNull('email')
                    ->count();
                $existing_phone_count = Calls::where('phone', $phone)
                    ->where('sales_person', $sales_person->model_id)
                    ->whereNotNull('phone')
                    ->count();
                if ($existing_email_count > 0 || $existing_phone_count > 0) {
                    $sales_person_id = $sales_person->model_id;
                    break;
                }
                else
                {
                    $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                    ->where('role_id', 7)
                    ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                    ->where('calls.status', 'New')
                    ->groupBy('calls.sales_person')
                    ->orderByRaw('COUNT(calls.id) ASC')
                    ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                }
            } 
            else {
                $existing_language_count = SalesPersonLaugauges::whereIn('sales_person', $sales_persons->pluck('model_id'))
                ->where('language', $language)
                ->count();      
                if ($existing_language_count === 1) {
                    $sales_person_id = $sales_person->model_id;
                    break;
                }
                elseif ($existing_language_count > 1) {
                    $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                    ->where('role_id', 7)
                    ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                    ->where('calls.status', 'New')
                    ->whereIn('model_has_roles.model_id', $sales_persons->pluck('model_id'))
                    ->groupBy('calls.sales_person')
                    ->orderByRaw('COUNT(calls.id) ASC')
                    ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                    break;
                    }
                else{
                    $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                    ->where('role_id', 7)
                    ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                    ->where('calls.status', 'New')
                    ->groupBy('calls.sales_person')
                    ->orderByRaw('COUNT(calls.id) ASC')
                    ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                }
                }
            }
        }
    else{
        $sales_person_id = $request->input('sales_person_id');
    }
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $dataValue = LeadSource::where('source_name', $request->input('milelemotors'))->value('id');
        $data = [
            'name' => $request->input('name'),
            'source' => $dataValue,
            'email' => $request->input('email'),
            'type' => $request->input('type'),
            'sales_person' => $sales_person_id,
            'remarks' => $request->input('remarks'),
            'location' => $request->input('location'),
            'phone' => $request->input('phone'),
            'custom_brand_model' => $request->input('custom_brand_model'),
            'language' => $request->input('language'),
            'created_at' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
        ];
        $model = new Calls($data);
        $model->save();
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->first();
        $table_id = $lastRecord->id;
        $modelLineIds = $request->input('model_line_ids');

if ($modelLineIds[0] !== null) {
foreach ($modelLineIds as $modelLineId) {
    $datacalls = [
        'lead_id' => $table_id,
        'model_line_id' => $modelLineId,
        'created_at' => $formattedDate
    ];

    $model = new CallsRequirement($datacalls);
    $model->save();
}
}
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $model = new Logs($logdata);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','Call Record created successfully');
    }
    public function show(Request $request, $call, $brand_id, $model_line_id, $location, $days, $custom_brand_model = null)
{   
    $brandId = $request->route('brand_id');
    $location = $request->route('location');
    $modelLineId = $request->route('model_line_id');
    $days = $request->route('days');
    $startDate = Carbon::now()->subDays($days)->startOfDay();
    $endDate = Carbon::now()->endOfDay();
    $callIds = DB::table('calls')
        ->join('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->where('master_model_lines.brand_id', $brandId)
        ->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})
        ->where('master_model_lines.id', $modelLineId)
        ->where('calls.location', $location)
        ->whereBetween('calls.created_at', [$startDate, $endDate])
        ->pluck('calls.id');   
$data = Calls::orderBy('status', 'DESC')
    ->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})
    ->whereIn('id', $callIds)
    ->whereIn('status', ['new', 'active'])
    ->get();

return view('calls.resultbrand', compact('data'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $calls = Calls::findOrFail($id);
        $Language = Language::get();
        $countries = CountryListFacade::getList('en');
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        
        return view('calls.edit', compact('calls','countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatehol(Request $request)
    {
        $this->validate($request, [
            'phone' => 'nullable|required_without:email',
            'email' => 'nullable|required_without:phone|email',           
            'location' => 'required',
            'milelemotors' => 'required',
            'language' => 'required',
            'type' => 'required',
            'sales_person_id' => ($request->input('sales-option') == "manual-assign") ? 'required' : '',
        ]);      
        if ($request->input('sales-option') == "manual-assign") 
		{
        $sales_person_id = $request->input('sales_person_id');
		}
		else{
		$sales_person_id = $request->input('old_sales_person_id');	
		}
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $dataValue = LeadSource::where('source_name', $request->input('milelemotors'))->value('id');
		$call_id = $request->input('call_id');
		$model = Calls::find($call_id);
		if ($model) {
		// Update the fields with the new values
		$model->name = $request->input('name');
		$model->source = $dataValue;
		$model->email = $request->input('email');
		$model->type = $request->input('type');
		$model->sales_person = $sales_person_id;
		$model->remarks = $request->input('remarks');
		$model->location = $request->input('location');
		$model->phone = $request->input('phone');
		$model->custom_brand_model = $request->input('custom_brand_model');
		$model->language = $request->input('language');
		$model->status = "New";
		$model->save();
		}
        $modelLineIds = $request->input('model_line_ids');
            foreach ($modelLineIds as $modelLineId) {
                if ($modelLineId !== null) {
                    $datacalls = [
                        'lead_id' => $call_id,
                        'model_line_id' => $modelLineId,
                        'created_at' => $formattedDate
                    ];
                    $model = new CallsRequirement($datacalls);
                    $model->save();
                }
            }       
       $table_id = $call_id;
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Update",
        ];
        $model = new Logs($logdata);
        $model->save();
        return redirect()->route('calls.index')
        ->with('success','Call Record created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $call = Calls::find($id);
    $call->delete();
    $callRequirements = CallRequirement::where('lead_id', $id)->get();
    if ($callRequirements->isNotEmpty()) {
        foreach ($callRequirements as $callRequirement) {
            $callRequirement->delete();
        }
    }
    return response()->json(['message' => 'Item deleted successfully']);
}
    public function getmodelline(Request $request)
    {
        $brandId = $request->input('brand'); 
        $data = MasterModelLines::where('brand_id', $brandId)
            ->pluck('model_line', 'id');
        return response()->json($data);
    }
    public function createbulk()
    {
        $countries = CountryListFacade::getList('en');
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        return view('calls.createbulk', compact('countries','LeadSource'));
    }
    public function uploadingbulk(Request $request)
{
    if ($request->hasFile('file') && $request->file('file')->isValid()) {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        // Check if the file is an Excel file
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return back()->with('error', 'Invalid file format. Only Excel files (XLS or XLSX) are allowed.');
        }
        $rows = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX)[0];
        $filteredRows = [];
        $rejectedRows = [];
        $acceptedCount = 0;
        $rejectedCount = 0;
        $headers = array_shift($rows);
        foreach ($rows as $row) {
            $call = new Calls();
            $name = $row[0];
            $phone = $row[1];
            $email = $row[2];
            $sales_person = $row[4];
            $source_name = $row[5];
            $language = $row[6];
            $location = $row[3];
			$brand =  $row[7];
			$model_line_name = $row[8];
            $custom_brand_model = $row[9];
            $remarks = $row[10];
            $errorDescription = '';
            if ($sales_person === null) {
			                $sales_persons = ModelHasRoles::where('role_id', 7)->get();
                            $sales_person_id = null;
                            $existing_email_count = null;
                            $existing_phone_count = null;
                            $existing_language_count = null;
                            foreach ($sales_persons as $sales_person) {
                                if ($language == "English") {
                                    $existing_email_count = Calls::where('email', $email)
                                        ->where('sales_person', $sales_person->model_id)
                                        ->whereNotNull('email')
                                        ->count();
                                    $existing_phone_count = Calls::where('phone', $phone)
                                        ->where('sales_person', $sales_person->model_id)
                                        ->whereNotNull('phone')
                                        ->count();
                                    if ($existing_email_count > 0 || $existing_phone_count > 0) {
                                        $sales_person_id = $sales_person->model_id;
                                        break;
                                    }
                                    else
                                    {
                                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                        ->where('role_id', 7)
                                        ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                                        ->where('calls.status', 'New')
                                        ->groupBy('calls.sales_person')
                                        ->orderByRaw('COUNT(calls.id) ASC')
                                        ->first();
                                        $sales_person_id = $lowest_lead_sales_person->model_id;
                                    }
                                } 
                                else {
                                    $existing_language_count = SalesPersonLaugauges::whereIn('sales_person', $sales_persons->pluck('model_id'))
                                    ->where('language', $language)
                                    ->count();      
                                    if ($existing_language_count === 1) {
                                        $sales_person_id = $sales_person->model_id;
                                        break;
                                    }
                                    elseif ($existing_language_count > 1) {
                                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                        ->where('role_id', 7)
                                        ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                                        ->where('calls.status', 'New')
                                        ->whereIn('model_has_roles.model_id', $sales_persons->pluck('model_id'))
                                        ->groupBy('calls.sales_person')
                                        ->orderByRaw('COUNT(calls.id) ASC')
                                        ->first();
                                        $sales_person_id = $lowest_lead_sales_person->model_id;
                                        break;
                                        }
                                    else{
                                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                        ->where('role_id', 7)
                                        ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                                        ->where('calls.status', 'New')
                                        ->groupBy('calls.sales_person')
                                        ->orderByRaw('COUNT(calls.id) ASC')
                                        ->first();
                                        $sales_person_id = $lowest_lead_sales_person->model_id;
                                    }
                                    }
                                }
                                $salesPerson = $sales_person_id;
                                }
             else {
                $salesPerson = User::where('name', $sales_person)->first();
                if($salesPerson)
                {
                $sales_person_id = $salesPerson->id;
                }
                else{
                    $salesPerson === 'not correct';
                }
            }
            if ($source_name !== null) {
                $leadSource = LeadSource::where('source_name', $source_name)->first();
                if ($leadSource) {
                    $lead_source_id = $leadSource->id;
                } else {
                    $lead_source_id = 1;
                }
            } 
			else {
                $lead_source_id = 1;
            }
            if ($language !== null) {
                $language = Language::where('name', $language)->first();
                if ($language) {
                    $language = $language->name;
                } else {
                    $language = 'Not Supported';
                }
            } 
			else {
                $language = 'Not Supported';
            }
            if ($location !== null) {
                $location = Country::where('name', $location)->first();
                if ($location) {
                    $location = $location->name;
                } else {
                    $location = 'Not Supported';
                }
            } 
			else {
                $location = 'Not Supported';
            }
            if($lead_source_id === 1 || $salesPerson === 'not correct' || $language === 'Not Supported' || $location === 'Not Supported')
            {
                $filteredRows[] = $row;
                if ($salesPerson === 'not correct') {
                    $errorDescription .= 'Invalid sales person.';
                }
                if ($lead_source_id === 1) {
                    $errorDescription .= 'Invalid Source ';
                }
                if ($language === 'Not Supported') {
                    $errorDescription .= 'Invalid Language ';
                }
                if ($location === 'Not Supported') {
                    $errorDescription .= 'Invalid Location';
                }
                if (!empty($errorDescription)) {
                    $row[] = $errorDescription;
                    $rejectedRows[] = $row;
                    $rejectedCount++;
                    continue;
                }                
            }
            else{
                $date = Carbon::now();
                $date->setTimezone('Asia/Dubai');
                $formattedDate = $date->format('Y-m-d H:i:s');
                $call->name = $row[0];
                $call->phone = $row[1];
                $call->email = $row[2];
                $call->custom_brand_model = $row[9];
                $call->remarks = $row[10];
                $call->source = $lead_source_id;
                $call->language = $row[6];
                $call->sales_person = $sales_person_id;
                $call->created_at = $formattedDate;
                $call->created_by = Auth::id();
                $call->status = "New";
                $call->location = $row[3];
                $call->save();
                if ($model_line_name !== null) {
                    $modelLine = MasterModelLines::where('model_line', $model_line_name)->first();
                    if ($modelLine) {
                        $model_line_id = $modelLine->id;
                        $callsRequirement = new CallsRequirement();
                        $callsRequirement->lead_id = $call->id;
                        $callsRequirement->model_line_id = $model_line_id;
                        $callsRequirement->save();
                    } 
                }
                $acceptedCount++;
            }
        }
        if (count($rejectedRows) > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $headers = [
                'Name',
                'Phone',
                'Email',
                'Sales Person',
                'Source Name',
                'Language',
                'Location',
                'Brand',
                'Model Line Name',
                'Custom Brand Model',
                'Remarks',
                'Error Description', // New column
            ];
            $sheet->fromArray($headers, null, 'A1');
            foreach ($rejectedRows as $row) {
                $sheet->setCellValue('A' . ($sheet->getHighestRow() + 1), $row[0]); 
                $sheet->setCellValue('B' . ($sheet->getHighestRow()), $row[1]);
                $sheet->setCellValue('C' . ($sheet->getHighestRow()), $row[2]);
                $sheet->setCellValue('D' . ($sheet->getHighestRow()), $row[3]);
                $sheet->setCellValue('E' . ($sheet->getHighestRow()), $row[4]);
                $sheet->setCellValue('F' . ($sheet->getHighestRow()), $row[5]);
                $sheet->setCellValue('G' . ($sheet->getHighestRow()), $row[6]);
                $sheet->setCellValue('H' . ($sheet->getHighestRow()), $row[7]);
                $sheet->setCellValue('I' . ($sheet->getHighestRow()), $row[8]);
                $sheet->setCellValue('J' . ($sheet->getHighestRow()), $row[9]);
                $sheet->setCellValue('K' . ($sheet->getHighestRow()), $row[10]);
                $errorDescriptionCell = 'L' . ($sheet->getHighestRow());
                $sheet->setCellValue($errorDescriptionCell, end($row)); 
                }
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'rejected_excel_file');
            $writer->save($tempFile);
        
            // Move the temporary file to storage
            $filename = 'rejected_records.xlsx';
            Storage::put($filename, file_get_contents($tempFile));
            unlink($tempFile);
        
            // Generate the download link
            $downloadLink = route('download.rejected', ['filename' => $filename]);
        
            return redirect()->route('calls.createbulk')->with('success', [
                'message' => "Data uploaded successfully! From the total " . (count($rows) - 1) . " records, {$acceptedCount} records are accepted & {$rejectedCount} records are rejected.",
                'fileLink' => route('download.rejected', ['filename' => 'rejected_records.xlsx']),
            ]);                    
        }
        return redirect()->route('calls.index')
            ->with('success', "Data uploaded successfully! From the total " . (count($rows) - 1) . " records, {$acceptedCount} records are accepted & {$rejectedCount} records are rejected.");
    } else {
        return back()->with('error', 'Please Select The Correct File for Uploading');
    }
}
    public function checkExistence(Request $request)
{
    $emailCount = 0;
    $phoneCount = 0;
    $phone = $request->input('phone');
    $email = $request->input('email');
    if ($phone !== null) {
        $phoneCount = Calls::where('phone', $phone)->count();
    }
    if ($email !== null) {
        $emailCount = Calls::where('email', $email)->count(); 
    }
    $customers = Calls::where('phone', $phone)->orWhere('email', $email)->get();
    $customerNames = $customers->pluck('name')->toArray();
    $data = [
        'phoneCount' => $phoneCount,
        'emailCount' => $emailCount,
        'customerNames' => $customerNames,
    ];
    
    return response()->json($data);
}
public function checkExistenceupdatecalls(Request $request)
{
    $emailCount = 0;
    $phoneCount = 0;
    $phone = $request->input('phone');
    $email = $request->input('email');
    $call_id = $request->input('call_id');
    info($phone);
    if ($phone !== null) {
        $phoneCount = Calls::where('phone', $phone)->where('id', '<>', $call_id)->count();
    }
    if ($email !== null) {
        $emailCount = Calls::where('email', $email)->where('id', '<>', $call_id)->count(); 
    }
    $customers = Calls::where(function ($query) use ($phone, $email, $call_id) {
        if ($phone !== null) {
            $query->where('phone', $phone);
        }
        if ($email !== null) {
            $query->orWhere('email', $email);
        }
        $query->where('id', '<>', $call_id);
    })->get();
    $customerNames = $customers->pluck('name')->toArray();
    $data = [
        'phoneCount' => $phoneCount,
        'emailCount' => $emailCount,
        'customerNames' => $customerNames,
    ];
    
    return response()->json($data);
}
public function sendDetails(Request $request)
{
    $phone = $request->query('phone');
    $email = $request->query('email');
    
    $calls = Call::where('phone', $phone)
        ->orWhere('email', $email)
        ->get();
    
    return view('calls.repeatedcustomers', compact('calls'));
}
public function removeRow(Request $request)
{
    $callRequirementId = $request->input('call_requirement_id');
    CallsRequirement::where('id', $callRequirementId)->delete();
    return response()->json(['success' => true]);
}
public function updaterow(Request $request)
{
    $callRequirementId = $request->input('callRequirementId');
    $modelLineMasterId = $request->input('modelLineMasterId');
    CallsRequirement::where('id', $callRequirementId)->update(['model_line_id' => $modelLineMasterId]);
    return response()->json(['message' => 'Row updated successfully']);
}
public function simplefile()
{
    $filePath = storage_path('app/public/sample/calls.xlsx'); // Path to the Excel file

    if (file_exists($filePath)) {
        // Generate a response with appropriate headers
        return Response::download($filePath, 'calls.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    } else {
        // File not found
        return redirect()->back()->with('error', 'The requested file does not exist.');
    }
}
public function varinatinfo()
{
    $Variants = AvailableColour::get();   
    return view('variants.vairantinfo', compact('Variants'));
}
public function createnewvarinats()
{
    $interiorColors = [
        'Black', 'Dark Gray', 'Light Gray', 'Beige', 'Tan', 'Cream',
        'Brown', 'Ivory', 'White', 'Red', 'Blue', 'Green',
        'Burgundy', 'Charcoal', 'Navy', 'Silver', 'Champagne', 'Pewter',
        'Almond', 'Ebony', 'Caramel', 'Slate', 'Graphite', 'Sand',
        'Oyster', 'Mocha', 'Parchment', 'Mahogany', 'Cocoa', 'Espresso',
        'Platinum', 'Jet Black', 'Stone Gray', 'Cashmere', 'Granite', 'Saddle',
        'Opal Gray', 'Pebble', 'Shadow', 'Walnut', 'Fawn', 'Pearl',
        'Chestnut', 'Sandalwood', 'Brick', 'Tawny', 'Hickory', 'Tuscan',
        'Driftwood', 'Olive', 'Cloud', 'Raven', 'Twilight', 'Chestnut Brown',
        'Mink', 'Mushroom', 'Clay', 'Slate Gray', 'Flint', 'Arctic',
        'Sandstone', 'Ebony Black', 'Cognac', 'Russet', 'Stone', 'Linen',
        'Carbon', 'Charcoal Gray', 'Bamboo', 'Nutmeg', 'Canyon', 'Terra Cotta',
        'Canyon Brown', 'Steel', 'Gunmetal', 'Bamboo Beige', 'Oatmeal', 'Mink Brown',
        'Warm Gray', 'Truffle', 'Light Stone', 'Tuxedo Black', 'Chalk', 'Agate',
        'Mojave', 'Blond', 'Ochre', 'Natural', 'Cobblestone', 'Stone Beige',
        'Light Beige', 'Granite Gray', 'Eclipse', 'Shale', 'Pumice', 'Ice',
        'Ash', 'Tarmac', 'Dove Gray', 'Desert Sand', 'Sable', 'Cappuccino',
        'Sandy Beige', 'Mist', 'Storm', 'Shetland', 'Onyx', 'Chestnut Brown',
        'Iron', 'Cashew', 'Pebble Beige', 'Storm Gray', 'Shadow Gray', 'Piano Black',
        // Add more color names here...
    ];
    $exteriorColors = [
        'Black', 'White', 'Silver', 'Gray', 'Red', 'Blue',
        'Green', 'Brown', 'Beige', 'Yellow', 'Orange', 'Purple',
        'Gold', 'Bronze', 'Copper', 'Charcoal', 'Navy', 'Burgundy',
        'Pearl', 'Metallic', 'Graphite', 'Platinum', 'Champagne', 'Midnight',
        'Ebony', 'Crimson', 'Ruby', 'Emerald', 'Sapphire', 'Amethyst',
        'Topaz', 'Garnet', 'Opal', 'Mocha', 'Cocoa', 'Ivory',
        'Cream', 'Tungsten', 'Quartz', 'Titanium', 'Lunar', 'Majestic',
        'Mystic', 'Radiant', 'Moonlight', 'Ingot', 'Cobalt', 'Azure',
        'Indigo', 'Slate', 'Shadow', 'Steel', 'Lime', 'Sunset',
        'Tangerine', 'Lemon', 'Olive', 'Forest', 'Teal', 'Mint',
        'Plum', 'Lavender', 'Violet', 'Coral', 'Copper', 'Bronze',
        'Sienna', 'Mahogany', 'Terra Cotta', 'Sandstone', 'Sandy', 'Desert',
        'Pebble', 'Stone', 'Granite', 'Graphite', 'Metallic', 'Midnight Blue',
        'Ruby Red', 'Emerald Green', 'Sapphire Blue', 'Amethyst Purple', 'Onyx Black', 'Lunar Silver',
        'Opulent Blue', 'Magnetic Gray', 'Pure White', 'Pearl White', 'Iridium Silver', 'Classic Red',
        'Race Blue', 'Frozen White', 'Bright Yellow', 'Sunset Orange', 'Velvet Red', 'Deep Blue',
        'Midnight Black', 'Galaxy Blue', 'Fire Red', 'Solar Yellow', 'Cosmic Black', 'Crystal White',
        'Phantom Black', 'Diamond Silver', 'Ruby Red', 'Storm Gray', 'Platinum White', 'Bronze Metallic',
        'Liquid Blue', 'Silk Silver', 'Majestic Blue', 'Metallic Black', 'Candy Red', 'Crystal Blue',
        'Quartz Gray', 'Slate Gray', 'Shimmering Silver', 'Eclipse Black', 'Hyper Red', 'Glacier White',
        // Add more color names here...
    ];
    return view('variants.add_new_variants', compact('interiorColors', 'exteriorColors'));
}
public function storenewvarinats(Request $request) {
    $variantName = $request->input('name');
    $existingVariant = Varaint::where('name', $variantName)->first();
    if ($existingVariant) {
        $variantId = $existingVariant->id;
        $existingColor = AvailableColour::where('varaint_id', $variantId)
            ->where('int_colour', $request->input('int_colour'))
            ->where('ext_colour', $request->input('ext_colour'))
            ->first();
        if ($existingColor) {
            return redirect()->back()->with('error', 'Color combination already exists for this variant');
        }
    } else {
        $variant = new Varaint();
        $variant->name = $variantName;
        $variant->save();
        $variantId = $variant->id;
    }
    $data = [
        'varaint_id' => $variantId,
        'int_colour' => $request->input('int_colour'),
        'ext_colour' => $request->input('ext_colour')
    ];
    $availableColour = new AvailableColour($data);
    $availableColour->save();
    return redirect()->back()->with('success', 'Variant and color details stored successfully');
}
public function downloadRejected($filename)
{
    $filePath = storage_path('app/public/' . $filename);
    if (file_exists($filePath)) {
        return response()->download($filePath);
    } else {
        return redirect()->route('calls.createbulk')->with('error', 'File not found.');
    }
}
}