<?php

namespace App\Http\Controllers;

use App\Models\Calls;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\UserActivities;
use App\Models\User;
use App\Exports\LeadsExport;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelHasRoles;
use App\Models\SalesPersonLaugauges;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Brand;
use App\Models\LeadsNotifications;
use App\Models\Country;
use App\Models\Language;
use App\Models\LeadSource;
use App\Models\Strategy;
use App\Models\MasterModelLines;
use App\Models\Logs;
use App\Models\CallsRequirement;
use Carbon\Carbon;
use App\Models\Varaint;
use App\Models\AvailableColour;
use App\Rules\CountryCodes;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CallsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datahot = Calls::where('calls.status', 'New')
            ->where('calls.priority', 'Hot')
            ->join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->orderBy('calls.created_at', 'desc')
            ->select('calls.*', 'lead_source.priority as lead_source_priority')
            ->get();
        $countdatahot = $datahot->count();
        $datanormal = Calls::where('calls.status', 'New')
            ->where('calls.priority', 'Normal')
            ->join('lead_source', 'calls.source', '=', 'lead_source.id')
            ->orderBy('calls.created_at', 'desc')
            ->select('calls.*', 'lead_source.priority as lead_source_priority')
            ->get();
        $countdatanormal = $datanormal->count();
        $datalow = Calls::where('calls.status', 'New')
        ->where('calls.priority', 'Low')
        ->join('lead_source', 'calls.source', '=', 'lead_source.id')
        ->orderBy('calls.created_at', 'desc')
        ->select('calls.*', 'lead_source.priority as lead_source_priority')
        ->get();
        $countdatalow = $datalow->count();
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Call & Lead Pending Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.index', compact('datahot', 'datanormal', 'datalow', 'countdatalow', 'countdatanormal', 'countdatahot'));
    }
    public function inprocess()
    {
        if (request()->ajax()) {
            $data = Calls::where('status', 'Prospecting')
                ->orWhere('status', 'New Demand')
                ->orWhere('status', 'Quoted')
                ->orWhere('status', 'Negotiation')
                ->where('created_at', '>=', Carbon::now()->subMonths(2))
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('sales_person', function ($row) {
                    $sales_persons = DB::table('users')->where('id', $row->sales_person)->first();
                    return $sales_persons ? $sales_persons->name : '';
                })
                ->addColumn('brands_models', function ($row) {
                    $leads_models_brands = DB::table('calls_requirement')
                        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
                        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                        ->where('calls_requirement.lead_id', $row->id)
                        ->get();

                    $models_brands_string = '';
                    foreach ($leads_models_brands as $lead_model_brand) {
                        $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
                    }
                    return rtrim($models_brands_string, ', ');
                })
                ->addColumn('lead_source', function ($row) {
                    $leadsource = DB::table('lead_source')->where('id', $row->source)->first();
                    return $leadsource ? $leadsource->source_name : '';
                })
                ->addColumn('remarks_messages', function ($row) {
                    $text = $row->remarks;
                    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
                    return str_replace(['<p>', '</p>'], '', strip_tags($remarks));
                })
                ->addColumn('sales_person_remarks', function ($row) {
                    $sales_notes = "";
                    if ($row->status == "Prospecting") {
                        $result = DB::table('prospectings')->where('calls_id', $row->id)->first();
                        if ($result) {
                            $sales_notes = $result->salesnotes;
                        }
                    } elseif ($row->status == "New Demand") {
                        $result = DB::table('demand')->where('calls_id', $row->id)->first();
                        if ($result) {
                            $sales_notes = $result->salesnotes;
                        }
                    } elseif ($row->status == "Quoted") {
                        $result = DB::table('quotations')->where('calls_id', $row->id)->first();
                        if ($result) {
                            $sales_notes = $result->sales_notes;
                        }
                    } else {
                        $result = DB::table('negotiations')->where('calls_id', $row->id)->first();
                        if ($result) {
                            $sales_notes = $result->sales_notes;
                        }
                    }
                    return $sales_notes;
                })
                ->rawColumns(['sales_person', 'brands_models', 'lead_source', 'remarks_messages', 'sales_person_remarks'])
                ->make(true);
        }

        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Call & Lead Inprocess Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();

        return view('calls.inprocess');
    }
    public function converted()
    {
        $data = Calls::where('status', 'Closed')->where(function ($query) {
            $query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');
        })->where('created_at', '>=', Carbon::now()->subMonths(2))->get();
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Call & Lead Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.converted', compact('data'));
    }
    public function rejected()
    {
        $data = Calls::where('status', 'Rejected')->where(function ($query) {
            $query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');
        })->where('created_at', '>=', Carbon::now()->subMonths(2))->get();
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Call & Lead Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.rejected', compact('data'));
    }
    public function datacenter(Request $request)
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open The Leads Database";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $callsQuery = Calls::query();
            if ($request->has('order')) {
                $order = $request->input('order')[0];
                $columnIndex = $order['column'];
                $columnName = $request->input('columns')[$columnIndex]['name'];
                $direction = $order['dir'];

                $callsQuery->orderBy($columnName, $direction);
            }
            foreach ($request->input('columns') as $column) {
                $searchValue = $column['search']['value'];
                $columnName = $column['name'];
                if (!empty($searchValue)) {
                    if ($columnName === 'date' && $searchValue !== null) {
                        $callsQuery->orWhere('created_at', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'status' && $searchValue !== null) {
                        $callsQuery->orWhere('status', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'type' && $searchValue !== null) {
                        $callsQuery->orWhere('type', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'priority' && $searchValue !== null) {
                        $callsQuery->orWhere('priority', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'name' && $searchValue !== null) {
                        $callsQuery->orWhere('name', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'email' && $searchValue !== null) {
                        $callsQuery->orWhere('email', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'phone' && $searchValue !== null) {
                        $callsQuery->orWhere('phone', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'location' && $searchValue !== null) {
                        $callsQuery->orWhere('location', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'custom_brand_model' && $searchValue !== null) {
                        $callsQuery->orWhere('custom_brand_model', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'remarks' && $searchValue !== null) {
                        $callsQuery->orWhere('remarks', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'remarks' && $searchValue !== null) {
                        $callsQuery->orWhere('remarks', 'like', '%' . $searchValue . '%');
                    } elseif ($columnName === 'salesperson' && $searchValue !== null) {
                        $callsQuery->orWhereHas('salesperson', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        });
                    } else if ($columnName === 'brand_model' && $searchValue !== null) {
                        $callsQuery->orWhereHas('requirements.masterModelLine.brand', function ($query) use ($searchValue) {
                            $query->where('brand_name', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('requirements.masterModelLine', function ($query) use ($searchValue) {
                            $query->where('model_line', 'like', '%' . $searchValue . '%');
                        });
                    } else if ($columnName === 'sales_remarks_coming' && $searchValue !== null) {
                        $callsQuery->orWhereHas('closed', function ($query) use ($searchValue) {
                            $query->where('sales_notes', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('rejectionleads', function ($query) use ($searchValue) {
                            $query->where('sales_notes', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('salesdemandleads', function ($query) use ($searchValue) {
                            $query->where('salesnotes', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('negotiationleads', function ($query) use ($searchValue) {
                            $query->where('sales_notes', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('quotationleads', function ($query) use ($searchValue) {
                            $query->where('sales_notes', 'like', '%' . $searchValue . '%');
                        });

                        $callsQuery->orWhereHas('prospectingleads', function ($query) use ($searchValue) {
                            $query->where('salesnotes', 'like', '%' . $searchValue . '%');
                        });
                    } else if ($columnName === 'strategies' && $searchValue !== null) {
                        $callsQuery->orWhereHas('strategies', function ($query) use ($searchValue) {
                            $query->where('name', 'like', '%' . $searchValue . '%');
                        });
                    }
                }
            }
            return DataTables::of($callsQuery)
                ->addColumn('created_at', function ($call) {
                    return date('d-M-Y', strtotime($call->created_at));
                })
                ->addColumn('status', function ($call) {
                    return $call->status;
                })
                ->addColumn('language', function ($call) {
                    return $call->language;
                })
                ->addColumn('custom_brand_model', function ($call) {
                    return $call->custom_brand_model;
                })
                ->addColumn('location', function ($call) {
                    return $call->location;
                })
                ->addColumn('remarks', function ($call) {
                    return $call->remarks;
                })
                ->addColumn('type', function ($call) {
                    return $call->type;
                })
                ->addColumn('name', function ($call) {
                    return $call->name;
                })
                ->addColumn('priority', function ($call) {
                    return $call->priority;
                })
                ->addColumn('salesperson', function ($call) {
                    return $call->salesperson->name;
                })
                ->addColumn('leadsource', function ($call) {
                    return $call->leadssouces ? $call->leadssouces->source_name : '';
                })
                ->addColumn('strategies', function ($call) {
                    return $call->strategies ? $call->strategies->name : '';
                })
                ->addColumn('brand_model', function ($call) {
                    $requirements = $call->requirements;
                    if ($requirements) {
                        $brand = optional($requirements->masterModelLine->brand)->brand_name ?? '';
                        $modelLine = optional($requirements->masterModelLine)->model_line ?? '';
                        return $brand . ' - ' . $modelLine;
                    } else {
                        return '';
                    }
                })
                ->addColumn('sales_remarks_coming', function ($call) {
                    $closed = $call->closed;
                    $rejection = $call->rejectionleads;
                    $negotiation = $call->negotiationleads;
                    $quotation = $call->quotationleads;
                    $quotation = $call->quotationleads;
                    $demandleads = $call->salesdemandleads;
                    $prospecting = $call->prospectingleads;
                    if ($closed) {
                        return $closed->sales_notes;
                    } elseif ($rejection) {
                        return $rejection->sales_notes;
                    } elseif ($demandleads) {
                        return $demandleads->salesnotes;
                    } elseif ($negotiation) {
                        return $negotiation->sales_notes;
                    } elseif ($quotation) {
                        return $quotation->sales_notes;
                    } elseif ($prospecting) {
                        return $prospecting->salesnotes;
                    } else {
                        return '';
                    }
                })
                ->toJson();
        }
        return view('calls.leadsdatabase');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = CountryListFacade::getList('en');
        $africanCountries = Country::where('is_african_country', 1)->pluck('name')->toArray();
        $Language = Language::get();
        $LeadSource = LeadSource::select('id', 'source_name')->orderBy('source_name', 'ASC')->where('status', 'active')->get();
        $strategy = Strategy::get();
        $modelLineMasters = MasterModelLines::select('id', 'brand_id', 'model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = User::where('manual_lead_assign', 1)
        ->select('id', 'name', 'is_dubai_sales_rep') 
        ->orderBy('name', 'asc')
        ->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.create', compact('countries', 'africanCountries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language', 'strategy'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validCountryCodes = CountryCodes::list();

        $validator = Validator::make(
            $request->all(),
            [
                'phone' => ['nullable', 'required_without:email', function ($attribute, $value, $fail) use ($validCountryCodes) {
                    if (!empty($value)) {
                        $value = preg_replace('/[^\d+]/', '', $value);

                        if ($value[0] !== '+') {
                            $value = '+' . $value;
                        }

                        $matchedCode = null;
                        foreach ($validCountryCodes as $code) {
                            if (strpos($value, $code) === 0) {
                                $matchedCode = $code;
                                break;
                            }
                        }

                        if (!$matchedCode) {
                            return $fail('Invalid country code in phone number.');
                        }

                        $localPart = substr($value, strlen($matchedCode));

                        if (!ctype_digit($localPart)) {
                            return $fail('Phone number can only contain digits after country code.');
                        }

                        $length = strlen($localPart);
                        if ($length < 5 || $length > 20) {
                            return $fail('Phone number must be between 5 to 20 digits (excluding country code).');
                        }
                    }
                }],
                'email' => 'nullable|required_without:phone|email',
                'location' => 'required',
                'milelemotors' => 'required',
                'language' => 'required',
                'strategy' => 'required',
                'priority' => 'required',
                'model_line_ids' => 'array',
                'model_line_ids.*' => 'distinct',
                'type' => 'required',
                'sales_person_id' => ($request->input('sales-option') == "manual-assign") ? 'required' : '',
            ],
            [
                'milelemotors.required' => 'The Source field is required.',
                'location.required' => 'The Destination field is required.',
                'strategy.required' => 'The Strategy field is required.',
                'priority.required' => 'The Priority field is required.',
                'phone.regex' => 'Invalid Phone Number.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Additional required field validation
        $customErrors = [];
        $rawPhone = $request->input('phone');
        $email = $request->input('email');
        $location = $request->input('location');
        $source_name = $request->input('milelemotors');
        $language = $request->input('language');
        $strategies = $request->input('strategy');
        $priority = strtolower(trim($request->input('priority')));
        // 1. Contact No. or Email
        if (empty($rawPhone) && empty($email)) {
            $customErrors['contact'] = 'Contact No or Email is required.';
        }
        // 2. Country
        if (empty($location) || !\App\Models\Country::where('name', $location)->exists()) {
            $customErrors['location'] = 'Country is required.';
        }
        // 3. Source
        if (empty($source_name) || !\App\Models\LeadSource::where('source_name', $source_name)->exists()) {
            $customErrors['source'] = 'Source is required.';
        }
        // 4. Preferred Language
        if (empty($language) || !\App\Models\Language::where('name', $language)->exists()) {
            $customErrors['language'] = 'Preferred Language is required.';
        }
        // 5. Strategies
        if (empty($strategies) || !\App\Models\Strategy::where('name', $strategies)->exists()) {
            $customErrors['strategies'] = 'Strategies is required.';
        }
        // 6. Priority
        $validPriorities = ['hot', 'low', 'normal'];
        if (empty($priority) || !in_array($priority, $validPriorities)) {
            $customErrors['priority'] = 'Priority is required.';
        }
        if (!empty($customErrors)) {
            return redirect()->back()->withErrors($customErrors)->withInput();
        }

        if ($request->input('sales-option') == "auto-assign") {
            Log::info("Starting auto-assignment for lead creation.");
            $assignmentResult = $this->autoAssignSalesPerson(
                $request->input('email'),
                $request->input('phone'),
                $request->input('language'),
                $request->input('location')
            );
            $sales_person_id = $assignmentResult['user_id'];
            $assignmentReason = $assignmentResult['reason'];
            if ($sales_person_id) {
                $salesPersonName = User::find($sales_person_id)?->name;
                Log::info("Final Assigned Sales Person: $salesPersonName (ID: $sales_person_id) - Reason: $assignmentReason");
            } else {
                Log::warning("No available sales person found for assignment - Reason: $assignmentReason");
            }
        } else {
            $sales_person_id = $request->input('sales_person_id');
        }
            $date = Carbon::now();
            $date->setTimezone('Asia/Dubai');
            $formattedDate = $date->format('Y-m-d H:i:s');
            $straigy = $request->input('strategy');
            $strategies_id = Strategy::where('name',$straigy)->first();
            $dataValue = LeadSource::where('source_name', $request->input('milelemotors'))->value('id');

            // if ($sales_person_id) {
            //     $salesPersonName = User::find($sales_person_id)?->name;
            //     Log::info("Final Assigned Sales Person: " . $salesPersonName . " (ID: $sales_person_id)");
            //     dd("Assigned Sales Person: " . $salesPersonName);
            // }

            $data = [
                'name' => $request->input('name'),
                'source' => $dataValue,
                'email' => $request->input('email'),
                'type' => $request->input('type'),
                'sales_person' => $sales_person_id,
                'remarks' => $request->input('remarks'),
                'assign_time' => Carbon::now(),
                'location' => $request->input('location'),
                'phone' => $request->input('phone'),
                // 'secondary_phone_number' => $request->input('secondary_phone_number'),
                'strategies_id' => $strategies_id->id,
                'priority' => $request->input('priority'),
                'custom_brand_model' => $request->input('custom_brand_model'),
                'language' => is_array($request->input('language')) ? implode(', ', $request->input('language')) : $request->input('language'),
                'created_at' => $formattedDate,
                'assign_time' => $formattedDate,
                'created_by' => Auth::id(),
                'status' => "New",
            ];
            $model = new Calls($data);
            $model->save();
            $lastRecord = Calls::where('created_by', $data['created_by'])
                    ->orderBy('id', 'desc')
                    ->first();
            $leads_notifications = New LeadsNotifications();
            $leads_notifications->calls_id = $lastRecord->id;
            $leads_notifications->remarks = "New Assign Lead";
            $leads_notifications->status = "New";
            $leads_notifications->user_id = $sales_person_id;
            $leads_notifications->category = "New Assign Lead";
            $leads_notifications->save();
            $table_id = $lastRecord->id;
            $modelLineIds = $request->input('model_line_ids');

        if (!empty($modelLineIds) && is_array($modelLineIds) && $modelLineIds[0] !== null) {
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
        $useractivities =  new UserActivities();
        $useractivities->activity = "Store New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return redirect()->route('calls.index')
            ->with('success', 'Call Record created successfully');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
    
            $destinationPath = public_path('uploads/summernote');
    
            if (!\File::isDirectory($destinationPath)) {
                \File::makeDirectory($destinationPath, 0777, true, true);
            }
    
            $file->move($destinationPath, $filename);
    
            return asset('uploads/summernote/'.$filename); 
        }
    
        return response()->json(['error' => 'No file uploaded.'], 400);
    }        
        
    public function showcalls(Request $request, $call, $brand_id, $model_line_id, $location, $days, $custom_brand_model = null)
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
            ->where(function ($query) {
                $query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');
            })
            ->where('master_model_lines.id', $modelLineId)
            ->where('calls.location', $location)
            ->whereBetween('calls.created_at', [$startDate, $endDate])
            ->pluck('calls.id');
        $data = Calls::orderBy('status', 'DESC')
            ->where(function ($query) {
                $query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');
            })
            ->whereIn('id', $callIds)
            ->whereIn('status', ['new', 'active'])
            ->get();
        $useractivities =  new UserActivities();
        $useractivities->activity = "View The Most Lead Brand And Models";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
        $africanCountries = Country::where('is_african_country', 1)->pluck('name')->toArray();
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $strategy = Strategy::get();
        $currentStrategyName = optional(Strategy::find($calls->strategies_id))->name;
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = User::select('id', 'name', 'is_dubai_sales_rep') 
            ->where('manual_lead_assign', 1)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Edit Page of Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.edit', compact('calls','countries', 'africanCountries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language', 'strategy', 'currentStrategyName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatehol(Request $request)
    {
        $validCountryCodes = CountryCodes::list();

        $validator = Validator::make($request->all(), [
            'phone' => ['nullable', 'required_without:email', function ($attribute, $value, $fail) use ($validCountryCodes) {
                if (!empty($value)) {
                    $value = preg_replace('/[^\d+]/', '', $value);

                    if ($value[0] !== '+') {
                        $value = '+' . $value;
                    }

                    $matchedCode = null;
                    foreach ($validCountryCodes as $code) {
                        if (strpos($value, $code) === 0) {
                            $matchedCode = $code;
                            break;
                        }
                    }

                    if (!$matchedCode) {
                        return $fail('Invalid country code in phone number.');
                    }

                    $localPart = substr($value, strlen($matchedCode));

                    if (!ctype_digit($localPart)) {
                        return $fail('Phone number can only contain digits after country code.');
                    }

                    $length = strlen($localPart);
                    if ($length < 5 || $length > 20) {
                        return $fail('Phone number must be between 5 to 20 digits (excluding country code).');
                    }
                }
            }],
            'email' => 'nullable|required_without:phone|email',
            'location' => 'required',
            'milelemotors' => 'required',
            'language' => 'required',
            'strategy' => 'required',
            'priority' => 'required',
            'type' => 'required',
            'sales_person_id' => ($request->input('sales-option') == "manual-assign") ? 'required' : '',
        ], [
            'milelemotors.required' => 'The Source field is required.',
            'location.required' => 'The Destination field is required.',
            'strategy.required' => 'The Strategy field is required.',
            'priority.required' => 'The Priority field is required.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->input('sales-option') == "manual-assign") {
            $sales_person_id = $request->input('sales_person_id');
        } else {
            $sales_person_id = $request->input('old_sales_person_id');
        }

        $strategyRecord = Strategy::where('name', $request->input('strategy'))->first();
        $strategies_id = $strategyRecord ? $strategyRecord->id : null;

        $phone = trim($request->input('phone'));
        if (!empty($phone) && substr($phone, 0, 1) !== '+') {
            $phone = '+' . $phone;
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
		$model->phone = $phone;
		// $model->secondary_phone_number = $request->input('secondary_phone_number');
		$model->custom_brand_model = $request->input('custom_brand_model');
		$model->language = is_array($request->input('language')) ? implode(', ', $request->input('language')) : $request->input('language');
        $model->strategies_id = $strategies_id;
        $model->priority = $request->input('priority');
		$model->status = "New";
		$model->save();
		}
        $modelLineIds = $request->input('model_line_ids') ?? [];

        CallsRequirement::where('lead_id', $call_id)->delete();

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
        $useractivities =  new UserActivities();
        $useractivities->activity = "Edit the Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return redirect()->route('calls.index')
            ->with('success', 'Call Record created successfully');
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
        $useractivities =  New UserActivities();
            $useractivities->activity = "Delete The Leads";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
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
        $LeadSource = LeadSource::select('id', 'source_name')->orderBy('source_name', 'ASC')->where('status', 'active')->get();
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Create Bulk Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.createbulk', compact('countries', 'LeadSource'));
    }

    public function uploadingbulk(Request $request)
    {
        $validCountryCodes = CountryCodes::list();
        
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return back()->with('error', 'Please Select The Correct File for Uploading');
        }

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, ['xls', 'xlsx'])) {
            return back()->with('error', 'Invalid file format. Only Excel files (XLS or XLSX) are allowed.');
        }

        $rows = Excel::toArray([], $file, null, \Maatwebsite\Excel\Excel::XLSX)[0];

        // Initialize arrays
        $rejectedRows = [];
        $acceptedCount = 0;
        $rejectedCount = 0;

        $headers = array_shift($rows);
        // Build header map: column name (upper, trimmed) => index
        $headerMap = [];
        foreach ($headers as $idx => $header) {
            $headerMap[strtoupper(trim($header))] = $idx;
        }
        // Define required columns (excluding CONTACT_NO and EMAIL for now)
        $requiredColumns = [
            'COUNTRY', 'SOURCE', 'PREFERRED LANGUAGE', 'STRATEGIES', 'PRIORITY'
        ];
        // Check for missing required columns
        $missingColumns = array_diff($requiredColumns, array_keys($headerMap));
        // Special check: at least one of CONTACT_NO or EMAIL must be present
        if (!isset($headerMap['CONTACT_NO']) && !isset($headerMap['EMAIL'])) {
            $missingColumns[] = 'CONTACT_NO or EMAIL (at least one required)';
        }
        if (!empty($missingColumns)) {
            return back()->with('error', 'Missing required columns in Excel: ' . implode(', ', $missingColumns));
        }

        foreach ($rows as $row) {
            $nonEmptyValues = array_filter($row, function ($value) {
                return !is_null($value) && trim($value) !== '';
            });
            if (empty($nonEmptyValues)) {
                continue;
            }
            $phone = null;
            $errorMessages = [];
            $isPhoneValid = false;
            $isEmailValid = false;
            // Extract data using header map
            $name = isset($headerMap['CUSTOMER']) ? ($row[$headerMap['CUSTOMER']] ?? '') : '';
            $rawPhone  = isset($headerMap['CONTACT_NO']) ? trim($row[$headerMap['CONTACT_NO']] ?? '') : '';
            $email = isset($headerMap['EMAIL']) ? trim($row[$headerMap['EMAIL']] ?? '') : '';
            $sales_person = isset($headerMap['SALES PERSON']) ? ($row[$headerMap['SALES PERSON']] ?? '') : '';
            $source_name = isset($headerMap['SOURCE']) ? ($row[$headerMap['SOURCE']] ?? '') : '';
            $language = isset($headerMap['PREFERRED LANGUAGE']) ? ($row[$headerMap['PREFERRED LANGUAGE']] ?? '') : '';
            $location = isset($headerMap['COUNTRY']) ? ($row[$headerMap['COUNTRY']] ?? '') : '';
            $brand = isset($headerMap['BRAND']) ? ($row[$headerMap['BRAND']] ?? '') : '';
            $model_line_name = isset($headerMap['MODEL LINE']) ? ($row[$headerMap['MODEL LINE']] ?? '') : '';
            $custom_brand_model = isset($headerMap['CUSTOM MODEL']) ? ($row[$headerMap['CUSTOM MODEL']] ?? '') : '';
            $strategies = isset($headerMap['STRATEGIES']) ? ($row[$headerMap['STRATEGIES']] ?? '') : '';
            $priority = isset($headerMap['PRIORITY']) ? strtolower(trim($row[$headerMap['PRIORITY']] ?? '')) : '';
            $carInterested = isset($headerMap['CAR INTERESTED']) ? trim($row[$headerMap['CAR INTERESTED']] ?? '') : '';
            $purchasePurpose = isset($headerMap['PURPOSE OF PURCHASE']) ? trim($row[$headerMap['PURPOSE OF PURCHASE']] ?? '') : '';
            $endUser = isset($headerMap['END USER']) ? trim($row[$headerMap['END USER']] ?? '') : '';
            $destinationCountry = isset($headerMap['DESTINATION COUNTRY']) ? trim($row[$headerMap['DESTINATION COUNTRY']] ?? '') : '';
            $plannedUnits = isset($headerMap['PLANNED UNITS']) ? trim($row[$headerMap['PLANNED UNITS']] ?? '') : '';
            $experience = isset($headerMap['EXPERIENCE WITH UAE SOURCING']) ? trim($row[$headerMap['EXPERIENCE WITH UAE SOURCING']] ?? '') : '';
            $shipping = isset($headerMap['SHIPPING ASSISTANCE REQUIRED']) ? trim($row[$headerMap['SHIPPING ASSISTANCE REQUIRED']] ?? '') : '';
            $paymentMethod = isset($headerMap['PAYMENT METHOD']) ? trim($row[$headerMap['PAYMENT METHOD']] ?? '') : '';
            $prevPurchase = isset($headerMap['PREVIOUS PURCHASE HISTORY']) ? trim($row[$headerMap['PREVIOUS PURCHASE HISTORY']] ?? '') : '';
            $timeline = isset($headerMap['PURCHASE TIMELINE']) ? trim($row[$headerMap['PURCHASE TIMELINE']] ?? '') : '';
            $additionalNotes = isset($headerMap['ADDITIONAL NOTES']) ? trim($row[$headerMap['ADDITIONAL NOTES']] ?? '') : '';

            $cleanPhone = preg_replace('/[\s\-]/', '', $rawPhone); 
            
            if (!empty($cleanPhone)) {
                if ($cleanPhone[0] !== '+') {
                    $cleanPhone = '+' . $cleanPhone;
                }
            
                if (!preg_match('/^\+\d{5,20}$/', $cleanPhone)) {
                    $errorMessages[] = 'Phone number must start with + and contain only digits, 5 to 20 digits total.';
                    $phone = null;
                } else {
                    $matchedCode = false;
                    $matchedCodeLength = 0;
            
                    foreach ($validCountryCodes as $code) {
                        if (strpos($cleanPhone, $code) === 0) {
                            $matchedCode = true;
                            $matchedCodeLength = strlen($code);
                            break;
                        }
                    }
            
                    if (!$matchedCode) {
                        $errorMessages[] = 'Invalid country code in phone number.';
                        $phone = null;
                    } else {
                        $digitsAfterCode = substr($cleanPhone, $matchedCodeLength);
                        if (!ctype_digit($digitsAfterCode)) {
                            $errorMessages[] = 'Phone number can only contain digits after country code.';
                            $phone = null;
                        } elseif (strlen($digitsAfterCode) < 5 || strlen($digitsAfterCode) > 20) {
                            $errorMessages[] = 'Phone number after country code must be 5 to 20 digits.';
                            $phone = null;
                        } else {
                            $phone = $cleanPhone;
                            $isPhoneValid = true;
                        }
                    }
                }
            }        
            

            // **Email Validation**
            if (!empty($email)) {
                $emailValidator = Validator::make(['email' => $email], ['email' => 'email:rfc,dns']);
                if ($emailValidator->fails()) {
                    $errorMessages[] = 'Invalid Email Address';
                    $email = null;
                } else {
                    $isEmailValid = true;
                }
            }

            // If both phone and email are missing, reject with clear error
            if (empty($rawPhone) && empty($email)) {
                $errorMessages[] = 'Missing Email and Phone Number';
            }

            if (!$isPhoneValid && !$isEmailValid) {
                $errorMessages[] = 'Either a valid Email or Phone Number is required';
            }
        
            $leadSource = LeadSource::where('source_name', $source_name)->first();
            if (!$leadSource) {
                $errorMessages[] = 'Invalid or Missing Source';
                $lead_source_id = null;
            } else {
                $lead_source_id = $leadSource->id;
            }

            $strategyRecord = Strategy::where('name', $strategies)->first();
            if (!$strategyRecord) {
                $errorMessages[] = 'Invalid or Missing Strategy';
                $strategies_id = null;
            } else {
                $strategies_id = $strategyRecord->id;
            }

            $validPriorities = ['hot', 'low', 'normal'];
                if (!in_array($priority, $validPriorities)) {
                    $errorMessages[] = 'Invalid Priority Value (Allowed: hot, low, normal)';
            }

            if (!empty($errorMessages)) {
                $row[] = implode(', ', array_unique($errorMessages));
                $rejectedRows[] = $row;
                $rejectedCount++;
                continue;
            }

            $acceptedCount++;
        }

        if ($rejectedCount > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $headers = [
                'Name', 'Phone', 'Email', 'Location', 'Sales Person', 'Source Name', 'Language',
                'Brand', 'Model Line Name', 'Custom Brand Model', 'Strategies', 'Priority', 'Error Description'
            ];
            $sheet->fromArray($headers, null, 'A1');

            foreach ($rejectedRows as $row) {
                $sheet->fromArray($row, null, 'A' . ($sheet->getHighestRow() + 1));
            }

            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'rejected_excel_file');
            $writer->save($tempFile);

            $filename = 'rejected_records.xlsx';
            Storage::put($filename, file_get_contents($tempFile));
            unlink($tempFile);

            $downloadLink = route('download.rejected', ['filename' => $filename]);

            return back()->with('error', [
                'message' => "Data upload failed! From the total " . (count($rows)) . " records, " . $acceptedCount . " were accepted & " . $rejectedCount . " were rejected. No data has been added.",
                'fileLink' => $downloadLink,
            ]);
        }

        else {
            // Define allowed users for auto-assignment
            $allowed_users = [
                'Lincoln Mukwada',
                'Nwanneka Nwani',
                'Raymond Chikoki',
                'Ali Arous',
                'Mohamad Azizi',
                'Yacine Guella',
                'Sarah Ferhane',
                'Ayoub Ididir',
            ];
            $allowed_user_ids = User::whereIn('name', $allowed_users)->pluck('id')->toArray();
            // Initialize lead counts for fair distribution
            $userLeadCounts = [];
            foreach ($allowed_user_ids as $uid) {
                $userLeadCounts[$uid] = Calls::where('sales_person', $uid)->where('status', 'New')->count();
            }
            // Separate leads into two groups: with and without sales person
            $leadsWithSalesPerson = [];
            $leadsWithoutSalesPerson = [];
            foreach ($rows as $index => $row) {
                $sales_person = trim($row[4]); // Sales person column
                if (!empty($sales_person)) {
                    $leadsWithSalesPerson[] = $row;
                } else {
                    $leadsWithoutSalesPerson[] = $row;
                }
            }
            Log::info("Processing bulk upload: " . count($leadsWithSalesPerson) . " leads with sales person, " . count($leadsWithoutSalesPerson) . " leads without sales person");
            // Process leads with specified sales person first
            foreach ($leadsWithSalesPerson as $row) {
                $call = new Calls();
                $name = $row[0];
                $email = $row[2];
                $sales_person = $row[4];
                $source_name = $row[5];
                $language = $row[6];
                $location = $row[3];
                $brand =  $row[7];
                $model_line_name = $row[8];
                $custom_brand_model = $row[9];
                $strategies = $row[10];
                $priority = strtolower(trim($row[11]));
                $carInterested = trim($row[12]);
                $purchasePurpose = trim($row[13]);
                $endUser = trim($row[14]);
                $destinationCountry = trim($row[15]);
                $plannedUnits = trim($row[16]);
                $experience = trim($row[17]);
                $shipping = trim($row[18]);
                $paymentMethod = trim($row[19]);
                $prevPurchase = trim($row[20]);
                $timeline = trim($row[21]);
                $additionalNotes = trim($row[22]);
                $remarksArray = [];
                if ($carInterested || $purchasePurpose || $endUser || $destinationCountry || $plannedUnits || $experience || $shipping || $paymentMethod || $prevPurchase || $timeline) {
                    $remarksArray[] = 'Lead Summary - Qualification Notes:';
                    if ($carInterested) $remarksArray[] = "1. Car Interested In: $carInterested";
                    if ($purchasePurpose) $remarksArray[] = "2. Purpose of Purchase: $purchasePurpose";
                    if ($endUser) $remarksArray[] = "3. End User: $endUser";
                    if ($destinationCountry) $remarksArray[] = "4. Destination Country: $destinationCountry";
                    if ($plannedUnits) $remarksArray[] = "5. Planned Units: $plannedUnits";
                    if ($experience) $remarksArray[] = "6. Experience with UAE Sourcing: $experience";
                    if ($shipping) $remarksArray[] = "7. Shipping Assistance Required: $shipping";
                    if ($paymentMethod) $remarksArray[] = "8. Payment Method: $paymentMethod";
                    if ($prevPurchase) $remarksArray[] = "9. Previous Purchase History: $prevPurchase";
                    if ($timeline) $remarksArray[] = "10. Purchase Timeline: $timeline";
                }
                if ($additionalNotes) {
                    $remarksArray[] = "General Remark / Additional Notes: $additionalNotes";
                }
                $remarksData = implode('###SEP###', $remarksArray);
                $errorDescription = '';
                // Assignment logic for leads with specified sales person
                $salesPerson = User::where('name', $sales_person)->first();
                if ($salesPerson) {
                    $sales_person_id = $salesPerson->id;
                    Log::info("Direct assignment: Lead assigned to " . $salesPerson->name . " (ID: $sales_person_id)");
                } else {
                    $errorDescription .= 'Specified Sales Person not found. ';
                    $sales_person_id = null;
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
                if ($strategies !== null) {
                    $strategiesid = Strategy::where('name', $strategies)->first();
                    if ($strategiesid) {
                        $strategies_id = $strategiesid->id;
                    } else {
                        $strategies_id = 1;
                    }
                } 
                else {
                    $strategies_id = 1;
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
                if (!empty($language)) {
                    $languageRecord = Language::where('name', $language)->first();
                    if ($languageRecord) {
                        $language = $languageRecord->name;
                    } else {
                        $errorDescription .= 'Invalid Language ';
                        $language = null;
                    }
                } else {
                    $errorDescription .= 'Language Missing ';
                    $language = null;
                }
                
                if (!empty($location)) {
                    $locationRecord = Country::where('name', $location)->first();
                    if ($locationRecord) {
                        $location = $locationRecord->name;
                    } else {
                        $errorDescription .= 'Invalid Location ';
                        $location = null;
                    }
                } else {
                    $errorDescription .= 'Location Missing ';
                    $location = null;
                }

                
                if($lead_source_id === 1 || $sales_person_id === null || $language === 'Not Supported' || $location === 'Not Supported' || $strategies_id === 1)
                {
                    if ($sales_person_id === null) {
                        $errorDescription .= 'Invalid sales person. ';
                    }
                    if ($lead_source_id === 1) {
                        $errorDescription .= 'Invalid Source ';
                    }
                    if ($strategies_id === 1) {
                        $errorDescription .= 'Invalid Strategies ';
                    }
                    if ($language === 'Not Supported') {
                        $errorDescription .= 'Invalid Language ';
                    }
                    if ($location === 'Not Supported') {
                        $errorDescription .= 'Invalid Location ';
                    }
                    
                    $row[] = $errorDescription;
                    $rejectedRows[] = $row;
                    $rejectedCount++;
                    continue;
                }
                else{
                    $date = Carbon::now();
                    $date->setTimezone('Asia/Dubai');
                    $formattedDate = $date->format('Y-m-d H:i:s');
                    $call->name = !empty(trim($row[0])) ? trim($row[0]) : null;
                    $rawPhoneForDb = trim($row[1]);
                    if (!empty($rawPhoneForDb) && $rawPhoneForDb[0] !== '+') {
                        $rawPhoneForDb = '+' . $rawPhoneForDb;
                    }
                    $call->phone = $rawPhoneForDb ?? null;
                    $call->email = !empty(trim($row[2])) ? trim($row[2]) : null;
                    $call->assign_time = Carbon::now();
                    $call->custom_brand_model = $row[9];
                    $call->remarks = $remarksData;
                    $call->source = $lead_source_id;
                    $call->strategies_id = $strategies_id;
                    $call->priority = $priority;
                    $call->language = is_array($row[6]) ? implode(', ', $row[6]) : $row[6];
                    $call->sales_person = $sales_person_id;
                    $call->created_at = $formattedDate;
                    $call->assign_time = $formattedDate;
                    $call->created_by = Auth::id();
                    $call->status = "New";
                    $call->location = $row[3];
                    $call->save(); 
                    $leads_notifications = New LeadsNotifications();
                    $leads_notifications->calls_id =  $call->id;
                    $leads_notifications->remarks = "New Assign Lead";
                    $leads_notifications->status = "New";
                    $leads_notifications->user_id = $sales_person_id;
                    $leads_notifications->category = "New Assign Lead";
                    $leads_notifications->save();
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
            
            // Process leads without sales person using in-memory round robin
            Log::info("Starting auto-assignment for " . count($leadsWithoutSalesPerson) . " leads without sales person");
            // Initialize in-memory lead counts for fair distribution
            $userLeadCounts = [];
            foreach ($allowed_user_ids as $uid) {
                $userLeadCounts[$uid] = Calls::where('sales_person', $uid)->where('status', 'New')->count();
            }
            // Log eligible user IDs and names at the start
            $eligibleUsersLog = [];
            foreach ($allowed_user_ids as $uid) {
                $user = User::find($uid);
                if ($user) {
                    $eligibleUsersLog[] = $user->name . ' (ID: ' . $uid . ', DB count: ' . $userLeadCounts[$uid] . ')';
                }
            }
            Log::info('Eligible users for assignment: ' . implode(', ', $eligibleUsersLog));
            // Prepare assigned counts for summary
            $assignedCounts = array_fill_keys($allowed_user_ids, 0);
            foreach ($leadsWithoutSalesPerson as $row) {
                $call = new Calls();
                $name = $row[0];
                $email = $row[2];
                $sales_person = $row[4]; // This will be empty for this group
                $source_name = $row[5];
                $language = $row[6];
                $location = $row[3];
                $brand =  $row[7];
                $model_line_name = $row[8];
                $custom_brand_model = $row[9];
                $strategies = $row[10];
                $priority = strtolower(trim($row[11]));
                $carInterested = trim($row[12]);
                $purchasePurpose = trim($row[13]);
                $endUser = trim($row[14]);
                $destinationCountry = trim($row[15]);
                $plannedUnits = trim($row[16]);
                $experience = trim($row[17]);
                $shipping = trim($row[18]);
                $paymentMethod = trim($row[19]);
                $prevPurchase = trim($row[20]);
                $timeline = trim($row[21]);
                $additionalNotes = trim($row[22]);
                $remarksArray = [];
                if ($carInterested || $purchasePurpose || $endUser || $destinationCountry || $plannedUnits || $experience || $shipping || $paymentMethod || $prevPurchase || $timeline) {
                    $remarksArray[] = 'Lead Summary - Qualification Notes:';
                    if ($carInterested) $remarksArray[] = "1. Car Interested In: $carInterested";
                    if ($purchasePurpose) $remarksArray[] = "2. Purpose of Purchase: $purchasePurpose";
                    if ($endUser) $remarksArray[] = "3. End User: $endUser";
                    if ($destinationCountry) $remarksArray[] = "4. Destination Country: $destinationCountry";
                    if ($plannedUnits) $remarksArray[] = "5. Planned Units: $plannedUnits";
                    if ($experience) $remarksArray[] = "6. Experience with UAE Sourcing: $experience";
                    if ($shipping) $remarksArray[] = "7. Shipping Assistance Required: $shipping";
                    if ($paymentMethod) $remarksArray[] = "8. Payment Method: $paymentMethod";
                    if ($prevPurchase) $remarksArray[] = "9. Previous Purchase History: $prevPurchase";
                    if ($timeline) $remarksArray[] = "10. Purchase Timeline: $timeline";
                }
                if ($additionalNotes) {
                    $remarksArray[] = "General Remark / Additional Notes: $additionalNotes";
                }
                $remarksData = implode('###SEP###', $remarksArray);
                $errorDescription = '';
                // Assignment with previous assignment check + language + phone country code business rule
                $assignedUserId = null;
                $assignmentReason = '';
                $langNorm = is_string($language) ? trim(strtolower($language)) : '';
                $leadPhone = trim($row[1]);
                $cleanedPhoneForMatch = '';
                if (!empty($leadPhone)) {
                    $cleanedPhoneForMatch = ltrim(preg_replace('/[^\d+]/', '', $leadPhone), '+');
                }
                // 1. Check for previous assignment by phone or email
                $previousAssignment = null;
                if (!empty($leadPhone)) {
                    $previousAssignment = Calls::where('phone', $leadPhone)->whereNotNull('sales_person')->orderBy('created_at', 'desc')->first();
                }
                if (!$previousAssignment && !empty($email)) {
                    $previousAssignment = Calls::where('email', $email)->whereNotNull('sales_person')->orderBy('created_at', 'desc')->first();
                }
                if ($previousAssignment && in_array($previousAssignment->sales_person, $allowed_user_ids)) {
                    $assignedUserId = $previousAssignment->sales_person;
                    $assignedUser = User::find($assignedUserId);
                    $assignmentReason = 'Previous assignment found for phone/email - assigned to same salesperson: ' . ($assignedUser ? $assignedUser->name : 'Unknown') . ' (ID: ' . $assignedUserId . ')';
                    Log::info("Previous assignment found: Phone=$leadPhone, Email=$email, Previous Salesperson: " . ($assignedUser ? $assignedUser->name : 'Unknown'));
                } else {
                    // 2. Sort allowed users by current in-memory lead count (ascending)
                    $sortedCandidates = $allowed_user_ids;
                    usort($sortedCandidates, function ($a, $b) use ($userLeadCounts) {
                        $countA = $userLeadCounts[$a] ?? 0;
                        $countB = $userLeadCounts[$b] ?? 0;
                        if ($countA === $countB) {
                            return $a <=> $b;
                        }
                        return $countA <=> $countB;
                    });

                    // 3. Find first min-count candidate who has a matching email/phone/language among their existing leads
                    $found = false;
                    foreach ($sortedCandidates as $candidateId) {
                        $hasMatch = Calls::where('sales_person', $candidateId)
                            ->where(function ($q) use ($email, $cleanedPhoneForMatch, $langNorm) {
                                if (!empty($email)) {
                                    $q->orWhere('email', $email);
                                }
                                if (!empty($cleanedPhoneForMatch)) {
                                    $q->orWhere('phone', 'LIKE', '%' . $cleanedPhoneForMatch);
                                }
                                if (!empty($langNorm)) {
                                    $q->orWhereRaw('LOWER(language) = ?', [$langNorm]);
                                }
                            })
                            ->exists();

                        if ($hasMatch) {
                            $assignedUserId = $candidateId;
                            $assignedUser = User::find($assignedUserId);
                            $assignmentReason = 'Assigned to ' . $assignedUser->name . ' (min-count candidate with matching email/phone/language)';
                            $found = true;
                            break;
                        }
                    }

                    // 4. If none matched, try min-count candidate on the basis of language
                    if (!$found) {
                        $langMatchedIds = [];
                        if (!empty($language)) {
                            $langMatchedIds = SalesPersonLaugauges::whereIn('sales_person', $allowed_user_ids)
                                ->where('language', $language)
                                ->pluck('sales_person')
                                ->toArray();
                        }
                        if (!empty($langMatchedIds)) {
                            // Choose min-count among language-matched users
                            $langSorted = $langMatchedIds;
                            usort($langSorted, function ($a, $b) use ($userLeadCounts) {
                                $countA = $userLeadCounts[$a] ?? 0;
                                $countB = $userLeadCounts[$b] ?? 0;
                                if ($countA === $countB) {
                                    return $a <=> $b;
                                }
                                return $countA <=> $countB;
                            });
                            $assignedUserId = $langSorted[0];
                            $assignedUser = User::find($assignedUserId);
                            $assignmentReason = 'No match - assigned to min-count language-matched user ' . $assignedUser->name;
                        } else {
                            // 5. Fall back to absolute min-count among all allowed
                            $assignedUserId = $sortedCandidates[0];
                            $assignedUser = User::find($assignedUserId);
                            $assignmentReason = 'No match - assigned to min-count user ' . $assignedUser->name;
                        }
                    }
                }
                $userLeadCounts[$assignedUserId]++;
                $assignedCounts[$assignedUserId]++;
                // Log assignment
                Log::info("Auto-assigning lead for: Email=$email, Phone=$leadPhone, Language=$language, Location=$location");
                Log::info("Auto-assigned to: " . ($assignedUser ? $assignedUser->name : 'Unknown') . " (ID: $assignedUserId) - Reason: $assignmentReason");
                $date = Carbon::now();
                $date->setTimezone('Asia/Dubai');
                $formattedDate = $date->format('Y-m-d H:i:s');
                // Source
                if ($source_name !== null) {
                    $leadSource = LeadSource::where('source_name', $source_name)->first();
                    if ($leadSource) {
                        $lead_source_id = $leadSource->id;
                    } else { 
                        $lead_source_id = 1;
                    }
                } else {
                    $lead_source_id = 1;
                }
                // Strategies
                if ($strategies !== null) {
                    $strategiesid = Strategy::where('name', $strategies)->first();
                    if ($strategiesid) {
                        $strategies_id = $strategiesid->id;
                    } else {
                        $strategies_id = 1;
                    }
                } else {
                    $strategies_id = 1;
                }
                // Language
                if ($language !== null) {
                    $languageObj = Language::where('name', $language)->first();
                    if ($languageObj) {
                        $language = $languageObj->name;
                    } else {
                        $language = 'Not Supported';
                    }
                } else {
                    $language = 'Not Supported';
                }
                // Location
                if (!empty($location)) {
                    $locationRecord = Country::where('name', $location)->first();
                    if ($locationRecord) {
                        $location = $locationRecord->name;
                    } else {
                        $location = null;
                    }
                } else {
                    $location = null;
                }
                $rawPhoneForDb = trim($row[1]);
                if (!empty($rawPhoneForDb) && $rawPhoneForDb[0] !== '+') {
                    $rawPhoneForDb = '+' . $rawPhoneForDb;
                }
                $call->name = !empty(trim($row[0])) ? trim($row[0]) : null;
                $call->phone = $rawPhoneForDb ?? null;
                $call->email = !empty(trim($row[2])) ? trim($row[2]) : null;
                $call->assign_time = Carbon::now();
                $call->custom_brand_model = $row[9];
                $call->remarks = $remarksData;
                $call->source = $lead_source_id;
                $call->strategies_id = $strategies_id;
                $call->priority = $priority;
                $call->language = is_array($row[6]) ? implode(', ', $row[6]) : $row[6];
                $call->sales_person = $assignedUserId;
                $call->created_at = $formattedDate;
                $call->assign_time = $formattedDate;
                $call->created_by = Auth::id();
                $call->status = "New";
                $call->location = $location;
                $call->save(); 
                $leads_notifications = New LeadsNotifications();
                $leads_notifications->calls_id =  $call->id;
                $leads_notifications->remarks = "New Assign Lead";
                $leads_notifications->status = "New";
                $leads_notifications->user_id = $assignedUserId;
                $leads_notifications->category = "New Assign Lead";
                $leads_notifications->save();
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
            // Log summary for each allowed user
            foreach ($allowed_users as $userName) {
                $user = User::where('name', $userName)->first();
                if ($user) {
                    $uid = $user->id;
                    $count = isset($assignedCounts[$uid]) ? $assignedCounts[$uid] : 0;
                    $reason = '';
                    if (!in_array($uid, $allowed_user_ids)) {
                        $reason = 'User not in allowed_user_ids (may be inactive, wrong role, or missing ModelHasRoles).';
                    } elseif ($count === 0) {
                        $reason = 'User was eligible but did not get any leads in this batch (round robin, random, or language mismatch).';
                    } else {
                        $reason = 'Assigned normally.';
                    }
                    Log::info("Assignment summary: $userName (ID: $uid) - Assigned: $count leads. Reason: $reason");
                } else {
                    Log::info("Assignment summary: $userName - User not found in DB.");
                }
            }
        }

        $useractivities =  New UserActivities();
        $useractivities->activity = "Create Bulk Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();

        return redirect()->route('calls.index')->with('success', "Data uploaded successfully! From the total " . count($rows) . " records, {$acceptedCount} were accepted.");
    }

    public function checkExistence(Request $request)
    {
        $emailCount = 0;
        $phoneCount = 0;
        $phone = $request->input('phone');
        $email = $request->input('email');
        if ($phone !== null) {
            $cleanedPhone = ltrim($phone, '+');
            $phoneCount = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)->count();
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
        $useractivities =  New UserActivities();
            $useractivities->activity = "Checking The Existing Customer";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
        return response()->json($data);
    }

    public function checkExistenceupdatecalls(Request $request)
    {
        $emailCount = 0;
        $phoneCount = 0;
        $phone = $request->input('phone');
        $email = $request->input('email');
        $call_id = $request->input('call_id');
        if ($phone !== null) {
            $cleanedPhone = ltrim($phone, '+');
            $phoneCount = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)->where('id', '<>', $call_id)->count();
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
        $useractivities =  New UserActivities();
            $useractivities->activity = "Open Existing Customer Check List Full";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
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
        $useractivities =  New UserActivities();
            $useractivities->activity = "Export Simple File for Bulk Leads";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
            $filePath = storage_path('app/calls.xlsx'); // Path to the Excel file

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
    public function bulkLeadsDataUplaodExcel()
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Export Simple File for Bulk Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $filePath = public_path('storage/calls.xlsx');
        Log::info("File path is : " . $filePath);

        if (file_exists($filePath)) {
            return Response::download($filePath, 'calls.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        } else {
            return redirect()->back()->with('error', 'The requested file does not exist.');
        }
    }
    public function varinatinfo()
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Variants Info Page";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $Variants = AvailableColour::get();
        return view('variants.vairantinfo', compact('Variants'));
    }
    public function createnewvarinats()
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Create New Variants";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $interiorColors = [
            'Black',
            'Dark Gray',
            'Light Gray',
            'Beige',
            'Tan',
            'Cream',
            'Brown',
            'Ivory',
            'White',
            'Red',
            'Blue',
            'Green',
            'Burgundy',
            'Charcoal',
            'Navy',
            'Silver',
            'Champagne',
            'Pewter',
            'Almond',
            'Ebony',
            'Caramel',
            'Slate',
            'Graphite',
            'Sand',
            'Oyster',
            'Mocha',
            'Parchment',
            'Mahogany',
            'Cocoa',
            'Espresso',
            'Platinum',
            'Jet Black',
            'Stone Gray',
            'Cashmere',
            'Granite',
            'Saddle',
            'Opal Gray',
            'Pebble',
            'Shadow',
            'Walnut',
            'Fawn',
            'Pearl',
            'Chestnut',
            'Sandalwood',
            'Brick',
            'Tawny',
            'Hickory',
            'Tuscan',
            'Driftwood',
            'Olive',
            'Cloud',
            'Raven',
            'Twilight',
            'Chestnut Brown',
            'Mink',
            'Mushroom',
            'Clay',
            'Slate Gray',
            'Flint',
            'Arctic',
            'Sandstone',
            'Ebony Black',
            'Cognac',
            'Russet',
            'Stone',
            'Linen',
            'Carbon',
            'Charcoal Gray',
            'Bamboo',
            'Nutmeg',
            'Canyon',
            'Terra Cotta',
            'Canyon Brown',
            'Steel',
            'Gunmetal',
            'Bamboo Beige',
            'Oatmeal',
            'Mink Brown',
            'Warm Gray',
            'Truffle',
            'Light Stone',
            'Tuxedo Black',
            'Chalk',
            'Agate',
            'Mojave',
            'Blond',
            'Ochre',
            'Natural',
            'Cobblestone',
            'Stone Beige',
            'Light Beige',
            'Granite Gray',
            'Eclipse',
            'Shale',
            'Pumice',
            'Ice',
            'Ash',
            'Tarmac',
            'Dove Gray',
            'Desert Sand',
            'Sable',
            'Cappuccino',
            'Sandy Beige',
            'Mist',
            'Storm',
            'Shetland',
            'Onyx',
            'Chestnut Brown',
            'Iron',
            'Cashew',
            'Pebble Beige',
            'Storm Gray',
            'Shadow Gray',
            'Piano Black',
            // Add more color names here...
        ];
        $exteriorColors = [
            'Black',
            'White',
            'Silver',
            'Gray',
            'Red',
            'Blue',
            'Green',
            'Brown',
            'Beige',
            'Yellow',
            'Orange',
            'Purple',
            'Gold',
            'Bronze',
            'Copper',
            'Charcoal',
            'Navy',
            'Burgundy',
            'Pearl',
            'Metallic',
            'Graphite',
            'Platinum',
            'Champagne',
            'Midnight',
            'Ebony',
            'Crimson',
            'Ruby',
            'Emerald',
            'Sapphire',
            'Amethyst',
            'Topaz',
            'Garnet',
            'Opal',
            'Mocha',
            'Cocoa',
            'Ivory',
            'Cream',
            'Tungsten',
            'Quartz',
            'Titanium',
            'Lunar',
            'Majestic',
            'Mystic',
            'Radiant',
            'Moonlight',
            'Ingot',
            'Cobalt',
            'Azure',
            'Indigo',
            'Slate',
            'Shadow',
            'Steel',
            'Lime',
            'Sunset',
            'Tangerine',
            'Lemon',
            'Olive',
            'Forest',
            'Teal',
            'Mint',
            'Plum',
            'Lavender',
            'Violet',
            'Coral',
            'Copper',
            'Bronze',
            'Sienna',
            'Mahogany',
            'Terra Cotta',
            'Sandstone',
            'Sandy',
            'Desert',
            'Pebble',
            'Stone',
            'Granite',
            'Graphite',
            'Metallic',
            'Midnight Blue',
            'Ruby Red',
            'Emerald Green',
            'Sapphire Blue',
            'Amethyst Purple',
            'Onyx Black',
            'Lunar Silver',
            'Opulent Blue',
            'Magnetic Gray',
            'Pure White',
            'Pearl White',
            'Iridium Silver',
            'Classic Red',
            'Race Blue',
            'Frozen White',
            'Bright Yellow',
            'Sunset Orange',
            'Velvet Red',
            'Deep Blue',
            'Midnight Black',
            'Galaxy Blue',
            'Fire Red',
            'Solar Yellow',
            'Cosmic Black',
            'Crystal White',
            'Phantom Black',
            'Diamond Silver',
            'Ruby Red',
            'Storm Gray',
            'Platinum White',
            'Bronze Metallic',
            'Liquid Blue',
            'Silk Silver',
            'Majestic Blue',
            'Metallic Black',
            'Candy Red',
            'Crystal Blue',
            'Quartz Gray',
            'Slate Gray',
            'Shimmering Silver',
            'Eclipse Black',
            'Hyper Red',
            'Glacier White',
            // Add more color names here...
        ];
        return view('variants.add_new_variants', compact('interiorColors', 'exteriorColors'));
    }
    public function storenewvarinats(Request $request)
    {
        // seems to be this function not using anywhere
        $useractivities =  new UserActivities();
        $useractivities->activity = "Show New Variants";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
        $useractivities =  new UserActivities();
        $useractivities->activity = "Download Rejected Lead List CSV";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $filePath = storage_path('app/public/' . $filename);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->route('calls.createbulk')->with('error', 'File not found.');
        }
    }
    public function addnewleads()
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Add New Lead Page Open";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $Language = Language::get();
        $LeadSource = LeadSource::select('id', 'source_name')->orderBy('source_name', 'ASC')->where('status', 'active')->get();
        $modelLineMasters = MasterModelLines::select('id', 'brand_id', 'model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        return view('calls.sscreate', compact('countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language'));
    }
    public function storeleads(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
            Log::info("Starting auto-assignment for lead creation.");
            $assignmentResult = $this->autoAssignSalesPerson(
                $request->input('email'),
                $request->input('phone'),
                $request->input('language'),
                $request->input('location')
            );
            $sales_person_id = $assignmentResult['user_id'];
            $assignmentReason = $assignmentResult['reason'];
            if ($sales_person_id) {
                $salesPersonName = User::find($sales_person_id)?->name;
                Log::info("Final Assigned Sales Person: $salesPersonName (ID: $sales_person_id) - Reason: $assignmentReason");
            } else {
                Log::warning("No available sales person found for assignment - Reason: $assignmentReason");
            }
        } else {
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
            'language' => is_array($request->input('language')) ? implode(', ', $request->input('language')) : $request->input('language'),
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
        return redirect()->route('home')->with('success', 'Lead Record created successfully');
    }
    public function leadsexport(Request $request)
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open Leads Export Page";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $strategies = Strategy::get();
        $LeadSource = LeadSource::select('id', 'source_name')->orderBy('source_name', 'ASC')->where('status', 'active')->get();
        $modelLineMasters = MasterModelLines::select('id', 'brand_id', 'model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        return view('calls.leadsexport', compact('countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'strategies'));
    }
    public function exportsleadsform(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Export the Leads Data";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $fromDate = $request->input('fromDate');
        $source = $request->input('source');
        $strategy = $request->input('strategy');
        $salesperson = $request->input('salesperson');
        $modelline = $request->input('modelline');
        $priority = $request->input('priority');
        $location = $request->input('location');
        $language = $request->input('language');
        $toDate = date('Y-m-d', strtotime($request->input('toDate') . ' +1 day'));
        $headings = [
            'Name',
            'Phone',
            'Email',
            'Location',
            'Language',
            'Created At',
            'Type',
            'Priority',
            'Custom Brand Model',
            'Sales Person Name',
            'Lead Source Name',
            'Strategies',
            'Model Line',
            'Status',
            'Car Interested In',
            'Purpose of Purchase',
            'End User',
            'Destination Country',
            'Planned Units',
            'Experience with UAE Sourcing',
            'Shipping Assistance Required',
            'Payment Method',
            'Previous Purchase History',
            'Purchase Timeline',
            'General Remark / Additional Notes'
        ];

        $data = \DB::table('calls as c')
            ->join('users as u', 'c.sales_person', '=', 'u.id')
            ->join('lead_source as ls', 'c.source', '=', 'ls.id')
            ->leftJoin('strategies as st', 'c.strategies_id', '=', 'st.id')
            ->leftJoin('calls_requirement as cr', 'c.id', '=', 'cr.lead_id')
            ->leftJoin('master_model_lines as mml', 'cr.model_line_id', '=', 'mml.id')
            ->whereBetween('c.created_at', [$fromDate, $toDate]);
        if ($source !== null) {
            $data->where('c.source', $source);
        }
        if ($strategy !== null) {
            $data->where('c.strategies_id', $strategy);
        }
        if ($salesperson !== null) {
            $data->where('c.sales_person', $salesperson);
        }
        if ($modelline !== null) {
            $data->where('cr.model_line_id', $modelline);
        }
        if ($priority !== null) {
            $data->where('c.priority', $priority);
        }
        if ($location !== null) {
            $data->where('c.location', $location);
        }
        if ($language !== null) {
            $data->where('c.language', $language);
        }
        $data->select(
            'c.name',
            \DB::raw('CAST(c.phone AS UNSIGNED) as phone'),
            'c.email',
            'c.remarks',
            'c.location',
            'c.language',
            'c.created_at',
            'c.type',
            'c.priority',
            'c.custom_brand_model',
            'u.name as sales_person_name',
            'ls.source_name as lead_source_name',
            \DB::raw('IFNULL(st.name, "No Strategy") as strategies'),
            'mml.model_line as model_line',
            'c.status'
        );
        $results = $data->get()->toArray();

        $parsedResults = [];
        foreach ($results as $row) {
            $parsed = [
                'Car Interested In' => '',
                'Purpose of Purchase' => '',
                'End User' => '',
                'Destination Country' => '',
                'Planned Units' => '',
                'Experience with UAE Sourcing' => '',
                'Shipping Assistance Required' => '',
                'Payment Method' => '',
                'Previous Purchase History' => '',
                'Purchase Timeline' => '',
                'General Remark / Additional Notes' => '',
            ];

            if (!empty($row->remarks)) {
                $lines = explode('###SEP###', $row->remarks);
                foreach ($lines as $line) {
                    foreach ($parsed as $key => $val) {
                        if (stripos($line, $key) !== false) {
                            $parts = explode(':', $line, 2);
                            if (isset($parts[1])) {
                                $parsed[$key] = trim($parts[1]);
                            }
                        }
                    }
                }
            }

            unset($row->remarks); // Remove original remarks
            $parsedResults[] = array_merge((array) $row, $parsed);
        }
        return Excel::download(new LeadsExport($parsedResults, $headings), 'leads_export.xlsx');
    }

    /**
     * Helper to auto-assign sales person based on business rules.
     */
    private function autoAssignSalesPerson($email, $phone, $language, $location)
    {
        $assignmentReason = '';
        $excluded_user_ids = User::where('sales_rap', 'Yes')->pluck('id')->toArray();
        $excluded_user_ids = array_unique(array_merge($excluded_user_ids, [204, 42, 20])); // Always exclude Nabia Kamran (204), Abdul Azeem Liaqat (42), Hanif Azad (20)
        $allowed_users = [
            'Lincoln Mukwada',
            'Nwanneka Nwani',
            'Raymond Chikoki',
            'Ali Arous',
            'Mohamad Azizi',
            'Yacine Guella',
            'Sarah Ferhane',
            'Ayoub Ididir',
        ];
        // 'Elie Zouein',
        // 'Manal Khamalli',

        // Get user IDs for allowed users only
        $allowed_user_ids = User::whereIn('name', $allowed_users)->pluck('id')->toArray();

        $isAfrican = false;
        if ($location && in_array($location, Country::where('is_african_country', 1)->pluck('name')->toArray())) {
            $isAfrican = true;
        }
        $cleanedPhone = $phone ? ltrim(preg_replace('/[^\d+]/', '', $phone), '+') : '';
        $matchByEmail = !empty($email) ? Calls::where('email', $email)->whereNotNull('email')->orderBy('created_at', 'desc')->first() : null;
        $matchByPhone = !empty($cleanedPhone) ? Calls::where('phone', 'LIKE', '%' . $cleanedPhone)->whereNotNull('phone')->orderBy('created_at', 'desc')->first() : null;

        // 1. Check for Previous Assignment
        $previousSalesPerson = null;
        if ($matchByEmail && in_array($matchByEmail->sales_person, $allowed_user_ids)) {
            $matchedUser = User::find($matchByEmail->sales_person);
            if ($matchedUser && in_array($matchedUser->name, $allowed_users)) {
                $previousSalesPerson = $matchByEmail->sales_person;
                $assignmentReason = "Previous assignment found by email - assigned to {$matchedUser->name}";
                Log::info("Auto-assignment reason: $assignmentReason");
                return ['user_id' => $previousSalesPerson, 'reason' => $assignmentReason];
            }
        } elseif ($matchByPhone && in_array($matchByPhone->sales_person, $allowed_user_ids)) {
            $matchedUser = User::find($matchByPhone->sales_person);
            if ($matchedUser && in_array($matchedUser->name, $allowed_users)) {
                $previousSalesPerson = $matchByPhone->sales_person;
                $assignmentReason = "Previous assignment found by phone - assigned to {$matchedUser->name}";
                Log::info("Auto-assignment reason: $assignmentReason");
                return ['user_id' => $previousSalesPerson, 'reason' => $assignmentReason];
            }
        }

        // 2. Proceed with New Assignment using min-count + match-by(email/phone/language) strategy
        $userQuery = ModelHasRoles::select('model_id')
            ->where('role_id', 7)
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->whereIn('model_has_roles.model_id', $allowed_user_ids);
        if ($isAfrican) {
            $userQuery->where('users.is_dubai_sales_rep', 'Yes');
        }
        $eligibleUserIds = $userQuery->pluck('model_id')->toArray();

        if (!empty($eligibleUserIds)) {
            // Build current lead counts (New status) for eligible users
            $leadCounts = Calls::whereIn('sales_person', $eligibleUserIds)
                ->where('status', 'New')
                ->selectRaw('sales_person, COUNT(*) as lead_count')
                ->groupBy('sales_person')
                ->pluck('lead_count', 'sales_person')
                ->toArray();

            // Sort candidates by current count asc, then by id asc
            $sortedCandidates = $eligibleUserIds;
            usort($sortedCandidates, function ($a, $b) use ($leadCounts) {
                $countA = $leadCounts[$a] ?? 0;
                $countB = $leadCounts[$b] ?? 0;
                if ($countA === $countB) {
                    return $a <=> $b;
                }
                return $countA <=> $countB;
            });

            $languageNorm = is_string($language) ? strtolower(trim($language)) : '';

            foreach ($sortedCandidates as $candidateId) {
                $matchExists = Calls::where('sales_person', $candidateId)
                    ->where(function ($q) use ($email, $cleanedPhone, $languageNorm) {
                        if (!empty($email)) {
                            $q->orWhere('email', $email);
                        }
                        if (!empty($cleanedPhone)) {
                            $q->orWhere('phone', 'LIKE', '%' . $cleanedPhone);
                        }
                        if (!empty($languageNorm)) {
                            $q->orWhereRaw('LOWER(language) = ?', [$languageNorm]);
                        }
                    })
                    ->exists();

                if ($matchExists) {
                    $assignedUser = User::find($candidateId);
                    $assignmentReason = "Assigned to {$assignedUser->name} (min-count candidate with matching email/phone/language)";
                    Log::info("Auto-assignment reason: $assignmentReason");
                    return ['user_id' => $candidateId, 'reason' => $assignmentReason];
                }
            }

            // No match found across candidates -> pick min-count user on the basis of language (if available)
            $fallbackCandidate = null;
            if (!empty($language)) {
                $langMatched = SalesPersonLaugauges::whereIn('sales_person', $eligibleUserIds)
                    ->where('language', $language)
                    ->pluck('sales_person')
                    ->toArray();
                if (!empty($langMatched)) {
                    // Choose min-count among language-matched users
                    $fallbackCandidate = array_reduce($langMatched, function ($carry, $uid) use ($leadCounts) {
                        if ($carry === null) {
                            return $uid;
                        }
                        $countCarry = $leadCounts[$carry] ?? 0;
                        $countUid = $leadCounts[$uid] ?? 0;
                        if ($countUid === $countCarry) {
                            return $uid < $carry ? $uid : $carry; // tie-break by id
                        }
                        return $countUid < $countCarry ? $uid : $carry;
                    }, null);
                }
            }
            if ($fallbackCandidate === null) {
                // Fall back to absolute min-count among all eligible
                $fallbackCandidate = $sortedCandidates[0];
            }
            $fallbackUser = User::find($fallbackCandidate);
            $currentCount = $leadCounts[$fallbackCandidate] ?? 0;
            $assignmentReason = !empty($language)
                ? "No field match - assigned to min-count language-matched user {$fallbackUser->name} (has {$currentCount} New leads)"
                : "No field match - assigned to min-count user {$fallbackUser->name} (has {$currentCount} New leads)";
            Log::info("Auto-assignment reason: $assignmentReason");
            return ['user_id' => $fallbackCandidate, 'reason' => $assignmentReason];
        }
        
        // FINAL FALLBACK: If no eligible user found, assign to any available active salesperson from allowed list
        $anyUser = ModelHasRoles::select('model_id')
            ->where('role_id', 7)
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->whereIn('model_has_roles.model_id', $allowed_user_ids)
            ->leftJoin('calls', function ($join) {
                $join->on('model_has_roles.model_id', '=', 'calls.sales_person')
                    ->where('calls.status', 'New');
            })
            ->groupBy('model_has_roles.model_id')
            ->orderByRaw('COALESCE(COUNT(calls.id), 0) ASC')
            ->first();
        
        if ($anyUser) {
            $fallbackUser = User::find($anyUser->model_id);
            $assignmentReason = "Final fallback - Assigned to {$fallbackUser->name} from allowed users list";
            Log::info("Auto-assignment reason: $assignmentReason");
            return ['user_id' => $anyUser->model_id, 'reason' => $assignmentReason];
        } else {
            $assignmentReason = "No eligible users found in final fallback";
            Log::warning("Auto-assignment failed: $assignmentReason");
            return ['user_id' => null, 'reason' => $assignmentReason];
        }
    }
}
