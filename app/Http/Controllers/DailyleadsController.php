<?php

namespace App\Http\Controllers;

use App\Models\Dailyleads;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivities;
use Illuminate\Support\Facades\Auth;
use App\Models\Calls;
use App\Models\CallsRequirement;
use Illuminate\Support\Facades\Storage;
use App\Models\ModelHasRoles;
use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\LeadDocument;
use App\Models\User;
use App\Models\LeadsTask;
use App\Models\Quotation;
use App\Models\Language;
use App\Models\Rejection;
use App\Models\Closed;
use App\Models\Varaint;
use App\Models\LeadSource;
use App\Models\Brand;
use App\Models\Fellowup;
use App\Models\PreOrder;
use App\Models\So;
use App\Models\Prospecting;
use App\Models\Salesdemand;
use App\Models\SalespersonOfClients;
use App\Models\Negotiation;
use App\Models\Booking;
use App\Models\LeadChat;
use App\Models\LeadChatReply;
use App\Models\Clients;
use App\Models\ClientLeads;
use App\Models\Country;
use App\Models\LeadsLog;
use App\Models\CallsConversationLog;
use Carbon\Carbon;
use App\Models\MasterModelLines;
use Monarobase\CountryList\CountryListFacade;
use App\Models\Logs;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class DailyleadsController extends Controller
{
    public function index(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Open the Daily Leads Section";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = Auth::user()->id;
        $clients = SalespersonOfClients::with('client')
        ->where('sales_person_id', $id)
        ->get();
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access')|| Auth::user()->hasPermissionForSelectedRole('leads-view-only');
        if($hasPermission)
        {
        $pendingdata = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
    ->where('calls.status', 'New')
    ->whereNull('calls.leadtype')
    ->orderByRaw("FIELD(calls.priority, 'Low', 'Normal', 'Hot') DESC")
    ->select('calls.*')
    ->get();
    }
    else
    {
        $pendingdata = Calls::join('lead_source', 'calls.source', '=', 'lead_source.id')
        ->where('calls.status', 'New')
        ->where('calls.sales_person', $id)
        ->whereNull('calls.leadtype')
        ->orderByRaw("FIELD(calls.priority, 'Low', 'Normal', 'Hot') DESC")
        ->orderBy('calls.created_by', 'desc')
        ->select('calls.*')
        ->get();
    }
        if ($request->ajax()) {
            $status = $request->input('status');
            if($status === "Closed")
            {
                $so = so::select([
                    'calls.name as customername',
                    'calls.email',
                    'calls.phone',
                    'calls.created_by',
                    'quotations.created_at',
                    'quotations.deal_value',
                    'quotations.sales_notes',
                    'quotations.file_path',
                    'users.name',
                    'so.so_number',
                    'so.so_date',
                ])
                ->leftJoin('quotations', 'so.quotation_id', '=', 'quotations.id')
                ->leftJoin('users', 'quotations.created_by', '=', 'users.id')
                ->leftJoin('calls', 'quotations.calls_id', '=', 'calls.id')
                ->groupby('so.id')
                ->get();
                return DataTables::of($so)->toJson();  
            }
            if($status === "Preorder")
            {
                $search = $request->input('search.value');

                $preorders = PreOrder::select([
                    'pre_orders.status as status',
                    'quotations.id as quotationsid',
                    \DB::raw("DATE_FORMAT(quotations.date, '%Y %m %d') as date_formatted"),
                    'quotations.deal_value as deal_value',
                    'quotations.sales_notes as sales_notes',
                    'pre_orders_items.qty',
                    'pre_orders_items.notes',
                    'varaints.name',
                    'users.name as salesperson',
                    'countries.name as countryname',
                ])
                ->leftJoin('quotations', 'pre_orders.quotations_id', '=', 'quotations.id')
                ->leftJoin('pre_orders_items', 'pre_orders.id', '=', 'pre_orders_items.preorder_id')
                ->leftJoin('varaints', 'pre_orders_items.variant_id', '=', 'varaints.id')
                ->leftJoin('countries', 'pre_orders_items.countries_id', '=', 'countries.id')
                ->leftJoin('users', 'pre_orders.requested_by', '=', 'users.id')
                ->where('pre_orders.requested_by', $id)
                ->groupby('pre_orders.id')
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('quotations.sales_notes', 'like', "%$search%")
                          ->orWhere('varaints.name', 'like', "%$search%")
                          ->orWhere('countries.name', 'like', "%$search%")
                          ->orWhere('users.name', 'like', "%$search%");
                    });
                });
                return DataTables::of($preorders)->toJson();  
            }
            else if($status === "followup")
            {
                $fellowup = Fellowup::select([
                    'calls.id',
                    'fellow_up.time',
                    \DB::raw("DATE_FORMAT(fellow_up.date, '%Y %m %d') as datefol"),
                    'fellow_up.method',
                    'calls.name',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.leadtype',
                    'calls.created_by', 
                    'calls.location',
                    'calls.language',
                    'master_model_lines.model_line',
                    'brands.brand_name',
                    \DB::raw("DATE_FORMAT(calls.created_at, '%Y %m %d') as leaddate"),
                    'fellow_up.sales_notes',
                    \DB::raw("sales_person_user.name as sales_person_name")
                ])
                ->leftJoin('calls', 'fellow_up.calls_id', '=', 'calls.id')
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id')  // Join for sales_person
                ->where('calls.sales_person', $id)
                ->orderBy('fellow_up.date')
                ->orderBy('fellow_up.time')
                ->groupby('calls.id')
                ->get();
                return DataTables::of($fellowup)->toJson();  
            }
            else if($status === "activelead") {
                $searchValue = $request->input('search.value');
            
                $activelead = Calls::select([
                    'calls.priority',
                    'calls.id',
                    'calls.name',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.created_by',
                    'calls.status',
                    'calls.leadtype', 
                    'calls.location',
                    'calls.language',
                    'master_model_lines.model_line',
                    'brands.brand_name',
                    DB::raw("DATE_FORMAT(calls.created_at, '%Y-%m-%d') as leaddate"),
                    DB::raw("sales_person_user.name as sales_person_name"),
                    DB::raw("created_by_user.name as created_by_name")
                ])
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id')
                ->leftJoin('users as created_by_user', 'calls.created_by', '=', 'created_by_user.id')
                ->whereIn('calls.status', ['contacted', 'working', 'qualify', 'converted', 'Follow Up', 'Prospecting']);
            
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                if(!$hasPermission) {
                    $activelead->where('calls.sales_person', $id);
                }
            
                if (!empty($searchValue)) {
                    $activelead->where(function ($query) use ($searchValue) {
                        $query->where('calls.name', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.phone', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.email', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.language', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.location', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.remarks', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.type', 'LIKE', "%$searchValue%")
                            ->orWhere('brands.brand_name', 'LIKE', "%$searchValue%")
                            ->orWhere('master_model_lines.model_line', 'LIKE', "%$searchValue%");
                    });
                }
                \Log::info('Search Term 11111 : ' . $searchValue);

            
                $activelead = $activelead->groupBy('calls.id');
            
                return DataTables::of($activelead)->toJson(); 
            }
            
            else if($status === "bulkleads")
            {
                $searchValue = $request->input('search.value');
            
                $bulkleads = Calls::select([
                    'calls.id',
                    'calls.name',
                    'calls.phone',
                    'calls.email',
                    'calls.remarks',
                    'calls.type',
                    'calls.location',
                    'calls.created_by',
                    'calls.sales_person',
                    'users.name as createdby',
                    'calls.language',
                    'master_model_lines.model_line',
                    'brands.brand_name',
                    'calls.created_at',
                    \DB::raw("DATE_FORMAT(calls.created_at, '%Y %m %d') as leaddate"),
                ])
                ->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id')
                ->leftJoin('users', 'calls.sales_person', '=', 'users.id')
                ->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                ->whereNotNull('calls.leadtype');
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                if(!$hasPermission) {
                    $bulkleads->where('calls.sales_person', $id);
                }

                $columns = $request->input('columns', []);
                foreach ($columns as $col) {
                    $colName = $col['name'] ?? '';
                    $search = trim($col['search']['value'] ?? '');

                    if ($colName === 'calls.created_at' && $search !== '') {
                        $terms = explode('|', $search);
                        foreach ($terms as $term) {
                            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $term)) {
                                $bulkleads->whereDate('calls.created_at', $term);
                            }
                        }
                    }
                }

                if (!empty($searchValue)) {
                    $bulkleads->where(function ($query) use ($searchValue) {
                        $query->where('calls.name', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.phone', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.email', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.language', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.location', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.remarks', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.type', 'LIKE', "%$searchValue%")
                            ->orWhere('brands.brand_name', 'LIKE', "%$searchValue%")
                            ->orWhere('master_model_lines.model_line', 'LIKE', "%$searchValue%");
                    });
                }
            
                \Log::info('Search Term bulkleads : ' . $searchValue);
            
                $bulkleads = $bulkleads->groupBy('calls.id');
            
                return DataTables::of($bulkleads)->toJson();   
            }
            
            else
            {
            $searchValue = $request->input('search.value');
            $data = Calls::select([
                'calls.id',
                DB::raw("DATE_FORMAT(calls.created_at, '%Y-%m-%d') as created_at"),
                'calls.type',
                'calls.name',
                'calls.phone',
                'calls.email',
                'calls.custom_brand_model',
                'calls.created_by',
                'calls.location',
                'calls.language',
                DB::raw("REPLACE(REPLACE(calls.remarks, '<p>', ''), '</p>', '') as plain_remarks"),
                'calls.remarks'
            ]);            
            if($status === "Prospecting")
            {
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                if($hasPermission)
                {
                    $data->whereIn('calls.status', ['Prospecting', 'New Demand'])->orderBy('created_at', 'desc');
                }
                else
                {
                    $data->whereIn('calls.status', ['Prospecting', 'New Demand'])->where('sales_person', $id)->orderBy('created_at', 'desc');
                }
            }
            else
            {
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access') || Auth::user()->hasPermissionForSelectedRole('leads-view-only');
                if($hasPermission)
                {
                    $data->where('calls.status', $status)->orderBy('created_at', 'desc');
                
                }
                else
                {
                    $data->where('calls.status', $status)->whereNull('calls.leadtype')->where('sales_person', $id)->orderBy('created_at', 'desc');
                }
            }
            $data->addSelect(DB::raw('(SELECT GROUP_CONCAT(CONCAT(brands.brand_name, " - ", master_model_lines.model_line) SEPARATOR ", ") FROM calls_requirement
                JOIN master_model_lines ON calls_requirement.model_line_id = master_model_lines.id
                JOIN brands ON master_model_lines.brand_id = brands.id
                WHERE calls_requirement.lead_id = calls.id) as models_brands'));
                if (!empty($searchValue)) {
                    $data->where(function ($query) use ($searchValue) {
                        $query->where('calls.name', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.created_at', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.email', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.phone', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.custom_brand_model', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.location', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.language', 'LIKE', "%$searchValue%")
                            ->orWhereExists(function ($subquery) use ($searchValue) {
                                $subquery->select(DB::raw(1))
                                    ->from('calls_requirement')
                                    ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
                                    ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
                                    ->whereRaw('calls_requirement.lead_id = calls.id')
                                    ->where(function ($subquery) use ($searchValue) {
                                        $subquery->whereRaw('LOWER(brands.brand_name) LIKE ?', ["%" . strtolower($searchValue) . "%"])
                                            ->orWhereRaw('LOWER(master_model_lines.model_line) LIKE ?', ["%" . strtolower($searchValue) . "%"]);
                                    });
                            });
                    });
                }
                if ($status === 'Prospecting') {
                    $data->addSelect(DB::raw("DATE_FORMAT(prospectings.date, '%Y %m %d') as date"), 'prospectings.salesnotes');
                    $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                    $data->addSelect(
                        DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                        DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes"),
                        DB::raw("IFNULL(demand.purchaserremarks, '') as purchaserremarks"),
                        DB::raw("created_by_user.name as created_by_name"),
                        DB::raw("sales_person_user.name as sales_person_name")
                    );
                    $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                    $data->leftJoin('users as created_by_user', 'calls.created_by', '=', 'created_by_user.id');
                    $data->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id');
                } elseif ($status === 'New Demand') {
                    $data->addSelect(
                        DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                        DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes"),
                        DB::raw("created_by_user.name as created_by_name"),
                        DB::raw("sales_person_user.name as sales_person_name")
                    );
                    $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                    $data->addSelect(DB::raw("DATE_FORMAT(demand.date, '%Y %m %d') as ddate"), 'demand.salesnotes as dsalesnotes');
                    $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                    $data->leftJoin('users as created_by_user', 'calls.created_by', '=', 'created_by_user.id');
                $data->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id');
                } elseif ($status === 'Quoted') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes"),
                    DB::raw("created_by_user.name as created_by_name"),
                    DB::raw("sales_person_user.name as sales_person_name")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->leftJoin('users as created_by_user', 'calls.created_by', '=', 'created_by_user.id');
                $data->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect([
                    'calls.remarks',
                    DB::raw("REPLACE(REPLACE(calls.remarks, '<p>', ''), '</p>', '') as plain_remarks"),
                    DB::raw("DATE_FORMAT(quotations.date, '%Y %m %d') as qdate"),
                    'quotations.sales_notes as qsalesnotes',
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as ddealvalues"),
                    DB::raw("IFNULL(quotations.signature_status, '') as signature_status"),
                    'users.name as salespersonname',
                ]);
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->leftJoin('users', 'quotations.created_by', '=', 'users.id');
                $data->leftJoin('calls_requirement', 'calls.id', '=', 'calls_requirement.lead_id');
                $data->leftJoin('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id');
                $data->leftJoin('brands', 'master_model_lines.brand_id', '=', 'brands.id');

                if (!empty($searchValue)) {
                    $data->where(function ($query) use ($searchValue) {
                        $query->where('calls.name', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.phone', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.email', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.language', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.location', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.type', 'LIKE', "%$searchValue%")
                            ->orWhere('brands.brand_name', 'LIKE', "%$searchValue%")
                            ->orWhere('master_model_lines.model_line', 'LIKE', "%$searchValue%")
                            ->orWhere('calls.remarks', 'LIKE', "%$searchValue%"); 
                    });
                }
                
            } elseif ($status === 'Negotiation') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
            } elseif ($status === 'Closed') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(lead_closed.date, '%Y %m %d'), '') as cdate"),
                    DB::raw("IFNULL(lead_closed.sales_notes, '') as csalesnotes"),
                    DB::raw("IFNULL(lead_closed.so_id, '') as so_id"),
                    DB::raw("CONCAT(IFNULL(FORMAT(quotations.deal_value, 0), ''), ' ', IFNULL(quotations.currency, '')) as cdealvalues"),
                    'users.name as salespersonname',
                );
                $data->leftJoin('lead_closed', 'calls.id', '=', 'lead_closed.call_id');
                $data->leftJoin('so', function ($join) {
                    $join->on('lead_closed.so_id', '=', 'so.id')
                         ->whereNotNull('lead_closed.so_id');
                });
                $data->addSelect('so.so_number');
                $data->leftJoin('users', 'quotations.created_by', '=', 'users.id');
            } elseif ($status === 'Rejected') {
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(prospectings.date, '%Y %m %d'), '') as date"),
                    DB::raw("IFNULL(prospectings.salesnotes, '') as salesnotes"),
                    DB::raw("created_by_user.name as created_by_name"),
                    DB::raw("sales_person_user.name as sales_person_name")
                );
                $data->leftJoin('prospectings', 'calls.id', '=', 'prospectings.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(demand.date, '%Y %m %d'), '') as ddate"),
                    DB::raw("IFNULL(demand.salesnotes, '') as dsalesnotes")
                );
                $data->leftJoin('demand', 'calls.id', '=', 'demand.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(quotations.date, '%Y %m %d'), '') as qdate"),
                    DB::raw("IFNULL(quotations.sales_notes, '') as qsalesnotes"),
                    DB::raw("IFNULL(quotations.file_path, '') as file_path"),
                    DB::raw("CONCAT(IFNULL(quotations.deal_value, ''), ' ', IFNULL(quotations.currency, '')) as qdealvalues"),
                );
                $data->leftJoin('quotations', 'calls.id', '=', 'quotations.calls_id');
                $data->addSelect(
                    DB::raw("IFNULL(DATE_FORMAT(negotiations.date, '%Y %m %d'), '') as ndate"),
                    DB::raw("IFNULL(negotiations.sales_notes, '') as nsalesnotes"),
                    DB::raw("IFNULL(negotiations.file_path, '') as nfile_path"),
                    DB::raw("CONCAT(IFNULL(negotiations.dealvalues, ''), ' ', IFNULL(negotiations.currency, '')) as ndealvalues"),
                );
                $data->leftJoin('negotiations', 'calls.id', '=', 'negotiations.calls_id');
                $data->addSelect(DB::raw("DATE_FORMAT(lead_rejection.date, '%Y %m %d') as rdate"), 'lead_rejection.sales_notes as rsalesnotes', 'lead_rejection.Reason as reason');
                $data->leftJoin('lead_rejection', 'calls.id', '=', 'lead_rejection.call_id');
                $data->leftJoin('users as created_by_user', 'calls.created_by', '=', 'created_by_user.id');
                $data->leftJoin('users as sales_person_user', 'calls.sales_person', '=', 'sales_person_user.id');
            }
            $data->groupBy('calls.id');
            $results = $data->get();

            $results->transform(function ($item) {
                $item->plain_remarks = strip_tags($item->remarks);
                return $item;
            });

            return DataTables::of($results)
    ->addColumn('models_brands', function ($row) {
        return $row->models_brands;
    })
    ->editColumn('plain_remarks', function ($row) {
        return $row->plain_remarks ?? '';
    })
    ->toJson();

        }
    }
        return view('dailyleads.index', compact('pendingdata', 'clients'));
    }
    public function create()
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Create the New Direct Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $countries = CountryListFacade::getList('en');
        $salespersonId = auth()->user()->id;
        $clients = SalespersonOfClients::with('client')
        ->where('sales_person_id', $salespersonId)
        ->get();
        $sales_persons = User::select('id', 'name') 
            ->where('manual_lead_assign', 1)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    
        $modelLineMasters = MasterModelLines::select('id','brand_id','model_line')->orderBy('model_line', 'ASC')->get();
        return view('dailyleads.create', compact('modelLineMasters', 'clients', 'countries','sales_persons'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Store the New Direct Lead";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $modelLineIdsRaw = $request->input('model_line_ids');
        if($modelLineIdsRaw)
        {
        $modelLineIds = json_decode($modelLineIdsRaw, true);
        $modelLineIds = array_map('strval', $modelLineIds);
        }
        $assignid = $request->input('assignto');
        $salesPersonId = $assignid ? $assignid : Auth::id();
        $client = $request->input('client_id');
        if(!$client)
        {
            $date = Carbon::now();
            $date->setTimezone('Asia/Dubai');
            $dataValue = '40';
            $formattedDate = $date->format('Y-m-d H:i:s');
            $data = [
                'source' => $dataValue,
                'type' => $request->input('type'),
                'sales_person' => $salesPersonId,
                'remarks' => $request->input('remarks'),
                'custom_brand_model' => $request->input('custom_brand_model'),
                'created_at' => $formattedDate,
                'assign_time' => $formattedDate,
                'created_by' => Auth::id(),
                'leadtype' => $request->input('leadtype'),
                'status' => "New",
                'priority' => "High",
                'customer_coming_type' => "Direct From Sales",
            ];
            $calls = new Calls($data);
            $calls->save();
        }
        else
        {
        $client = Clients::find($request->input('client_id'));
        $date = Carbon::now();
        $date->setTimezone('Asia/Dubai');
        $dataValue = LeadSource::where('source_name', $client->source)->value('id');
        $formattedDate = $date->format('Y-m-d H:i:s');
        $data = [
            'name' => $client->name,
            'source' => $dataValue,
            'email' => $client->email,
            'type' => $request->input('type'),
            'sales_person' => $salesPersonId,
            'remarks' => $request->input('remarks'),
            'location' => $client->destination,
            'phone' => $client->phone,
            'custom_brand_model' => $request->input('custom_brand_model'),
            'language' => $client->lauguage,
            'created_at' => $formattedDate,
            'assign_time' => $formattedDate,
            'created_by' => Auth::id(),
            'status' => "New",
            'priority' => "High",
            'customer_coming_type' => "Direct From Sales",
        ];
        $calls = new Calls($data);
        $calls->save();
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $calls->id; 
        $clientleads->clients_id = $client->id;
        $clientleads->save();
        }
        $lastRecord = Calls::where('created_by', $data['created_by'])
                   ->orderBy('id', 'desc')
                   ->where('sales_person', Auth::id())
                   ->first();
        $table_id = $lastRecord->id;
        if($modelLineIdsRaw)
        {
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
        }
        $logdata = [
            'table_name' => "calls",
            'table_id' => $table_id,
            'user_id' => Auth::id(),
            'action' => "Create",
        ];
        $model = new Logs($logdata);
        $model->save();
        return redirect()->route('dailyleads.index')
        ->with('success','Lead Record created successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dailyleads $dailyleads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dailyleads $dailyleads)
    {
        //
    }
    public function processStep(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead Status";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $status = $request->input('status');
    $calls = Calls::where('status', $status)->get();

    $data = [];
    foreach ($calls as $call) {
        $modelLines = CallsRequirement::where('lead_id', $call->id)
            ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
            ->pluck('master_model_lines.model_line')
            ->toArray();
        $data[] = [
            'created_at' => $call->created_at,
            'name' => $call->name,
            'type' => $call->type,
            'phone' => $call->phone,
            'email' => $call->email,
            'model_lines' => $modelLines,
            'custom_brand_model' => $call->custom_brand_model,
            'language' => $call->	language,
            'remarks' => $call->remarks
        ];
    }

    return response()->json($data);
}
public function prospecting($id)
    {
        $dailyLead = Calls::findOrFail($id);
        return view('dailyleads.prospecting', ['dailyLead' => $dailyLead]);
    }

    public function qoutations(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead to Qoutation";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'dealValue' => 'nullable|numeric',
        'salesNotes' => 'nullable|string',
        'currency' => 'nullable|string',
    ]);
    $quotation = new quotation();
    $quotation->date = $validatedData['date'];
    $quotation->deal_value = isset($validatedData['dealValue']) ? $validatedData['dealValue'] : '';
    $quotation->sales_notes = isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
    $quotation->currency = $validatedData['currency'];
    $quotation->created_by = auth()->user()->id;
    $quotation->created_at = now();
    $quotation->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('quotation_files', $filename, 'public');
        $quotation->file_path = $path;
    }
    $quotation->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Quoted';
    $call->save();
    return response()->json(['success' => true]);
}
public function rejection(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the lead into Rejection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $rejection = new Rejection();
    $rejection->date = $request->date;
    $rejection->Reason = $request->reason;
    $rejection->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
    $rejection->created_by = auth()->user()->id;
    $rejection->created_at = now();
    $rejection->call_id = $request->callId;
    $rejection->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Rejected';
    $call->save();
    return response()->json(['success' => true]);
}

public function closed(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the lead into Sales Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $sonumber = $request->sonumber;
    $so = So::where('so_number', $sonumber)->first();
    if (!$so) {
        $so = new So();
        $so->so_number = $sonumber;
        $so->sales_person_id = auth()->user()->id;
        $so->so_date = $request->date;
        $so->created_at = now();
        $so->notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $so->save();
    }
    $Closed = new Closed();
    $Closed->date = $request->date;
    $Closed->so_id = $so->id;
    $Closed->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
    $Closed->dealvalues = $request->has('dealvalues') ? $request->dealvalues : '';
    $Closed->currency = $request->currency;
    $Closed->created_by = auth()->user()->id;
    $Closed->created_at = now();
    $Closed->call_id = $request->callId;
    $Closed->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Closed';
    $call->save();
    return response()->json(['success' => true]);
}
public function savenegotiation(Request $request)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Negotiation";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $negotiation = new Negotiation();
    $negotiation->date = $request->date;
    $negotiation->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
    $negotiation->dealvalues = $request->has('dealvalues') ? $request->dealvalues : '';
    $negotiation->currency = $request->currency;
    $negotiation->created_by = auth()->user()->id;
    $negotiation->created_at = now();
    $negotiation->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('negotiation_files', $filename, 'public');
        $negotiation->file_path = $path;
    }
    $negotiation->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Negotiation';
    $call->save();
    return response()->json(['success' => true]);
}
public function saveprospecting(Request $request)
	{
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Prospecting";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $prospecting = new Prospecting();
    $prospecting->date = $validatedData['date'];
    $prospecting->salesnotes =  isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
    $prospecting->created_by = auth()->user()->id;
    $prospecting->created_at = now();
    $prospecting->calls_id = $request->callId;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('prospecting', $filename, 'public');
        $prospecting->file_path = $path;
    }
    $prospecting->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'Prospecting';
    $call->priority = $request->has('priority') ? $request->priority : '';
    $call->save();
    $existingclients = Clients::where('phone', $call->phone)->orwhere('email', $call->email);
    $existingleads = ClientLeads::where('calls_id', $call->id);
    if(!$existingleads)
    {
    if($existingclients)
    {
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $call->id; 
        $clientleads->clients_id = $existingclients->id;
        $clientleads->save();  
    }
    else
    {
        $client = New Clients();
        $client->name = $call->name;
        $client->phone = $call->phone;
        $client->email = $call->email;
        $client->source = $call->source;
        $client->lauguage = $call->language;
        $client->destination = $call->location;
        $client->save();
        $clientleads = New ClientLeads();
        $clientleads->calls_id = $call->id; 
        $clientleads->clients_id =  $client->id;
        $clientleads->save();  
    }
    }
    return response()->json(['success' => true]);
	}
    public function savedemand(Request $request)
	{
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change Lead into Demand";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $validatedData = $request->validate([
        'date' => 'required|date',
        'salesNotes' => 'nullable|string',
    ]);
    $demands = new Salesdemand();
    $demands->date = $validatedData['date'];
    $demands->salesnotes =  isset($validatedData['salesNotes']) ? $validatedData['salesNotes'] : '';
    $demands->created_by = auth()->user()->id;
    $demands->created_at = now();
    $demands->calls_id = $request->callId;
    $demands->save();
    $call = Calls::findOrFail($request->callId);
    $call->status = 'New Demand';
    $call->save();
    return response()->json(['success' => true]);
	}
    public function leadspage($calls_id)
    {
    $calls = Calls::find($calls_id);
    $prospecting = Prospecting::where('calls_id', $calls_id)->get();
    $quotations = quotation::where('calls_id', $calls_id)->get();
    $negotiations = Negotiation::where('calls_id', $calls_id)->get();
    $closed = Closed::where('call_id', $calls_id)->get();
    $bookingDetails = Booking::join('vehicles', 'booking.vehicle_id', '=', 'vehicles.id')
    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
    ->where('booking.calls_id', $calls_id)
    ->select('booking.*', 'vehicles.*', 'brands.brand_name', 'varaints.name', 'master_model_lines.model_line')
    ->groupby('booking.id')
    ->get();
    $demands = Salesdemand::where('calls_id', $calls_id)->get();
    return view('dailyleads.singleleadview', compact('calls', 'prospecting', 'quotations', 'demands', 'negotiations', 'closed', 'bookingDetails'));
    }
    public function savefollowup(Request $request)
	{
        $callsid = $request->callId;
        $callupdate = Calls::find($callsid);
        $callupdate->status = "Follow Up";
        $callupdate->save();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Follow Up";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $followup = new Fellowup();
        $followup->date = $request->has('date') ? $request->date : '';
        $followup->time = $request->has('time') ? $request->time : '';
        $followup->method = $request->has('method') ? $request->method : '';
        $followup->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $followup->calls_id = $callsid;
        $followup->save();
        return response()->json(['success' => true]);
	}
    public function savefollowupdate(Request $request)
	{
        $callsid = $request->callId;
        $callupdate = Calls::find($callsid);
        $callupdate->status = "Follow Up";
        $callupdate->save();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Lead into Follow Up";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $followup = Fellowup::where('calls_id', $callsid)->first();
        $followup->date = $request->has('date') ? $request->date : '';
        $followup->time = $request->has('time') ? $request->time : '';
        $followup->method = $request->has('method') ? $request->method : '';
        $followup->sales_notes = $request->has('salesNotes') ? $request->salesNotes : '';
        $followup->save();
        return response()->json(['success' => true]);
	} 
    public function followupgetdata($id)
    {
        $data = Fellowup::where('calls_id', $id)->first();
        return response()->json($data);
    } 
    public function checkAuthorization(Request $request)
{
    $call = Calls::find($request->call_id);
    if ($call && $call->sales_person == auth()->user()->id) {
        return response()->json(['authorized' => true]);
    } else {
        return response()->json(['authorized' => false]);
    }
}
public function updateCallClient(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'call_id' => 'required|exists:calls,id',
    ]);
    $client = Clients::find($request->client_id);
    if (!$client) {
        return response()->json(['message' => 'Client not found!'], 404);
    }
    $call = Calls::find($request->call_id);
    if (!$call) {
        return response()->json(['message' => 'Call not found!'], 404);
    }
    $call->name = $client->name;
    $call->phone = $client->phone;
    $call->email = $client->email;
    $call->language = $client->lauguage;
    $call->location = $client->destination;
    $call->save();
    $clientLead = new ClientLeads();
    $clientLead->clients_id = $request->client_id;
    $clientLead->calls_id = $request->call_id;
    $clientLead->save();
    return response()->json(['message' => 'Client updated successfully!'], 200);
}
public function leaddetailpage($id)
{
    $lead = Calls::find($id);
    $languages = Language::all();
    $requirements = CallsRequirement::where('lead_id', $id)->with(['masterModelLine.brand', 'country'])->get();
    $brands = Brand::all();
    $countries = Country::get();
    $mastermodellines = MasterModelLines::all();
    $users = User::where('status', 'Active')->get();
    $tasks = LeadsTask::where('lead_id', $id)->orderBy('created_at', 'desc')->get(); 
    $documents = LeadDocument::where('lead_id', $id)->get();
    $logs = DB::table('leads_log')
        ->join('users', 'leads_log.user_id', '=', 'users.id')
        ->select('leads_log.activity', 'leads_log.created_at', 'users.name as user_name')
        ->where('leads_log.lead_id', $id)
        ->orderBy('leads_log.created_at', 'desc')
        ->get();
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        
        if($hasPermission)
        {
        $nextLead = Calls::where('status', '!=', 'Quoted')
        ->where('status', '!=', 'Closed')
        ->where('id', '>', $id)
        ->orderBy('id', 'asc')
        ->first();
        }
        else
        {
        $nextLead = Calls::where('sales_person', auth()->id())
        ->where('status', '!=', 'Quoted')
        ->where('status', '!=', 'Closed')
        ->where('id', '>', $id)
        ->orderBy('id', 'asc')
        ->first();
    }
    // Fetch Previous Lead
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('sales-support-full-access');
        if($hasPermission)
        {
        $previousLead = Calls::where('status', '!=', 'Quoted')
        ->where('status', '!=', 'Closed')
        ->where('id', '<', $id)
        ->orderBy('id', 'desc')
        ->first();
        }
        else
        {
    $previousLead = Calls::where('sales_person', auth()->id())
        ->where('status', '!=', 'Quoted')
        ->where('status', '!=', 'Closed')
        ->where('id', '<', $id)
        ->orderBy('id', 'desc')
        ->first();
        }
    return view('dailyleads.leads', compact('lead', 'languages', 'countries', 'requirements', 'brands', 'mastermodellines', 'countries', 'documents','users','tasks','logs', 'nextLead', 'previousLead'));
}
public function leaddeupdate(Request $request)
{
    $request->validate([
        'field' => 'required|string',
        'value' => 'required',
        'lead_id' => 'required|exists:calls,id',
    ]);
    $lead = Calls::find($request->lead_id);
    $field = $request->field;
    $oldValue = $lead->{$field};
    $lead->{$request->field} = $request->value;
    $log = new LeadsLog();
    $log->user_id = auth()->id();
    $log->lead_id = $request->lead_id;
    $log->activity = 'Updated "' . $field . '" from "' . $oldValue . '" to "' . $request->value . '"';
    $log->save();
    $lead->save();
    return response()->json(['success' => true]);
}
public function removeModelLine($requirementId)
{
    // Find and delete the model line
    $requirement = CallsRequirement::find($requirementId);
    $requirement->delete();
    return response()->json(['success' => true]);
}
public function storeMessages(Request $request)
    {
        $message = LeadChat::create([
            'lead_id' => $request->leadid,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);
        return response()->json($message->load('user'));
    }
    public function storeReply(Request $request)
    {
        $reply = LeadChatReply::create([
            'chat_id' => $request->message_id,
            'user_id' => auth()->id(),
            'reply' => $request->reply
        ]);

        return response()->json($reply->load('user'));
    }
    public function indexmessages($leadid)
    {
        $messages = LeadChat::where('lead_id', $leadid)
                            ->with('user', 'replies.user')
                            ->get();

        return response()->json($messages);
    }
    public function getModelLines($brandId) {
        $modelLines = MasterModelLines::where('brand_id', $brandId)->get();
        return response()->json($modelLines);
    }
    public function getTrimAndVariants($modelLineId) {
        $uniqueTrims = Varaint::where('master_model_lines_id', $modelLineId)->groupby('model_detail')->get();             
        $variants = Varaint::where('master_model_lines_id', $modelLineId)->get();
        return response()->json([
            'trims' => $uniqueTrims,
            'variants' => $variants,
        ]);
    }
    public function addModelLine(Request $request) {
        $trim = $request->input('trim') === 'other' ? $request->input('custom_trim') : $request->input('trim');
        $variant = $request->input('variant') === 'other' ? $request->input('custom_variant') : $request->input('variant');
    $modelLine = new CallsRequirement();
    $modelLine->model_line_id = $request->input('model_line');
    $modelLine->lead_id = $request->input('lead_id');
    $modelLine->qty = $request->input('qty');
    $modelLine->asking_price = $request->input('asking_price');
    $modelLine->offer_price = $request->input('offer_price');
    $modelLine->countries_id = $request->input('countries_id');
    $modelLine->trim = $trim;
    $modelLine->variant = $variant;
    $modelLine->save();
    $modelLine = CallsRequirement::with(['masterModelLine.brand', 'country'])
        ->find($modelLine->id);
        $log = new LeadsLog();
        $log->user_id = auth()->id();
        $log->lead_id = $request->input('lead_id');
        $log->activity = 'Added new model line with ID ' . $modelLine->id . 
                         ' (Brand: ' . ($modelLine->masterModelLine->brand->brand_name ?? 'N/A') . 
                         ', Model Line: ' . ($modelLine->masterModelLine->model_line ?? 'N/A') . 
                         ', Trim: ' . $modelLine->trim . 
                         ', Variant: ' . $modelLine->variant . ')';
        $log->save();
    return response()->json([
        'id' => $modelLine->id,
        'brand' => $modelLine->masterModelLine->brand->brand_name ?? 'N/A',  // Returning brand name
        'model_line' => $modelLine->masterModelLine->model_line ?? 'N/A',
        'trim' => $modelLine->trim,
        'qty' => $modelLine->qty,
        'asking_price' => $modelLine->asking_price,
        'offer_price' => $modelLine->offer_price,
        'country' => $modelLine->country->name ?? 'N/A',
        'variant' => $modelLine->variant,
    ]);
    }
    public function fileupload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('storage/PL_Documents');
            $file->move($destinationPath, $fileNameToStore);
            $filePath = 'storage/PL_Documents/' . $fileNameToStore;
            $document = LeadDocument::create([
                'lead_id' => $request->input('lead_id'),
                'document_name' => $file->getClientOriginalName(),
                'document_path' => $filePath,
                'document_type' => in_array($extension, ['jpg', 'jpeg', 'png']) ? 'image' : 'pdf',
            ]);
            $log = new LeadsLog();
        $log->user_id = auth()->id();
        $log->lead_id = $request->input('lead_id');
        $log->activity = 'Uploading New File Document "' . $document->document_name . '"';
        $log->save();
            return response()->json([
                'success' => true,
                'file' => [
                    'name' => $document->document_name,
                    'url' => url($document->document_path),
                    'type' => $document->document_type
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded.']);
        }
    }
    public function removeFile(Request $request)
{
    $document = LeadDocument::find($request->id);

    if ($document) {
        $filePath = public_path($document->document_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        $log = new LeadsLog();
        $log->user_id = auth()->id();
        $log->lead_id = $document->lead_id;
        $log->activity = 'Deleted the Uploading file Document name is "' . $document->document_name . '"';
        $log->save();
        $document->delete();
        return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
    } else {
        return response()->json(['success' => false, 'message' => 'File not found.']);
    }
}  
public function storeLog(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|integer',
            'conversation' => 'required|string|max:1000',
        ]);
        $log = CallsConversationLog::create([
            'lead_id' => $request->input('lead_id'),
            'conversation' => $request->input('conversation')
        ]);

        return response()->json([
            'success' => true,
            'log' => $log,
            'formatted_time' => Carbon::parse($log->created_at)->format('H:i:s d M Y'),
            'relative_time' => Carbon::parse($log->created_at)->diffForHumans()
        ]);
    }

    public function getLogs($lead_id)
    {
        $logs = CallsConversationLog::where('lead_id', $lead_id)->orderBy('created_at', 'asc')->get();

        return response()->json($logs);
    }
    public function storeTask(Request $request)
    {
        $assigner = User::find($request->assign_by);
        $task = LeadsTask::create([
            'lead_id' => $request->lead_id,
            'assigned_by' => $request->assign_by,
            'task_message' => $request->task_message,
            'status' => 'Pending'
        ]);
        $task->load('assigner');
        $log = new LeadsLog();
        $log->user_id = auth()->id();
        $log->lead_id = $request->lead_id;
        $log->activity = 'Assigned new task to ' . ($assigner ? $assigner->name : 'Unknown') . ': "' . $request->task_message . '"';
        $log->save();
        $leadLink = route('calls.leaddetailpage', ['id' => $request->lead_id]);
        if ($assigner && $assigner->email) {
            Mail::to($assigner->email)->send(new TaskAssigned($assigner, $request->task_message, $leadLink));
        }
        return response()->json([
            'success' => true,
            'task' => $task,
            'created_at' => Carbon::parse($task->created_at)->format('H:i:s d M Y'),
            'relative_time' => Carbon::parse($task->created_at)->diffForHumans(),
            'assigner_name' => $task->assigner ? $task->assigner->name : 'Unknown'
        ]);
    }    
public function getTasks($lead_id)
{
    $tasks = LeadsTask::where('lead_id', $lead_id)
        ->with('assigner')
        ->orderBy('created_at', 'desc')
        ->get();
    return response()->json($tasks);
}
public function tasksupdateStatus(Request $request)
{
    $request->validate([
        'task_id' => 'required|exists:leads_task,id',
        'status' => 'required|in:Pending,In Progress,Completed',
    ]);
    $updated = LeadsTask::where('id', $request->task_id)
        ->update(['status' => $request->status]);
    if ($updated) {
        $log = new LeadsLog();
        $log->user_id = auth()->id();
        $log->lead_id = LeadsTask::find($request->task_id)->lead_id;
        $log->activity = 'Updated task status to ' . $request->status;
        $log->save();
        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'updated_status' => $request->status,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Task not found or status not updated.',
        ], 404);
    }
}
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
        $lead = Calls::findOrFail($id);
        $lead->status = $request->input('status');
        if ($lead->save()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } 
        else {
            return response()->json(['success' => false, 'message' => 'Failed to update status.']);
        }
    }
}