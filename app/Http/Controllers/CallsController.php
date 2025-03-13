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
use App\Rules\ValidPhoneNumber;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Illuminate\Support\Facades\Validator; 

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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Call & Lead Pending Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.index',compact('datahot', 'datanormal', 'datalow', 'countdatalow', 'countdatanormal', 'countdatahot'));
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
                ->addColumn('sales_person', function($row) {
                    $sales_persons = DB::table('users')->where('id', $row->sales_person)->first();
                    return $sales_persons ? $sales_persons->name : '';
                })
                ->addColumn('brands_models', function($row) {
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
                ->addColumn('lead_source', function($row) {
                    $leadsource = DB::table('lead_source')->where('id', $row->source)->first();
                    return $leadsource ? $leadsource->source_name : '';
                })
                ->addColumn('remarks_messages', function($row) {
                    $text = $row->remarks;
                    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
                    return str_replace(['<p>', '</p>'], '', strip_tags($remarks));
                })
                ->addColumn('sales_person_remarks', function($row) {
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
        $data = Calls::where('status','Closed')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->where('created_at', '>=', Carbon::now()->subMonths(2))->get();    
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Call & Lead Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.converted',compact('data'));
    }
    public function rejected()
    {
        $data = Calls::where('status','Rejected')->where(function ($query) {$query->where('customer_coming_type', '')->orWhereNull('customer_coming_type');})->where('created_at', '>=', Carbon::now()->subMonths(2))->get(); 
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Call & Lead Info";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.rejected',compact('data'));
    }
    public function datacenter(Request $request)
    {
        $useractivities =  New UserActivities();
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
                }
                elseif ($columnName === 'location' && $searchValue !== null) {
                    $callsQuery->orWhere('location', 'like', '%' . $searchValue . '%');
                }
                elseif ($columnName === 'custom_brand_model' && $searchValue !== null) {
                    $callsQuery->orWhere('custom_brand_model', 'like', '%' . $searchValue . '%');
                }
                elseif ($columnName === 'remarks' && $searchValue !== null) {
                    $callsQuery->orWhere('remarks', 'like', '%' . $searchValue . '%');
                }
                elseif ($columnName === 'remarks' && $searchValue !== null) {
                    $callsQuery->orWhere('remarks', 'like', '%' . $searchValue . '%');
                }
                elseif ($columnName === 'salesperson' && $searchValue !== null) {
                    $callsQuery->orWhereHas('salesperson', function ($query) use ($searchValue) {
                        $query->where('name', 'like', '%' . $searchValue . '%');
                    });
                }
                else if ($columnName === 'brand_model' && $searchValue !== null) {
                    $callsQuery->orWhereHas('requirements.masterModelLine.brand', function ($query) use ($searchValue) {
                        $query->where('brand_name', 'like', '%' . $searchValue . '%');
                    });
                
                    $callsQuery->orWhereHas('requirements.masterModelLine', function ($query) use ($searchValue) {
                        $query->where('model_line', 'like', '%' . $searchValue . '%');
                    });
                }
                else if ($columnName === 'sales_remarks_coming' && $searchValue !== null) {
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
                }
                else if ($columnName === 'strategies' && $searchValue !== null) {
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
                    }elseif ($demandleads) {
                        return $demandleads->salesnotes;
                    }elseif ($negotiation) {
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
        $Language = Language::get();
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $strategy = Strategy::get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->whereNot('users.id', 20)->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.create', compact('countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language', 'strategy'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        { 
            $this->validate($request, [
                'phone' => ['nullable', 'required_without:email', new ValidPhoneNumber('AE')],
                // 'secondary_phone_number' => ['nullable', new ValidPhoneNumber('AE')],
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
                $excluded_user_ids = User::where('sales_rap', 'Yes')->pluck('id')->toArray();
                $email = $request->input('email');
                $phone = $request->input('phone');
                // $secondaryPhone = $request->input('secondary_phone_number');
                $language = $request->input('language');
                $sales_persons = ModelHasRoles::where('role_id', 7)
                ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                ->where('users.status', 'active')
                ->whereNot('users.id', 20)
                ->get();

                $sales_person_id = null;
                $existing_email_count = null;
                $existing_phone_count = null;
                $existing_language_count = null;

                foreach ($sales_persons as $sales_person) {
                    if ($language == "English") {
                        $existing_email_count = Calls::where('email', $email)
                        ->whereIn('sales_person', $excluded_user_ids)
                        ->whereNotNull('email')
                        ->count();
                        $cleanedPhone = ltrim($phone, '+');
                        $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                        ->whereIn('sales_person', $excluded_user_ids)
                        ->whereNotNull('phone')
                        ->count();
                        if ($existing_email_count > 0 || $existing_phone_count > 0) {
                        if($existing_email_count > 0)
                        {
                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                                    ->whereIn('sales_person', $excluded_user_ids)
                                    ->orWhere('email', $email);
                            })
                            ->where(function ($query) {
                                $query->WhereNotNull('email');
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();
                        $sales_person_id = $sales_person->sales_person;
                        break;
                        }else
                        {
                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();
                        $sales_person_id = $sales_person->sales_person;
                        break;
                        }
                    }
                else
                    {
                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                            ->where('role_id', 7)
                                            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                                            ->where('users.status', 'active')
                                            ->leftJoin('calls', function ($join) {
                                                $join->on('model_has_roles.model_id', '=', 'calls.sales_person')
                                                    ->where('calls.status', 'New');
                                            })
                                            ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                                            ->groupBy('model_has_roles.model_id')
                                            ->orderByRaw('COALESCE(COUNT(calls.id), 0) ASC')
                                            ->first();
                        $sales_person_id = $lowest_lead_sales_person->model_id;
                    }
                } 
                else {
                    $existing_email_count = Calls::where('email', $email)
                    ->whereIn('sales_person', $excluded_user_ids)
                    ->whereNotNull('email')
                    ->count();
                    $cleanedPhone = ltrim($phone, '+');
                    $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                    ->whereIn('sales_person', $excluded_user_ids)
                    ->whereNotNull('phone')
                    ->count();
                    if ($existing_email_count > 0 || $existing_phone_count > 0) {
                    if($existing_email_count > 0)
                    {
                        $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                            $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                                    ->whereIn('sales_person', $excluded_user_ids)
                                ->orWhere('email', $email);
                        })
                        ->where(function ($query) {
                            $query->WhereNotNull('email');
                        })
                        ->orderBy('created_at', 'desc')
                        ->first();
                    $sales_person_id = $sales_person->sales_person;
                    break;
                    }else
                    {
                        $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                            $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                        })
                        ->orderBy('created_at', 'desc')
                        ->first();
                    $sales_person_id = $sales_person->sales_person;
                    break;
                    }
                }
                    else
                    {
                    $sales_person_languages = SalesPersonLaugauges::whereIn('sales_person', $sales_persons->pluck('model_id'))
                    ->where('language', $language)
                    ->get();
                    $existing_language_count = $sales_person_languages->count();     
                    if ($existing_language_count === 1) {
                        $sales_person = $sales_person_languages->first();
                        $sales_person_id = $sales_person->sales_person;
                        break;
                    }
                    elseif ($existing_language_count > 1) {
                        $sales_person_ids = $sales_person_languages->pluck('sales_person');
                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
        ->where('role_id', 7)
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->where('users.status', 'active')
        ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
        ->join('sales_person_laugauges', 'model_has_roles.model_id', '=', 'sales_person_laugauges.sales_person')
        ->whereIn('model_has_roles.model_id', $excluded_user_ids)
        ->whereIn('model_has_roles.model_id', $sales_person_ids)
        ->where('calls.status', 'New')
        ->where('sales_person_laugauges.language', $language)
        ->groupBy('calls.sales_person')
        ->orderByRaw('COUNT(calls.id) ASC')
        ->first();

        $sales_person_id = $lowest_lead_sales_person->model_id;

                        break;
                        }
                    else{
                        $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                        ->where('role_id', 7)
                        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                        ->where('users.status', 'active')
                        ->leftJoin('calls', function ($join) {
                            $join->on('model_has_roles.model_id', '=', 'calls.sales_person')
                                ->where('calls.status', 'New');
                        })
                        ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                        ->groupBy('model_has_roles.model_id')
                        ->orderByRaw('COALESCE(COUNT(calls.id), 0) ASC')
                        ->first();
                        $sales_person_id = $lowest_lead_sales_person->model_id;
                    }
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
            $straigy = $request->input('strategy');
            $strategies_id = Strategy::where('name',$straigy)->first();
            $dataValue = LeadSource::where('source_name', $request->input('milelemotors'))->value('id');
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
                'language' => $request->input('language'),
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
            $useractivities =  New UserActivities();
            $useractivities->activity = "Store New Lead";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
            return redirect()->route('calls.index')
            ->with('success','Call Record created successfully');
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
        $useractivities =  New UserActivities();
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
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        $sales_persons = ModelHasRoles::where('role_id', 7)->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Edit Page of Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.edit', compact('calls','countries', 'modelLineMasters', 'LeadSource', 'sales_persons', 'Language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatehol(Request $request)
    {
        $this->validate($request, [
            'phone' => ['nullable', 'required_without:email', new ValidPhoneNumber('AE')],
            // 'secondary_phone_number' => ['nullable', new ValidPhoneNumber('AE')],
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
		// $model->secondary_phone_number = $request->input('secondary_phone_number');
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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Edit the Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Create Bulk Leads";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        return view('calls.createbulk', compact('countries','LeadSource'));
    }

public function uploadingbulk(Request $request)
    {
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
        $phoneUtil = PhoneNumberUtil::getInstance();

        foreach ($rows as $row) {
            $errorMessages = [];
            $isPhoneValid = false;
            $isEmailValid = false;

            // Extract data
            $name = $row[0];
            $phone = trim($row[1]);
            $email = trim($row[2]);
            $sales_person = $row[4];
            $source_name = $row[5];
            $language = $row[6];
            $location = $row[3];
            $brand = $row[7];
            $model_line_name = $row[8];
            $custom_brand_model = $row[9];
            $remarks = $row[10];
            $strategies = $row[11];
            $priority = $row[12];

            // **Phone Validation**
            if (!empty($phone) && substr($phone, 0, 1) !== '+') {
                $phone = '+' . $phone;
            }
            if (!empty($phone)) {
                try {
                    $numberProto = $phoneUtil->parse($phone, 'null');
                    if ($phoneUtil->isValidNumber($numberProto)) {
                        $phone = $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
                        $isPhoneValid = true;
                    } else {
                        $errorMessages[] = 'Invalid Phone Number';
                        $phone = null;
                    }
                } catch (NumberParseException $e) {
                    $errorMessages[] = 'Invalid Phone Number';
                    $phone = null;
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

            if (!$isPhoneValid && !$isEmailValid) {
                $errorMessages[] = 'Either a valid Email or Phone Number is required';
            }

            // **If any errors, reject the row**
            if (!empty($errorMessages)) {
                $row[] = implode(', ', array_unique($errorMessages)); // Store error messages
                $rejectedRows[] = $row;
                $rejectedCount++;
                continue;
            }

            //   if (
            //     $lead_source_id === 1 || $salesPerson === 'not correct' ||
            //     $language === 'Not Supported' || $location === 'Not Supported' ||
            //     $strategies_id === 1 || (!$phone && !$email)
            // ) {

            //     $filteredRows[] = $row;

            //     if ($salesPerson === 'not correct') {
            //         $errorDescription .= 'Invalid sales person.';
            //     }
            //     if ($lead_source_id === 1) {
            //         $errorDescription .= 'Invalid Source ';
            //     }
            //     if ($strategies_id === 1) {
            //         $errorDescription .= 'Invalid Strategies ';
            //     }
            //     if ($language === 'Not Supported') {
            //         $errorDescription .= 'Invalid Language ';
            //     }
            //     if ($location === 'Not Supported') {
            //         $errorDescription .= 'Invalid Location';
            //     }
            // }

            $acceptedCount++;
        }

        if ($rejectedCount > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $headers = [
                'Name', 'Phone', 'Email', 'Sales Person', 'Source Name', 'Language',
                'Location', 'Brand', 'Model Line Name', 'Custom Brand Model', 'Remarks', 'Error Description'
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
                'message' => "Data upload failed! From the total " . count($rows) . " records, " . $acceptedCount . " were accepted & " . $rejectedCount . " were rejected. No data has been added.",
                'fileLink' => $downloadLink,
            ]);
        }

        else {
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
                $strategies = $row[11];
                $priority = $row[12];
                $errorDescription = '';
                if ($sales_person == null) {
                    $excluded_user_ids = User::where('sales_rap', 'Yes')->pluck('id')->toArray();
                                $sales_persons = ModelHasRoles::where('role_id', 7)->get();
                                $sales_person_id = null;
                                $existing_email_count = null;
                                $existing_phone_count = null;
                                $existing_language_count = null;
                                foreach ($sales_persons as $sales_person) {
                                    if ($language == "English") {
                                        $existing_email_count = Calls::where('email', $email)
                                        ->whereIn('sales_person', $excluded_user_ids)
                                        ->whereNotNull('email')
                                        ->count();
                                        $cleanedPhone = ltrim($phone, '+');
                                        $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                                        ->whereIn('sales_person', $excluded_user_ids)
                                        ->whereNotNull('phone')
                                        ->count();
                                        if ($existing_email_count > 0 || $existing_phone_count > 0) {
                                        if($existing_email_count > 0)
                                        {
                                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                                                        ->whereIn('sales_person', $excluded_user_ids)
                                                    ->orWhere('email', $email);
                                            })
                                            ->where(function ($query) {
                                                $query->WhereNotNull('email');
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $sales_person_id = $sales_person->sales_person;
                                        break;
                                        }else
                                        {
                                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $sales_person_id = $sales_person->sales_person;
                                        break;
                                        }
                                    }
                                        else
                                        {
                                            $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                            ->where('role_id', 7)
                                            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                                            ->where('users.status', 'active')
                                            ->leftJoin('calls', function ($join) {
                                                $join->on('model_has_roles.model_id', '=', 'calls.sales_person')
                                                    ->where('calls.status', 'New');
                                            })
                                            ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                                            ->groupBy('model_has_roles.model_id')
                                            ->orderByRaw('COALESCE(COUNT(calls.id), 0) ASC')
                                            ->first();
                                            $sales_person_id = $lowest_lead_sales_person->model_id;
                                        }
                                    } 
                                    else {
                                        $existing_email_count = Calls::where('email', $email)
                                        ->whereNotNull('email')
                                        ->whereIn('sales_person', $excluded_user_ids)
                                        ->count();
                                        $cleanedPhone = ltrim($phone, '+');
                                        $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                                        ->whereNotNull('phone')
                                        ->whereIn('sales_person', $excluded_user_ids)
                                        ->count();
                                        if ($existing_email_count > 0 || $existing_phone_count > 0) {
                                        if($existing_email_count > 0)
                                        {
                                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                                                ->whereIn('sales_person', $excluded_user_ids)
                                                    ->orWhere('email', $email);
                                            })
                                            ->where(function ($query) {
                                                $query->WhereNotNull('email');
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $sales_person_id = $sales_person->sales_person;
                                        break;
                                        }else
                                        {
                                            $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                                                $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                                        $sales_person_id = $sales_person->sales_person;
                                        break;
                                        }
                                    }
                                    else
                                    {
                                        $sales_person_languages = SalesPersonLaugauges::whereIn('sales_person', $sales_persons->pluck('model_id'))
                                        ->where('language', $language)
                                        ->get();
                                        $existing_language_count = $sales_person_languages->count();
                                        if ($existing_language_count === 1) {
                                            $sales_person = $sales_person_languages->first();
                                            $sales_person_id = $sales_person->sales_person;
                                            break;
                                        }
                                        elseif ($existing_language_count > 1) {
                                            $sales_person_ids = $sales_person_languages->pluck('sales_person');
                                            $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                            ->where('role_id', 7)
                                            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                                            ->where('users.status', 'active')
                                            ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                                            ->join('sales_person_laugauges', 'model_has_roles.model_id', '=', 'sales_person_laugauges.sales_person')
                                            ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                                            ->whereIn('model_has_roles.model_id', $sales_person_ids)
                                            ->where('calls.status', 'New')
                                            ->where('sales_person_laugauges.language', $language)
                                            ->groupBy('calls.sales_person')
                                            ->orderByRaw('COUNT(calls.id) ASC')
                                            ->first();
                                            $sales_person_id = $lowest_lead_sales_person->model_id;
                                            break;
                                            }
                                        else{
                                            $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                            ->where('role_id', 7)
                                            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                                            ->where('users.status', 'active')
                                            ->leftJoin('calls', function ($join) {
                                                $join->on('model_has_roles.model_id', '=', 'calls.sales_person')
                                                    ->where('calls.status', 'New');
                                            })
                                            ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                                            ->groupBy('model_has_roles.model_id')
                                            ->orderByRaw('COALESCE(COUNT(calls.id), 0) ASC')
                                            ->first();
                                            $sales_person_id = $lowest_lead_sales_person->model_id;
                                        }
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
                        $salesPerson = 'not correct';
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
                if($lead_source_id === 1 || $salesPerson === 'not correct' || $language === 'Not Supported' || $location === 'Not Supported' || $strategies_id === 1)
                {
                    $filteredRows[] = $row;
                    if ($salesPerson === 'not correct') {
                        $errorDescription .= 'Invalid sales person.';
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
                    $call->assign_time = Carbon::now();
                    $call->custom_brand_model = $row[9];
                    $call->remarks = $row[10];
                    $call->source = $lead_source_id;
                    $call->strategies_id = $strategies_id;
                    $call->priority = $row[12];
                    $call->language = $row[6];
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
    $useractivities =  New UserActivities();
        $useractivities->activity = "Open Variants Info Page";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $Variants = AvailableColour::get();   
    return view('variants.vairantinfo', compact('Variants'));
}
public function createnewvarinats()
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Create New Variants";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
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
    $useractivities =  New UserActivities();
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
    $useractivities =  New UserActivities();
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
        $useractivities =  New UserActivities();
        $useractivities->activity = "Add New Lead Page Open";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $Language = Language::get();
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
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
            'phone' => ['nullable', 'required_without:email', new ValidPhoneNumber('AE')],            
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
        $excluded_user_ids = User::where('sales_rap', 'Yes')->pluck('id')->toArray();
        $email = $request->input('email');
        $phone = $request->input('phone');
        $language = $request->input('language');
        $sales_persons = ModelHasRoles::where('role_id', 7)
        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
        ->where('users.status', 'active')
        ->get();
        $sales_person_id = null;
        $existing_email_count = null;
        $existing_phone_count = null;
        $existing_language_count = null;
        foreach ($sales_persons as $sales_person) {
            if ($language == "English") {
                $existing_email_count = Calls::where('email', $email)
                ->whereIn('sales_person', $excluded_user_ids)
                ->whereNotNull('email')
                ->count();
                $cleanedPhone = ltrim($phone, '+');
                $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                ->whereIn('sales_person', $excluded_user_ids)
                ->whereNotNull('phone')
                ->count();
                if ($existing_email_count > 0 || $existing_phone_count > 0) {
                if($existing_email_count > 0)
                {
                    $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                        $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                              ->whereIn('sales_person', $excluded_user_ids)
                              ->orWhere('email', $email);
                    })
                    ->where(function ($query) {
                        $query->WhereNotNull('email');
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
                $sales_person_id = $sales_person->sales_person;
                break;
                }else
                {
                    $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                        $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
                $sales_person_id = $sales_person->sales_person;
                break;
                }
            }
                else
                {
                    $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                    ->where('role_id', 7)
                    ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                    ->where('users.status', 'active')
                    ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                    ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                    ->where('calls.status', 'New')
                    ->groupBy('calls.sales_person')
                    ->orderByRaw('COUNT(calls.id) ASC')
                    ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                }
            } 
            else {
                $existing_email_count = Calls::where('email', $email)
                ->whereIn('sales_person', $excluded_user_ids)
                ->whereNotNull('email')
                ->count();
                $cleanedPhone = ltrim($phone, '+');
                $existing_phone_count = Calls::where('phone', 'LIKE', '%' . $cleanedPhone)
                ->whereIn('sales_person', $excluded_user_ids)
                ->whereNotNull('phone')
                ->count();
                if ($existing_email_count > 0 || $existing_phone_count > 0) {
                if($existing_email_count > 0)
                {
                    $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                        $query->where('phone', 'LIKE', '%' . $cleanedPhone)
                                ->whereIn('sales_person', $excluded_user_ids)
                              ->orWhere('email', $email);
                    })
                    ->where(function ($query) {
                        $query->WhereNotNull('email');
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
                $sales_person_id = $sales_person->sales_person;
                break;
                }else
                {
                    $sales_person = Calls::where(function ($query) use ($cleanedPhone, $email, $excluded_user_ids) {
                        $query->where('phone', 'LIKE', '%' . $cleanedPhone)->whereIn('sales_person', $excluded_user_ids);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();
                $sales_person_id = $sales_person->sales_person;
                break;
                }
            }
                else
                {
                $sales_person_languages = SalesPersonLaugauges::whereIn('sales_person', $sales_persons->pluck('model_id'))
                ->where('language', $language)
                ->get();
                $existing_language_count = $sales_person_languages->count();     
                if ($existing_language_count === 1) {
                    $sales_person = $sales_person_languages->first();
                    $sales_person_id = $sales_person->sales_person;
                    break;
                }
                elseif ($existing_language_count > 1) {
                    $sales_person_ids = $sales_person_languages->pluck('sales_person');
                     $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                                        ->where('role_id', 7)
                                        ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                                        ->where('users.status', 'active')
                                        ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                                        ->join('sales_person_laugauges', 'model_has_roles.model_id', '=', 'sales_person_laugauges.sales_person')
                                        ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                                        ->where('calls.status', 'New')
                                        ->whereIn('model_has_roles.model_id', $sales_person_ids)
                                        ->where('sales_person_laugauges.language', $language)
                                        ->groupBy('calls.sales_person')
                                        ->orderByRaw('COUNT(calls.id) ASC')
                                        ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                    break;
                    }
                else{
                    $lowest_lead_sales_person = ModelHasRoles::select('model_id')
                    ->where('role_id', 7)
                    ->join('users', 'model_has_roles.model_id', '=', 'users.id')
                    ->where('users.status', 'active')
                    ->join('calls', 'model_has_roles.model_id', '=', 'calls.sales_person')
                    ->where('calls.status', 'New')
                    ->whereIn('model_has_roles.model_id', $excluded_user_ids)
                    ->groupBy('calls.sales_person')
                    ->orderByRaw('COUNT(calls.id) ASC')
                    ->first();
                    $sales_person_id = $lowest_lead_sales_person->model_id;
                }
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
        return redirect()->route('home')->with('success', 'Lead Record created successfully');
    }
    public function leadsexport(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open Leads Export Page";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $strategies = Strategy::get();
        $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->get();
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
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
        'Remarks',
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
    return Excel::download(new LeadsExport($results, $headings), 'leads_export.xlsx');
}
}