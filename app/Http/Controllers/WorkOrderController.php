<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\WOVehicles;
use App\Models\WOVehicleRecordHistory;
use App\Models\WOVehicleAddons;
use App\Models\WOVehicleAddonRecordHistory;
use App\Models\WOComments;
use App\Models\CommentVehicleMapping;
use App\Models\CommentVehicleAddonMapping;
use App\Models\CommentFile;
use App\Models\WORecordHistory;
use App\Models\WOApprovals;
use App\Models\WOApprovalDataHistory;
use App\Models\WOApprovalDepositAganistVehicle;
use App\Models\WOApprovalAddonDataHistory;
use App\Models\WOApprovalVehicleDataHistory;
use App\Models\WoStatus;
use App\Models\Customer;
use App\Models\Clients;
use App\Models\Vehicles;
use App\Models\User;
use App\Models\So;
use Spatie\Permission\Models\Role;
use App\Models\AddonDetails;
use App\Models\WOUserFilterInputs;
use App\Models\Masters\MasterAirlines;
use App\Models\Masters\MasterCharges;
use App\Models\Masters\MasterOfficeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use App\Http\Controllers\UserActivityController;
use App\Http\Requests\StoreWorkOrderRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use App\Models\WOBOE;
use App\Mail\WOBOEStatusMail;
use Illuminate\Pagination\LengthAwarePaginator;
use Rap2hpoutre\FastExcel\FastExcel;

class WorkOrderController extends Controller
{
    /**
     * Filter out fahad@milele.com from email recipient lists
     * This ensures fahad@milele.com never receives work order emails
     */
    private function filterExcludedEmails($emails)
    {
        $excludedEmails = ['fahad@milele.com'];
        
        if (is_array($emails)) {
            return array_filter($emails, function($email) use ($excludedEmails) {
                return !in_array(strtolower(trim($email)), array_map('strtolower', $excludedEmails));
            });
        }
        
        return $emails;
    }
    public function workOrderCreate($type)
    {
        $authId = Auth::id();
        (new UserActivityController)->createActivity('Open ' . $type . ' work order create page');

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'view-current-user-export-exw-wo-list',
            'view-current-user-export-cnf-wo-list',
            'view-current-user-local-sale-wo-list'
        ]);
        $formData = $this->getWorkOrderFormDropdownData($authId, $hasLimitedAccess, null, false);

        return view('work_order.export_exw.create', array_merge(compact('type'), $formData));
    }
    /**
     * Display a listing of the resource.
     */
    public function index($type, Request $request)
    {
        $search = $request->query('search'); // Get the 'search' query parameter
        $authId = Auth::id();
        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'view-current-user-export-exw-wo-list',
            'view-current-user-export-cnf-wo-list',
            'view-current-user-local-sale-wo-list'
        ]);

        // Fetch saved filters for the current user and decode JSON into an array
        $savedFilters = WOUserFilterInputs::where('user_id', $authId)->first();
        $filters = $savedFilters ? json_decode($savedFilters->filters, true) : [];
        $statuses = WoStatus::distinct()->orderBy('status', 'asc')->pluck('status');

        // Dropdown options must be computed, but avoid loading ALL records into memory.
        $salesSupportDataConfirmations = collect();
        if (WorkOrder::whereNull('sales_support_data_confirmation_at')->exists()) {
            $salesSupportDataConfirmations->push('Not Confirmed');
        }
        if (WorkOrder::whereNotNull('sales_support_data_confirmation_at')->exists()) {
            $salesSupportDataConfirmations->push('Confirmed');
        }
        $salesSupportDataConfirmations = $salesSupportDataConfirmations->unique()->sort()->values();

        // Latest finance/coo status per work order (matches WorkOrder accessors which use latest by updated_at).
        $latestFinanceSub = DB::table('w_o_approvals as wa')
            ->joinSub(
                DB::table('w_o_approvals')
                    ->select('work_order_id', DB::raw('MAX(updated_at) as max_updated_at'))
                    ->where('type', 'finance')
                    ->groupBy('work_order_id'),
                'm',
                function ($join) {
                    $join->on('wa.work_order_id', '=', 'm.work_order_id')
                        ->on('wa.updated_at', '=', 'm.max_updated_at');
                }
            )
            ->where('wa.type', 'finance')
            ->select('wa.work_order_id', 'wa.status');

        $latestCooSub = DB::table('w_o_approvals as wa')
            ->joinSub(
                DB::table('w_o_approvals')
                    ->select('work_order_id', DB::raw('MAX(updated_at) as max_updated_at'))
                    ->where('type', 'coo')
                    ->groupBy('work_order_id'),
                'm',
                function ($join) {
                    $join->on('wa.work_order_id', '=', 'm.work_order_id')
                        ->on('wa.updated_at', '=', 'm.max_updated_at');
                }
            )
            ->where('wa.type', 'coo')
            ->select('wa.work_order_id', 'wa.status');

        $financeApprovalStatuses = $latestFinanceSub
            ->distinct()
            ->pluck('status')
            ->map(function ($status) {
                return match ($status) {
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    default => null,
                };
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $financeApprovalStatuses = $financeApprovalStatuses->push('Blank')->sort()->values();

        $cooApprovalStatuses = $latestCooSub
            ->distinct()
            ->pluck('status')
            ->map(function ($status) {
                return match ($status) {
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    default => null,
                };
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $cooApprovalStatuses = $cooApprovalStatuses->push('Blank')->sort()->values();

        // Docs status options: emulate WorkOrder::docs_status accessor (override to "Not Initiated").
        $latestDocsSub = DB::table('wo_docs_status as d')
            ->joinSub(
                DB::table('wo_docs_status')
                    ->select('wo_id', DB::raw('MAX(doc_status_changed_at) as max_doc_status_changed_at'))
                    ->groupBy('wo_id'),
                'm',
                function ($join) {
                    $join->on('d.wo_id', '=', 'm.wo_id')
                        ->on('d.doc_status_changed_at', '=', 'm.max_doc_status_changed_at');
                }
            )
            ->select('d.wo_id', 'd.is_docs_ready');

        $docsStatuses = DB::table('work_orders as wo')
            ->leftJoinSub($latestFinanceSub, 'fin', function ($join) {
                $join->on('wo.id', '=', 'fin.work_order_id');
            })
            ->leftJoinSub($latestCooSub, 'coo', function ($join) {
                $join->on('wo.id', '=', 'coo.work_order_id');
            })
            ->leftJoinSub($latestDocsSub, 'docs', function ($join) {
                $join->on('wo.id', '=', 'docs.wo_id');
            })
            ->selectRaw("
                CASE
                    WHEN wo.sales_support_data_confirmation_at IS NOT NULL
                         AND fin.status = 'approved'
                         AND coo.status = 'approved'
                    THEN 'Not Initiated'
                    ELSE COALESCE(docs.is_docs_ready, 'Blank')
                END as docs_status
            ")
            ->distinct()
            ->orderBy('docs_status', 'asc')
            ->pluck('docs_status');

        // These are used for UI legends/tooltips (not the heavy list dataset).
        // Not used by the current export list UI (see export_exw/index.blade.php).
        // Keeping these empty avoids querying non-existent DB columns (they're computed accessors).
        $vehiclesModificationSummary = collect();
        $pdiSummary = collect();
        $deliverySummary = collect();
        $columns = [
            'all' => [
                'id',
                'type',
                'date',
                'so_number',
                'temporary_exit',
                'delivery_advise',
                'showroom_transfer',
                'cross_trade',
                'is_batch',
                'batch',
                'wo_number',
                'customer_name',
                'customer_email',
                'customer_company_number',
                'customer_address',
                'customer_representative_name',
                'customer_representative_email',
                'customer_representative_contact',
                'freight_agent_name',
                'freight_agent_email',
                'freight_agent_contact_number',
                'port_of_loading',
                'port_of_discharge',
                'final_destination',
                'transport_type',
                'brn_file',
                'brn',
                'container_number',
                'airline',
                'airway_bill',
                'shipping_line',
                'forward_import_code',
                'trailer_number_plate',
                'transportation_company',
                'transporting_driver_contact_number',
                'airway_details',
                'transportation_company_details',
                'currency',
                'so_total_amount',
                'so_vehicle_quantity',
                'amount_received',
                'balance_amount',
                'delivery_location',
                'delivery_contact_person',
                'delivery_contact_person_number',
                'delivery_date',
                'preferred_shipping_line_of_customer',
                'bill_of_loading_details',
                'shipper',
                'consignee',
                'notify_party',
                'special_or_transit_clause_or_request',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'sales_support_data_confirmation_at',
                'updated_by',
                'sales_person_id',
                'created_by',
                'created_at',
                'updated_at',
                'lto',
                'has_claim'
            ],
            'status_report' => [
                'id',
                'date',
                'so_number',
                'is_batch',
                'batch',
                'wo_number',
                'airway_details',
                'sales_support_data_confirmation_at',
                'updated_by',
                'sales_person_id',
                'created_by',
                'created_at',
                'updated_at'
            ],
            'export_exw' => [
                'id',
                'type',
                'date',
                'so_number',
                'temporary_exit',
                'delivery_advise',
                'showroom_transfer',
                'is_batch',
                'batch',
                'wo_number',
                'customer_name',
                'customer_email',
                'customer_company_number',
                'customer_address',
                'customer_representative_name',
                'customer_representative_email',
                'customer_representative_contact',
                'freight_agent_name',
                'freight_agent_email',
                'freight_agent_contact_number',
                'port_of_loading',
                'port_of_discharge',
                'final_destination',
                'transport_type',
                'brn_file',
                'brn',
                'container_number',
                'airline',
                'airway_bill',
                'shipping_line',
                'forward_import_code',
                'trailer_number_plate',
                'transportation_company',
                'transporting_driver_contact_number',
                'airway_details',
                'transportation_company_details',
                'currency',
                'so_total_amount',
                'so_vehicle_quantity',
                'amount_received',
                'balance_amount',
                'delivery_location',
                'delivery_contact_person',
                'delivery_contact_person_number',
                'delivery_date',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'sales_support_data_confirmation_at',
                'updated_by',
                'sales_person_id',
                'created_by',
                'created_at',
                'updated_at',
                'has_claim'
            ],
            'export_cnf' => [
                'id',
                'type',
                'date',
                'so_number',
                'temporary_exit',
                'cross_trade',
                'is_batch',
                'batch',
                'wo_number',
                'customer_name',
                'customer_email',
                'customer_company_number',
                'customer_address',
                'customer_representative_name',
                'customer_representative_email',
                'customer_representative_contact',
                'port_of_loading',
                'port_of_discharge',
                'final_destination',
                'transport_type',
                'brn_file',
                'brn',
                'container_number',
                'airline',
                'airway_bill',
                'shipping_line',
                'forward_import_code',
                'trailer_number_plate',
                'transportation_company',
                'transporting_driver_contact_number',
                'airway_details',
                'transportation_company_details',
                'currency',
                'so_total_amount',
                'so_vehicle_quantity',
                'amount_received',
                'balance_amount',
                'delivery_location',
                'delivery_contact_person',
                'delivery_contact_person_number',
                'delivery_date',
                'preferred_shipping_line_of_customer',
                'bill_of_loading_details',
                'shipper',
                'consignee',
                'notify_party',
                'special_or_transit_clause_or_request',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'sales_support_data_confirmation_at',
                'updated_by',
                'sales_person_id',
                'created_by',
                'created_at',
                'updated_at',
                'has_claim'
            ],
            'local_sale' => [
                'id',
                'date',
                'so_number',
                'wo_number',
                'customer_name',
                'customer_email',
                'customer_company_number',
                'customer_address',
                'customer_representative_name',
                'customer_representative_email',
                'customer_representative_contact',
                'transporting_driver_contact_number',
                'airway_details',
                'currency',
                'so_total_amount',
                'so_vehicle_quantity',
                'amount_received',
                'balance_amount',
                'delivery_location',
                'delivery_contact_person',
                'delivery_contact_person_number',
                'delivery_date',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'sales_support_data_confirmation_at',
                'updated_by',
                'sales_person_id',
                'created_by',
                'created_at',
                'updated_at',
                'lto',
                'has_claim'
            ],
        ];
        $nonSearchableFields = [
            'id',
            'brn_file',
            'signed_pfi',
            'signed_contract',
            'payment_receipts',
            'noc',
            'enduser_trade_license',
            'enduser_passport',
            'enduser_contract',
            'updated_by',
            'sales_person_id',
            'created_by',
            'vehicle_handover_person_id'
        ];
        // Helper function to apply select and search
        $applySelectAndSearch = function ($query, $search, $columns) use ($nonSearchableFields) {
            $query->select($columns);
            // Check if there is a search term
            if ($search) {
                $searchableColumns = array_filter($columns, function ($column) use ($nonSearchableFields) {
                    return !in_array($column, $nonSearchableFields);
                });
                $searchTerms = preg_split('/\s+/', $search);
                $query->where(function ($query) use ($searchTerms, $searchableColumns) {
                    foreach ($searchTerms as $term) {
                        $singleBatchTerms = ['single', 'singl', 'sing', 'sin', 'si', 's'];
                        if (in_array(strtolower($term), $singleBatchTerms)) {
                            $query->orWhere('is_batch', 0);
                        }
                        $query->orWhere(function ($query) use ($term, $searchableColumns) {
                            foreach ($searchableColumns as $column) {
                                $query->orWhere($column, 'LIKE', "%{$term}%");
                            }
                        });
                        $query->orWhereHas('salesPerson', fn($q) => $q->where('name', 'LIKE', "%{$term}%"))
                            ->orWhereHas('CreatedBy', fn($q) => $q->where('name', 'LIKE', "%{$term}%"))
                            ->orWhereHas('UpdatedBy', fn($q) => $q->where('name', 'LIKE', "%{$term}%"))
                            ->orWhereRaw("DATE_FORMAT(date, '%b') = ?", [$term])
                            ->orWhereRaw("DATE_FORMAT(date, '%M') = ?", [$term])
                            ->orWhereRaw("DATE_FORMAT(created_at, '%b') = ?", [$term])
                            ->orWhereRaw("DATE_FORMAT(created_at, '%M') = ?", [$term])
                            ->orWhereRaw("DATE_FORMAT(updated_at, '%b') = ?", [$term])
                            ->orWhereRaw("DATE_FORMAT(updated_at, '%M') = ?", [$term]);
                    }
                });
            }
        };
        $needsInMemoryPostFilters = !empty($filters['finance_approval_filter'])
            || !empty($filters['coo_approval_filter'])
            || !empty($filters['docs_status_filter']);
        $isExcelExport = $request->export == 'EXCEL';

        $baseQuery = WorkOrder::with([
            'salesPerson',
            'CreatedBy',
            'UpdatedBy',
            'latestFinance',
            'latestCOO',
            'currentFinanceApproval',
            'firstFinanceApproval',
            'currentCooApproval',
            'firstCooApproval',
            'latestStatus',
            'latestDocsStatus',
            // Only load vehicle fields required for list summaries/counts.
            'vehicles' => function ($q) {
                // modification_status/pdi_status/delivery_status are computed accessors,
                // so we only select real columns needed by those accessors.
                $q->select('id', 'work_order_id', 'boe_number', 'modification_or_jobs_to_perform_per_vin', 'deleted_at')
                    ->with([
                        'addons',
                        'latestModificationStatus',
                        'latestPdiStatus',
                        'latestDeliveryStatus',
                    ]);
            },
        ])
            ->when(array_key_exists($type, $columns), function ($query) use ($type, $search, $columns, $applySelectAndSearch) {
                $applySelectAndSearch($query, $search, $columns[$type]);
            })
            ->when($type !== 'all' && $type !== 'status_report', function ($queryType) use ($type) {
                return $queryType->where('type', $type);
            })
            ->when($hasLimitedAccess, function ($queryLimited) use ($authId) {
                return $queryLimited->where(function ($subQuery) use ($authId) {
                    $subQuery->where('created_by', $authId)
                        ->orWhere('sales_person_id', $authId);
                });
            })
            ->when($filters, function ($queryStatus) use ($filters) {
                // Apply status filter
                if (!empty($filters['status_filter'])) {
                    $queryStatus->whereHas('latestStatus', function ($statusFltr) use ($filters) {
                        $statusFltr->whereIn('status', $filters['status_filter']);
                    });
                }

                // Apply sales support filter
                if (!empty($filters['sales_support_filter'])) {
                    $queryStatus->where(function ($salesSupportCnfrm) use ($filters) {
                        if (in_array('Confirmed', $filters['sales_support_filter'])) {
                            $salesSupportCnfrm->orWhereNotNull('sales_support_data_confirmation_at');
                        }
                        if (in_array('Not Confirmed', $filters['sales_support_filter'])) {
                            $salesSupportCnfrm->orWhereNull('sales_support_data_confirmation_at');
                        }
                    });
                }
            })
            ->when(!empty($request->start_date) && !empty($request->end_date), function ($query) use ($request) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            })
            ->latest()
            ;

        // Fast path: no finance/coo/docs in-memory post-filtering and not exporting Excel.
        if (!$isExcelExport && !$needsInMemoryPostFilters) {
            $page = request()->get('page', 1);
            $perPage = 100;

            $datas = $baseQuery->paginate($perPage, ['*'], 'page', $page);
            $datas->appends(request()->query());

            return view('work_order.export_exw.index', compact(
                'type',
                'datas',
                'filters',
                'statuses',
                'salesSupportDataConfirmations',
                'search',
                'financeApprovalStatuses',
                'cooApprovalStatuses',
                'docsStatuses',
                'vehiclesModificationSummary',
                'pdiSummary',
                'deliverySummary'
            ));
        }

        $datas = $baseQuery->get();
        $filteredDatas = $datas;
        if (isset($filters['finance_approval_filter']) && !empty($filters['finance_approval_filter'])) {
            $normalizedFinanceApprovalFilter = array_map('strtolower', $filters['finance_approval_filter']);
            $includeBlank = in_array('blank', $normalizedFinanceApprovalFilter);
            $filteredDatas = $filteredDatas->filter(function ($data) use ($normalizedFinanceApprovalFilter, $includeBlank) {
                if ($includeBlank && (!$data->latestFinance || $data->can_show_fin_approval == 'no')) {
                    return true;
                }
                return $data->latestFinance && in_array(strtolower($data->latestFinance->status), $normalizedFinanceApprovalFilter) && $data->can_show_fin_approval == 'yes';
            });
        }
        if (isset($filters['coo_approval_filter']) && !empty($filters['coo_approval_filter'])) {
            $normalizedCOOApprovalFilter = array_map('strtolower', $filters['coo_approval_filter']);
            $includeBlankCOO = in_array('blank', $normalizedCOOApprovalFilter);
            $filteredDatas = $filteredDatas->filter(function ($data) use ($normalizedCOOApprovalFilter, $includeBlankCOO) {
                if ($includeBlankCOO && (!$data->latestCOO || $data->can_show_coo_approval == 'no')) {
                    return true;
                }
                return $data->latestCOO && in_array(strtolower($data->latestCOO->status), $normalizedCOOApprovalFilter) && $data->can_show_coo_approval == 'yes';
            });
        }
        if (isset($filters['docs_status_filter']) && !empty($filters['docs_status_filter'])) {
            $docsStatusFilter = $filters['docs_status_filter'];
            $includeBlankDocs = in_array('Blank', $docsStatusFilter);

            $filteredDatas = $datas->filter(function ($data) use ($docsStatusFilter, $includeBlankDocs) {
                // Check if docs_status is in filter list and other conditions are met
                $matchesFilter = in_array($data->docs_status, $docsStatusFilter)
                    && $data->sales_support_data_confirmation_at !== null
                    && $data->coo_approval_status === 'Approved'
                    && $data->finance_approval_status === 'Approved';

                // Handle cases where docs_status is "Blank" and other conditions aren't fully met
                $matchesBlank = $includeBlankDocs
                    && ($data->docs_status == 'Blank')
                    && ($data->sales_support_data_confirmation_at === null
                        || $data->coo_approval_status !== 'Approved'
                        || $data->finance_approval_status !== 'Approved');

                return $matchesFilter || $matchesBlank;
            });
        }
        if ($request->export == 'EXCEL') {
            (new UserActivityController)->createActivity('Export Work Order List');

            return (new FastExcel($filteredDatas))->download('work-orders.csv', function ($data) {

                return [
                    'Type' => $data->type,
                    'status' => $data->latestStatus->status ?? '',
                    'Sales Support Confirmation' => $data->sales_support_data_confirmation ?? '',
                    'Finance Approval Status' => $data->finance_approval_status ?? '',
                    'COO Office Approval Status' => $data->coo_approval_status ?? '',
                    'Documentation Status' => $data->docs_status ?? '',
                    'Vehicle Modification status' => $data->vehicles_modification_summary ?? '',
                    'PDI Status' => $data->pdi_summary ?? '',
                    'Delivery Status' => $data->delivery_summary ?? '',
                    'Sales Person Name' => $data->salesPerson->name ?? '',
                    'SO Number' => $data->so_number,
                    'WO Number' => $data->wo_number,
                    'Date' => $data->date,
                    'Batch' => $data->is_batch == 0 ? 'Single' : $data->batch ?? '',
                    'Customer Name' => $data->customer_name ?? '',
                    'Customer Email' => $data->customer_email ?? '',
                    'Customer Contact Number' => "\t" . $data->customer_company_number ?? '',
                    'Customer Representative Name' => $data->customer_representative_name ?? '',
                    'Customer Representative Email' => $data->customer_representative_email ?? '',
                    'Customer Representative Number' =>  "\t" . $data->customer_representative_contact ?? '',
                    'Freight Agent Name' => $data->freight_agent_name ?? '',
                    'Freight Agent Email' => $data->freight_agent_email ?? '',
                    'Freight Agent Contact Number' => "\t" . $data->freight_agent_contact_number ?? '',
                    'Delivery Advise' => $data->delivery_advise ?? '',
                    'Transfer Of Ownership' => $data->showroom_transfer ?? '',
                    'Cross Trade' => $data->cross_trade ?? '',
                    'LTO' => $data->lto ?? '',
                    'Temporary Exit' => $data->temporary_exit ?? '',
                    'Port Of Loading' => $data->port_of_loading ?? '',
                    'Port Of Discharge' => $data->port_of_discharge ?? '',
                    'Final Destination' => $data->final_destination ?? '',
                    'Transport Type' => $data->transport_type ?? '',
                    'Airline/Shipping Line/Trailer No.' => $data->transport_type ? $data->getTransportField('name') ?? '' : '',
                    'AWB/Container No./Transportation Company' => $data->transport_type ? $data->getTransportField('id') ?? '' : '',
                    'Airway Info/Fwd Import Code/Driver Contact No.' => $data->transport_type ? $data->getTransportField('details') ?? '' : '',
                    'BRN/Transportation Com. Info' => $data->transport_type === 'sea' || $data->transport_type === 'road' ? $data->getTransportField('additional') ?? '' : '',
                    'SO Vehicle Quantity' => $data->so_vehicle_quantity ?? '',
                    'SO Currency' => $data->currency ?? '',
                    'SO Amount' => $data->so_total_amount != 0.00 ? $data->so_total_amount : '',
                    'Deposit' => $data->amount_received != 0.00 ? $data->amount_received : '',
                    'Balance' => $data->balance_amount != 0.00 ? $data->balance_amount : '',
                    'Delivery Location' => $data->delivery_location ?? '',
                    'Delivery Contact Person' => $data->delivery_contact_person ?? '',
                    'Delivery Contact Number' => "\t" . $data->delivery_contact_person_number ?? '',
                    'Delivery Date' => $data->delivery_date ? Carbon::parse($data->delivery_date)->format('d M Y') : '',
                    'Preferred Shipping Line' => $data->preferred_shipping_line_of_customer ?? '',
                    'Bill of Lading' => $data->bill_of_loading_details ?? '',
                    'Shipper' => $data->shipper ?? '',
                    'Consignee' => $data->consignee ?? '',
                    'Notify Party' => $data->notify_party ?? '',
                    'Special Requests' => $data->special_or_transit_clause_or_request ?? '',
                    'Created By' => $data->CreatedBy->name ?? '',
                    'Created At' => $data->created_at ?? '',
                    'Last Updated By' => $data->UpdatedBy->name ?? '',
                    'Last Updated At' => $data->updated_at ?? '',
                    'Sales Support Confirmation By' => $data->salesSupportDataConfirmationBy->name ?? '',
                    'Sales Support Confirmation At' => $data->sales_support_data_confirmation_at ?? '',
                    'Total BOE' => $data->total_number_of_boe ?? '',
                    'Has Claim' => $data->has_claim ?? '',
                    'Vehicle Count' => $data->vehicles->count() ?? 0,
                ];
            });
        }

        // Pagination parameters
        $page = request()->get('page', 1);
        $perPage = 100;

        // Slice the collection to get items for the current page
        $pagedData = $filteredDatas->slice(($page - 1) * $perPage, $perPage)->values();

        // Create the LengthAwarePaginator instance
        $paginatedFilteredDatas = new LengthAwarePaginator(
            $pagedData,
            $filteredDatas->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $datas = $paginatedFilteredDatas;

        return view('work_order.export_exw.index', compact(
            'type',
            'datas',
            'filters',
            'statuses',
            'salesSupportDataConfirmations',
            'search',
            'financeApprovalStatuses',
            'cooApprovalStatuses',
            'docsStatuses',
            'vehiclesModificationSummary',
            'pdiSummary',
            'deliverySummary'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $today = Carbon::today();

        // // Get all `WOBOE` records where the 25th day after the `declaration_date` is today or earlier
        // $boes = WOBOE::where('declaration_date', '<=', $today->subDays(24))
        //     ->with(['vehicles', 'workOrder.salesPerson'])  // Load vehicles and related salesperson through work order
        //     ->get();

        // // Filter out vehicles with 'Delivered' status in PHP (as it's an appended attribute)
        // $filteredBoes = $boes->map(function ($boe) {
        //     $boe->vehicles = $boe->vehicles->filter(function ($vehicle) {
        //         return $vehicle->delivery_status !== 'Delivered';  // Only non-delivered vehicles
        //     });
        //     return $boe;
        // })->filter(function ($boe) {
        //     return $boe->vehicles->isNotEmpty();  // Keep only if there are valid vehicles
        // });

        // // Send email notifications to each salesperson
        // foreach ($filteredBoes as $boe) {
        //     // Access the related salesperson through the work order relationship
        //     $salesperson = $boe->workOrder->salesPerson;

        //     // Fetch team emails from the .env file
        //     $salesSupportEmail = env('SALESUPPORT_TEAM_EMAIL');
        //     $logisticsTeamEmail = env('LOGISTICS_TEAM_EMAIL');
        //     // Send email to the salesperson's email and team emails from .env file
        //     Mail::to([$salesperson->email, $salesSupportEmail, $logisticsTeamEmail])
        //         ->send(new WOBOEStatusMail($boe, $salesperson));
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOrderRequest $request)
    {
        // Prepare file uploads before transaction
        $fileFields = [
            'brn_file' => 'wo/brn_file',
            'signed_pfi' => 'wo/signed_pfi',
            'signed_contract' => 'wo/signed_contract',
            'payment_receipts' => 'wo/payment_receipts',
            'noc' => 'wo/noc',
            'enduser_trade_license' => 'wo/enduser_trade_license',
            'enduser_passport' => 'wo/enduser_passport',
            'enduser_contract' => 'wo/enduser_contract',
            'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
        ];
        $fileData = [];
        foreach ($fileFields as $fileField => $path) {
            if ($request->hasFile($fileField)) {
                $file = $request->file($fileField);
                if ($file->isValid() && $file->getError() == UPLOAD_ERR_OK) {
                    $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
                    $file->move(public_path($path), $fileName);
                    $fileData[] = [
                        'file_name' => $fileField,
                        'file_type' => $file->getClientMimeType(),
                        'file_path' => $path . '/' . $fileName
                    ];
                }
            }
        }

        DB::beginTransaction();
        try {
            $authId = Auth::id();
            $input = $request->all();
            $input['customer_company_number'] = $request->customer_company_number['full'] ?? null;
            $input['customer_representative_contact'] = $request->customer_representative_contact['full'] ?? null;
            $input['delivery_contact_person_number'] = $request->delivery_contact_person_number['full'] ?? null;
            $input['freight_agent_contact_number'] = $request->freight_agent_contact_number['full'] ?? null;
            $input['transporting_driver_contact_number'] = $request->transporting_driver_contact_number['full'] ?? null;
            $input['created_by'] = $authId;
            $input['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $input['amount_received'] = $request->amount_received ?? 0.00;
            $input['balance_amount'] = $request->balance_amount ?? 0.00;
            $input['date'] = Carbon::now()->format('Y-m-d');
            if ($request->customer_type == 'new') {
                $input['customer_name'] = $request->new_customer_name;
            } else if ($request->customer_type == 'existing') {
                $input['customer_name'] = $request->existing_customer_name;
            }
            $fields = [
                'air' => [
                    'brn',
                    'container_number',
                    'shipping_line',
                    'forward_import_code',
                    'trailer_number_plate',
                    'transportation_company',
                    'transporting_driver_contact_number',
                    'transportation_company_details'
                ],
                'sea' => [
                    'airline_reference_id',
                    'airline',
                    'airway_bill',
                    'trailer_number_plate',
                    'transportation_company',
                    'transporting_driver_contact_number',
                    'airway_details',
                    'transportation_company_details'
                ],
                'road' => [
                    'brn_file',
                    'brn',
                    'container_number',
                    'airline_reference_id',
                    'airline',
                    'airway_bill',
                    'shipping_line',
                    'airway_details',
                    'forward_import_code'
                ]
            ];
            $transportType = $request->transport_type;
            if (isset($fields[$transportType])) {
                foreach ($fields[$transportType] as $field) {
                    $input[$field] = NULL;
                }
            }
            foreach ($fileData as $data) {
                $input[$data['file_name']] = basename($data['file_path']);
            }
            if (!isset($request->is_batch)) {
                $input['batch'] = NULL;
                $input['is_batch'] = 0;
            } else {
                $input['is_batch'] = 1;
            }
            if (isset($request->lto)) {
                $input['lto'] = 'yes';
            } elseif (!isset($request->lto) && $request->type === 'local_sale') {
                $input['lto'] = 'no';
            } else {
                $input['lto'] = null;
            }
            
            // Additional safety check: Verify SO exists and is not cancelled
            if (isset($request->so_number)) {
                $so = So::where('so_number', $request->so_number)
                    ->where(function ($query) {
                        $query->where('status', '!=', 'Cancelled')
                            ->orWhereNull('status');
                    })
                    ->first();
                
                if (!$so) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => 'The selected sales order does not exist or has been cancelled.'
                    ], 422);
                }
            }
            
            $workOrder = WorkOrder::create($input);
            $createwostatus = [
                'wo_id' => $workOrder->id,
                'status_changed_by' => $authId,
                'status' => 'Active',
                'comment' => 'The system generated the status when this work order was created.',
                'status_changed_at' => Carbon::now()
            ];
            $WoStatusCreate = WoStatus::create($createwostatus);
            $woHistoryBulk = [];
            if (isset($request->is_batch)) {
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'batch',
                    'old_value' => NULL,
                    'new_value' => $request->batch,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ];
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'is_batch',
                    'old_value' => NULL,
                    'new_value' => 1,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ];
            }
            if ($request->customer_type == 'new' && !is_null($request->new_customer_name)) {
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => NULL,
                    'new_value' => $request->new_customer_name,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ];
            } elseif ($request->customer_type == 'existing' && !is_null($request->existing_customer_name)) {
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => NULL,
                    'new_value' => $request->existing_customer_name,
                    'type' => 'Set',
                    'changed_at' => Carbon::now()
                ];
            }
            $excludeFields = [
                '_token',
                'customerCount',
                'type',
                'customer_type',
                'comments',
                'currency',
                'wo_id',
                'new_customer_name',
                'brn_file',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'batch',
                'is_batch'
            ];
            $nonNullData = array_filter($request->all(), function ($value, $key) use ($excludeFields) {
                return !is_null($value) && !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);
            $nestedFields = [
                'customer_company_number' => 'full',
                'customer_representative_contact' => 'full',
                'delivery_contact_person_number' => 'full',
                'freight_agent_contact_number' => 'full',
                'transporting_driver_contact_number' => 'full'
            ];
            $canCreateFinanceApproval = false;
            foreach ($nonNullData as $field => $value) {
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'field_name' => $field,
                    'old_value' => NULL,
                    'new_value' => $value,
                    'type' => 'Set',
                    'user_id' => Auth::id(),
                    'changed_at' => Carbon::now(),
                ];
                if ($field == 'so_total_amount') {
                    $woHistoryBulk[] = [
                        'work_order_id' => $workOrder->id,
                        'field_name' => 'currency',
                        'old_value' => NULL,
                        'new_value' => $request->currency,
                        'type' => 'Set',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ];
                }
                if (in_array($field, ['so_total_amount', 'so_vehicle_quantity', 'amount_received', 'balance_amount'])) {
                    $canCreateFinanceApproval = true;
                }
            }
            foreach ($nestedFields as $field => $subField) {
                if (isset($request->$field[$subField]) && !is_null($request->$field[$subField])) {
                    $woHistoryBulk[] = [
                        'work_order_id' => $workOrder->id,
                        'field_name' => $field . '.' . $subField,
                        'old_value' => NULL,
                        'new_value' => $request->$field[$subField],
                        'type' => 'Set',
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                    ];
                }
            }
            foreach ($fileData as $data) {
                $woHistoryBulk[] = [
                    'work_order_id' => $workOrder->id,
                    'field_name' => $data['file_name'],
                    'old_value' => NULL,
                    'new_value' => $data['file_path'],
                    'type' => 'Set',
                    'user_id' => Auth::id(),
                    'changed_at' => Carbon::now(),
                ];
            }
            if (!empty($woHistoryBulk)) {
                WORecordHistory::insert($woHistoryBulk);
            }
            $canCreateCOOApproval = false;
            $vehicleHistoryBulk = [];
            if (isset($request->vehicle)) {
                if (count($request->vehicle) > 0) {
                    foreach ($request->vehicle as $key => $vehicleData) {
                        $createWOVehicles = [];
                        $createWOVehicles['work_order_id'] = $workOrder->id;
                        $createWOVehicles['vehicle_id'] = $vehicleData['vehicle_id'] ?? null;
                        $createWOVehicles['vin'] = $vehicleData['vin'] ?? null;
                        $createWOVehicles['brand'] = $vehicleData['brand'] ?? null;
                        $createWOVehicles['variant'] = $vehicleData['variant'] ?? null;
                        $createWOVehicles['engine'] = $vehicleData['engine'] ?? null;
                        $createWOVehicles['model_description'] = $vehicleData['model_description'] ?? null;
                        $createWOVehicles['model_year'] = $vehicleData['model_year'] ?? null;
                        $createWOVehicles['model_year_to_mention_on_documents'] = $vehicleData['model_year_to_mention_on_documents'] ?? null;
                        $createWOVehicles['steering'] = $vehicleData['steering'] ?? null;
                        $createWOVehicles['exterior_colour'] = $vehicleData['exterior_colour'] ?? null;
                        $createWOVehicles['interior_colour'] = $vehicleData['interior_colour'] ?? null;
                        $createWOVehicles['warehouse'] = $vehicleData['warehouse'] ?? null;
                        $createWOVehicles['territory'] = $vehicleData['territory'] ?? null;
                        $createWOVehicles['preferred_destination'] = $vehicleData['preferred_destination'] ?? null;
                        $createWOVehicles['import_document_type'] = $vehicleData['import_document_type'] ?? null;
                        $createWOVehicles['ownership_name'] = $vehicleData['ownership_name'] ?? null;
                        $createWOVehicles['modification_or_jobs_to_perform_per_vin'] = $vehicleData['modification_or_jobs_to_perform_per_vin'] ?? null;
                        $createWOVehicles['certification_per_vin'] = $vehicleData['certification_per_vin'] ?? null;
                        $createWOVehicles['special_request_or_remarks'] = $vehicleData['special_request_or_remarks'] ?? null;
                        $createWOVehicles['shipment'] = $vehicleData['shipment'] ?? null;
                        $createWOVehicles['created_by'] = $authId;

                        $woVehicles = WOVehicles::create($createWOVehicles);
                        $canCreateCOOApproval = true;
                        // Define the fields to exclude
                        $excludeVehicleFields = [
                            'vehicle_id'
                        ];

                        // Filter out non-null, non-array values, and exclude specified fields
                        $nonNullVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                            return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleFields);
                        }, ARRAY_FILTER_USE_BOTH);

                        foreach ($nonNullVehicleData as $field => $value) {
                            $vehicleHistoryBulk[] = [
                                'w_o_vehicle_id' => $woVehicles->id,
                                'field_name' => $field,
                                'old_value' => null,
                                'new_value' => $value,
                                'type' => 'Set',
                                'user_id' => $authId,
                                'changed_at' => Carbon::now(),
                            ];
                        }
                        if (isset($vehicleData['addons'])) {
                            if (count($vehicleData['addons']) > 0) {
                                foreach ($vehicleData['addons'] as $key => $addonData) {
                                    if (isset($addonData['addon_code']) && $addonData['addon_code'] != null) {
                                        $canCreateFinanceApproval = $this->processNewAddons($woVehicles, $addonData, $authId);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($vehicleHistoryBulk)) {
                WOVehicleRecordHistory::insert($vehicleHistoryBulk);
            }

            $woVehiclesByVin = WOVehicles::where('work_order_id', $workOrder->id)->get()->keyBy('vin');
            $postVehicleHistoryBulk = [];
            $now = Carbon::now();

            // BOE assignment: bulk update instead of per‑VIN save()
            if (isset($request->boe) && is_array($request->boe) && count($request->boe) > 0) {
                $boeGroups = [];
                foreach ($request->boe as $boeNumber => $boe) {
                    if (!isset($boe['vin']) || !is_array($boe['vin']) || count($boe['vin']) === 0) {
                        continue;
                    }
                    foreach ($boe['vin'] as $vin) {
                        $vinUpdate = $woVehiclesByVin->get($vin);
                        if (!$vinUpdate) {
                            continue;
                        }
                        $boeGroups[(string) $boeNumber][] = $vin;
                        $postVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $vinUpdate->id,
                            'field_name' => 'boe_number',
                            'old_value' => null,
                            'new_value' => $boeNumber,
                            'type' => 'Set',
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                    }
                }
                foreach ($boeGroups as $boeNumber => $vins) {
                    $vins = array_values(array_unique(array_filter($vins)));
                    if (!empty($vins)) {
                        WOVehicles::where('work_order_id', $workOrder->id)
                            ->whereIn('vin', $vins)
                            ->update(['boe_number' => $boeNumber]);
                    }
                }
            }

            // Deposit received flags: bulk update instead of per‑VIN save()
            if (isset($request->deposit_received_as) && $request->deposit_received_as === 'custom_deposit') {
                $depositVins = (isset($request->deposit_aganist_vehicle) && is_array($request->deposit_aganist_vehicle))
                    ? array_values(array_unique(array_filter($request->deposit_aganist_vehicle)))
                    : [];

                if (!empty($depositVins)) {
                    WOVehicles::where('work_order_id', $workOrder->id)
                        ->whereIn('vin', $depositVins)
                        ->update(['deposit_received' => 'yes']);

                    foreach ($depositVins as $vin) {
                        $vinUpdate = $woVehiclesByVin->get($vin);
                        if (!$vinUpdate) {
                            continue;
                        }
                        $postVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $vinUpdate->id,
                            'field_name' => 'deposit_received',
                            'old_value' => null,
                            'new_value' => 'yes',
                            'type' => 'Set',
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                    }
                    $canCreateFinanceApproval = true;
                }
            }

            if (!empty($postVehicleHistoryBulk)) {
                WOVehicleRecordHistory::insert($postVehicleHistoryBulk);
            }
            // Initialize an array to keep track of old to new comment IDs
            // Handle comments
            $comments = json_decode($request->input('comments'), true);
            $commentIdMap = [];

            if (isset($comments) && $comments != null) {
                // First pass: Create all comments and map their IDs
                foreach ($comments as $comment) {
                    $newComment = WOComments::create([
                        'work_order_id' => $workOrder->id,
                        'text' => $comment['text'] ?? null, // Allow null text
                        'parent_id' => null, // Temporary null, will update later
                        'user_id' => auth()->id(),
                    ]);

                    // Map the old comment ID to the new comment ID
                    $commentIdMap[$comment['commentId']] = $newComment->id;
                }

                // Second pass: Update parent IDs and save files
                foreach ($comments as $comment) {
                    $newCommentId = $commentIdMap[$comment['commentId']];

                    if (!empty($comment['parentId'])) {
                        $newParentId = $commentIdMap[$comment['parentId']];
                        WOComments::where('id', $newCommentId)->update(['parent_id' => $newParentId]);
                    }

                    // Save files associated with the comment
                    if (isset($comment['files']) && is_array($comment['files'])) {
                        foreach ($comment['files'] as $file) {
                            // Check if the filename exceeds 50 characters
                            $originalFileName = $file['name'];
                            if (strlen($originalFileName) > 50) {
                                // Extract file extension
                                $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                                // Truncate the filename and append a unique identifier
                                $baseName = pathinfo($originalFileName, PATHINFO_FILENAME);
                                $truncatedName = substr($baseName, 0, 30); // Truncate to 30 characters

                                // Append a unique identifier
                                $uniqueIdentifier = uniqid(); // You can use time() or any other unique string generator
                                $newFileName = $truncatedName . '_' . $uniqueIdentifier . '.' . $extension;
                            } else {
                                // Use the original filename if it's within the length limit
                                $newFileName = $originalFileName;
                            }

                            // Save the file data (image or PDF) to the database
                            CommentFile::create([
                                'comment_id' => $newCommentId,
                                'file_name' => $newFileName,
                                'file_data' => $file['src'], // This stores the base64 data
                            ]);
                        }
                    }

                    // Handle mentioned users
                    if (isset($comment['mentioned_users'])) {
                        WOComments::find($newCommentId)->mentionedUsers()->attach($comment['mentioned_users']);
                    }

                    // Extract mentioned users from the text
                    preg_match_all('/@\[([^\]]+)\]/', $comment['text'] ?? '', $matches);
                    $mentionedUserNames = $matches[1];

                    if (!empty($mentionedUserNames)) {
                        $mentionedUsers = User::whereIn('name', $mentionedUserNames)->get();

                        foreach ($mentionedUsers as $user) {
                            // Queue email notifications for efficiency
                            dispatch(function () use ($workOrder, $newCommentId, $user) {
                                $template = [
                                    'from' => 'no-reply@milele.com',
                                    'from_name' => 'Milele Matrix'
                                ];
                                $customerName = $workOrder->customer_name ?? 'Unknown Customer';
                                $subject = "You were mentioned in a comment - " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;
                                $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
                                $accessLinkWithComment = $accessLink . '#comment-' . $newCommentId;

                                // Retrieve the comment object from the database
                                $comment = WOComments::find($newCommentId);

                                Mail::send('work_order.emails.mentioned_in_comment', [
                                    'workOrder' => $workOrder,
                                    'accessLink' => $accessLink,
                                    'accessLinkWithComment' => $accessLinkWithComment,
                                    'comment' => $comment, // This ensures $comment is an object
                                    'user' => $user // Pass the user object to the view
                                ], function ($message) use ($subject, $template, $user) {
                                    $message->from($template['from'], $template['from_name'])
                                        ->to($user->email)
                                        ->subject($subject);
                                });
                            })->onQueue('emails');
                        }
                    }
                }
            }

            if (isset($request->deposit_received_as) && $request->deposit_received_as != '') {
                $canCreateFinanceApproval = true;
            }
            if ($canCreateCOOApproval == true) {
                WOApprovals::create([
                    'work_order_id' => $workOrder->id,
                    'type' => 'coo',
                    'status' => 'pending',
                    'action_at' => NULL,
                ]);
                // Call the private function to send the email
                // $this->sendVehicleUpdateEmail($workOrder);
            }
            if ($canCreateFinanceApproval == true) {
                WOApprovals::create([
                    'work_order_id' => $workOrder->id,
                    'type' => 'finance',
                    'status' => 'pending',
                    'action_at' => NULL,
                ]);
            }
            (new UserActivityController)->createActivity('Create ' . $request->type . ' work order');

            $sendSoAmountEmail = $workOrder->dataHistories()
                ->whereIn('field_name', ['amount_received', 'balance_amount', 'currency', 'deposit_received_as', 'so_total_amount', 'so_vehicle_quantity'])
                ->exists();

            $workOrderId = $workOrder->id;
            DB::commit();

            $this->dispatchAfterResponse(function () use ($workOrderId) {
                $this->sendNewWorkOrderEmail($workOrderId);
            });
            if ($sendSoAmountEmail) {
                $this->dispatchAfterResponse(function () use ($workOrderId) {
                    $this->sendSOAmountUpdateEmail($workOrderId, null);
                });
            }

            return response()->json(['success' => true, 'message' => 'Work order created successfully.']);
        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error creating Work Order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    private function dispatchAfterResponse(\Closure $callback): void
    {
        dispatch($callback)->afterResponse();
    }

    private function getWorkOrderEmailRecipients(): array
    {
        return \Cache::remember('workorder_email_recipients', 300, function () {
            $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
            $managementEmails = User::where('can_send_wo_email', 'yes')
                ->pluck('email')
                ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
                ->toArray();

            return $this->filterExcludedEmails(array_filter(array_merge([$financeEmail, $operationsEmail], $managementEmails)));
        });
    }

    private function getWorkOrderTypeName(?string $type): string
    {
        return match ($type) {
            'export_exw' => 'Export EXW',
            'export_cnf' => 'Export CNF',
            'local_sale' => 'Local Sale',
            'lto' => 'LTO',
            default => '',
        };
    }

    private function loadWorkOrderForEmail(int $workOrderId): ?WorkOrder
    {
        $workOrder = WorkOrder::query()->find($workOrderId);
        if ($workOrder) {
            $workOrder->setAppends([]);
        }

        return $workOrder;
    }

    private function sendNewWorkOrderEmail(int $workOrderId): void
    {
        $workOrder = $this->loadWorkOrderForEmail($workOrderId);
        if (!$workOrder) {
            return;
        }

        $template = ['from' => 'no-reply@milele.com', 'from_name' => 'Milele Matrix'];
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        $vehicleCount = WOVehicles::where('work_order_id', $workOrderId)->whereNull('deleted_at')->count();
        $typeName = $this->getWorkOrderTypeName($workOrder->type);
        $subject = "New Work Order {$workOrder->wo_number} {$customerName} {$vehicleCount} Unit {$typeName}";
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $recipients = $this->getWorkOrderEmailRecipients();

        if (empty($recipients)) {
            Log::info('No valid recipients found. Skipping email sending for Work Order: ' . $workOrder->wo_number);
            return;
        }

        Mail::send('work_order.emails.new_wo', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
        ], function ($message) use ($subject, $recipients, $template) {
            $message->from($template['from'], $template['from_name'])
                ->to($recipients)
                ->subject($subject);
        });
    }

    private function sendSOAmountUpdateEmail(int $workOrderId, $comment): void
    {
        $workOrder = $this->loadWorkOrderForEmail($workOrderId);
        if (!$workOrder) {
            return;
        }

        $hasEditConfirmedPermission = Auth::user()?->hasPermissionForSelectedRole(['edit-confirmed-work-order']) ?? false;
        // Prepare the from details
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        $vehicleCount = WOVehicles::where('work_order_id', $workOrderId)->whereNull('deleted_at')->count();
        $typeName = $this->getWorkOrderTypeName($workOrder->type);
        $woLabel = $workOrder->wo_number ?? $workOrder->id;

        if ($hasEditConfirmedPermission) {
            $subject = "WO Deposit Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}"
                . ' By system administrator (' . Auth::user()->name . ' - ' . Auth::user()->email . ')';
        } else {
            $subject = "WO Deposit Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}";
        }

        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $recipients = $this->getWorkOrderEmailRecipients();
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);
        if ($createdByEmail) {
            $recipients = $this->filterExcludedEmails(array_merge($recipients, [$createdByEmail]));
        }

        if (empty($recipients)) {
            Log::info('No valid recipients found. Skipping email sending for WO-' . $woLabel);
            return;
        }

        $authUserName = auth()->user()?->name ?? 'System';
        $authUserEmail = auth()->user()?->email ?? '';
        // Get the current date and time in d M Y, h:i:s A format
        $currentDateTime = now()->format('d M Y, h:i:s A');
        // Send email using a Blade template
        Mail::send('work_order.emails.amount_update', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'authUserName' => $authUserName, // Pass the authenticated user's name
            'authUserEmail' => $authUserEmail, // Pass the authenticated user's email
            'currentDateTime' => $currentDateTime, // Pass the current date and time
            'comment' => $comment,
            'hasEditConfirmedPermission' => $hasEditConfirmedPermission, // Pass the permission flag
        ], function ($message) use ($subject, $recipients, $template) {
            $message->from($template['from'], $template['from_name'])
                ->to($recipients)
                ->subject($subject);
        });
    }
    private function sendDataUpdateEmail(int $workOrderId, $comment): void
    {
        $workOrder = $this->loadWorkOrderForEmail($workOrderId);
        if (!$workOrder) {
            return;
        }

        $hasEditConfirmedPermission = Auth::user()?->hasPermissionForSelectedRole(['edit-confirmed-work-order']) ?? false;
        $template = ['from' => 'no-reply@milele.com', 'from_name' => 'Milele Matrix'];
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        $vehicleCount = WOVehicles::where('work_order_id', $workOrderId)->whereNull('deleted_at')->count();
        $typeName = $this->getWorkOrderTypeName($workOrder->type);
        $woLabel = $workOrder->wo_number ?? $workOrder->id;

        if ($hasEditConfirmedPermission) {
            $subject = "WO Deposit Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}"
                . ' By system administrator (' . Auth::user()->name . ' - ' . Auth::user()->email . ')';
        } else {
            $subject = "WO Deposit Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}";
        }

        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $emailList = collect($this->getWorkOrderEmailRecipients());

        if ($emailList->isEmpty()) {
            Log::info('No valid email addresses found for WO email. Skipping email sending for WO-' . $woLabel);
            return;
        }

        $authUserName = auth()->user()?->name ?? 'System';
        $authUserEmail = auth()->user()?->email ?? '';
        // Get the current date and time in d M Y, h:i:s A format
        $currentDateTime = now()->format('d M Y, h:i:s A');
        // Send email using a Blade template
        Mail::send('work_order.emails.data_update', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'authUserName' => $authUserName, // Pass the authenticated user's name
            'authUserEmail' => $authUserEmail,
            'currentDateTime' => $currentDateTime, // Pass the current date and time
            'comment' => $comment,
            'hasEditConfirmedPermission' => $hasEditConfirmedPermission, // Pass the permission flag
        ], function ($message) use ($subject, $emailList, $template) {
            $message->from($template['from'], $template['from_name'])
                ->to($emailList->toArray()) // Convert the collection to an array
                ->subject($subject);
        });
    }
    private function sendVehicleUpdateEmail(int $workOrderId, $newComment): void
    {
        $workOrder = $this->loadWorkOrderForEmail($workOrderId);
        if (!$workOrder) {
            return;
        }

        $hasEditConfirmedPermission = Auth::user()?->hasPermissionForSelectedRole(['edit-confirmed-work-order']) ?? false;
        $template = ['from' => 'no-reply@milele.com', 'from_name' => 'Milele Matrix'];
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        $vehicleCount = WOVehicles::where('work_order_id', $workOrderId)->whereNull('deleted_at')->count();
        $typeName = $this->getWorkOrderTypeName($workOrder->type);
        $woLabel = $workOrder->wo_number ?? $workOrder->id;

        if ($hasEditConfirmedPermission) {
            $subject = "WO Vehicle Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}"
                . ' By system administrator (' . Auth::user()->name . ' - ' . Auth::user()->email . ')';
        } else {
            $subject = "WO Vehicle Update {$woLabel} {$customerName} {$vehicleCount} Unit {$typeName}";
        }

        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $managementEmails = $this->getWorkOrderEmailRecipients();

        if (empty($managementEmails)) {
            Log::info('No valid email addresses found for users with permission to receive WO emails. Skipping email for WO-' . $woLabel);
            return;
        }

        $authUserName = auth()->user()?->name ?? 'System';
        $authUserEmail = auth()->user()?->email ?? '';
        // Get the current date and time in d M Y, h:i:s A format
        $currentDateTime = now()->format('d M Y, h:i:s A');
        // Send email using a Blade template
        Mail::send('work_order.emails.vehicle_update', [
            'workOrder' => $workOrder,
            'accessLink' => $accessLink,
            'newComment' => $newComment,
            'authUserName' => $authUserName, // Pass the authenticated user's name
            'authUserEmail' => $authUserEmail,
            'currentDateTime' => $currentDateTime, // Pass the current date and time
            'hasEditConfirmedPermission' => $hasEditConfirmedPermission, // Pass the permission flag
        ], function ($message) use ($subject, $managementEmails, $template) {
            $message->from($template['from'], $template['from_name'])
                ->to($managementEmails)
                ->subject($subject);
        });
    }
    public function processNewAddons($woVehicles, $addonData, $authId)
    {
        $createWOVehiclesAddons = [];
        $createWOVehiclesAddons['w_o_vehicle_id'] = $woVehicles->id;
        // $createWOVehiclesAddons['addon_reference_id'] = $addonData['vin'] ?? null;
        // $createWOVehiclesAddons['addon_reference_type'] = $addonData['brand'] ?? null;
        $createWOVehiclesAddons['addon_code'] = $addonData['addon_code'] ?? null;
        // $createWOVehiclesAddons['addon_name'] = $addonData['addon_name'] ?? null;
        // $createWOVehiclesAddons['addon_name_description'] = $addonData['addon_name_description'] ?? null;
        $createWOVehiclesAddons['addon_quantity'] = $addonData['addon_quantity'] ?? null;
        $createWOVehiclesAddons['addon_description'] = $addonData['addon_description'] ?? null;
        $createWOVehiclesAddons['created_by'] = $authId;

        $WOVehicleAddons = WOVehicleAddons::create($createWOVehiclesAddons);
        $canCreateFinanceApproval = true;
        // Filter out non-null, non-array values, and exclude specified fields
        $excludeVehicleAddonFields = [
            'id',
            'w_o_vehicle_id',
        ];
        $nonNullVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
            return !is_null($value) && !in_array($key, $excludeVehicleAddonFields);
        }, ARRAY_FILTER_USE_BOTH);


        // Store each non-null, non-array field in the data history
        foreach ($nonNullVehicleAddonData as $field => $value) {
            WOVehicleAddonRecordHistory::create([
                'w_o_vehicle_addon_id' => $WOVehicleAddons->id,
                'field_name' => $field,
                'old_value' => NULL,
                'new_value' => $value,
                'type' => 'Set',
                'user_id' => Auth::id(),
                'changed_at' => Carbon::now(),
            ]);
        }
        return $canCreateFinanceApproval;
    }

    /**
     * Handle file upload.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    private function handleFileUpload($file, $path)
    {
        $fileName = auth()->id() . '_' . time() . '.' . $file->extension();
        $file->move(public_path($path), $fileName);
        return $fileName;
    }
    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        $authId = Auth::id();
        $type = $workOrder->type;

        // Store permission checks to avoid redundant calls
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'export-exw-wo-details',
            'export-cnf-wo-details',
            'local-sale-wo-details'
        ]);

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'current-user-export-exw-wo-details',
            'current-user-export-cnf-wo-details',
            'current-user-local-sale-wo-details'
        ]);

        $query = WorkOrder::where('id', $workOrder->id)
            ->with($this->getWorkOrderDetailEagerLoads());

        // Adjust the query based on user permissions
        if ($hasLimitedAccess) {
            $query->where(function ($subQuery) use ($authId) {
                $subQuery->where('created_by', $authId)
                    ->orWhere('sales_person_id', $authId);
            });
        }

        try {
            // Fetch the current work order
            $workOrder = $query->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $errorMsg = "Sorry! You don't have permission to access this page.";
            return view('hrm.notaccess', compact('errorMsg'));
        }

        // Retrieve previous and next work order IDs
        $previous = WorkOrder::where('type', $type)
            ->where('id', '<', $workOrder->id)
            ->when($hasLimitedAccess, function ($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->max('id');

        $next = WorkOrder::where('type', $type)
            ->where('id', '>', $workOrder->id)
            ->when($hasLimitedAccess, function ($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->min('id');

        $users = $this->getWorkOrderActiveUsers();
        $locations = \Cache::remember('workorder_office_locations', 300, function () {
            return MasterOfficeLocation::select('id', 'name')->get();
        });
        return view('work_order.export_exw.show', compact('type', 'users', 'workOrder', 'previous', 'next', 'locations'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        $previous = $next = '';
        $authId = Auth::id();
        // Store permission checks
        $hasFullAccess = Auth::user()->hasPermissionForSelectedRole([
            'edit-all-export-exw-work-order',
            'edit-all-export-cnf-work-order',
            'edit-all-local-sale-work-order'
        ]);

        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'edit-current-user-export-exw-work-order',
            'edit-current-user-export-cnf-work-order',
            'edit-current-user-local-sale-work-order'
        ]);
        $type = $workOrder->type;
        $workOrderQuery = WorkOrder::where('id', $workOrder->id)
            ->with($this->getWorkOrderDetailEagerLoads());

        if ($hasLimitedAccess) {
            $workOrderQuery->where('created_by', $authId);
        }

        try {
            $workOrder = $workOrderQuery->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $errorMsg = "Sorry! You don't have permission to access this page.";
            return view('hrm.notaccess', compact('errorMsg'));
        }

        $previous = WorkOrder::where('type', $type)
            ->where('id', '<', $workOrder->id)
            ->when($hasLimitedAccess, function ($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->max('id');

        $next = WorkOrder::where('type', $type)
            ->where('id', '>', $workOrder->id)
            ->when($hasLimitedAccess, function ($query) use ($authId) {
                return $query->where('created_by', $authId);
            })
            ->min('id');

        $formData = $this->getWorkOrderFormDropdownData($authId, $hasLimitedAccess, $workOrder, false);
        $workOrderJson = $this->prepareWorkOrderForEditJson($workOrder);
        $editVinOptions = $workOrder->vehicles->map(function (WOVehicles $vehicle) {
            return ['vin' => $vehicle->vin, 'label' => $vehicle->vin];
        })->values();
        $canDisableBatch = WorkOrder::where('id', '!=', $workOrder->id)
            ->where('so_number', $workOrder->so_number)
            ->exists();

        return view('work_order.export_exw.create', array_merge(
            compact('canDisableBatch', 'previous', 'next', 'workOrder', 'type', 'workOrderJson', 'editVinOptions'),
            $formData
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        $workOrderId = $workOrder->id;
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);

        $hasEditConfirmedPermission = Auth::user()->hasPermissionForSelectedRole(['edit-confirmed-work-order']);
        $isFinanceUser = Auth::user()->hasPermissionForSelectedRole(['do-finance-approval']);
        $isFinanceApproved = WOApprovals::where('work_order_id', $workOrderId)
            ->where('type', 'finance')
            ->orderByDesc('updated_at')
            ->value('status') === 'approved';
        // For non‑finance users, keep existing restriction on editing confirmed work orders
        if (!is_null($workOrder->sales_support_data_confirmation_at) && !$hasEditConfirmedPermission && !$isFinanceUser) {
            return response()->json(['success' => false, 'message' => "Can't edit the work order because the sales support confirmed the data."], 400);
        }

        DB::beginTransaction();
        try {
            $canCreateFinanceApproval = false;
            $canCreateCOOApproval = false;
            $sendSoAmountEmail = false;
            $sendDataUpdateEmailFlag = false;
            $sendVehicleUpdateEmailFlag = false;
            if (isset($request->deposit_received_as) && $request->deposit_received_as != '' && ($request->deposit_received_as != $workOrder->deposit_received_as)) {
                $canCreateFinanceApproval = true;
            }
            $authId = Auth::id();
            $newComment = WOComments::create([
                'work_order_id' => $workOrder->id,
                'text' => "The work order data was changed as follows by " . auth()->user()->name . ($hasEditConfirmedPermission ? " (System Administrator)" : ""), // Conditionally append "System Administrator"
                'parent_id' => null, // Temporary null, will update later
                'user_id' => null,
            ]);
            $CommentId = $newComment->id;
            $canDeleteComment = true;
            // Initialize newData array
            $newData = [];
            $newData = $request->all();
            if (isset($request->is_batch)) {
                $newData['is_batch'] = 1;
                $newData['batch'] = $request->batch;
            } else {
                $newData['is_batch'] = 0;
                $newData['batch'] = NULL;
            }
            if (isset($request->delivery_advise)) {
                $newData['delivery_advise'] = 'yes';
            } else {
                $newData['delivery_advise'] = 'no';
            }
            if (isset($request->temporary_exit)) {
                $newData['temporary_exit'] = 'yes';
            } else {
                $newData['temporary_exit'] = 'no';
            }
            if (isset($request->showroom_transfer)) {
                $newData['showroom_transfer'] = 'yes';
            } else {
                $newData['showroom_transfer'] = 'no';
            }
            if (isset($request->cross_trade)) {
                $newData['cross_trade'] = 'yes';
            } else {
                $newData['cross_trade'] = 'no';
            }
            if (isset($request->lto)) {
                $newData['lto'] = 'yes';
            } elseif (!isset($request->lto) && $request->type === 'local_sale') {
                $newData['lto'] = 'no';
            } else {
                $newData['lto'] = null;
            }
            // Extract full values for specific nested fields
            $nestedFields = [
                'customer_company_number',
                'customer_representative_contact',
                'delivery_contact_person_number',
                'freight_agent_contact_number',
                'transporting_driver_contact_number'
            ];

            foreach ($nestedFields as $field) {
                $newData[$field] = $request->$field['full'] ?? null;
            }

            // Additional data processing
            $newData['updated_by'] = $authId;
            $newData['so_total_amount'] = $request->so_total_amount ?? 0.00;
            $newData['amount_received'] = $request->amount_received ?? 0.00;
            $newData['balance_amount'] = $request->balance_amount ?? 0.00;

            if ($request->customer_type == 'new') {
                $newData['customer_name'] = $request->new_customer_name;
            } else if ($request->customer_type == 'existing') {
                $newData['customer_name'] = $request->existing_customer_name;
            }

            // Reset fields based on transport type
            $fields = [
                'air' => ['brn', 'container_number', 'shipping_line', 'forward_import_code', 'trailer_number_plate', 'transportation_company', 'transporting_driver_contact_number', 'transportation_company_details'],
                'sea' => ['airline_reference_id', 'airline', 'airway_bill', 'trailer_number_plate', 'transportation_company', 'transporting_driver_contact_number', 'airway_details', 'transportation_company_details'],
                'road' => ['brn_file', 'brn', 'container_number', 'airline_reference_id', 'airline', 'airway_bill', 'shipping_line', 'airway_details', 'forward_import_code']
            ];

            $transportType = $request->transport_type;
            if (isset($fields[$transportType])) {
                foreach ($fields[$transportType] as $field) {
                    $newData[$field] = null;
                }
            }

            // Helper function to handle file upload and history recording
            function handleFileUpload($request, $fileKey, $path, &$newData, $workOrder, $oldData, $deleteFlag = null, $CommentId)
            {
                $fileName = null;
                if ($request->hasFile($fileKey)) {
                    $fileName = auth()->id() . '_' . time() . '.' . $request->file($fileKey)->extension();
                    $type = $request->file($fileKey)->getClientMimeType();
                    $size = $request->file($fileKey)->getSize();
                    $request->file($fileKey)->move(public_path($path), $fileName);
                }
                if (isset($fileName)) {
                    $newData[$fileKey] = $fileName;
                } elseif ($deleteFlag && $request->input($deleteFlag) == 1) {
                    $newData[$fileKey] = NULL;
                }

                $oldValue = $oldData[$fileKey] ?? NULL;
                $newValue = $newData[$fileKey] ?? NULL;

                if ($oldValue != $newValue && $newValue != NULL) {
                    if ($oldValue != NULL && $newValue != NULL) {
                        $type = 'Change';
                        $newFilePath = 'wo/' . $fileKey . '/' . $newValue;
                        $oldFilePath = 'wo/' . $fileKey . '/' . $oldValue;
                    } elseif ($oldValue == NULL && $newValue != NULL) {
                        $type = 'Set';
                        $newFilePath = 'wo/' . $fileKey . '/' . $newValue;
                        $oldFilePath = NULL;
                    } elseif ($oldValue != NULL && $newValue == NULL) {
                        $type = 'Unset';
                        $newFilePath = NULL;
                        $oldFilePath = 'wo/' . $fileKey . '/' . $oldValue;
                    }
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'field_name' => $fileKey,
                        'old_value' => $oldFilePath ?? NULL,
                        'new_value' => $newFilePath,
                        'type' => $type,
                        'user_id' => Auth::id(),
                        'changed_at' => Carbon::now(),
                        'comment_id' => $CommentId,
                    ]);
                    $canDeleteComment = false;
                }
            }

            // Prepare old data for comparison
            $oldData = $workOrder->getOriginal();

            // Handle file uploads and history for various files
            $filesToHandle = [
                'brn_file' => 'wo/brn_file',
                'signed_pfi' => 'wo/signed_pfi',
                'signed_contract' => 'wo/signed_contract',
                'payment_receipts' => 'wo/payment_receipts',
                'noc' => 'wo/noc',
                'enduser_trade_license' => 'wo/enduser_trade_license',
                'enduser_passport' => 'wo/enduser_passport',
                'enduser_contract' => 'wo/enduser_contract',
                'vehicle_handover_person_id' => 'wo/vehicle_handover_person_id'
            ];

            foreach ($filesToHandle as $fileKey => $path) {
                handleFileUpload($request, $fileKey, $path, $newData, $workOrder, $oldData, 'is_' . $fileKey . '_delete', $CommentId);
            }

            // List of fields to exclude
            $excludeFields = [
                '_method',
                '_token',
                'customerCount',
                'type',
                'customer_type',
                'comments',
                'currency',
                'wo_id',
                'updated_by',
                'brn_file',
                'signed_pfi',
                'signed_contract',
                'payment_receipts',
                'noc',
                'enduser_trade_license',
                'enduser_passport',
                'enduser_contract',
                'vehicle_handover_person_id',
                'new_customer_name',
                'existing_customer_name',
                'customer_name',
                'customer_reference_id',
                'is_brn_file_delete',
                'is_signed_pfi_delete',
                'is_signed_contract_delete',
                'is_payment_receipts_delete',
                'is_noc_delete',
                'is_enduser_trade_license_delete',
                'is_enduser_passport_delete',
                'is_enduser_contract_delete',
                'is_vehicle_handover_person_id_delete',
                'vin_multiple',
                'has_claim'
            ];

            // Filter $newData to exclude array values and fields in the exclude list
            $filteredNewData = array_filter($newData, function ($value, $key) use ($excludeFields) {
                return !is_array($value) && !in_array($key, $excludeFields);
            }, ARRAY_FILTER_USE_BOTH);

            // Initialize an array to hold the changes
            $changes = [];

            // Iterate through filtered $newData
            foreach ($filteredNewData as $field => $newValue) {
                // Get the old value if it exists
                $oldValue = $oldData[$field] ?? null;

                // Check if the old value is different from the new value
                if ($oldValue != $newValue) {
                    // Determine the type of change
                    $changeType = 'Change';
                    if (in_array($field, ['so_total_amount', 'amount_received', 'balance_amount'])) {
                        if ($oldValue == 0.00 && ($newValue != 0.00 || !is_null($newValue))) {
                            $changeType = 'Set';
                            $oldValue = NULL;
                        } elseif ($oldValue != 0.00 && ($newValue == 0.00 || is_null($newValue))) {
                            $changeType = 'Unset';
                            $newValue = NULL;
                        }
                    } else {
                        if (is_null($oldValue) && !is_null($newValue)) {
                            $changeType = 'Set';
                        } elseif (!is_null($oldValue) && is_null($newValue)) {
                            $changeType = 'Unset';
                        }
                    }

                    // Add the change to the changes array
                    $changes[] = [
                        'work_order_id' => $workOrder->id,
                        'user_id' => $authId,
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'type' => $changeType,
                        'changed_at' => Carbon::now(),
                        'comment_id' => $CommentId,
                    ];
                    $canDeleteComment = false;
                    // Check for specified fields and create an entry in the WOApprovals model
                    if (in_array($field, ['so_total_amount', 'so_vehicle_quantity', 'amount_received', 'balance_amount'])) {
                        $canCreateFinanceApproval = true;
                    }
                }
            }

            // Handle customer name changes based on customer type
            if ($request->customer_type == 'new' && $oldData['customer_name'] != $request->new_customer_name) {
                $changeType = 'Change';
                if (is_null($oldData['customer_name']) && !is_null($request->new_customer_name)) {
                    $changeType = 'Set';
                } elseif (!is_null($oldData['customer_name']) && is_null($request->new_customer_name)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => $oldData['customer_name'],
                    'new_value' => $request->new_customer_name,
                    'type' => $changeType,
                    'changed_at' => Carbon::now(),
                    'comment_id' => $CommentId,
                ];
                $canDeleteComment = false;
            } else if ($request->customer_type == 'existing' && $oldData['customer_name'] != $request->existing_customer_name) {
                $changeType = 'Change';
                if (is_null($oldData['customer_name']) && !is_null($request->existing_customer_name)) {
                    $changeType = 'Set';
                } else if (!is_null($oldData['customer_name']) && is_null($request->existing_customer_name)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'customer_name',
                    'old_value' => $oldData['customer_name'],
                    'new_value' => $request->existing_customer_name,
                    'type' => $changeType,
                    'changed_at' => Carbon::now(),
                    'comment_id' => $CommentId,
                ];
                $canDeleteComment = false;
            }
            // Handle currency changes based on SO Amount, Amount Received and Balance Amount
            if ($oldData['currency'] != $request->currency) {
                $changeType = 'Change';
                if (is_null($oldData['currency']) && !is_null($request->currency)) {
                    $changeType = 'Set';
                } else if (!is_null($oldData['currency']) && is_null($request->currency)) {
                    $changeType = 'Unset';
                }
                $changes[] = [
                    'work_order_id' => $workOrder->id,
                    'user_id' => $authId,
                    'field_name' => 'currency',
                    'old_value' => $oldData['currency'],
                    'new_value' => $request->currency,
                    'type' => $changeType,
                    'changed_at' => Carbon::now(),
                    'comment_id' => $CommentId,
                ];
                $canDeleteComment = false;
                $canCreateFinanceApproval = true;
            }
            // If there are changes, insert them into the WORecordHistory
            if (!empty($changes)) {
                WORecordHistory::insert($changes);
            }
            
            // Validate SO number if it's being changed
            if (isset($newData['so_number']) && $newData['so_number'] != $workOrder->so_number) {
                $so = So::where('so_number', $newData['so_number'])
                    ->where(function ($query) {
                        $query->where('status', '!=', 'Cancelled')
                            ->orWhereNull('status');
                    })
                    ->first();
                
                if (!$so) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => 'The selected sales order does not exist or has been cancelled.'
                    ], 422);
                }
            }
            
            $workOrder['updated_by'] = $authId;
            // Update the WorkOrder
            $workOrder->update($newData);

            // VEHICLES START.....................................

            // Assuming $request->vehicles is an array of vehicles with unique VINs
            $vehiclesData = $request->vehicle ?? [];
            // Ensure $vehiclesData is an array
            if (!is_array($vehiclesData) || empty($vehiclesData)) {
                // Handle the case where $vehiclesData is not an array or is empty
                $vehiclesData = [];
            }

            // Extract the id of vehicles from the incoming request data
            $incomingIds = array_column($vehiclesData, 'id');

            // Get the existing vehicles from the database
            $existingVehicles = WOVehicles::whereIn('id', $incomingIds)->get()->keyBy('id');

            // Track vehicles that were processed
            $processedIds = [];
            $NewVehicleIdArr = [];
            $updateVehicleHistoryBulk = [];
            foreach ($vehiclesData as $vehicleData) {
                $id = $vehicleData['id'];
                // Define the fields to exclude
                $excludeVehicleFields = [
                    'id',
                    'work_order_id',
                    'vehicle_id',
                    'updated_by',
                    'created_by',
                    'comment_id'
                ];

                // Update if exists, otherwise create
                if (isset($existingVehicles[$id])) {
                    // Mark this VIN as processed
                    $processedIds[] = $id;
                    $vehicle = $existingVehicles[$id];

                    $createVehComMap = [];
                    $createVehComMap['type'] = 'update';
                    $createVehComMap['comment_id'] = $CommentId;
                    $createVehComMap['vehicle_id'] = $vehicle->id;
                    $createVehComMap['wo_id'] = $workOrder->id;
                    $CreatedVehComMap = CommentVehicleMapping::create($createVehComMap);
                    $canDeleteCreatedVehComMap = true;

                    $vehicleData['updated_by'] = Auth::id();
                    // Filter out non-null, non-array values, and exclude specified fields
                    $filterredVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                        return !is_array($value) && !in_array($key, $excludeVehicleFields);
                    }, ARRAY_FILTER_USE_BOTH);
                    // Check and store only changed fields
                    foreach ($filterredVehicleData as $field => $newValue) {
                        $oldValue = $vehicle->$field;
                        if ($oldValue !== $newValue) {
                            $changeType = 'Change';
                            if (is_null($oldValue) && !is_null($newValue)) {
                                $changeType = 'Set';
                            } elseif (!is_null($oldValue) && is_null($newValue)) {
                                $changeType = 'Unset';
                            }
                            $vehicle->$field = $newValue;
                            $updateVehicleHistoryBulk[] = [
                                'w_o_vehicle_id' => $vehicle->id,
                                'field_name' => $field,
                                'old_value' => $oldValue,
                                'new_value' => $newValue,
                                'type' => $changeType,
                                'user_id' => $authId,
                                'changed_at' => Carbon::now(),
                                'comment_vehicle_id' => $CreatedVehComMap->id,
                            ];
                            $canDeleteCreatedVehComMap = false;
                            $canCreateCOOApproval = true;
                        }
                    }
                    $vehicle->updated_by = $authId;
                    // Save the vehicle with updated data
                    $vehicle->save();
                    // ADDON START....................

                    // Assuming $vehicleData['addons'] is an array of vehicle addons with unique id
                    $vehicleAddonsData = $vehicleData['addons'] ?? [];
                    // Ensure $vehicleAddonsData is an array
                    if (!is_array($vehicleAddonsData) || empty($vehicleAddonsData)) {
                        // Handle the case where $vehicleAddonsData is not an array or is empty
                        $vehicleAddonsData = [];
                    }
                    // Extract the ID of addons from the incoming request data
                    $incomingAddonIds = array_column($vehicleAddonsData, 'id');
                    // Get the existing addons from the database
                    $existingAddons = WOVehicleAddons::whereIn('id', $incomingAddonIds)->get()->keyBy('id');

                    // Track addons that were processed
                    $processedAddonIds = [];
                    foreach ($vehicleAddonsData as $addonData) {
                        $addonId = $addonData['id'] ?? null;
                        // Define the fields to exclude
                        $excludeVehicleAddonFields = [
                            'id',
                            'work_order_id',
                            'w_o_vehicle_id',
                            'w_o_vehicle_addon_id',
                            'vehicle_id',
                            'updated_by',
                            'created_by',
                        ];
                        // Update if exists, otherwise create
                        if (isset($existingAddons[$addonId])) {
                            $processedAddonIds[] = $addonId; // Append ID to array
                            $addon = $existingAddons[$addonId];

                            $createCommVehAddon['type'] = 'update';
                            $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                            $createCommVehAddon['addon_id'] = $addon->id;
                            $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                            $canDeleteCreateVehComAddMap = true;
                            $addonData['updated_by'] = Auth::id();
                            // Filter out non-null, non-array values, and exclude specified fields
                            $filterredVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                                return !is_array($value) && !in_array($key, $excludeVehicleAddonFields);
                            }, ARRAY_FILTER_USE_BOTH);
                            // Check and store only changed fields
                            foreach ($filterredVehicleAddonData as $field => $newValue) {
                                $oldValue = $addon->$field;

                                // Trim string values
                                if (is_string($oldValue)) {
                                    $oldValue = trim($oldValue);
                                }
                                if (is_string($newValue)) {
                                    $newValue = trim($newValue);
                                }

                                // Convert numeric strings to numbers
                                if (is_numeric($oldValue) && is_numeric($newValue)) {
                                    $oldValue = (float)$oldValue;
                                    $newValue = (float)$newValue;
                                }

                                if ($oldValue !== $newValue) {

                                    $changeType = 'Change';
                                    if (is_null($oldValue) && !is_null($newValue)) {
                                        $changeType = 'Set';
                                    } elseif (!is_null($oldValue) && is_null($newValue)) {
                                        $changeType = 'Unset';
                                    }

                                    // Change the vehicle data
                                    $addon->$field = $newValue;
                                    // Store the change in history
                                    WOVehicleAddonRecordHistory::create([
                                        'w_o_vehicle_addon_id' => $addon->id,
                                        'field_name' => $field,
                                        'old_value' => $oldValue,
                                        'new_value' => $newValue,
                                        'type' => $changeType,
                                        'user_id' => Auth::id(),
                                        'changed_at' => Carbon::now(),
                                        'cvm_id' => $createdCommVehAddonMapp->id,
                                    ]);
                                    $canCreateFinanceApproval = true;
                                    $canDeleteCreateVehComAddMap = false;
                                }
                            }
                            if ($canDeleteCreateVehComAddMap == true) {
                                $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id', $createdCommVehAddonMapp->id)->first();
                                if ($deleteCreateVehComAddMap) {
                                    $deleteCreateVehComAddMap->delete();
                                }
                            } else {
                                $canDeleteCreatedVehComMap = false;
                            }
                            // Save the vehicle with updated data
                            $addon->save();
                        } else {
                            $addonData['w_o_vehicle_id'] = $vehicle->id;
                            $addonData['created_by'] = Auth::id();
                            $addonData['comment_id'] = $CommentId;
                            $woVehicleAddon = WOVehicleAddons::create($addonData);
                            $canCreateFinanceApproval = true;
                            $createCommVehAddon['type'] = 'store';
                            $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                            $createCommVehAddon['addon_id'] = $woVehicleAddon->id;
                            $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                            $canDeleteCreateVehComAddMap = true;
                            $processedAddonIds[] = $woVehicleAddon->id; // Append ID to array
                            // Filter out non-null, non-array values, and exclude specified fields
                            $nonNullVehicleData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                                return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleAddonFields);
                            }, ARRAY_FILTER_USE_BOTH);

                            // Store each non-null, non-array field in the data history
                            foreach ($nonNullVehicleData as $field => $value) {
                                WOVehicleAddonRecordHistory::create([
                                    'w_o_vehicle_addon_id' => $woVehicleAddon->id,
                                    'field_name' => $field,
                                    'old_value' => NULL,
                                    'new_value' => $value,
                                    'type' => 'Set',
                                    'user_id' => Auth::id(),
                                    'changed_at' => Carbon::now(),
                                    'cvm_id' => $createdCommVehAddonMapp->id,
                                ]);
                                $canDeleteCreateVehComAddMap = false;
                            }
                            if ($canDeleteCreateVehComAddMap == true) {
                                $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id', $createdCommVehAddonMapp->id)->first();
                                if ($deleteCreateVehComAddMap) {
                                    $deleteCreateVehComAddMap->delete();
                                }
                            } else {
                                $canDeleteCreatedVehComMap = false;
                            }
                        }
                    }

                    // Ensure $processedIds only contains valid IDs
                    $processedAddonIds = array_filter($processedAddonIds, function ($id) {
                        return !is_null($id);
                    });
                    // Retrieve addons that were not in the incoming request and update deleted_by field
                    $addonsToDelete = WOVehicleAddons::whereNotIn('id', $processedAddonIds)->where('w_o_vehicle_id', $vehicle->id)->get();
                    foreach ($addonsToDelete as $addon) {
                        $createCommVehAddon['type'] = 'delete';
                        $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                        $createCommVehAddon['addon_id'] = $addon->id;
                        $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                        $addon->deleted_by = Auth::id();
                        $addon->delete_cvm_id = $CreatedVehComMap->id;
                        $addon->save();
                        $canDeleteCreatedVehComMap = false;
                    }

                    // Now delete the addons
                    WOVehicleAddons::whereNotIn('id', $processedAddonIds)->where('w_o_vehicle_id', $vehicle->id)->delete();
                    if ($canDeleteCreatedVehComMap == true) {
                        $deleteCommVehMap = CommentVehicleMapping::where('id', $CreatedVehComMap->id)->first();
                        if ($deleteCommVehMap) {
                            $deleteCommVehMap->delete();
                        }
                    } else {
                        $canDeleteComment = false;
                    }
                    // ADDON END..............................
                } else {
                    $vehicleData['work_order_id'] = $workOrder->id;
                    $vehicleData['created_by'] = Auth::id();
                    $vehicleData['comment_id'] = $CommentId;
                    $woVehicles = WOVehicles::create($vehicleData);
                    $canCreateCOOApproval = true;
                    $createVehComMap = [];
                    $createVehComMap['type'] = 'store';
                    $createVehComMap['comment_id'] = $CommentId;
                    $createVehComMap['vehicle_id'] = $woVehicles->id;
                    $createVehComMap['wo_id'] = $workOrder->id;
                    $CreatedVehComMap = CommentVehicleMapping::create($createVehComMap);
                    $canDeleteCreatedVehComMap = true;

                    // Push the newly created vehicle's ID into the array
                    $NewVehicleIdArr[] = $woVehicles->id;
                    // Filter out non-null, non-array values, and exclude specified fields
                    $nonNullVehicleData = array_filter($vehicleData, function ($value, $key) use ($excludeVehicleFields) {
                        return !is_null($value) && !is_array($value) && !in_array($key, $excludeVehicleFields);
                    }, ARRAY_FILTER_USE_BOTH);

                    foreach ($nonNullVehicleData as $field => $value) {
                        $updateVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $woVehicles->id,
                            'field_name' => $field,
                            'old_value' => null,
                            'new_value' => $value,
                            'type' => 'Set',
                            'user_id' => $authId,
                            'changed_at' => Carbon::now(),
                            'comment_vehicle_id' => $CreatedVehComMap->id,
                        ];
                        $canDeleteCreatedVehComMap = false;
                    }

                    // ADDON START.................
                    if (isset($vehicleData['addons'])) {
                        if (count($vehicleData['addons']) > 0) {
                            foreach ($vehicleData['addons'] as $key => $addonData) {
                                if (isset($addonData['addon_code']) && $addonData['addon_code'] != null) {
                                    // $this->processNewAddons($woVehicles,$addonData,$authId);
                                    $createWOVehiclesAddons = [];
                                    $createWOVehiclesAddons['w_o_vehicle_id'] = $woVehicles->id;
                                    // $createWOVehiclesAddons['addon_reference_id'] = $addonData['vin'] ?? null;
                                    // $createWOVehiclesAddons['addon_reference_type'] = $addonData['brand'] ?? null;
                                    $createWOVehiclesAddons['addon_code'] = $addonData['addon_code'] ?? null;
                                    // $createWOVehiclesAddons['addon_name'] = $addonData['addon_name'] ?? null;
                                    // $createWOVehiclesAddons['addon_name_description'] = $addonData['addon_name_description'] ?? null;
                                    $createWOVehiclesAddons['addon_quantity'] = $addonData['addon_quantity'] ?? null;
                                    $createWOVehiclesAddons['addon_description'] = $addonData['addon_description'] ?? null;
                                    $createWOVehiclesAddons['created_by'] = $authId;
                                    $WOVehicleAddons = WOVehicleAddons::create($createWOVehiclesAddons);
                                    $canCreateFinanceApproval = true;
                                    $createCommVehAddon['type'] = 'store';
                                    $createCommVehAddon['comment_vehicle_mapping_id'] = $CreatedVehComMap->id;
                                    $createCommVehAddon['addon_id'] = $WOVehicleAddons->id;
                                    $createdCommVehAddonMapp = CommentVehicleAddonMapping::create($createCommVehAddon);
                                    $canDeleteCreateVehComAddMap = true;
                                    // Filter out non-null, non-array values, and exclude specified fields
                                    $excludeVehicleAddonFields = [
                                        'id',
                                        'w_o_vehicle_id',
                                    ];
                                    $nonNullVehicleAddonData = array_filter($addonData, function ($value, $key) use ($excludeVehicleAddonFields) {
                                        return !is_null($value) && !in_array($key, $excludeVehicleAddonFields);
                                    }, ARRAY_FILTER_USE_BOTH);


                                    // Store each non-null, non-array field in the data history
                                    foreach ($nonNullVehicleAddonData as $field => $value) {
                                        WOVehicleAddonRecordHistory::create([
                                            'w_o_vehicle_addon_id' => $WOVehicleAddons->id,
                                            'field_name' => $field,
                                            'old_value' => NULL,
                                            'new_value' => $value,
                                            'type' => 'Set',
                                            'user_id' => Auth::id(),
                                            'changed_at' => Carbon::now(),
                                            'cvm_id' => $createdCommVehAddonMapp->id,
                                        ]);
                                        $canDeleteCreateVehComAddMap = false;
                                    }
                                    // Mark this addon as processed
                                    if ($canDeleteCreateVehComAddMap == true) {
                                        $deleteCreateVehComAddMap = CommentVehicleAddonMapping::where('id', $createdCommVehAddonMapp->id)->first();
                                        if ($deleteCreateVehComAddMap) {
                                            $deleteCreateVehComAddMap->delete();
                                        }
                                    } else {
                                        $canDeleteCreatedVehComMap = false;
                                    }
                                }
                            }
                        }
                    }
                    // ADDON END ..................  
                    $processedIds[] = $woVehicles->id;
                    if ($canDeleteCreatedVehComMap == true) {
                        $deleteCommVehMap = CommentVehicleMapping::where('id', $CreatedVehComMap->id)->first();
                        if ($deleteCommVehMap) {
                            $deleteCommVehMap->delete();
                        }
                    } else {
                        $canDeleteComment = false;
                    }
                }
            }
            // Ensure $processedIds only contains valid IDs
            $processedIds = array_filter($processedIds, function ($id) {
                return !is_null($id);
            });

            $vehiclesToDelete = WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id', $workOrder->id)->get();
            foreach ($vehiclesToDelete as $vehicle) {
                $vehicle->deleted_by = Auth::id();
                $vehicle->deleted_comment_id = $CommentId;
                $vehicle->save();
                $canDeleteComment = false;
                $canCreateCOOApproval = true;
            }

            // Now delete the vehicles
            WOVehicles::whereNotIn('id', $processedIds)->where('work_order_id', $workOrder->id)->delete();

            $woVehiclesByVin = WOVehicles::where('work_order_id', $workOrder->id)->get()->keyBy('vin');

            if (isset($request->boe) && is_array($request->boe) && count($request->boe) > 0) {
                $now = Carbon::now();

                // Build desired BOE mapping for vins
                $desiredBoeByVin = [];
                foreach ($request->boe as $boeNumber => $boe) {
                    if (!isset($boe['vin']) || !is_array($boe['vin']) || count($boe['vin']) === 0) {
                        continue;
                    }
                    foreach ($boe['vin'] as $vin) {
                        $desiredBoeByVin[$vin] = $boeNumber;
                    }
                }

                $unsetVins = [];
                $setGroups = [];

                foreach ($woVehiclesByVin as $woVehicle) {
                    $vin = $woVehicle->vin;
                    $current = $woVehicle->boe_number;
                    $desired = array_key_exists($vin, $desiredBoeByVin) ? $desiredBoeByVin[$vin] : null;

                    if ($desired === null) {
                        if ($current !== null) {
                            $unsetVins[] = $vin;
                            $updateVehicleHistoryBulk[] = [
                                'w_o_vehicle_id' => $woVehicle->id,
                                'field_name' => 'boe_number',
                                'old_value' => $current,
                                'new_value' => null,
                                'type' => 'Unset',
                                'user_id' => $authId,
                                'changed_at' => $now,
                            ];
                            $canDeleteComment = false;
                        }
                        continue;
                    }

                    if ((string) $current !== (string) $desired) {
                        $changeType = is_null($current) ? 'Set' : 'Change';
                        $setGroups[(string) $desired][] = $vin;
                        $updateVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $woVehicle->id,
                            'field_name' => 'boe_number',
                            'old_value' => $current,
                            'new_value' => $desired,
                            'type' => $changeType,
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                        $canDeleteComment = false;
                    }
                }

                $unsetVins = array_values(array_unique(array_filter($unsetVins)));
                if (!empty($unsetVins)) {
                    WOVehicles::where('work_order_id', $workOrder->id)
                        ->whereIn('vin', $unsetVins)
                        ->update(['boe_number' => null]);
                }

                foreach ($setGroups as $boeNumber => $vins) {
                    $vins = array_values(array_unique(array_filter($vins)));
                    if (!empty($vins)) {
                        WOVehicles::where('work_order_id', $workOrder->id)
                            ->whereIn('vin', $vins)
                            ->update(['boe_number' => $boeNumber]);
                    }
                }
            }

            if (isset($request->deposit_received_as) && $request->deposit_received_as === 'custom_deposit') {
                $now = $now ?? Carbon::now();
                $depositRequestVins = (isset($request->deposit_aganist_vehicle) && is_array($request->deposit_aganist_vehicle))
                    ? array_values(array_unique(array_filter($request->deposit_aganist_vehicle)))
                    : [];

                $setYesVins = [];
                $setNoVins = [];

                foreach ($woVehiclesByVin as $woVehicle) {
                    $vin = $woVehicle->vin;
                    $desiredYes = in_array($vin, $depositRequestVins, true);
                    $current = $woVehicle->deposit_received;

                    if ($desiredYes && $current != 'yes') {
                        $setYesVins[] = $vin;
                        $updateVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $woVehicle->id,
                            'field_name' => 'deposit_received',
                            'old_value' => $current,
                            'new_value' => 'yes',
                            'type' => 'Change',
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                        $canDeleteComment = false;
                        $canCreateFinanceApproval = true;
                    } elseif (!$desiredYes && $current != 'no') {
                        $setNoVins[] = $vin;
                        $updateVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $woVehicle->id,
                            'field_name' => 'deposit_received',
                            'old_value' => $current,
                            'new_value' => 'no',
                            'type' => 'Change',
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                        $canDeleteComment = false;
                        $canCreateFinanceApproval = true;
                    }
                }

                $setYesVins = array_values(array_unique(array_filter($setYesVins)));
                if (!empty($setYesVins)) {
                    WOVehicles::where('work_order_id', $workOrder->id)
                        ->whereIn('vin', $setYesVins)
                        ->update(['deposit_received' => 'yes']);
                }
                $setNoVins = array_values(array_unique(array_filter($setNoVins)));
                if (!empty($setNoVins)) {
                    WOVehicles::where('work_order_id', $workOrder->id)
                        ->whereIn('vin', $setNoVins)
                        ->update(['deposit_received' => 'no']);
                }
            } elseif (
                (isset($request->deposit_received_as) && $request->deposit_received_as === null) ||
                !isset($request->deposit_received_as)
            ) {
                $now = $now ?? Carbon::now();

                $yesVins = [];
                foreach ($woVehiclesByVin as $woVehicle) {
                    if ($woVehicle->deposit_received == 'yes') {
                        $yesVins[] = $woVehicle->vin;
                        $updateVehicleHistoryBulk[] = [
                            'w_o_vehicle_id' => $woVehicle->id,
                            'field_name' => 'deposit_received',
                            'old_value' => 'yes',
                            'new_value' => 'no',
                            'type' => 'Change',
                            'user_id' => $authId,
                            'changed_at' => $now,
                        ];
                        $canDeleteComment = false;
                        $canCreateFinanceApproval = true;
                    }
                }
                $yesVins = array_values(array_unique(array_filter($yesVins)));
                if (!empty($yesVins)) {
                    WOVehicles::where('work_order_id', $workOrder->id)
                        ->whereIn('vin', $yesVins)
                        ->update(['deposit_received' => 'no']);
                }
            }

            if (!empty($updateVehicleHistoryBulk)) {
                WOVehicleRecordHistory::insert($updateVehicleHistoryBulk);
            }

            // VEHICLES END ........................................

            if ($canDeleteComment == true) {
                $deleteComment = WOComments::where('id', $CommentId)->first();
                if ($deleteComment) {
                    $deleteComment->delete();
                }
            } else {
                $sendSoAmountEmail = $newComment->wo_histories()
                    ->whereIn('field_name', ['amount_received', 'balance_amount', 'currency', 'deposit_received_as', 'so_total_amount', 'so_vehicle_quantity'])
                    ->exists();
                $sendDataUpdateEmailFlag = $newComment->wo_histories()
                    ->whereIn('field_name', [
                        'airline',
                        'airway_bill',
                        'airway_details',
                        'batch',
                        'brn',
                        'brn_file',
                        'container_number',
                        'customer_address',
                        'customer_company_number',
                        'customer_company_number.full',
                        'customer_email',
                        'customer_name',
                        'customer_representative_contact',
                        'customer_representative_contact.full',
                        'customer_representative_email',
                        'customer_representative_name',
                        'delivery_contact_person',
                        'delivery_contact_person_number',
                        'delivery_date',
                        'delivery_location',
                        'enduser_contract',
                        'enduser_passport',
                        'enduser_trade_license',
                        'existing_customer_name',
                        'final_destination',
                        'forward_import_code',
                        'freight_agent_contact_number',
                        'freight_agent_contact_number.full',
                        'freight_agent_email',
                        'freight_agent_name',
                        'is_batch',
                        'noc',
                        'payment_receipts',
                        'port_of_discharge',
                        'port_of_loading',
                        'shipment',
                        'shipping_line',
                        'signed_contract',
                        'signed_pfi',
                        'so_number',
                        'trailer_number_plate',
                        'transport_type',
                        'transportation_company',
                        'transportation_company_details',
                        'transporting_driver_contact_number',
                        'transporting_driver_contact_number.full',
                        'vehicle_handover_person_id',
                        'wo_number',
                        'preferred_shipping_line_of_customer',
                        'bill_of_loading_details',
                        'shipper',
                        'consignee',
                        'notify_party',
                        'special_or_transit_clause_or_request',
                    ])->exists();
            }
            if ($canCreateCOOApproval == true) {
                if (!$hasEditConfirmedPermission) {
                    $cooPendingApprovals = WOApprovals::where('work_order_id', $workOrder->id)->where('type', 'coo')->where('status', 'pending')->first();
                    if ($cooPendingApprovals == null) {
                        WOApprovals::create([
                            'work_order_id' => $workOrder->id,
                            'type' => 'coo',
                            'status' => 'pending',
                            'action_at' => NULL,
                        ]);
                    } else {
                        $cooPendingApprovals->updated_at = Carbon::now();
                        $cooPendingApprovals->update();
                    }
                }
                $sendVehicleUpdateEmailFlag = true;
            }
            if ($canCreateFinanceApproval == true) {
                // Do not re‑initiate finance approval if finance user is editing an already finance‑approved work order
                if ($isFinanceUser && $isFinanceApproved) {
                    WORecordHistory::create([
                        'work_order_id' => $workOrder->id,
                        'user_id' => Auth::id(),
                        'field_name' => 'finance_approval_status',
                        'old_value' => 'Approved',
                        'new_value' => 'Approved',
                        'type' => 'Finance Edit - No Reapproval',
                        'changed_at' => Carbon::now(),
                    ]);
                } elseif (!$hasEditConfirmedPermission) {
                    $financePendingApproval = WOApprovals::where('work_order_id', $workOrder->id)->where('type', 'finance')->where('status', 'pending')->first();
                    if ($financePendingApproval == null) {
                        WOApprovals::create([
                            'work_order_id' => $workOrder->id,
                            'type' => 'finance',
                            'status' => 'pending',
                            'action_at' => NULL,
                        ]);
                    } else {
                        $financePendingApproval->updated_at = Carbon::now();
                        $financePendingApproval->update();
                    }
                }
            }
            (new UserActivityController)->createActivity('Update ' . $request->type . ' work order');

            $workOrderId = $workOrder->id;
            $commentId = $CommentId;
            DB::commit();

            if ($sendSoAmountEmail) {
                $this->dispatchAfterResponse(function () use ($workOrderId, $commentId) {
                    $this->sendSOAmountUpdateEmail($workOrderId, WOComments::find($commentId));
                });
            }
            if ($sendDataUpdateEmailFlag) {
                $this->dispatchAfterResponse(function () use ($workOrderId, $commentId) {
                    $this->sendDataUpdateEmail($workOrderId, WOComments::find($commentId));
                });
            }
            if ($sendVehicleUpdateEmailFlag) {
                $this->dispatchAfterResponse(function () use ($workOrderId, $commentId) {
                    $this->sendVehicleUpdateEmail($workOrderId, WOComments::find($commentId));
                });
            }

            return response()->json(['success' => true, 'message' => 'Work order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Work Order: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $authId = Auth::id(); // Get the authenticated user's ID

        // Start the transaction
        DB::beginTransaction();

        try {
            // Find the WorkOrder by ID
            $workOrder = WorkOrder::findOrFail($id);

            // Check if the WorkOrder has already been soft-deleted
            if ($workOrder->trashed()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This work order has already been deleted.'
                ], 400);
            }

            // Soft delete related WOVehicles records
            WOVehicles::where('work_order_id', $id)->each(function ($vehicle) use ($authId) {
                $vehicle->deleted_by = $authId;
                $vehicle->save();
                $vehicle->delete();
            });

            // Update 'deleted_by' and soft delete the WorkOrder
            $workOrder->deleted_by = $authId;
            $workOrder->save();
            $workOrder->delete();

            // Commit the transaction
            DB::commit();

            // Return JSON success response
            return response()->json([
                'status' => 'success',
                'message' => 'Work order and related vehicles deleted successfully.'
            ]);
        } catch (\Exception $e) {
            // Roll back the transaction on error
            DB::rollBack();

            // Log the error for debugging (optional)
            Log::error('Error deleting work order', [
                'error' => $e->getMessage(),
                'work_order_id' => $id
            ]);

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete work order. Please try again later.',
                'error_details' => $e->getMessage() // Optional: Remove this in production
            ], 500);
        }
    }

    public function fetchAddons(Request $request)
    {
        // $vins = $request->input('vins');
        // if (isset($vins) && count($vins) > 0) {
        $kit = AddonDetails::select('addon_details.id', 'addon_details.addon_code', DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "), DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'K')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $accessories = AddonDetails::select('addon_details.id', 'addon_details.addon_code', DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "), DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'P')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $spareParts = AddonDetails::select('addon_details.id', 'addon_details.addon_code', DB::raw("CONCAT(addons.name, 
                IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name
                "), DB::raw("'App\\Models\\AddonDetails' as reference_type"))
            ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
            ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
            ->where('addon_details.addon_type_name', 'SP')
            ->orderBy('addon_details.id', 'asc')
            ->get();
        $data['charges'] = MasterCharges::select(
            'master_charges.id',
            'master_charges.addon_code',
            DB::raw("CONCAT(
                IF(master_charges.name IS NOT NULL, master_charges.name, ''), 
                IF(master_charges.name IS NOT NULL AND master_charges.description IS NOT NULL, ' - ', ''), 
                IF(master_charges.description IS NOT NULL, master_charges.description, '')) as addon_name"),
            DB::raw("'App\\Models\\Masters\\MasterCharges' as reference_type")
        )
            ->orderBy('master_charges.id', 'asc')
            ->get();
        // Merge collections
        $data['addons'] = $accessories->merge($spareParts)->merge($kit);
        return response()->json($data);
        // }

        // return response()->json([]);
    }
    public function storeComments(Request $request)
    {
        // Validate the request data, making 'text' nullable
        $request->validate([
            'text' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:w_o_comments,id',
            'work_order_id' => 'required|integer|exists:work_orders,id',
            'mentioned_users' => 'array',
            'mentioned_users.*' => 'exists:users,id',
            'files.*' => 'file|mimes:jpg,png,pdf|max:2048', // File validation
        ]);

        // Check if text is null and there are no files
        if (is_null($request->input('text')) && !$request->hasFile('files')) {
            return response()->json(['error' => 'Text or files are required.'], 422);
        }

        // Store empty space if text is null
        $text = $request->input('text') ?? '';
        // Create the comment with nullable text
        $comment = WOComments::create([
            'work_order_id' => $request->input('work_order_id'),
            'text' => $text, // Store text or empty space
            'parent_id' => $request->input('parent_id'),
            'user_id' => auth()->id(), // Assuming you're using Laravel's authentication
        ]);
        $workOrder = WorkOrder::find($comment->work_order_id);
        if ($request->hasFile('files')) {
            $files = [];
            foreach ($request->file('files') as $file) {
                $files[] = [
                    'file_name' => $file->getClientOriginalName(),
                    'file_data' => 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file->getRealPath())),
                ];
            }
            $comment->files()->createMany($files);
        }

        if ($request->has('mentioned_users')) {
            $comment->mentionedUsers()->attach($request->input('mentioned_users'));
        }

        // Extract mentioned users from the text
        preg_match_all('/@\[([^\]]+)\]/', $text, $matches);
        $mentionedUserNames = $matches[1];

        if (!empty($mentionedUserNames)) {
            $mentionedUsers = User::whereIn('name', $mentionedUserNames)->get();

            foreach ($mentionedUsers as $user) {
                // Queue email notifications for efficiency
                dispatch(function () use ($workOrder, $comment, $user) {
                    $template['from'] = 'no-reply@milele.com';
                    $template['from_name'] = 'Milele Matrix';
                    $customerName = $workOrder->customer_name ?? 'Unknown Customer';
                    $subject = "You were mentioned in a comment - " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;
                    $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
                    $accessLinkWithComment = $accessLink . '#comment-' . $comment->id;
                    Mail::send('work_order.emails.mentioned_in_comment', [
                        'workOrder' => $workOrder,
                        'accessLink' => $accessLink,
                        'accessLinkWithComment' => $accessLinkWithComment,
                        'comment' => $comment,
                        'user' => $user // Pass the user variable to the view
                    ], function ($message) use ($subject, $template, $user) {
                        $message->from($template['from'], $template['from_name'])
                            ->to($user->email)
                            ->subject($subject);
                    });
                })->onQueue('emails');
            }
        }

        return response()->json($comment->load('files', 'mentionedUsers', 'user'), 201);
    }
    // public function getComments($workOrderId)
    // {
    //     // Fetch comments for the specified work order
    //     $comments = WOComments::where('work_order_id', $workOrderId)->get();
    //     return response()->json(['comments' => $comments]);
    // }

    public function getComments($workOrderId)
    {
        $comments = WOComments::where('work_order_id', $workOrderId)
            ->with(
                'files',
                'user',
                'wo_histories',
                'removed_vehicles',
                'new_vehicles.vehicle.addonsWithTrashed',
                'updated_vehicles.vehicle.addonsWithTrashed',
                'new_vehicles.recordHistories',
                'updated_vehicles.recordHistories',
                'new_vehicles.storeMappingAddons.recordHistories',
                'updated_vehicles.updateMappingAddons.recordHistories',
                'new_vehicles.storeMappingAddons.addon',
                'updated_vehicles.updateMappingAddons.addon',
                'updated_vehicles.storeMappingAddons.recordHistories',
                'updated_vehicles.storeMappingAddons.addon',
                'updated_vehicles.deleteMappingAddons.addon',
                'updated_vehicles.updateMappingAddons.recordHistories',
                'updated_vehicles.updateMappingAddons.addon'
            )
            // 'updated_vehicles.deleteMappingAddons'
            ->get();
        return response()->json(['comments' => $comments]);
    }
    public function uniqueWO(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wo_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            try {
                $wo = WorkOrder::where('wo_number', $request->wo_number);
                if ($request->id != NULL || $request->id != '') {
                    $wo = $wo->where('id', '!=', $request->id);
                }
                $wo = $wo->get()->filter(function ($item) {
                    return $item->status != 'Cancelled';  // Filter out records where status is 'Cancelled'
                });
                if ($wo->count() > 0) {
                    return false;
                } else {
                    return true;
                }
            } catch (\Exception $e) {
                info($e);
            }
        }
    }
    public function uniqueSO(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_number' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            try {
                $wo = WorkOrder::where('so_number', $request->so_number);
                if ($request->id != NULL || $request->id != '') {
                    $wo = $wo->whereNot('id', $request->id);
                }
                $wo = $wo->get();
                if (count($wo) > 0) {
                    return false;
                } else {
                    return true;
                }
            } catch (\Exception $e) {
                info($e);
            }
        }
    }
    public function vehicleDataHistory($id)
    {
        $woVehicle = WOVehicles::withTrashed()->where('id', $id)->first();
        $datas = WOVehicleRecordHistory::where('w_o_vehicle_id', $id)->get();
        return view('work_order.export_exw.show_vehicle_history', compact('datas', 'woVehicle'));
    }
    public function vehicleAddonDataHistory($id)
    {
        $woVehicleAddon = WOVehicleAddons::where('id', $id)->first();
        $datas = WOVehicleAddonRecordHistory::where('w_o_vehicle_addon_id', $id)->get();
        return view('work_order.export_exw.show_vehicle_addon_history', compact('datas', 'woVehicleAddon'));
    }
    public function financeApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $woApprovals = WOApprovals::where('id', $request->id)->first();
                $workOrder = WorkOrder::where('id', $woApprovals->work_order_id)->first();

                if ($woApprovals && $woApprovals->action_at == '' && $woApprovals->status == 'pending') {
                    $woApprovals->action_at = Carbon::now();
                    $woApprovals->user_id = $authId;
                    $woApprovals->comments = $request->comments;

                    $woApprovals->status = ($request->status == 'approve') ? 'approved' : 'rejected';
                    $woApprovals->update();

                    $fields = ['so_total_amount', 'currency', 'so_vehicle_quantity', 'amount_received', 'balance_amount', 'deposit_received_as'];

                    $woHistory = WORecordHistory::where('work_order_id', $woApprovals->work_order_id)
                        ->whereIn('field_name', $fields)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique('field_name');

                    foreach ($woHistory as $record) {
                        WOApprovalDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_history_id' => $record->id
                        ]);
                    }
                    if ($workOrder->deposit_received_as == 'custom_deposit') {
                        $depAgnVeh = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->where('deposit_received', 'yes')->select('id')->get();
                        foreach ($depAgnVeh as $depAgnVehId) {
                            WOApprovalDepositAganistVehicle::create([
                                'w_o_approvals_id' => $woApprovals->id,
                                'w_o_vehicle_id' => $depAgnVehId->id
                            ]);
                        }
                    }

                    $woVehicleIds = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->pluck('id');
                    $woAddonIds = WOVehicleAddons::whereIn('w_o_vehicle_id', $woVehicleIds)->pluck('id');

                    $woAddonHistory = WOVehicleAddonRecordHistory::whereIn('w_o_vehicle_addon_id', $woAddonIds)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique(function ($item) {
                            return $item['field_name'] . $item['w_o_vehicle_addon_id'];
                        });

                    foreach ($woAddonHistory as $record) {
                        WOApprovalAddonDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_addon_history_id' => $record->id
                        ]);
                    }
                    DB::commit();
                    // Send email notification
                    $this->sendFinanceApprovalEmail($workOrder, $woApprovals->status, $woApprovals->comments, $woApprovals->user->name);
                    return response()->json('success');
                } else if ($woApprovals && $woApprovals->action_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg = "Something went wrong! Contact your admin";
                return view('hrm.notaccess', compact('errorMsg'));
            }
        }
    }
    private function sendFinanceApprovalEmail($workOrder, $status, $comments, $userName)
    {
        // Prepare the from details
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';

        // Handle cases where customer_name is null
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        // Determine status name
        $statusName = $status === 'approved' ? 'Approved' : 'Rejected';
        // Prepare email subject
        $subject = "WO Finance " . $statusName . " " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // Define a quick access link (adjust the route as needed)
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $approvalHistoryLink = env('BASE_URL') . '/finance-approval-history/' . $workOrder->id;
        // Retrieve email addresses from the users table where can_send_wo_email is true
        $managementEmails = \App\Models\User::where('can_send_wo_email', true)->pluck('email')->filter(function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        })->toArray();
        
        // Filter out excluded emails (fahad@milele.com)
        $managementEmails = $this->filterExcludedEmails($managementEmails);
        // Retrieve and validate email addresses from .env for finance and operations teams
        $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

        // Retrieve the CreatedBy user's email and validate it
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);

        // Log email addresses to help with debugging
        \Log::info('Email Recipients:', [
            'financeEmail' => $financeEmail,
            'operationsEmail' => $operationsEmail,
            'createdByEmail' => $createdByEmail ?? 'null',
            'managementEmails' => implode(', ', $managementEmails) ?: 'none found'
        ]);
        // Combine all recipient emails into a single array
        $recipients = array_filter(array_merge([$financeEmail, $operationsEmail, $createdByEmail], $managementEmails));
        
        // Filter out excluded emails (fahad@milele.com)
        $recipients = $this->filterExcludedEmails($recipients);
        // If no valid recipients, log the issue and skip sending the email
        if (empty($recipients)) {
            \Log::info('No valid recipients found. Skipping email sending for WO-' . $workOrder->wo_number);
            return; // Exit the function without throwing an exception
        }

        // Send email using a Blade template
        try {
            Mail::send('work_order.emails.fin_approval', [
                'workOrder' => $workOrder,
                'accessLink' => $accessLink,
                'approvalHistoryLink' => $approvalHistoryLink,
                'comments' => $comments,
                'userName' => $userName,
                'status' => $status
            ], function ($message) use ($subject, $recipients, $template) {
                $message->from($template['from'], $template['from_name'])
                    ->to($recipients)
                    ->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
    public function coeOfficeApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $woApprovals = WOApprovals::where('id', $request->id)->first();
                $workOrder = WorkOrder::where('id', $woApprovals->work_order_id)->first();
                if ($woApprovals && $woApprovals->action_at == '' && $woApprovals->status == 'pending') {
                    $woApprovals->action_at = Carbon::now();
                    $woApprovals->user_id = $authId;
                    $woApprovals->comments = $request->comments;

                    $woApprovals->status = ($request->status == 'approve') ? 'approved' : 'rejected';
                    $woApprovals->update();
                    $woVehicleIds = WOVehicles::where('work_order_id', $woApprovals->work_order_id)->pluck('id');

                    $woVehicleHistory = WOVehicleRecordHistory::whereIn('w_o_vehicle_id', $woVehicleIds)
                        ->orderBy('changed_at', 'desc')
                        ->get()
                        ->unique(function ($item) {
                            return $item['field_name'] . $item['w_o_vehicle_id'];
                        });
                    foreach ($woVehicleHistory as $record) {
                        WOApprovalVehicleDataHistory::create([
                            'w_o_approvals_id' => $woApprovals->id,
                            'wo_vehicle_history_id' => $record->id
                        ]);
                    }

                    // If COO approval is approved, trigger finance approval notification
                    if (isset($woApprovals) && $woApprovals->status == 'approved') {
                        // Create finance approval record if it doesn't exist
                        $financePending = WOApprovals::where('work_order_id', $workOrder->id)
                            ->where('type', 'finance')
                            ->where('status', 'pending')
                            ->orderBy('id', 'ASC')
                            ->first();
                        
                        if (!$financePending) {
                            $financePending = WOApprovals::create([
                                'work_order_id' => $workOrder->id,
                                'type' => 'finance',
                                'status' => 'pending',
                                'action_at' => NULL,
                            ]);
                        }
                        
                        if ($financePending) {
                            // Prepare the from details
                            $template['from'] = 'no-reply@milele.com';
                            $template['from_name'] = 'Milele Matrix';

                            // Handle cases where customer_name is null
                            $customerName = $workOrder->customer_name ?? 'Unknown Customer';

                            // Prepare email data
                            $subject = "COO approved the work order " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;
                            // Define a quick access link (adjust the route as needed)
                            $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
                            $approvalHistoryLink = env('BASE_URL') . '/finance-approval-history/' . $workOrder->id;

                            $rolesWithPermission = Role::whereHas('permissions', function ($query) {
                                $query->where('name', 'do-finance-approval');
                            })->pluck('id')->toArray();
                            // Get users with the required roles and valid email addresses
                            $recipients = \App\Models\User::role($rolesWithPermission)
                                ->whereIn('status', ['new', 'active'])
                                ->where('password', '!=', '')
                                ->whereHas('roles')
                                ->pluck('email')
                                ->filter(function ($email) {
                                    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
                                })
                                ->toArray();

                            if (!empty($recipients)) {
                                Mail::send('work_order.emails.coo_approval', [
                                    'workOrder' => $workOrder,
                                    'subject' => $subject,
                                    'accessLink' => $accessLink,
                                    'approvalHistoryLink' => $approvalHistoryLink,
                                    'customerName' => $customerName,
                                    'userName' => $woApprovals->user->name,
                                    'comments' => $woApprovals->comments,
                                    'status' => $woApprovals->status,
                                ], function ($message) use ($recipients, $subject) {
                                    $message->to($recipients)
                                        ->subject($subject);
                                });
                            }
                        }
                    }

                    DB::commit();
                    // Send email notification
                    $this->sendCOOApprovalEmail($workOrder, $woApprovals->status, $woApprovals->comments, $woApprovals->user->name);
                    return response()->json('success');
                } else if ($woApprovals && $woApprovals->action_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg = "Something went wrong! Contact your admin";
                return view('hrm.notaccess', compact('errorMsg'));
            }
        }
    }
    private function sendCOOApprovalEmail($workOrder, $status, $comments, $userName)
    {
        // Prepare the from details
        $template['from'] = 'no-reply@milele.com';
        $template['from_name'] = 'Milele Matrix';

        // Handle cases where customer_name is null
        $customerName = $workOrder->customer_name ?? 'Unknown Customer';
        // Determine status name
        $statusName = $status === 'approved' ? 'Approved' : 'Rejected';
        // Prepare email subject
        $subject = "WO COO Office " . $statusName . " " . $workOrder->wo_number . " " . $customerName . " " . $workOrder->vehicle_count . " Unit " . $workOrder->type_name;

        // Define a quick access link (adjust the route as needed)
        $accessLink = env('BASE_URL') . '/work-order/' . $workOrder->id;
        $approvalHistoryLink = env('BASE_URL') . '/coo-approval-history/' . $workOrder->id;
        // Retrieve email addresses from the users table where can_send_wo_email is true
        $managementEmails = \App\Models\User::where('can_send_wo_email', true)->pluck('email')->filter(function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        })->toArray();
        
        // Filter out excluded emails (fahad@milele.com)
        $managementEmails = $this->filterExcludedEmails($managementEmails);
        // Retrieve and validate email addresses from .env for finance and operations teams
        $financeEmail = filter_var(env('FINANCE_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';
        $operationsEmail = filter_var(env('OPERATIONS_TEAM_EMAIL'), FILTER_VALIDATE_EMAIL) ?: 'no-reply@milele.com';

        // Retrieve the CreatedBy user's email and validate it
        $createdByEmail = filter_var(optional($workOrder->CreatedBy)->email, FILTER_VALIDATE_EMAIL);

        // Log email addresses to help with debugging
        \Log::info('Email Recipients:', [
            'financeEmail' => $financeEmail,
            'operationsEmail' => $operationsEmail,
            'createdByEmail' => $createdByEmail ?? 'null',
            'managementEmails' => implode(', ', $managementEmails) ?: 'none found'
        ]);
        // Combine all recipient emails into a single array
        $recipients = array_filter(array_merge([$financeEmail, $operationsEmail, $createdByEmail], $managementEmails));
        
        // Filter out excluded emails (fahad@milele.com)
        $recipients = $this->filterExcludedEmails($recipients);
        // If no valid recipients, log the issue and skip sending the email
        if (empty($recipients)) {
            \Log::info('No valid recipients found. Skipping email sending for WO-' . $workOrder->wo_number);
            return; // Exit the function without throwing an exception
        }

        // Send email using a Blade template
        try {
            Mail::send('work_order.emails.coo_approval', [
                'workOrder' => $workOrder,
                'accessLink' => $accessLink,
                'approvalHistoryLink' => $approvalHistoryLink,
                'comments' => $comments,
                'userName' => $userName,
                'status' => $status
            ], function ($message) use ($subject, $recipients, $template) {
                $message->from($template['from'], $template['from_name'])
                    ->to($recipients)
                    ->subject($subject);
            });
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
    public function salesApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $wo = WorkOrder::where('id', $request->id)->first();
                if ($wo && $wo->sales_support_data_confirmation_at == '' && $wo->sales_support_data_confirmation_by == '') {
                    $wo->sales_support_data_confirmation_at = Carbon::now();
                    $wo->sales_support_data_confirmation_by = $authId;
                    $wo->update();
                    WORecordHistory::create([
                        'work_order_id' => $wo->id,
                        'user_id' => $authId,
                        'field_name' => 'sales_support_data_confirmation_at',
                        'old_value' => NULL,
                        'new_value' => Carbon::now()->format('d M Y, H:i:s'),
                        'type' => 'Set',
                        'changed_at' => Carbon::now()
                    ]);
                    WORecordHistory::create([
                        'work_order_id' => $wo->id,
                        'user_id' => $authId,
                        'field_name' => 'sales_support_data_confirmation_by',
                        'old_value' => NULL,
                        'new_value' => Auth::user()->name,
                        'type' => 'Set',
                        'changed_at' => Carbon::now()
                    ]);
                    // Create COO approval record if it doesn't exist
                    $existingCooApproval = WOApprovals::where('work_order_id', $wo->id)
                        ->where('type', 'coo')
                        ->first();
                    
                    if (!$existingCooApproval) {
                        $cooPending = WOApprovals::create([
                            'work_order_id' => $wo->id,
                            'type' => 'coo',
                            'status' => 'pending',
                            'action_at' => NULL,
                        ]);
                        \Log::info('COO approval record created for work order: ' . $wo->wo_number . ' (ID: ' . $wo->id . ')');
                    } else {
                        // If COO approval exists but is not pending, create a new one
                        if ($existingCooApproval->status != 'pending') {
                            $cooPending = WOApprovals::create([
                                'work_order_id' => $wo->id,
                                'type' => 'coo',
                                'status' => 'pending',
                                'action_at' => NULL,
                            ]);
                            \Log::info('New COO approval record created for work order: ' . $wo->wo_number . ' (ID: ' . $wo->id . ') - Previous status: ' . $existingCooApproval->status);
                        } else {
                            $cooPending = $existingCooApproval;
                            \Log::info('Existing COO approval record found for work order: ' . $wo->wo_number . ' (ID: ' . $wo->id . ') - Status: ' . $existingCooApproval->status);
                        }
                    }
                    
                    if ($cooPending) {
                        // Prepare the from details
                        $template['from'] = 'no-reply@milele.com';
                        $template['from_name'] = 'Milele Matrix';

                        // Handle cases where customer_name is null
                        $customerName = $wo->customer_name ?? 'Unknown Customer';

                        // Prepare email data
                        $subject = "Sales support confirmed the work order " . $wo->wo_number . " " . $customerName . " " . $wo->vehicle_count . " Unit " . $wo->type_name;

                        // Define a quick access link (adjust the route as needed)
                        $accessLink = env('BASE_URL') . '/work-order/' . $wo->id;
                        $approvalHistoryLink = env('BASE_URL') . '/coo-approval-history/' . $wo->id;

                        $rolesWithPermission = Role::whereHas('permissions', function ($query) {
                            $query->where('name', 'do-coo-office-approval');
                        })->pluck('id')->toArray();
                        $recipients = \App\Models\User::role($rolesWithPermission)->whereIn('status', ['new', 'active'])->where('password', '!=', '')->whereHas('roles')
                            ->pluck('email')->filter(function ($email) {
                                return filter_var($email, FILTER_VALIDATE_EMAIL);
                            })->toArray();
                        
                        // Filter out excluded emails (fahad@milele.com)
                        $recipients = $this->filterExcludedEmails($recipients);
                            
                        // Log email addresses to help with debugging
                        \Log::info('Email Recipients:', [
                            'recipients' => implode(', ', $recipients) ?: 'none found',
                        ]);
                        // Log and handle invalid email addresses (but do not throw an exception, just log)
                        if (empty($recipients)) {
                            \Log::info('No valid recipients found. Skipping email sending for Work Order: ' . $wo->wo_number);
                            return;
                        }
                        // Send email using a Blade template
                        Mail::send('work_order.emails.confirmed_coo_pending', [
                            'workOrder' => $wo,
                            'accessLink' => $accessLink,
                            'approvalHistoryLink' => $approvalHistoryLink,
                        ], function ($message) use ($subject, $recipients, $template) {
                            $message->from($template['from'], $template['from_name'])
                                ->to($recipients)
                                ->subject($subject);
                        });
                    }
                    DB::commit();
                    return response()->json('success');
                } else if ($wo && $wo->sales_support_data_confirmation_at != '') {
                    DB::commit();
                    return response()->json('error');
                }
            } catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg = "Something went wrong! Contact your admin";
                return view('hrm.notaccess', compact('errorMsg'));
            }
        }
    }
    public function revertSalesApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            DB::beginTransaction();
            try {
                $authId = Auth::id();
                $wo = WorkOrder::where('id', $request->id)->first();
                if ($wo && $wo->sales_support_data_confirmation_at != '' && $wo->sales_support_data_confirmation_by != '') {
                    $wo->sales_support_data_confirmation_at = NULL;
                    $wo->sales_support_data_confirmation_by = NULL;
                    $wo->update();
                    WORecordHistory::create([
                        'work_order_id' => $wo->id,
                        'user_id' => $authId,
                        'field_name' => 'sales_support_data_confirmation_at',
                        'old_value' => $wo->sales_support_data_confirmation_at,
                        'new_value' => NULL,
                        'type' => 'Set',
                        'changed_at' => Carbon::now()
                    ]);
                    WORecordHistory::create([
                        'work_order_id' => $wo->id,
                        'user_id' => $authId,
                        'field_name' => 'sales_support_data_confirmation_by',
                        'old_value' => $wo->sales_support_data_confirmation_by,
                        'new_value' => NULL,
                        'type' => 'Set',
                        'changed_at' => Carbon::now()
                    ]);
                    DB::commit();
                    return response()->json('success');
                } else if ($wo && $wo->sales_support_data_confirmation_at == '') {
                    DB::commit();
                    return response()->json('error');
                }
            } catch (\Exception $e) {
                DB::rollback();
                info($e);
                $errorMsg = "Something went wrong! Contact your admin";
                return view('hrm.notaccess', compact('errorMsg'));
            }
        }
    }
    public function saveFilters(Request $request)
    {
        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Serialize the filters to store as a JSON string
        $filters = json_encode([
            'status_filter' => $request->status_filter,
            'sales_support_filter' => $request->sales_support_filter,
            'finance_approval_filter' => $request->finance_approval_filter,
            'coo_approval_filter' => $request->coo_approval_filter,
            'docs_status_filter' => $request->docs_status_filter,
        ]);

        // Check if the filter record exists for the current user
        $userFilter = WOUserFilterInputs::where('user_id', $userId)->first();

        if ($userFilter) {
            // If record exists, update the filters
            $userFilter->filters = $filters;
            $userFilter->save();
        } else {
            // If no record exists, create a new one
            WOUserFilterInputs::create([
                'user_id' => $userId,
                'filters' => $filters
            ]);
        }

        // Redirect back to the index route with the type parameter
        return redirect()->route('work-order.index', ['type' => $request->input('type')]);
    }
    public function checkSONumber(Request $request)
    {
        $soNumber = $request->input('so_number');
        $workOrderId = $request->input('work_order_id'); // In case of edit 

        // Helper closure to extract the numeric batch
        $extractNumericBatch = function ($batch) {
            return (int) filter_var($batch, FILTER_SANITIZE_NUMBER_INT);
        };
        if ($workOrderId) {
            $workOrders = WorkOrder::where('id', $workOrderId)->first();
            if ($workOrders->so_number == $soNumber) {
                $largestBatch = $extractNumericBatch($workOrders->batch);
                $isBatch = $workOrders->is_batch;
                return response()->json([
                    'exists' => true,
                    'largest_batch' => $largestBatch,
                    'is_batch' => $isBatch,
                ]);
            } else {
                $workOrders = WorkOrder::where('so_number', $soNumber)->get();
                if ($workOrders->isEmpty()) {
                    return response()->json(['exists' => false]); // SO number doesn't exist
                }
                $numericBatches = $workOrders->where('is_batch', 1)
                    ->pluck('batch')
                    ->map($extractNumericBatch);
                $largestBatch = $numericBatches->max() ?? 0;
                $isBatch = $workOrders->first()->is_batch;
                return response()->json([
                    'exists' => true,
                    'largest_batch' => $largestBatch,
                    'is_batch' => $isBatch,
                ]);
            }
        } else {
            $workOrders = WorkOrder::where('so_number', $soNumber)->get();
            if ($workOrders->isEmpty()) {
                return response()->json(['exists' => false]); // SO number doesn't exist
            }
            $numericBatches = $workOrders->where('is_batch', 1)
                ->pluck('batch')
                ->map($extractNumericBatch);
            $largestBatch = $numericBatches->max() ?? 0;
            $isBatch = $workOrders->first()->is_batch; // Get the is_batch status from the first result
            return response()->json([
                'exists' => true,
                'largest_batch' => $largestBatch,
                'is_batch' => $isBatch,
            ]);
        }
    }
    public function isExistInSalesOrder(Request $request)
    {
        $soNumber = $request->input('so_number');
        // Check if the so_number exists in the So model
        $exists = So::where('so_number', $soNumber)->exists();
        return response()->json(['valid' => $exists]);
    }
    function formatPhoneForExcel($number)
    {
        return $number ? " " . $number : '';
    }
    public function searchWorkOrderVins(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        if (strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $vehicles = Vehicles::select('id', 'vin', 'engine', 'varaints_id', 'int_colour', 'ex_colour', 'latest_location', 'territory', 'documents_id')
            ->whereNotNull('vin')
            ->where('vin', 'like', '%' . $term . '%')
            ->orderBy('vin', 'ASC')
            ->with([
                'variant.master_model_lines.brand',
                'interior',
                'exterior',
                'warehouseLocation',
                'document',
            ])
            ->limit(40)
            ->get()
            ->unique('vin')
            ->values();

        $results = $vehicles->map(function (Vehicles $vehicle) {
            $brandName = $vehicle->variant->master_model_lines->brand->brand_name ?? '';
            $modelLine = $vehicle->variant->master_model_lines->model_line ?? '';

            return [
                'id' => $vehicle->vin,
                'text' => trim($vehicle->vin . ' / ' . $brandName . ' / ' . $modelLine, ' /'),
            ];
        })->values();

        return response()->json(['results' => $results]);
    }

    public function getWorkOrderVinDetails(Request $request)
    {
        $vins = $request->input('vins', []);
        if (!is_array($vins) || empty($vins)) {
            return response()->json(['vins' => []]);
        }

        $vins = array_values(array_unique(array_filter($vins)));

        $vehicles = Vehicles::select('id', 'vin', 'engine', 'varaints_id', 'int_colour', 'ex_colour', 'latest_location', 'territory', 'documents_id')
            ->whereNotNull('vin')
            ->whereIn('vin', $vins)
            ->with([
                'variant.master_model_lines.brand',
                'interior',
                'exterior',
                'warehouseLocation',
                'document',
            ])
            ->get()
            ->unique('vin')
            ->values();

        return response()->json([
            'vins' => $vehicles->map(fn (Vehicles $vehicle) => $this->formatVinForWorkOrderForm($vehicle))->values(),
        ]);
    }

    public function searchWorkOrderCustomers(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        if (strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $authId = Auth::id();
        $hasLimitedAccess = Auth::user()->hasPermissionForSelectedRole([
            'view-current-user-export-exw-wo-list',
            'view-current-user-export-cnf-wo-list',
            'view-current-user-local-sale-wo-list',
        ]);

        $likeTerm = '%' . $term . '%';

        $workOrders = WorkOrder::select(
            DB::raw('TRIM(customer_name) as customer_name'),
            'customer_email',
            'customer_company_number',
            'customer_address',
            DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\WorkOrder' as reference_type"),
            DB::raw('NULL as country_id'),
            DB::raw('NULL as is_demand_planning_customer'),
            DB::raw("CONCAT(TRIM(customer_name), '_', IFNULL(customer_email, ''), '_', IFNULL(customer_company_number, '')) as unique_id")
        )
            ->where(function ($query) use ($likeTerm) {
                $query->where('customer_name', 'like', $likeTerm)
                    ->orWhere('customer_email', 'like', $likeTerm)
                    ->orWhere('customer_company_number', 'like', $likeTerm);
            });

        $clients = Clients::select(
            DB::raw('TRIM(name) as customer_name'),
            DB::raw('email as customer_email'),
            DB::raw('phone as customer_company_number'),
            DB::raw('NULL as customer_address'),
            DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
            DB::raw("'App\\Models\\Clients' as reference_type"),
            'country_id',
            'is_demand_planning_customer',
            DB::raw("CONCAT(TRIM(name), '_', IFNULL(email, ''), '_', IFNULL(phone, ''), '_', IFNULL(country_id, '')) as unique_id")
        )
            ->where(function ($query) use ($likeTerm) {
                $query->where('name', 'like', $likeTerm)
                    ->orWhere('email', 'like', $likeTerm)
                    ->orWhere('phone', 'like', $likeTerm);
            });

        if ($hasLimitedAccess) {
            $workOrders->where('created_by', $authId);
            $clients->where('created_by', $authId);
        }

        $combinedResults = $workOrders->limit(25)->get()->concat($clients->limit(25)->get());
        $combinedResults = $combinedResults->map(function ($item) {
            $item->customer_name = $this->cleanField($item->customer_name);
            $item->customer_email = $this->cleanField($item->customer_email);
            $item->customer_company_number = $this->cleanField($item->customer_company_number);
            $item->customer_address = $this->cleanField($item->customer_address);

            return $item;
        });

        $customers = $combinedResults->groupBy('unique_id')->map(function ($items) {
            return $items->sortByDesc('score')->first();
        })->values()->sortBy('customer_name')->take(30)->values();

        $results = $customers->map(function ($customer) {
            return [
                'id' => $customer->customer_name,
                'text' => $customer->customer_name,
                'unique_id' => $customer->unique_id,
                'customer_email' => $customer->customer_email,
                'customer_company_number' => $customer->customer_company_number,
                'customer_address' => $customer->customer_address,
                'reference_type' => $customer->reference_type,
                'country_id' => $customer->country_id,
                'is_demand_planning_customer' => $customer->is_demand_planning_customer,
            ];
        });

        return response()->json(['results' => $results]);
    }

    private function formatVinForWorkOrderForm(Vehicles $vin): array
    {
        return [
            'id' => '',
            'vehicle_id' => $vin->id,
            'vin' => $vin->vin ?? '',
            'brand' => $vin->variant->master_model_lines->brand->brand_name ?? '',
            'variant' => $vin->variant->name ?? '',
            'engine' => $vin->engine ?? '',
            'model_description' => $vin->variant->model_detail ?? '',
            'model_year' => $vin->variant->my ?? '',
            'model_year_to_mention_on_documents' => $vin->variant->my ?? '',
            'steering' => $vin->variant->steering ?? '',
            'exterior_colour' => $vin->exterior->name ?? '',
            'interior_colour' => $vin->interior->name ?? '',
            'warehouse' => $vin->warehouseLocation->name ?? '',
            'territory' => $vin->territory ?? '',
            'preferred_destination' => '',
            'import_document_type' => $vin->document->import_type ?? '',
            'ownership_name' => $vin->document->ownership ?? '',
            'certification_per_vin' => '',
            'modification_or_jobs_to_perform_per_vin' => '',
            'special_request_or_remarks' => '',
            'shipment' => '',
        ];
    }

    private function getWorkOrderDetailEagerLoads(): array
    {
        return [
            'comments',
            'financePendingApproval',
            'cooPendingApproval',
            'latestDocsStatus',
            'latestStatus',
            'latestFinance',
            'latestCOO',
            'boe',
            'salesPerson:id,name',
            'vehicles' => function ($query) {
                $query->with([
                    'addons',
                    'latestModificationStatus',
                    'latestPdiStatus',
                    'latestDeliveryStatus',
                ])->withCount('addons');
            },
        ];
    }

    private function getWorkOrderActiveUsers()
    {
        return \Cache::remember('workorder_active_users', 300, function () {
            return User::select('id', 'name')
                ->orderBy('name', 'ASC')
                ->where('status', 'active')
                ->whereNotIn('id', [1, 16])
                ->whereHas('empProfile', function ($q) {
                    $q->where('type', 'employee');
                })
                ->get();
        });
    }

    private function getWorkOrderFormDropdownData(int $authId, bool $hasLimitedAccess, ?WorkOrder $workOrder = null, bool $loadVinAndCustomerLists = false): array
    {
        $customers = collect();
        if ($loadVinAndCustomerLists) {
            $customersCacheKey = 'workorder_customers_' . ($hasLimitedAccess ? 'limited_' . $authId : 'full');
            $customers = \Cache::remember($customersCacheKey, 300, function () use ($authId, $hasLimitedAccess) {
                $workOrders = WorkOrder::select(
                    DB::raw('TRIM(customer_name) as customer_name'),
                    'customer_email',
                    'customer_company_number',
                    'customer_address',
                    DB::raw('(IF(customer_email IS NOT NULL, 1, 0) + IF(customer_company_number IS NOT NULL, 1, 0) + IF(customer_address IS NOT NULL, 1, 0)) as score'),
                    DB::raw("'App\\Models\\WorkOrder' as reference_type"),
                    DB::raw('NULL as country_id'),
                    DB::raw('NULL as is_demand_planning_customer'),
                    DB::raw("CONCAT(TRIM(customer_name), '_', IFNULL(customer_email, ''), '_', IFNULL(customer_company_number, '')) as unique_id")
                );
                $clients = Clients::select(
                    DB::raw('TRIM(name) as customer_name'),
                    DB::raw('email as customer_email'),
                    DB::raw('phone as customer_company_number'),
                    DB::raw('NULL as customer_address'),
                    DB::raw('(IF(email IS NOT NULL, 1, 0) + IF(phone IS NOT NULL, 1, 0)) as score'),
                    DB::raw("'App\\Models\\Clients' as reference_type"),
                    'country_id',
                    'is_demand_planning_customer',
                    DB::raw("CONCAT(TRIM(name), '_', IFNULL(email, ''), '_', IFNULL(phone, ''), '_', IFNULL(country_id, '')) as unique_id")
                );
                if ($hasLimitedAccess) {
                    $workOrders->where('created_by', $authId);
                    $clients->where('created_by', $authId);
                }
                $combinedResults = $workOrders->union($clients)->get();
                $combinedResults = $combinedResults->map(function ($item) {
                    $item->customer_name = $this->cleanField($item->customer_name);
                    $item->customer_email = $this->cleanField($item->customer_email);
                    $item->customer_company_number = $this->cleanField($item->customer_company_number);
                    $item->customer_address = $this->cleanField($item->customer_address);
                    return $item;
                });

                return $combinedResults->groupBy('unique_id')->map(function ($items) {
                    return $items->sortByDesc('score')->first();
                })->values()->sortBy('customer_name')->values();
            });
        }

        $addons = \Cache::remember('workorder_addons', 300, function () {
            $allAddons = AddonDetails::select(
                'addon_details.id',
                'addon_details.addon_code',
                DB::raw("CONCAT(addons.name, IF(addon_descriptions.description IS NOT NULL AND addon_descriptions.description != '', CONCAT(' - ', addon_descriptions.description), '')) as addon_name"),
                DB::raw("'App\\Models\\AddonDetails' as reference_type"),
                'addon_details.addon_type_name'
            )
                ->join('addon_descriptions', 'addon_details.description', '=', 'addon_descriptions.id')
                ->join('addons', 'addon_descriptions.addon_id', '=', 'addons.id')
                ->whereIn('addon_details.addon_type_name', ['K', 'P', 'SP'])
                ->orderBy('addon_details.id', 'asc')
                ->get();
            $kit = $allAddons->where('addon_type_name', 'K')->values();
            $accessories = $allAddons->where('addon_type_name', 'P')->values();
            $spareParts = $allAddons->where('addon_type_name', 'SP')->values();

            return $accessories->merge($spareParts)->merge($kit);
        });

        $charges = \Cache::remember('workorder_charges', 300, function () {
            return MasterCharges::select(
                'master_charges.id',
                'master_charges.addon_code',
                DB::raw("CONCAT(IF(master_charges.name IS NOT NULL, master_charges.name, ''), IF(master_charges.name IS NOT NULL AND master_charges.description IS NOT NULL, ' - ', ''), IF(master_charges.description IS NOT NULL, master_charges.description, '')) as addon_name"),
                DB::raw("'App\\Models\\Masters\\MasterCharges' as reference_type")
            )
                ->orderBy('master_charges.id', 'asc')
                ->get();
        });

        $airlines = \Cache::remember('workorder_airlines', 300, function () {
            return MasterAirlines::orderBy('name', 'ASC')->get();
        });

        $vins = $loadVinAndCustomerLists ? $this->getWorkOrderVinsList() : collect();
        $users = $this->getWorkOrderActiveUsers();

        $salesPersons = collect();
        $hasAllSalesAccess = Auth::user()->hasPermissionForSelectedRole(['create-wo-for-all-sales-person']);
        if ($hasAllSalesAccess) {
            $salesPersons = User::select('id', 'name')
                ->where(function ($query) use ($workOrder) {
                    $query->where(function ($q) {
                        $q->where('status', 'active')
                            ->where('is_sales_rep', 'Yes')
                            ->whereNotIn('id', [1, 16])
                            ->whereHas('empProfile', function ($sub) {
                                $sub->where('type', 'employee');
                            });
                    });
                    if ($workOrder && $workOrder->sales_person_id) {
                        $query->orWhere('id', $workOrder->sales_person_id);
                    }
                })
                ->orderBy('name', 'ASC')
                ->get()
                ->unique('id')
                ->values();
        }

        return [
            'customers' => $customers,
            'customerCount' => $customers->count(),
            'addons' => $addons,
            'charges' => $charges,
            'airlines' => $airlines,
            'vins' => $vins,
            'users' => $users,
            'salesPersons' => $salesPersons,
        ];
    }

    private function getWorkOrderVinsList()
    {
        return \Cache::remember('workorder_vins_list_v2', 300, function () {
            return Vehicles::select('id', 'vin', 'engine', 'varaints_id', 'int_colour', 'ex_colour', 'latest_location', 'territory', 'documents_id')
                ->whereNotNull('vin')
                ->orderBy('vin', 'ASC')
                ->with([
                    'variant.master_model_lines.brand',
                    'interior',
                    'exterior',
                    'warehouseLocation',
                    'document',
                ])
                ->get()
                ->unique('vin')
                ->values();
        });
    }

    private function prepareWorkOrderForEditJson(WorkOrder $workOrder): array
    {
        $data = $workOrder->getAttributes();
        $data['vehicles'] = $workOrder->vehicles->map(function (WOVehicles $vehicle) {
            $vehicle->setAppends([]);
            $vehicleData = $vehicle->toArray();
            $vehicleData['addons'] = $vehicle->addons->map(function ($addon) {
                $addon->setAppends([]);

                return $addon->toArray();
            })->values()->all();

            return $vehicleData;
        })->values()->all();

        return $data;
    }

    private function cleanField($value)
    {
        if (is_null($value)) return null;
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value); // remove control chars
        return preg_replace('/\s+/', ' ', trim($value));
    }

    /**
     * Update the created_at field for a work order
     */
    public function updateCreatedAt(Request $request)
    {
        try {
            $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'created_at' => 'required|date',
            ]);

            $workOrder = WorkOrder::findOrFail($request->work_order_id);
            $oldCreatedAt = $workOrder->created_at;
            
            // Set the time to 00:00:00 for the date input
            $newCreatedAt = $request->created_at . ' 00:00:00';
            
            // Update the created_at field
            $workOrder->created_at = $newCreatedAt;
            $workOrder->save();

            // Create history record for the change
            WORecordHistory::create([
                'work_order_id' => $workOrder->id,
                'field_name' => 'created_at',
                'old_value' => $oldCreatedAt ? $oldCreatedAt->format('Y-m-d H:i:s') : null,
                'new_value' => $newCreatedAt,
                'type' => 'Change',
                'user_id' => Auth::id(),
                'changed_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Created date updated successfully.',
                'formatted_date' => Carbon::parse($newCreatedAt)->format('d M Y')
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating created_at: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update created date. Please try again.'
            ], 500);
        }
    }

    /**
     * Update the date field for a work order
     */
    public function updateDate(Request $request)
    {
        try {
            $request->validate([
                'work_order_id' => 'required|exists:work_orders,id',
                'date' => 'required|date',
            ]);

            $workOrder = WorkOrder::findOrFail($request->work_order_id);
            $oldDate = $workOrder->date;
            
            // Set the time to 00:00:00 for the date input
            $newDate = $request->date . ' 00:00:00';
            
            // Update the date field, created_at, updated_at, and updated_by
            $workOrder->date = $newDate;
            $workOrder->created_at = $newDate; // Also update created_at with the new date
            $workOrder->updated_at = Carbon::now();
            $workOrder->updated_by = Auth::id();
            $workOrder->save();

            // Create history record for the change
            WORecordHistory::create([
                'work_order_id' => $workOrder->id,
                'field_name' => 'date',
                'old_value' => $oldDate ? Carbon::parse($oldDate)->format('Y-m-d H:i:s') : null,
                'new_value' => $newDate,
                'type' => 'Change',
                'user_id' => Auth::id(),
                'changed_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Date updated successfully.',
                'formatted_date' => Carbon::parse($newDate)->format('d M Y'),
                'updated_at_formatted' => Carbon::now()->format('d M Y'),
                'updated_by_name' => Auth::user()->name
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating date: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update date. Please try again.'
            ], 500);
        }
    }
}
