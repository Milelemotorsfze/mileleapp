<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\MasterModelLines;
use App\Models\UserActivities;
use App\Models\Clients;
use App\Models\ClientLeads;
use Monarobase\CountryList\CountryListFacade;
use App\Models\LeadSource;
use App\Models\SalespersonOfClients;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        (new UserActivityController)->createActivity('Open Customer List Page');

        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Open Customer Create Page');

        $countries = Country::all();
        return view('customer.create', compact('countries'));

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        (new UserActivityController)->createActivity('New Customer Created');

        $this->validate($request, [
            'name' => 'required',
            'country_id' => 'required',
            'type' => 'required',
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
//        $customer->company_name = $request->company_name;
        $customer->country_id = $request->country_id;
        $customer->type = $request->type;
        $customer->address = $request->address;
        $customer->created_by = Auth::id();

        if ($request->has('passport_file'))
        {
            $file = $request->file('passport_file');

            $extension = $file->getClientOriginalExtension();
            $fileName = 'passport'.time().'.'.$extension;
            $destinationPath = 'customers/passports';
            $file->move($destinationPath, $fileName);

            $customer->passport_file = $fileName;
        }
        if ($request->has('trade_license_file'))
        {
            $file = $request->file('trade_license_file');

            $extension = $file->getClientOriginalExtension();
            $fileName2 = 'trade_license'.time().'.'.$extension;
            $destinationPath = 'customers/trade_licenses';
            $file->move($destinationPath, $fileName2);

            $customer->trade_license_file = $fileName2;
        }

        $customer->save();

        return redirect()->route('dm-customers.index')->with('success','Customer Created Successfully.');
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
        (new UserActivityController)->createActivity('Open Customer Edit Page');

         $customer = Customer::find($id);
         $countries = Country::all();

         return view('customer.edit', compact('customer','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        (new UserActivityController)->createActivity('Customer Detail Updated');

        $this->validate($request, [
            'name' => 'required',
            'country_id' => 'required',
            'type' => 'required',
        ]);

        $customer = Customer::find($id);
        $customer->name = $request->name;
//        $customer->company_name = $request->company_name;
        $customer->country_id = $request->country_id;
        $customer->type = $request->type;
        $customer->address = $request->address;

        if ($request->has('passport_file'))
        {
            $file = $request->file('passport_file');

            $extension = $file->getClientOriginalExtension();
            $fileName = 'passport'.time().'.'.$extension;
            $destinationPath = 'customers/passports';
            $file->move($destinationPath, $fileName);

            $customer->passport_file = $fileName;
        }
        if ($request->has('trade_license_file'))
        {
            $file = $request->file('trade_license_file');

            $extension = $file->getClientOriginalExtension();
            $fileName2 = 'trade_license'.time().'.'.$extension;
            $destinationPath = 'customers/trade_licenses';
            $file->move($destinationPath, $fileName2);

            $customer->trade_license_file = $fileName2;
        }

        $customer->save();

        return redirect()->route('dm-customers.index')->with('success','Customer Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        
        return response(true);

    }
    public function salescustomers(Request $request)
{
    $useractivities = new UserActivities();
    $useractivities->activity = "Open The Customers Page";
    $useractivities->users_id = Auth::id();
    $useractivities->save();
    if ($request->ajax()) {
        $data = Clients::select([
            'id', // Include the Client ID
            'name',
            'phone',
            'email',
            'lauguage',
            'source',
            'destination',
            'customertype',
            'company_name',
            'tradelicense',
            'tender',
            'passport',
        ]);

        return DataTables::of($data)
            ->addColumn('file_icons', function ($row) {
                $icons = '';

                $fileTypes = [
                    'tradelicense' => 'far fa-file-alt',
                    'tender' => 'fas fa-gavel',
                    'passport' => 'fas fa-passport',
                ];

                foreach ($fileTypes as $column => $iconClass) {
                    $fileLink = $row->$column;

                    if (!empty($fileLink)) {
                        $fileName = ucfirst($column);

                        $icons .= '<a href="#" class="file-icon" data-file="' . $fileLink . '" data-toggle="tooltip" title="' . $fileName . '"><i class="' . $iconClass . ' fa-lg"></i></a>';
                    }
                }

                return $icons;
            })
            ->addColumn('view_history_icon', function ($row) {
                return '<a href="#" class="view-history-icon" data-client-id="' . $row->id . '" data-toggle="tooltip" title="View History"><i class="fas fa-history fa-lg"></i></a>';
            })
            ->rawColumns(['file_icons', 'view_history_icon'])
            ->toJson();
    }

    return view('clients.index');
}
    public function viewHistory($clientId)
{
    // return view('clients.view_history', compact('clientId'));
}
public function createcustomers()
{
    $LeadSource = LeadSource::select('id','source_name')->orderBy('source_name', 'ASC')->where('status','active')->whereIn('id', [6, 16, 35, 40])->get();
    $countries = CountryListFacade::getList('en');
    return view('clients.create', compact('countries', 'LeadSource'));
}
        public function storecustomers(Request $request)
        {
        $clients = New Clients();
        $clients->name = $request->input('name');
        $clients->phone = $request->input('phone');
        $clients->email = $request->input('email');
        $clients->source = $request->input('milelemotors');
        $clients->lauguage = $request->input('language');
        $clients->destination = $request->input('location');
        $clients->company_name = $request->input('company_name');
        $clients->customertype = $request->input('customertype');
        $customertype = $request->input('customertype');
        $filetrade = $request->file('tradelicense');
        if ($filetrade) {
                if ($filetrade->isValid()) {
                    $pathtrade = $filetrade->store('tradelicenses');
                    $clients->tradelicense = $pathtrade;
                }
            }
                $filetender = $request->file('tender');
                if ($filetender) {
                if ($filetender->isValid()) {
                    $pathtender = $filetender->store('tenders');
                    $clients->tender = $pathtender;
                }
            }
                $filepassport = $request->file('passport');
                if ($filepassport) {
                if ($filepassport->isValid()) {
                    $pathpassport = $filepassport->store('passports');
                    $clients->passport = $pathpassport;
                }
            }
                $clients->save();   
                $salespersonId = auth()->user()->id;                                                            
                $clientleads = New SalespersonOfClients();
                $clientleads->sales_person_id = $salespersonId; 
                $clientleads->clients_id = $clients->id;
                $clientleads->save();
                return redirect()->route('salescustomers.index')->with('success','Customer Created Successfully');
            }
            public function viewcustomers($clientId)
            {
            $client = Clients::find($clientId);
            if ($client) {
                return view('clients.show', ['client' => $client]);
            } else {
                abort(404, 'Client not found');
            }   
            }
            public function viewleads($clientId)
            {
                $data = ClientLeads::select([
                    DB::raw("GROUP_CONCAT(DISTINCT CONCAT(brands.brand_name, ' - ', master_model_lines.model_line) SEPARATOR ', ') AS brand_model_lines"),
                    'calls.custom_brand_model',
                    'calls.type',
                    'calls.id',
                    'calls.source',
                    'calls.countryofexport',
                    'calls.status',
                    DB::raw("DATE_FORMAT(CONVERT_TZ(calls.created_at, '+00:00', '+03:00'), '%e %b %Y - %h:%i %p') AS formatted_created_at"),
                    'lead_source.source_name',
                    'calls.remarks',
                ])
                    ->leftJoin('calls', 'client_leads.calls_id', '=', 'calls.id')
                    ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                    ->leftJoin('lead_source', 'lead_source.id', '=', 'calls.source')
                    ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                    ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                    ->where('client_leads.clients_id', $clientId)
                    ->where('calls.status', 'New')
                    ->groupBy('calls.id');
                return datatables()->of($data)->make(true);
            }
            public function qoutationview($clientId)
            {
                $data = ClientLeads::select([
                    'quotations.shipping_method',
                    'quotation_details.incoterm',
                    'quotations.currency',
                    'quotations.id',
                    'quotations.calls_id',
                    DB::raw("DATE_FORMAT(CONVERT_TZ(quotations.date, '+00:00', '+03:00'), '%e %b %Y') AS formatted_date"),
                    'quotations.deal_value',
                    'master_shipping_ports.name as portname',
                    'countries.name',
                    'quotation_details.place_of_supply',
                    'quotations.file_path', // Include the file_path column
                    \DB::raw('COUNT(quotation_items.quantity) as quotation_items_count'),
                ])
                    ->leftJoin('calls', 'client_leads.calls_id', '=', 'calls.id')
                    ->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id')
                    ->leftJoin('quotation_details', 'quotations.id', '=', 'quotation_details.id')
                    ->leftJoin('countries', 'countries.id', '=', 'quotation_details.country_id')
                    ->leftJoin('master_shipping_ports', 'master_shipping_ports.id', '=', 'quotation_details.shipping_port_id')
                    ->leftJoin('quotation_items', function ($join) {
                        $join->on('quotations.id', '=', 'quotation_items.quotation_id')
                            ->where('quotation_items.reference_type', 'App\Models\Varaint')
                            ->orWhere('quotation_items.reference_type', 'App\Models\Brand')
                            ->orWhere('quotation_items.reference_type', 'App\Models\MasterModelLines');
                    })
                    ->where('client_leads.clients_id', $clientId)
                    ->where('calls.status', 'Quoted')
                    ->groupBy('quotations.id');
                    return datatables()->of($data)
                    ->addColumn('action', function ($row) {
                    return '<button class="btn btn-info view-file" data-file-path="'.$row->file_path.'" data-toggle="modal" data-target="#fileModal">View File</button>';
                     })
                    ->make(true);
                     }
}
