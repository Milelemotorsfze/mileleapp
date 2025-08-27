<?php

namespace App\Http\Controllers;

use App\Models\ColorCode;
use Illuminate\Support\Facades\Log;
use App\Events\DataUpdatedEvent;
use App\Models\VehicleApprovalRequests;
use App\Models\Vehicles;
use App\Models\PurchasingOrder;
use App\Models\Varaint;
use App\Models\WordpressPost;
use App\Models\WordpressPostMeta;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Brand;
use App\Models\Grn;
use App\Models\Movement;
use App\Models\MovementGrn;
use App\Models\MovementsReference;
use App\Models\Gdn;
use App\Models\Document;
use App\Models\Documentlog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ModelHasRoles;
use App\Models\So;
use App\Models\UserActivities;
use App\Models\Vehicleslog;
use App\Models\Solog;
use App\Models\Remarks;
use App\Models\Warehouse;
use App\Models\Inspection;
use App\Models\VehicleExtraItems;
use App\Models\VehiclePicture;
use App\Models\Incident;
use App\Models\Pdi;
use DataTables;
use App\Models\MasterModelLines;
use App\Models\VariantItems;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss);
            $data = $data->where(function ($query) {
                $query->whereNull('gdn_id')
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNotNull('gdn_id')
                            ->whereHas('gdn', function ($gdnQuery) {
                                $sixMonthsAgo = now()->subMonths(1);
                                $gdnQuery->where('date', '>', $sixMonthsAgo);
                            });
                    });
            });
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $columnNames = $request->query('columnName');
            $searchQueries = $request->query('searchQuery');
            // Check if any filters were applied
            if (!empty($columnNames) && !empty($searchQueries)) {
                // Loop through the array of column names and search queries
                foreach ($columnNames as $index => $columnName) {
                    $searchQuery = $searchQueries[$index];
                    // Apply filtering logic based on the column name and search query
                    // Customize this part based on your filtering requirements
                    switch ($columnName) {
                        case 'vin':
                            $vinNumbers = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($vinNumbers) {
                                foreach ($vinNumbers as $vin) {
                                    if ($vin == "null") {
                                        $query->orWhere('vin', null);
                                    } else {
                                        $query->orWhere('vin', 'LIKE', '%' . trim($vin) . '%');
                                    }
                                }
                            });
                            break;
                        case 'vehicle_id':
                            $vehicle_id = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($vehicle_id) {
                                foreach ($vehicle_id as $id) {
                                    if ($id == "null") {
                                        $query->orWhere('id', null);
                                    } else {
                                        $query->orWhere('id', 'LIKE', '%' . trim($id) . '%');
                                    }
                                }
                            });
                            break;
                        case 'estimation_date':
                            $estimation_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($estimation_date) {
                                foreach ($estimation_date as $estimation_date) {
                                    if ($estimation_date == "null") {
                                        $query->orWhere('estimation_date', null);
                                    } else {
                                        $query->orWhere('estimation_date', 'LIKE', '%' . trim($estimation_date) . '%');
                                    }
                                }
                            });
                            break;
                        case 'grn_remark':
                            $grn_remark = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($grn_remark) {
                                foreach ($grn_remark as $grn_remark) {
                                    if ($grn_remark == "null") {
                                        $query->orWhere('grn_remark', null);
                                    } else {
                                        $query->orWhere('grn_remark', 'LIKE', '%' . trim($grn_remark) . '%');
                                    }
                                }
                            });
                            break;
                        case 'qc_remarks':
                            $qc_remarks = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($qc_remarks) {
                                foreach ($qc_remarks as $qc_remarks) {
                                    if ($qc_remarks == "null") {
                                        $query->orWhere('qc_remarks', null);
                                    } else {
                                        $query->orWhere('qc_remarks', 'LIKE', '%' . trim($qc_remarks) . '%');
                                    }
                                }
                            });
                            break;
                        case 'pdi_date':
                            $pdi_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($pdi_date) {
                                foreach ($pdi_date as $pdi_date) {
                                    if ($pdi_date == "null") {
                                        $query->orWhere('pdi_date', null);
                                    } else {
                                        $query->orWhere('pdi_date', 'LIKE', '%' . trim($pdi_date) . '%');
                                    }
                                }
                            });
                            break;
                        case 'pdi_remarks':
                            $pdi_remarks = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($pdi_remarks) {
                                foreach ($pdi_remarks as $pdi_remarks) {
                                    if ($pdi_remarks == "null") {
                                        $query->orWhere('pdi_remarks', null);
                                    } else {
                                        $query->orWhere('pdi_remarks', 'LIKE', '%' . trim($pdi_remarks) . '%');
                                    }
                                }
                            });
                            break;
                        case 'extra_features':
                            $extra_features = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($extra_features) {
                                foreach ($extra_features as $extra_features) {
                                    if ($extra_features == "null") {
                                        $query->orWhere('extra_features', null);
                                    } else {
                                        $query->orWhere('extra_features', 'LIKE', '%' . trim($extra_features) . '%');
                                    }
                                }
                            });
                            break;
                        case 'inspection_date':
                            $inspection_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($inspection_date) {
                                foreach ($inspection_date as $inspection_date) {
                                    if ($inspection_date == "null") {
                                        $query->orWhere('inspection_date', null);
                                    } else {
                                        $query->orWhere('inspection_date', 'LIKE', '%' . trim($inspection_date) . '%');
                                    }
                                }
                            });
                            break;
                        case 'reservation_start_date':
                            $reservation_start_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($reservation_start_date) {
                                foreach ($reservation_start_date as $reservation_start_date) {
                                    if ($reservation_start_date == "null") {
                                        $query->orWhere('reservation_start_date', null);
                                    } else {
                                        $query->orWhere('reservation_start_date', 'LIKE', '%' . trim($reservation_start_date) . '%');
                                    }
                                }
                            });
                            break;
                        case 'reservation_end_date':
                            $reservation_end_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($reservation_end_date) {
                                foreach ($reservation_end_date as $reservation_end_date) {
                                    if ($reservation_end_date == "null") {
                                        $query->orWhere('reservation_end_date', null);
                                    } else {
                                        $query->orWhere('reservation_end_date', 'LIKE', '%' . trim($reservation_end_date) . '%');
                                    }
                                }
                            });
                            break;
                        case 'engine':
                            $engine = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($engine) {
                                foreach ($engine as $engine) {
                                    if ($engine == "null") {
                                        $query->orWhere('engine', null);
                                    } else {
                                        $query->orWhere('engine', 'LIKE', '%' . trim($engine) . '%');
                                    }
                                }
                            });
                            break;
                        case 'ppmmyyy':
                            $ppmmyyy = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($ppmmyyy) {
                                foreach ($ppmmyyy as $ppmmyyy) {
                                    if ($ppmmyyy == "null") {
                                        $query->orWhere('ppmmyyy', null);
                                    } else {
                                        $query->orWhere('ppmmyyy', 'LIKE', '%' . trim($ppmmyyy) . '%');
                                    }
                                }
                            });
                            break;
                        case 'price':
                            $price = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($price) {
                                foreach ($price as $price) {
                                    if ($price == "null") {
                                        $query->orWhere('price', null);
                                    } else {
                                        $query->orWhere('price', 'LIKE', '%' . trim($price) . '%');
                                    }
                                }
                            });
                            break;
                        case 'po_number':
                            $poNumbers = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($poNumbers) {
                                foreach ($poNumbers as $poNumber) {
                                    if ($poNumber == "null") {
                                        $query->orWhere('purchasing_order_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $purchasingOrder = PurchasingOrder::where('po_number', 'LIKE', '%' . trim($poNumber) . '%')->first();
                                        if ($purchasingOrder) {
                                            $query->orWhere('purchasing_order_id', $purchasingOrder->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'po_date':
                            $po_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($po_date) {
                                foreach ($po_date as $po_date) {
                                    if ($po_date == "null") {
                                        $query->orWhere('purchasing_order_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $purchasingOrder = PurchasingOrder::where('po_date', 'LIKE', '%' . trim($po_date) . '%')->first();
                                        if ($purchasingOrder) {
                                            $query->orWhere('purchasing_order_id', $purchasingOrder->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'grn_number':
                            $grn_number = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($grn_number) {
                                foreach ($grn_number as $grn_number) {
                                    if ($grn_number == "null") {
                                        $query->orWhere('movement_grn_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $grn = MovementGrn::where('grn_number', 'LIKE', '%' . trim($grn_number) . '%')->first();
                                        if ($grn) {
                                            $query->orWhere('movement_grn_id', $grn->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'grn_date':
                            $grn_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($grn_date) {
                                foreach ($grn_date as $grn_date) {
                                    if ($grn_date == "null") {
                                        $query->orWhere('movement_grn_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $grn = MovementsReference::where('date', 'LIKE', '%' . trim($grn_date) . '%')->first();
                                        if ($grn) {
                                            $query->orWhere('movement_grn_id', $grn->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'so_number':
                            $so_number = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($so_number) {
                                foreach ($so_number as $so_number) {
                                    if ($so_number == "null") {
                                        $query->orWhere('so_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $so = So::where('so_number', 'LIKE', '%' . trim($so_number) . '%')->first();
                                        if ($so) {
                                            $query->orWhere('so_id', $so->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'so_date':
                            $so_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($so_date) {
                                foreach ($so_date as $so_date) {
                                    if ($so_date == "null") {
                                        $query->orWhere('so_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $sodte = So::where('so_date', 'LIKE', '%' . trim($so_date) . '%')->first();
                                        if ($sodte) {
                                            $query->orWhere('so_id', $sodte->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'sales_person_id':
                            $sales_person_id = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($sales_person_id) {
                                foreach ($sales_person_id as $sales_person_id) {
                                    if ($sales_person_id == "null") {
                                        $query->orWhere('so_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $sosales = So::where('sales_person_id', 'LIKE', '%' . trim($sales_person_id) . '%')->first();
                                        if ($sosales) {
                                            $query->orWhere('so_id', $sosales->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'gdn_number':
                            $gdn_number = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($gdn_number) {
                                foreach ($gdn_number as $gdn_number) {
                                    if ($gdn_number == "null") {
                                        $query->orWhere('gdn_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $gdnumber = Gdn::where('gdn_number', 'LIKE', '%' . trim($gdn_number) . '%')->first();
                                        if ($gdnumber) {
                                            $query->orWhere('gdn_id', $gdnumber->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'gdn_date':
                            $gdn_date = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($gdn_date) {
                                foreach ($gdn_date as $gdn_date) {
                                    if ($gdn_date == "null") {
                                        $query->orWhere('gdn_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $gdndate = Gdn::where('date', 'LIKE', '%' . trim($gdn_date) . '%')->first();
                                        if ($gdndate) {
                                            $query->orWhere('gdn_id', $gdndate->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'variant':
                            $variant = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($variant) {
                                foreach ($variant as $variant) {
                                    if ($variant == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $variant = Varaint::where('name', 'LIKE', '%' . trim($variant) . '%')->first();
                                        if ($variant) {
                                            $query->orWhere('varaints_id', $variant->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'variant_details':
                            $variant_details = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($variant_details) {
                                foreach ($variant_details as $variant_details) {
                                    if ($variant_details == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $variant_details = Varaint::where('detail', 'LIKE', '%' . trim($variant_details) . '%')->first();
                                        if ($variant_details) {
                                            $query->orWhere('varaints_id', $variant_details->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'model_description':
                            $model_description = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($model_description) {
                                foreach ($model_description as $model_description) {
                                    if ($model_description == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $model_description = Varaint::where('model_detail', 'LIKE', '%' . trim($model_description) . '%')->first();
                                        if ($model_description) {
                                            $query->orWhere('varaints_id', $model_description->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'model_year':
                            $model_year = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($model_year) {
                                foreach ($model_year as $model_year) {
                                    if ($model_year == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $model_year = Varaint::where('my', 'LIKE', '%' . trim($model_year) . '%')->first();
                                        if ($model_year) {
                                            $query->orWhere('varaints_id', $model_year->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'steering':
                            $steering = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($steering) {
                                foreach ($steering as $steering) {
                                    if ($steering == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $steering = Varaint::where('steering', 'LIKE', '%' . trim($steering) . '%')->first();
                                        if ($steering) {
                                            $query->orWhere('varaints_id', $steering->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'seats':
                            $seats = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($seats) {
                                foreach ($seats as $seats) {
                                    if ($seats == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $seats = Varaint::where('seat', 'LIKE', '%' . trim($seats) . '%')->first();
                                        if ($seats) {
                                            $query->orWhere('varaints_id', $seats->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'upholestry':
                            $upholestry = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($upholestry) {
                                foreach ($upholestry as $upholestry) {
                                    if ($upholestry == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $upholestry = Varaint::where('upholestry', 'LIKE', '%' . trim($upholestry) . '%')->first();
                                        if ($upholestry) {
                                            $query->orWhere('varaints_id', $upholestry->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'fuel_type':
                            $fuel_type = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($fuel_type) {
                                foreach ($fuel_type as $fuel_type) {
                                    if ($fuel_type == "null") {
                                        $query->orWhere('varaints_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $fuel_type = Varaint::where('fuel_type', 'LIKE', '%' . trim($fuel_type) . '%')->first();
                                        if ($fuel_type) {
                                            $query->orWhere('varaints_id', $fuel_type->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'brand':
                            $brandNames = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($brandNames) {
                                foreach ($brandNames as $brandName) {
                                    if ($brandName == "null") {
                                        $query->orWhereIn('varaints_id', null);
                                    } else {
                                        // Find the brand_id based on the brand name from the brands table
                                        $brand = Brand::where('brand_name', 'LIKE', '%' . trim($brandName) . '%')->first();
                                        if ($brand) {
                                            // Find the varaints_id based on the brand_id from the variants table
                                            $variantsWithBrand = Varaint::where('brands_id', $brand->id)->pluck('id');
                                            $query->orWhereIn('varaints_id', $variantsWithBrand);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'model_line':
                            $modelline = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($modelline) {
                                foreach ($modelline as $modellines) {
                                    // Find the brand_id based on the brand name from the brands table
                                    if ($modellines == "null") {
                                        $query->orWhereIn('varaints_id', null);
                                    } else {
                                        $modelline = MasterModelLines::where('model_line', 'LIKE', '%' . trim($modellines) . '%')->first();
                                        if ($modelline) {
                                            // Find the varaints_id based on the brand_id from the variants table
                                            $variantsWithBrand = Varaint::where('master_model_lines_id', $modelline->id)->pluck('id');
                                            $query->orWhereIn('varaints_id', $variantsWithBrand);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'ex_colour':
                            $ex_colour = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($ex_colour) {
                                foreach ($ex_colour as $ex_colour) {
                                    if ($ex_colour == "null") {
                                        $query->orWhere('ex_colour', null);
                                    } else {
                                        $ex_colour = ColorCode::where('name', 'LIKE', '%' . trim($ex_colour) . '%')->where('belong_to', 'ex')->first();
                                        if ($ex_colour) {
                                            $query->orWhere('ex_colour', $ex_colour->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'int_colour':
                            $int_colour = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($int_colour) {
                                foreach ($int_colour as $int_colour) {
                                    if ($int_colour == "null") {
                                        $query->orWhere('int_colour', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $int_colour = ColorCode::where('name', 'LIKE', '%' . trim($int_colour) . '%')->where('belong_to', 'int')->first();
                                        if ($int_colour) {
                                            $query->orWhere('int_colour', $int_colour->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'importdoc':
                            $import_type = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($import_type) {
                                foreach ($import_type as $import_type) {
                                    if ($import_type == "null") {
                                        $query->orWhere('documents_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $import_type = Document::where('import_type', 'LIKE', '%' . trim($import_type) . '%')->first();
                                        if ($import_type) {
                                            $query->orWhere('documents_id', $import_type->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'ownership':
                            $owership = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($owership) {
                                foreach ($owership as $owership) {
                                    if ($owership == "null") {
                                        $query->orWhere('documents_id', null);;
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $owership = Document::where('owership', 'LIKE', '%' . trim($owership) . '%')->first();
                                        if ($owership) {
                                            $query->orWhere('documents_id', $owership->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'documentwith':
                            $documentwith = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($documentwith) {
                                foreach ($documentwith as $documentwith) {
                                    if ($documentwith == "null") {
                                        $query->orWhere('documents_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $documentwith = Document::where('documentwith', 'LIKE', '%' . trim($documentwith) . '%')->first();
                                        if ($documentwith) {
                                            $query->orWhere('documents_id', $documentwith->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'bl_number':
                            $bl_number = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($bl_number) {
                                foreach ($bl_number as $bl_number) {
                                    if ($bl_number == "null") {
                                        $query->orWhere('documents_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $bl_number = Document::where('bl_number', 'LIKE', '%' . trim($bl_number) . '%')->first();
                                        if ($bl_number) {
                                            $query->orWhere('documents_id', $bl_number->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'bl_dms_uploading':
                            $bl_dms_uploading = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($bl_dms_uploading) {
                                foreach ($bl_dms_uploading as $bl_dms_uploading) {
                                    if ($bl_dms_uploading == "null") {
                                        $query->orWhere('documents_id', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $bl_dms_uploading = Document::where('bl_dms_uploading', 'LIKE', '%' . trim($bl_dms_uploading) . '%')->first();
                                        if ($bl_dms_uploading) {
                                            $query->orWhere('documents_id', $bl_dms_uploading->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'latest_location':
                            $latest_location = explode(',', $searchQuery);
                            $data = $data->where(function ($query) use ($latest_location) {
                                foreach ($latest_location as $latest_location) {
                                    if ($latest_location == "null") {
                                        $query->orWhere('latest_location', null);
                                    } else {
                                        // Find the purchasing_order_id based on the po_number
                                        $latest_location = Warehouse::where('latest_location', 'LIKE', '%' . trim($latest_location) . '%')->first();
                                        if ($latest_location) {
                                            $query->orWhere('latest_location', $latest_location->id);
                                        }
                                    }
                                }
                            });
                            break;
                        case 'territory':
                            if ($searchQuery == "null") {
                                $data->where('territory', null);
                            } else {
                                $data->where('territory', 'LIKE', '%' . $searchQuery . '%');
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count();
            $previousYearSold = $this->previousYearSold()->count();
            $previousYearBooked = $this->previousYearBooked()->count();
            $previousMonthSold = $this->previousMonthSold()->count();
            $previousMonthBooked = $this->previousMonthBooked()->count();
            $yesterdaySold = $this->yesterdaySold()->count();
            $yesterdayBooked  = $this->yesterdayBooked()->count();
            $previousYearAvailable  = $this->previousYearAvailable()->count();
            $previousMonthAvailable  = $this->previousMonthAvailable()->count();
            $yesterdayAvailable  = $this->yesterdayAvailable()->count();
            $previousYearPurchased = $this->previousYearPurchased()->count();
            $previousMonthPurchased = $this->previousMonthPurchased()->count();
            $yesterdayPurchased = $this->yesterdayPurchased()->count();

            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesvehss',
                'warehousesveher',
                'previousYearSold',
                'previousMonthSold',
                'previousYearBooked',
                'previousMonthBooked',
                'yesterdaySold',
                'yesterdayBooked',
                'previousYearAvailable',
                'previousMonthAvailable',
                'yesterdayAvailable',
                'yesterdayPurchased',
                'previousMonthPurchased',
                'previousYearPurchased',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function stockCountFilter(Request $request)
    {

        if ($request->key) {
            $searchKey = $request->key;
            $vehicleIds = Vehicles::pluck('id');

            if ($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_SOLD) {
                $vehicleIds = $this->previousYearSold()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_SOLD) {
                $vehicleIds = $this->previousMonthSold()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_YESTERDAY_SOLD) {
                $vehicleIds = $this->yesterdaySold()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_BOOKED) {
                $vehicleIds = $this->previousYearBooked()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_BOOKED) {
                $vehicleIds = $this->previousMonthBooked()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_YESTERDAY_BOOKED) {
                $vehicleIds = $this->yesterdayBooked()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_AVAILABLE) {
                $vehicleIds = $this->previousYearAvailable()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_AVAILABLE) {
                $vehicleIds = $this->previousMonthAvailable()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_YESTERDAY_AVAILABLE) {
                $vehicleIds = $this->yesterdayAvailable()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_YEAR_PURCHASED) {
                $vehicleIds = $this->previousYearPurchased()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_PREVIOUS_MONTH_PURCHASED) {
                $vehicleIds = $this->previousMonthPurchased()->pluck('id');
            }
            if ($searchKey == Vehicles::FILTER_YESTERDAY_PURCHASED) {
                $vehicleIds = $this->yesterdayPurchased()->pluck('id');
            }
            $data = Vehicles::whereIn('id', $vehicleIds)->paginate(100);
        } else {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss);
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
        }
        $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
            ->groupBy('vehicle_id')->get();
        $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
        $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
        $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
        $sales_persons = ModelHasRoles::get();
        $sales_ids = $sales_persons->pluck('model_id');
        $sales = User::whereIn('id', $sales_ids)->get();
        $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
        $interiorColours = ColorCode::where('belong_to', 'int')->get();
        $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
        $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
        $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
        $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
        $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
        $countwarehouse = $warehouses->count();

        $previousYearSold = $this->previousYearSold()->count();
        $previousYearBooked = $this->previousYearBooked()->count();
        $previousMonthSold = $this->previousMonthSold()->count();
        $previousMonthBooked = $this->previousMonthBooked()->count();
        $yesterdaySold = $this->yesterdaySold()->count();
        $yesterdayBooked  = $this->yesterdayBooked()->count();
        $previousYearAvailable  = $this->previousYearAvailable()->count();
        $previousMonthAvailable  = $this->previousMonthAvailable()->count();
        $yesterdayAvailable  = $this->yesterdayAvailable()->count();
        $previousYearPurchased = $this->previousYearPurchased()->count();
        $previousMonthPurchased = $this->previousMonthPurchased()->count();
        $yesterdayPurchased = $this->yesterdayPurchased()->count();

        return view('vehicles.index', compact(
            'data',
            'varaint',
            'sales',
            'datapending',
            'exteriorColours',
            'interiorColours',
            'pendingVehicleDetailForApprovalCount',
            'warehouses',
            'countwarehouse',
            'warehousesveh',
            'warehousesvehss',
            'warehousesveher',
            'previousYearSold',
            'previousMonthSold',
            'previousYearBooked',
            'previousMonthBooked',
            'yesterdaySold',
            'yesterdayBooked',
            'previousYearAvailable',
            'previousMonthAvailable',
            'yesterdayAvailable',
            'yesterdayPurchased',
            'previousMonthPurchased',
            'previousYearPurchased'
        ));
    }
    public function previousYearPurchased()
    {

        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $data = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                    ->from('movement_grns')
                    ->join('movements_reference', 'movements_reference.id', '=', 'movement_grns.movement_reference_id')
                    ->whereColumn('movement_grns.id', '=', 'vehicles.movement_grn_id')
                    ->whereBetween('movements_reference.date', [$startDateLastMonth, $endDateLastMonth]);
                // ->whereBetween('grn.date', [$startDate, $endDate]);
            })
            ->paginate(100);

        return $data;
    }
    public function previousMonthPurchased()
    {

        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $data = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                $query->select(DB::raw(1))
                    ->from('movement_grns')
                    ->join('movements_reference', 'movements_reference.id', '=', 'movement_grns.movement_reference_id')
                    ->whereColumn('movement_grns.id', '=', 'vehicles.movement_grn_id')
                    ->whereBetween('movements_reference.date', [$startDateLastMonth, $endDateLastMonth]);
            })
            ->paginate(100);

        return $data;
    }
    public function yesterdayPurchased()
    {

        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');
        $data = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($yesterday) {
                $query->select(DB::raw(1))
                    // ->from('grn')
                    // ->whereColumn('grn.id', '=', 'vehicles.grn_id')
                    ->from('movement_grns')
                    ->join('movements_reference', 'movements_reference.id', '=', 'movement_grns.movement_reference_id')
                    ->whereColumn('movement_grns.id', '=', 'vehicles.movement_grn_id')
                    ->whereDate('movements_reference.date', $yesterday);
            })
            ->paginate(100);

        return $data;
    }
    public function previousYearSold()
    {
        // logic => if gdn id is there then that vehicle is completed.
        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearSold = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->whereBetween('gdn.date', [$startDate, $endDate]);
            })
            ->paginate(100);

        return $countPreviouseYearSold;
    }
    public function previousYearBooked()
    {
        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearBooked = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->whereBetween('so.so_date', [$startDate, $endDate]);
            })
            ->paginate(100);

        return $countPreviouseYearBooked;
    }
    public function previousYearAvailable()
    {

        $currentYear = \Carbon\Carbon::now()->year;
        $previousYear = $currentYear - 1;
        $startDate = \Carbon\Carbon::createFromDate($previousYear, 1, 1);
        $endDate = \Carbon\Carbon::createFromDate($previousYear, 12, 31);
        $countPreviouseYearAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->whereBetween('gdn.date', [$startDate, $endDate])
            ->paginate(100);

        return $countPreviouseYearAvailable;
    }
    public function previousMonthSold()
    {
        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countLastMonth = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->whereBetween('gdn.date', [$startDateLastMonth, $endDateLastMonth]);
            })
            ->paginate(100);

        return $countLastMonth;
    }
    public function previousMonthBooked()
    {
        // logic => if gdn id is there then that vehicle is completed.
        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countLastMonth = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($startDateLastMonth, $endDateLastMonth) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->whereBetween('so.so_date', [$startDateLastMonth, $endDateLastMonth]);
            })
            ->paginate(100);

        return $countLastMonth;
    }
    public function previousMonthAvailable()
    {

        $startDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->startOfMonth();
        $endDateLastMonth = \Carbon\Carbon::now()->subMonth(1)->endOfMonth();

        $countPreviousYearAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereBetween('gdn.date', [$startDateLastMonth, $endDateLastMonth])
            ->whereDate('so.so_date', '>=', $endDateLastMonth)
            ->paginate(100);

        return $countPreviousYearAvailable;
    }

    public function yesterdaySold()
    {
        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');
        $countYesterdaySold = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($yesterday) {
                $query->select(DB::raw(1))
                    ->from('gdn')
                    ->whereColumn('gdn.id', '=', 'vehicles.gdn_id')
                    ->where('gdn.date', $yesterday);
            })
            ->paginate(100);

        return $countYesterdaySold;
    }
    public function yesterdayBooked()
    {
        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');

        $countYesterdayBooked = \Illuminate\Support\Facades\DB::table('vehicles')
            ->whereExists(function ($query) use ($yesterday) {
                $query->select(DB::raw(1))
                    ->from('so')
                    ->whereColumn('so.id', '=', 'vehicles.so_id')
                    ->where('so.so_date', $yesterday);
            })
            ->paginate(100);

        return $countYesterdayBooked;
    }
    public function yesterdayAvailable()
    {

        $yesterday = Carbon::now()->subDay(1)->format('Y-m-d');

        $countYesterdayAvailable = \Illuminate\Support\Facades\DB::table('vehicles')
            ->join('gdn', 'gdn.id', '=', 'vehicles.gdn_id')
            ->join('so', 'so.id', '=', 'vehicles.so_id')
            ->whereDate('gdn.date', $yesterday)
            ->whereDate('so.so_date', '>=', $yesterday)
            ->paginate(100);

        return $countYesterdayAvailable;
    }

    public function pendingapprovals(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $fieldValues = ['ex_colour', 'int_colour', 'variants_id', 'ppmmyyy', 'inspection_date', 'engine'];
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->join('vehicle_detail_approval_requests', 'vehicles.id', '=', 'vehicle_detail_approval_requests.vehicle_id')
                ->where('vehicle_detail_approval_requests.status', '=', 'Pending')
                ->where('vehicles.latest_location', '=', $warehouseId) // Replace $warehousesveher->id with $warehouseId
                ->where(function ($query) use ($fieldValues) {
                    $query->whereIn('field', $fieldValues);
                });
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesvehss',
                'warehousesveher',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function pendinginspection(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('movement_grn_id')
                ->whereNull('inspection_date');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesvehss',
                'warehousesveher',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function incomingstocks(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->whereNull('movement_grn_id')
                ->whereNull('gdn_id');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count();
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesvehss',
                'warehousesveher',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
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
    public function update(Request $request, string $id)
    {
        //
    }
    public function destroy(string $id)
    {
        //
    }
    public function getVehicleDetails(Request $request)
    {
        $variant = Varaint::find($request->variant_id);
        $brand = $variant->brand->brand_name ?? '';
        $data['brand'] = $brand;
        $data['model_line'] = $variant->master_model_lines->model_line ?? '';
        $data['my'] = $variant->my ?? '';
        $data['model_detail'] = $variant->model_detail ?? '';
        $data['seat'] = $variant->seat ?? '';
        $data['fuel_type'] = $variant->fuel_type ?? '';
        $data['gearbox'] = $variant->gearbox ?? '';
        $data['steering'] = $variant->steering ?? '';
        $data['upholestry'] = $variant->upholestry ?? '';
        $data['detail'] = $variant->detail ?? '';
        return $data;
    }

    public function updatevehiclesdata(Request $request)
    {
        $vehiclesId = $request->input('vehicles_id');
        $column = $request->input('column');
        $value = $request->input('value');
        if ($column === "vin") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->vin = $value;
            $vehicle->save();
        }
        if ($column === "int_colour") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->int_colour = $value;
            $vehicle->save();
        }
        if ($column === "ex_colour") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->ex_colour = $value;
            $vehicle->save();
        }
        if ($column === "engine") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->engine = $value;
            $vehicle->save();
        }
        if ($column === "remarks") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->remarks = $value;
            $vehicle->save();
        }
        if ($column === "territory") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->territory = $value;
            $vehicle->save();
        }
        if ($column === "documzinout") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->documzinout = $value;
            $vehicle->save();
        }
        if ($column === "ppmmyyy") {
            $vehicle = Vehicles::find($vehiclesId);
            $vehicle->ppmmyyy = $value;
            $vehicle->save();
        }
        if ($column === "variants_name") {
            $variant = Varaint::where('name', $value)->first();
            if ($variant) {
                Vehicles::where('id', $vehiclesId)
                    ->update(['varaints_id' => $variant->id]);
                Log::info('Variant Change Detected 12. Variant Changed in (updatevehiclesdata)', [
                    'vehicle_id' => $vehiclesId,
                    'new_variant_id' => $variant->id,
                    'variant_name' => $variant->name,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'N/A',
                    'source' => 'updatevehiclesinvehiclesConroller',
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        }
        if ($column === "import_type") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->import_type = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->import_type = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if ($column === "owership") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->owership = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->owership = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        if ($column === "document_with") {
            $vehicle = Vehicles::find($vehiclesId);
            if ($vehicle) {
                $documents_id = $vehicle->documents_id;
                if ($documents_id) {
                    $documents = Document::find($documents_id);
                    if ($documents) {
                        $documents->document_with = $value;
                        $documents->save();
                    }
                } else {
                    $newdocument = new Document();
                    $newdocument->document_with = $value;
                    $newdocument->save();
                    $vehicle->documents_id = $newdocument->id;
                    $vehicle->save();
                }
            }
        }
        return response()->json(['message' => 'Vehicle data updated successfully']);
    }
    public function fatchvariantdetails(Request $request)
    {
        $variantName = $request->input('value');
        $result = DB::table('varaints')
            ->join('brands', 'varaints.brands_id', '=', 'brands.id')
            ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->where('varaints.name', $variantName)
            ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat', 'varaints.gearbox', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
            ->first();
        $responseData = [
            'varaints_detail' => $result->detail ?? null,
            'brand_name' => $result->brand_name ?? null,
            'model_line' => $result->model_line ?? null,
            'my' => $result->my ?? null,
            'upholestry' => $result->upholestry ?? null,
            'steering' => $result->steering ?? null,
            'fuel' => $result->fuel_type ?? null,
            'seat' => $result->seat ?? null,
            'gearbox' => $result->gearbox ?? null,
            'vehicles_id' => $request->input('vehicles_id'),
        ];
        return response()->json($responseData);
    }
    public function updatedata(Request $request)
    {
        $updatedData = $request->json()->all();
        foreach ($updatedData as $data) {
            $vehicleId = $data['id'];
            $fieldName = $data['name'];
            $fieldValue = $data['value'];
            $vehicle = Vehicles::find($vehicleId);
            if ($vehicle) {
                if (in_array($fieldName, ['so_number', 'so_date', 'sales_person_id'])) {
                    // Handling 'so_number', 'so_date', and 'sales_person_id' fields
                    $so_id = $vehicle->so_id;
                    $so = $so_id ? So::find($so_id) : new So();

                    $oldValue = $so->$fieldName ?? null;
                    $newValue = $fieldValue;
                    // Save changes to the log if the old and new values differ and the field is not sales_person_id
                    if ($oldValue !== $newValue) {
                        $soLog = new SoLog();
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $soLog->time = $currentDateTime->toTimeString();
                        $soLog->date = $currentDateTime->toDateString();
                        $soLog->status = 'Update SO';
                        $soLog->so_id = $so_id;
                        $soLog->field = $fieldName;
                        $soLog->old_value = $oldValue;
                        $soLog->new_value = $newValue;
                        $soLog->created_by = auth()->user()->id;
                        $soLog->role = Auth::user()->selectedRole;
                        $soLog->save();

                        if ($newValue !== null) {
                            $approvalLog = new VehicleApprovalRequests();
                            $approvalLog->vehicle_id = $vehicleId;
                            $approvalLog->status = 'Pending';
                            $approvalLog->field = $fieldName;
                            $approvalLog->old_value = $oldValue;
                            $approvalLog->new_value = $fieldValue;
                            $approvalLog->updated_by = auth()->user()->id;
                            $approvalLog->save();
                        }
                    }
                } elseif (in_array($fieldName, ['import_type', 'document_with', 'bl_number', 'owership', 'bl_dms_uploading'])) {
                    $documents_id = $vehicle->documents_id;
                    $document = $documents_id ? Document::find($documents_id) : new Document();
                    $oldValue = $document->$fieldName ?? null;
                    $newValue = $fieldValue;
                    if ($documents_id === null && $newValue !== null) {
                        $documentupdate = new Document();
                        $documentupdate->$fieldName = $newValue;
                        $documentupdate->save();
                        event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
                        $newdocuments_id = $documentupdate->id;
                        $vehicle->documents_id = $newdocuments_id;
                        $vehicle->save();
                        $documentLog = new DocumentLog();
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $documentLog->time = $currentDateTime->toTimeString();
                        $documentLog->date = $currentDateTime->toDateString();
                        $documentLog->status = 'Update Document';
                        $documentLog->documents_id = $documents_id;
                        $documentLog->field = $fieldName;
                        $documentLog->old_value = $oldValue;
                        $documentLog->new_value = $newValue;
                        $documentLog->created_by = auth()->user()->id;
                        $documentLog->role = Auth::user()->selectedRole;
                        $documentLog->save();
                    } else {
                        if ($oldValue !== $newValue) {
                            $documentLog = new DocumentLog();
                            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                            $currentDateTime = Carbon::now($dubaiTimeZone);
                            $documentLog->time = $currentDateTime->toTimeString();
                            $documentLog->date = $currentDateTime->toDateString();
                            $documentLog->status = 'Update Document';
                            $documentLog->documents_id = $documents_id;
                            $documentLog->field = $fieldName;
                            $documentLog->old_value = $oldValue;
                            $documentLog->new_value = $newValue;
                            $documentLog->created_by = auth()->user()->id;
                            $documentLog->role = Auth::user()->selectedRole;
                            $documentLog->save();
                            $document->$fieldName = $newValue;
                            $document->save();
                            //     if ($newValue !== null) {
                            //     $approvalLog = new VehicleApprovalRequests();
                            //     $approvalLog->vehicle_id = $vehicleId;
                            //     $approvalLog->status = 'Pending';
                            //     $approvalLog->field = $fieldName;
                            //     $approvalLog->old_value = $oldValue;
                            //     $approvalLog->new_value = $fieldValue;
                            //     $approvalLog->updated_by = auth()->user()->id;
                            //     $approvalLog->save();
                            // }
                        }
                    }
                } elseif (in_array($fieldName, ['warehouse-remarks', 'sales-remarks'])) {
                    $department = ($fieldName === 'sales-remarks') ? 'sales' : 'warehouse';
                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                    $currentDateTime = Carbon::now($dubaiTimeZone);
                    if ($fieldValue !== null) {
                        $fieldValue = rtrim($fieldValue, 'View All');
                        $existingRemark = Remarks::where('vehicles_id', $vehicleId)
                            ->where('department', $department)
                            ->where('remarks', $fieldValue)
                            ->first();
                        if (!$existingRemark) {
                            $remarks = new Remarks();
                            $remarks->vehicles_id = $vehicleId;
                            $remarks->department = $department;
                            $remarks->date = $currentDateTime->toDateString();
                            $remarks->time = $currentDateTime->toTimeString();
                            $remarks->remarks = $fieldValue;
                            $remarks->created_by = auth()->user()->id;
                            event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
                            $remarks->save();
                            $vehicleslog = new Vehicleslog();
                            $vehicleslog->time = $currentDateTime->toTimeString();
                            $vehicleslog->date = $currentDateTime->toDateString();
                            $vehicleslog->status = 'Adding New Remarks';
                            $vehicleslog->vehicles_id = $vehicleId;
                            $vehicleslog->field = $fieldName;
                            $vehicleslog->old_value = "";
                            $vehicleslog->new_value = $fieldValue;
                            $vehicleslog->created_by = auth()->user()->id;
                            $vehicleslog->save();
                        }
                    }
                } elseif (in_array($fieldName, ['conversion', 'netsuit_grn_number', 'netsuit_grn_date', 'qc_remarks'])) {
                    $oldValues = $vehicle->getAttributes();
                    $changes = [];
                    foreach ($oldValues as $field => $oldValue) {
                        if ($field !== 'created_at' && $field !== 'updated_at') {
                            $newValue = $field === $fieldName ? $fieldValue : $vehicle->$field;
                            if ($oldValue != $newValue) {
                                $changes[$field] = [
                                    'old_value' => $oldValue,
                                    'new_value' => $newValue,
                                ];
                            }
                        }
                    }
                    if (!empty($changes)) {
                        $vehicle->$fieldName = $fieldValue;
                        $vehicle->save();
                        event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        $currentDateTime = Carbon::now($dubaiTimeZone);
                        $vehicleslog = new Vehicleslog();
                        $vehicleslog->time = $currentDateTime->toTimeString();
                        $vehicleslog->date = $currentDateTime->toDateString();
                        $vehicleslog->status = 'Update QC Values';
                        $vehicleslog->vehicles_id = $vehicleId;
                        $vehicleslog->field = $fieldName;
                        $vehicleslog->old_value = $oldValues[$fieldName];
                        $vehicleslog->new_value = $fieldValue;
                        $vehicleslog->created_by = auth()->user()->id;
                        $vehicleslog->save();
                    }
                } else {
                    $oldValues = $vehicle->getAttributes();
                    $changes = [];
                    foreach ($oldValues as $field => $oldValue) {
                        if ($field !== 'created_at' && $field !== 'updated_at') {
                            $newValue = $field === $fieldName ? $fieldValue : $vehicle->$field;
                            if ($oldValue != $newValue) {
                                $changes[$field] = [
                                    'old_value' => $oldValue,
                                    'new_value' => $newValue,
                                ];
                            }
                        }
                    }
                    if (!empty($changes)) {
                        // Save approval log if the old value is null and the new value is not null
                        if ($fieldValue !== null) {
                            // Update the field in the 'Vehicles' table with the new value
                            $vehicle->$fieldName = $fieldValue;
                            // $vehicle->save();
                            // // Save vehicle log for the specific field
                            // $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                            // $currentDateTime = Carbon::now($dubaiTimeZone);
                            // $vehicleslog = new Vehicleslog();
                            // $vehicleslog->time = $currentDateTime->toTimeString();
                            // $vehicleslog->date = $currentDateTime->toDateString();
                            // $vehicleslog->status = 'Update QC Values';
                            // $vehicleslog->vehicles_id = $vehicleId;
                            // $vehicleslog->field = $fieldName;
                            // $vehicleslog->old_value = $oldValues[$fieldName];
                            // $vehicleslog->new_value = $fieldValue;
                            // $vehicleslog->created_by = auth()->user()->id;
                            // $vehicleslog->save();
                            $approvalLog = new VehicleApprovalRequests();
                            $approvalLog->vehicle_id = $vehicleId;
                            $approvalLog->status = 'Pending';
                            $approvalLog->field = $fieldName;
                            $approvalLog->old_value = $oldValues[$fieldName];
                            $approvalLog->new_value = $fieldValue;
                            $approvalLog->updated_by = auth()->user()->id;
                            $approvalLog->save();
                        }
                    }
                }
            }
        }

        $responseData = ['status' => 'success', 'message' => 'Data updated successfully'];
        return response()->json($responseData);
    }
    public function updateso(Request $request)
    {
        //        return $request->all();
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId) {
            $vehicle = Vehicles::find($vehicleId);
            $soId = $vehicle->so_id;
            if ($soId) {
                $so = So::find($soId);
                if (!empty($so->so_number)) {
                    if ($so->so_number != $request->so_numbers[$key]) {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_number';
                        $vehicleDetailApproval->old_value = $so->so_number;
                        $vehicleDetailApproval->new_value = $request->so_numbers[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
                } else {
                    if ($so->so_number != $request->so_numbers[$key]) {
                        $solog = new Solog();

                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $soId;
                        $solog->field = 'so_number';
                        $solog->old_value = $so->so_number;
                        $solog->new_value = $request->so_numbers[$key];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();
                        $so->so_number = $request->so_numbers[$key];

                        $so->save();
                    }
                }

                $oldSoDate = Carbon::parse($so->so_date)->format('d M Y');
                $newSoDate = Carbon::parse($request->so_dates[$key])->format('d M Y');
                if (!empty($so->so_date)) {
                    if ($oldSoDate != $newSoDate) {
                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'so_date';
                        $vehicleDetailApproval->old_value = $so->so_date;
                        $vehicleDetailApproval->new_value = $request->so_dates[$key];
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
                } else {
                    if ($oldSoDate != $newSoDate) {
                        $solog = new Solog();
                        //                    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                        //                    $currentDateTime = Carbon::now($dubaiTimeZone);
                        $solog->time = $currentDateTime->toTimeString();
                        $solog->date = $currentDateTime->toDateString();
                        $solog->status = 'Update Sales Values';
                        $solog->so_id = $soId;
                        $solog->field = 'so_date';
                        $solog->old_value = $so->so_date;
                        $solog->new_value = $request->so_dates[$key];
                        $solog->created_by = auth()->user()->id;
                        $solog->save();
                        $so->so_date = $request->so_dates[$key];

                        $so->save();
                    }
                }
            } else {
                // so should be unique then so number is updtaing existing so number.
                $existingSo = So::where('so_number', $request->so_numbers[$key])->first();
                //                if ($existingSo) {
                //                    // Update existing So
                //                    if($existingSo->so_number != $request->so_numbers[$key])
                //                    {
                $vehicleDetailApproval = new VehicleApprovalRequests();
                $vehicleDetailApproval->vehicle_id = $vehicle->id;
                $vehicleDetailApproval->field = 'so_number';
                $vehicleDetailApproval->old_value = $so->so_number;
                $vehicleDetailApproval->new_value = $request->so_numbers[$key];
                $vehicleDetailApproval->updated_by = auth()->user()->id;
                $vehicleDetailApproval->status = 'Pending';
                $vehicleDetailApproval->save();
                //                    }
                $oldSoDate = Carbon::parse($so->so_date)->format('d M Y');
                $newSoDate = Carbon::parse($request->so_dates[$key])->format('d M Y');
                if ($oldSoDate != $newSoDate) {
                    $vehicleDetailApproval = new VehicleApprovalRequests();
                    $vehicleDetailApproval->vehicle_id = $vehicle->id;
                    $vehicleDetailApproval->field = 'so_date';
                    $vehicleDetailApproval->old_value = $so->so_date;
                    $vehicleDetailApproval->new_value = $request->so_dates[$key];
                    $vehicleDetailApproval->updated_by = auth()->user()->id;
                    $vehicleDetailApproval->status = 'Pending';
                    $vehicleDetailApproval->save();
                }
                //                    $existingSo->save();

                //                }
            }

            if ($request->reservation_start_dates[$key]) {
                $newReservationStartDate = $request->reservation_start_dates[$key];
                $isStartDateChanged = $vehicle->reservation_start_date != $newReservationStartDate;
                if (!empty($vehicle->reservation_start_date)) {
                    if ($vehicle->reservation_start_date != $newReservationStartDate) {

                        $vehicleDetailApproval = new VehicleApprovalRequests();
                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'reservation_start_date';
                        $vehicleDetailApproval->old_value = $vehicle->reservation_start_date;
                        $vehicleDetailApproval->new_value = $newReservationStartDate;
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
                } else {
                    if ($vehicle->reservation_start_date != $newReservationStartDate) {
                        $vehicle->reservation_start_date = $newReservationStartDate;

                        $reservationStartDateLog = new Vehicleslog();
                        $reservationStartDateLog->time = $currentDateTime->toTimeString();
                        $reservationStartDateLog->date = $currentDateTime->toDateString();
                        $reservationStartDateLog->status = 'Update Vehicle Values';
                        $reservationStartDateLog->vehicles_id = $vehicleId;
                        $reservationStartDateLog->field = 'reservation_start_date';
                        $reservationStartDateLog->old_value = $vehicle->getOriginal('reservation_start_date');
                        $reservationStartDateLog->new_value = $newReservationStartDate;
                        $reservationStartDateLog->created_by = auth()->user()->id;
                        $reservationStartDateLog->save();
                    }
                }
            }
            if ($request->reservation_end_dates[$key]) {
                $newReservationEndDate = $request->reservation_end_dates[$key];
                $isEndDateChanged = $vehicle->reservation_end_date != $newReservationEndDate;
                if (!empty($vehicle->reservation_end_date)) {
                    if ($vehicle->reservation_end_date != $newReservationEndDate) {
                        $vehicleDetailApproval = new VehicleApprovalRequests();

                        $vehicleDetailApproval->vehicle_id = $vehicle->id;
                        $vehicleDetailApproval->field = 'reservation_end_date';
                        $vehicleDetailApproval->old_value = $vehicle->reservation_end_date;
                        $vehicleDetailApproval->new_value = $newReservationEndDate;
                        $vehicleDetailApproval->updated_by = auth()->user()->id;
                        $vehicleDetailApproval->status = 'Pending';
                        $vehicleDetailApproval->save();
                    }
                } else {
                    if ($vehicle->reservation_end_date != $newReservationEndDate) {
                        $vehicle->reservation_end_date = $newReservationEndDate;

                        $reservationEndDateLog = new Vehicleslog();
                        $reservationEndDateLog->time = $currentDateTime->toTimeString();
                        $reservationEndDateLog->date = $currentDateTime->toDateString();
                        $reservationEndDateLog->status = 'Update Vehicle Values';
                        $reservationEndDateLog->vehicles_id = $vehicleId;
                        $reservationEndDateLog->field = 'reservation_end_date';
                        $reservationEndDateLog->old_value = $vehicle->getOriginal('reservation_end_date');
                        $reservationEndDateLog->new_value = $newReservationEndDate;
                        $reservationEndDateLog->created_by = auth()->user()->id;
                        $reservationEndDateLog->save();
                    }
                }
            }

            if ($request->remarks[$key]) {

                $remarksdata = new Remarks();

                $remarksdata->time = $currentDateTime->toTimeString();
                $remarksdata->date = $currentDateTime->toDateString();
                $remarksdata->vehicles_id = $vehicleId;
                $remarksdata->remarks = $request->remarks[$key];
                $remarksdata->created_by = auth()->user()->id;
                $remarksdata->department = "Sales";
                $remarksdata->created_at = $currentDateTime;
                $remarksdata->save();
                event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
                //                if($soID) {
                //                    $remarklog = new Solog();
                //                    $remarklog->time = $currentDateTime->toTimeString();
                //                    $remarklog->date = $currentDateTime->toDateString();
                //                    $remarklog->status = 'New Created';
                //                    $remarklog->so_id = $soID;
                //                    $remarklog->field = 'remarks';
                //                    $remarklog->new_value = $request->remarks[$key];
                //                    $remarklog->created_by = auth()->user()->id;
                //                    $remarklog->save();
                //                }

            }
            // Save changes in the vehicles table
            $vehicle->save();
        }
        ///  Currenty not updating /////////////
        // Update payment_percentage if changed
        //        if ($request->has('payment_percentage')) {
        //            $newPaymentPercentage = $request->input('payment_percentage');
        //            if ($vehicle->payment_percentage != $newPaymentPercentage) {
        //                $vehicle->payment_percentage = $newPaymentPercentage;
        //                $paymentPercentageLog = new Vehicleslog();
        //                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        //                $currentDateTime = Carbon::now($dubaiTimeZone);
        //                $paymentPercentageLog->time = $currentDateTime->toTimeString();
        //                $paymentPercentageLog->date = $currentDateTime->toDateString();
        //                $paymentPercentageLog->status = 'Update Vehicle Values';
        //                $paymentPercentageLog->vehicles_id = $vehicle;
        //                $paymentPercentageLog->field = 'payment_percentage';
        //                $paymentPercentageLog->old_value = $vehicle->getOriginal('payment_percentage');
        //                $paymentPercentageLog->new_value = $newPaymentPercentage;
        //                $paymentPercentageLog->created_by = auth()->user()->id;
        //                $paymentPercentageLog->save();
        //            }
        //        }
        // Update reservation_end_date if changed

        return redirect()->back()->with('success', 'Sales details sent for approval successfully.');
    }
    public function deletes($id)
    {
        $vehicle = Vehicles::find($id);
        if ($vehicle->movement_grn_id === null) {
            $vehicle->status = 'cancel';
            $vehicle->save();
            return redirect()->back()->with('success', 'Vehicle status updated to "cancel" successfully.');
        } else {
            return redirect()->back()->with('error', 'Vehicle has already been delivered and cannot be canceled.');
        }
    }
    public function viewLogDetails($id)
    {
        $vehicle = Vehicles::find($id);
        $documentsLog = Documentlog::with('roleName')->where('documents_id', $vehicle->documents_id);
        $soLog = Solog::with('roleName')->where('so_id', $vehicle->so_id);
        $remarks = Remarks::where('vehicles_id', $id)->where('department', 'warehouse')->get();
        $vehiclesLog = Vehicleslog::with('roleName')->where('vehicles_id', $vehicle->id);
        $mergedLogs = $documentsLog->union($soLog)->union($vehiclesLog)->orderBy('updated_at')->get();
        $pendingVehicleDetailApprovalRequests = VehicleApprovalRequests::where('vehicle_id', $id)->orderBy('id', 'DESC')->get();
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.vehicleslog', [
            'currentId' => $id,
            'previousId' => $previousId,
            'nextId' => $nextId
        ], compact('mergedLogs', 'vehicle', 'pendingVehicleDetailApprovalRequests', 'remarks'));
    }
    public function  viewremarks(Request $request, $id)
    {
        $remarks = Remarks::where('vehicles_id', $id)->where('department', 'Sales')->get();
        if ($request->type == 'WareHouse') {
            $remarks = Remarks::where('vehicles_id', $id)->where('department', 'WareHouse')->get();
        }
        // sales
        $previousId = Vehicles::where('id', '<', $id)->max('id');
        $nextId = Vehicles::where('id', '>', $id)->min('id');
        return view('vehicles.viewremarks', [
            'currentId' => $id,
            'previousId' => $previousId,
            'nextId' => $nextId
        ], compact('remarks'));
    }
    public function updatelogistics(Request $request)
    {
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicle) {
            $vehicle = Vehicles::find($vehicle);
            $documents_id = $vehicle->documents_id;
            if ($documents_id) {
                $documents = Document::find($documents_id);

                if ($documents->import_type != $request->import_types[$key]) {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'import_type';
                    $documentlog->old_value = $documents->import_type;
                    $documentlog->new_value = $request->import_types[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->import_type = $request->import_types[$key];
                }
                if ($documents->owership != $request->owerships[$key]) {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'owership';
                    $documentlog->old_value = $documents->owership;
                    $documentlog->new_value = $request->owerships[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->owership = $request->owerships[$key];
                }
                if ($documents->document_with != $request->documents_with[$key]) {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'document_with';
                    $documentlog->old_value = $documents->document_with;
                    $documentlog->new_value = $request->documents_with[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->document_with = $request->documents_with[$key];
                }
                if ($documents->bl_number != $request->bl_numbers[$key]) {
                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'Update Document Values';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->field = 'bl_number';
                    $documentlog->old_value = $documents->bl_number;
                    $documentlog->new_value = $request->bl_numbers[$key];
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();

                    $documents->bl_number = $request->bl_numbers[$key];
                }
                $documents->save();
            } else {
                if (
                    !empty($request->import_types[$key]) || !empty($request->owerships[$key]) ||
                    !empty($request->documents_with[$key]) || !empty($request->bl_numbers[$key])
                ) {
                    $documents = new Document();
                    $documents->import_type = $request->import_types[$key];
                    $documents->owership = $request->owerships[$key];
                    $documents->document_with = $request->documents_with[$key];
                    $documents->bl_number = $request->bl_numbers[$key];
                    $documents->save();
                    event(new DataUpdatedEvent(['id' => $vehicle->id, 'message' => "Data Update"]));
                    $documents_id = $documents->id;

                    $documentlog = new Documentlog();
                    $documentlog->time = $currentDateTime->toTimeString();
                    $documentlog->date = $currentDateTime->toDateString();
                    $documentlog->status = 'New Created';
                    $documentlog->documents_id = $documents_id;
                    $documentlog->created_by = auth()->user()->id;
                    $documentlog->save();
                    $vehicle->documents_id = $documents_id;
                    $vehicle->save();
                }
            }
        }
        //         $vehicleId = $request->input('vehicle_id');

        return redirect()->back()->with('success', 'Vehicle details updated successfully.');
    }
    public function viewpictures($id)
    {
        $vehiclePictures = VehiclePicture::where('vehicle_id', $id)->get();
        return view('vehicle_pictures.show', compact('vehiclePictures'));
    }
    public function updatewarehouse(Request $request)
    {
        $vehicles = $request->vehicle_ids;
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);

        foreach ($vehicles as $key => $vehicleId) {
            $vehicle = Vehicles::find($vehicleId);
            //            $vehicle->conversion = $request->conversions[$key];

            if ($vehicle->remarks != $request->warehouse_remarks[$key]) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'remarks';
                $vehicleslog->old_value = $vehicle->remarks;
                $vehicleslog->new_value = $request->warehouse_remarks[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();

                $remarksdata = new Remarks();

                $remarksdata->time = $currentDateTime->toTimeString();
                $remarksdata->date = $currentDateTime->toDateString();
                $remarksdata->vehicles_id = $vehicleId;
                $remarksdata->remarks = $request->warehouse_remarks[$key];
                $remarksdata->created_by = auth()->user()->id;
                $remarksdata->department = "WareHouse";
                $remarksdata->created_at = $currentDateTime;
                $remarksdata->save();
                event(new DataUpdatedEvent(['id' => $vehicleId, 'message' => "Data Update"]));
                $vehicle->remarks = $request->warehouse_remarks[$key];
            }
            if ($vehicle->conversion != $request->conversions[$key]) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Update QC Values';
                $vehicleslog->vehicles_id = $vehicleId;
                $vehicleslog->field = 'conversion';
                $vehicleslog->old_value = $vehicle->conversion;
                $vehicleslog->new_value = $request->conversions[$key];
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->save();
                $vehicle->conversion = $request->conversions[$key];
            }
            $vehicle->save();
        }

        return redirect()->back()->with('success', 'Details updated successfully.');
    }

    public function getModelLines($brandId)
    {
        $modelLines = MasterModelLines::where('brand_id', $brandId)->get();
        return response()->json($modelLines);
    }
    public function incomingpendingpdis(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('so_id')
                ->whereNull('pdi_date');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesveher',
                'warehousesvehss'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function checkEntry(Request $request)
    {
        $vin = $request->input('vin');
        $vehicle = Vehicles::where('vin', $vin)->first();
        if ($vehicle) {
            $vehiclePicture = VehiclePicture::where('vehicle_id', $vehicle->id)->get();
            if ($vehiclePicture) {
                return response()->json(['entryExists' => true, 'category' => $vehiclePicture->category]);
            } else {
                return response()->json(['entryExists' => false]);
            }
        } else {
            return response()->json(['entryExists' => false]);
        }
    }

    public function soldvehss(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('so_id');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesveher',
                'warehousesvehss',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function avalibless(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('inspection_date')
                ->whereNull('so_id');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesveher',
                'warehousesvehss',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function pendinggrnnetsuilt(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('movement_grn_id')
                ->whereNull('netsuit_grn_number');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    $query->whereNull('so_id')
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesveher',
                'warehousesvehss',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function pendingapprovalssales(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        if ($hasPermission) {
            $fieldValues = ['so_number', 'so_date', 'sales_person_id', 'reservation_start_date', 'reservation_end_date'];
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->join('vehicle_detail_approval_requests', 'vehicles.id', '=', 'vehicle_detail_approval_requests.vehicle_id')
                ->where('vehicle_detail_approval_requests.status', '=', 'Pending')
                ->where('vehicles.latest_location', '=', $warehouseId) // Replace $warehousesveher->id with $warehouseId
                ->where(function ($query) use ($fieldValues) {
                    $query->whereIn('field', $fieldValues);
                });
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesvehss',
                'warehousesveher',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function bookedstocked(Request $request)
    {
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-full-view');
        $warehouseId = $request->query('warehouse_id');
        $today = today();
        if ($hasPermission) {
            $statuss = "Approved";
            $data = Vehicles::where('status', $statuss)
                ->where('latest_location', $warehouseId)
                ->whereNotNull('reservation_end_date')
                ->where('reservation_end_date', '<=', $today)
                ->whereNotNull('so_id')
                ->whereNull('gdn_id');
            $hasEditSOPermission = Auth::user()->hasPermissionForSelectedRole('edit-so');
            if ($hasEditSOPermission) {
                $data = $data->where(function ($query) {
                    // Include vehicles with 'so_id' is null
                    $query->whereNull('so_id')
                        // OR vehicles associated with sales orders where sales_person_id matches the user's role ID
                        ->orWhereHas('So', function ($query) {
                            $query->where('sales_person_id', Auth::user()->role_id);
                        });
                });
            }
            $data = $data->paginate(100);
            $pendingVehicleDetailForApprovals = VehicleApprovalRequests::where('status', 'Pending')
                ->groupBy('vehicle_id')->get();
            $pendingVehicleDetailForApprovalCount = $pendingVehicleDetailForApprovals->count();
            $datapending = Vehicles::where('status', '!=', 'cancel')->whereNull('inspection_date')->get();
            $varaint = Varaint::whereNotNull('master_model_lines_id')->get();
            $sales_persons = ModelHasRoles::get();
            $sales_ids = $sales_persons->pluck('model_id');
            $sales = User::whereIn('id', $sales_ids)->get();
            $exteriorColours = ColorCode::where('belong_to', 'ex')->get();
            $interiorColours = ColorCode::where('belong_to', 'int')->get();
            $warehouses = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousessold = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveh = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesvehss = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $warehousesveher = Warehouse::whereNotIn('name', ['Supplier', 'Customer', 'In Transit', 'Fleet - Assigned', 'SHIPPER', 'NEW STOCK'])->get();
            $countwarehouse = $warehouses->count() ?? 0;
            return view('vehicles.index', compact(
                'data',
                'varaint',
                'sales',
                'datapending',
                'exteriorColours',
                'interiorColours',
                'pendingVehicleDetailForApprovalCount',
                'warehouses',
                'countwarehouse',
                'warehousesveh',
                'warehousesveher',
                'warehousesvehss',
                'warehousessold'
            ));
        } else {
            return redirect()->route('home');
        }
    }
    public function viewall(Request $request)
    {
        return view('vehicles.indext');
    }
    public function viewalls(Request $request)
    {
        // testing needed
        info("testing grn");
        $offset = $request->input('offset', 0);
        $length = $request->input('length', 40);
        $searchParams = $request->input('columns', []);
        $query = Vehicles::with(['So', 'PurchasingOrder', 'Grn', 'movementGrn', 'Gdn', 'variant', 'document', 'warehouse', 'interior', 'exterior', 'variant.brand', 'variant.master_model_lines', 'So.salesperson', 'latestRemarkSales', 'latestRemarkWarehouse'])->where(function ($subQuery) {
            $subQuery->where('status', 'Approved')
                ->whereNull('gdn_id');
            $subQuery->orWhereHas('Gdn', function ($gdnSubQuery) {
                $gdnSubQuery->whereNotNull('gdn_id')
                    ->where('date', '>=', now()->subMonths(3)->toDateString());
            });
        });
        foreach ($searchParams as $column => $searchValue) {
            if ($searchValue !== null) {
                if ($column === "po_number") {
                    $query->whereHas('PurchasingOrder', function ($subQuery) use ($searchValue) {
                        $subQuery->where('po_number', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "po_date") {
                    $query->whereHas('PurchasingOrder', function ($subQuery) use ($searchValue) {
                        $subQuery->where('po_date', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "estimation_date") {
                    $query->where('estimation_date', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "grn_number") {
                    $query->whereHas('movementGrn', function ($subQuery) use ($searchValue) {
                        $subQuery->where('grn_number', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "grn_date") {
                    $query->whereHas('movementGrn.Movementrefernce', function ($subQuery) use ($searchValue) {
                        $subQuery->where('date', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "inspection_date") {
                    $query->where('inspection_date', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "grn_remark") {
                    $query->where('grn_remark', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "qc_remarks") {
                    $query->where('qc_remarks', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "so_number") {
                    $query->whereHas('So', function ($subQuery) use ($searchValue) {
                        $subQuery->where('so_number', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "so_date") {
                    $query->whereHas('So', function ($subQuery) use ($searchValue) {
                        $subQuery->where('so_date', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "reservation_start_date") {
                    $query->where('reservation_start_date', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "reservation_end_date") {
                    $query->where('reservation_end_date', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "pdi_date") {
                    $query->where('pdi_date', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "pdi_remarks") {
                    $query->where('pdi_remarks', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "gdn_number") {
                    $query->whereHas('Gdn', function ($subQuery) use ($searchValue) {
                        $subQuery->where('gdn_number', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "gdn_date") {
                    $query->whereHas('Gdn', function ($subQuery) use ($searchValue) {
                        $subQuery->where('date', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "gdn_date") {
                    $query->whereHas('Gdn', function ($subQuery) use ($searchValue) {
                        $subQuery->where('date', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "brand") {
                    $query->whereHas('variant.brand', function ($subQuery) use ($searchValue) {
                        $subQuery->where('brand_name', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "model_line") {
                    $query->whereHas('variant.master_model_lines', function ($subQuery) use ($searchValue) {
                        $subQuery->where('model_line', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "model_description") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('model_detail', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "variant") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "variant_details") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('detail', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "vin") {
                    $query->where('vin', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "conversion") {
                    $query->where('conversion', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "engine") {
                    $query->where('engine', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "model_year") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('my', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "steering") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('steering', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "seats") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('seat', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "fuel_type") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('fuel_type', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "gear") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('gear', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "int_colour") {
                    $query->whereHas('interior', function ($subQuery) use ($searchValue) {
                        $subQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "ex_colour") {
                    $query->whereHas('exterior', function ($subQuery) use ($searchValue) {
                        $subQuery->where('name', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "upholestry") {
                    $query->whereHas('variant', function ($subQuery) use ($searchValue) {
                        $subQuery->where('upholestry', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "extra_features") {
                    $query->where('extra_features', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "ppmmyyy") {
                    $query->where('ppmmyyy', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "territory") {
                    $query->where('territory', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "latest_location") {
                    $query->where('latest_location', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "warehouseremarks") {
                    $query->whereHas('latestRemarkWarehouse', function ($subQuery) use ($searchValue) {
                        $subQuery->where('remark', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "price") {
                    $query->where('price', 'LIKE', '%' . $searchValue . '%');
                }
                if ($column === "importdoc") {
                    $query->whereHas('document', function ($subQuery) use ($searchValue) {
                        $subQuery->where('importdoc', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "ownership") {
                    $query->whereHas('document', function ($subQuery) use ($searchValue) {
                        $subQuery->where('ownership', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "documentwith") {
                    $query->whereHas('document', function ($subQuery) use ($searchValue) {
                        $subQuery->where('documentwith', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "bl_number") {
                    $query->whereHas('document', function ($subQuery) use ($searchValue) {
                        $subQuery->where('bl_number', 'LIKE', '%' . $searchValue . '%');
                    });
                }
                if ($column === "bl_dms_uploading") {
                    $query->whereHas('document', function ($subQuery) use ($searchValue) {
                        $subQuery->where('bl_dms_uploading', 'LIKE', '%' . $searchValue . '%');
                    });
                }
            }
        }
        $vehicles = $query->select('id', 'status', 'vin', 'latest_location', 'ex_colour', 'int_colour', 'varaints_id', 'so_id', 'purchasing_order_id', 'grn_id', 'movement_grn_id', 'gdn_id', 'documents_id', 'estimation_date', 'inspection_date', 'grn_remark', 'qc_remarks', 'reservation_start_date', 'reservation_start_date', 'pdi_date', 'pdi_remarks', 'conversion', 'engine', 'extra_features', 'ppmmyyy', 'territory', 'price')->skip($offset)->take($length)->get();
        $modifiedVehicles = $vehicles->map(function ($vehicle) {
            $vehicle->so_number = $vehicle->so ? $vehicle->so->so_number : '';
            $vehicle->so_date = $vehicle->so ? $vehicle->so->so_date : '';
            $vehicle->po_number = $vehicle->purchasingOrder ? $vehicle->purchasingOrder->po_number : '';
            $vehicle->po_date = $vehicle->purchasingOrder ? $vehicle->purchasingOrder->po_date : '';
            $vehicle->grn_date = $vehicle->grn ? $vehicle->movementGrn->Movementrefernce->date : '';
            $vehicle->grn_number = $vehicle->grn ? $vehicle->movementGrn->grn_number : '';
            $vehicle->gdn_date = $vehicle->gdn ? $vehicle->gdn->date : '';
            $vehicle->gdn_number = $vehicle->gdn ? $vehicle->gdn->gdn_number : '';
            $vehicle->variantname = $vehicle->variant ? $vehicle->variant->name : '';
            $vehicle->variantdetail = $vehicle->variant ? $vehicle->variant->detail : '';
            $vehicle->variantmy = $vehicle->variant ? $vehicle->variant->my : '';
            $vehicle->variantsteering = $vehicle->variant ? $vehicle->variant->steering : '';
            $vehicle->variantseat = $vehicle->variant ? $vehicle->variant->seat : '';
            $vehicle->model_detail = $vehicle->variant ? $vehicle->variant->model_detail : '';
            $vehicle->variantfuel_type = $vehicle->variant ? $vehicle->variant->fuel_type : '';
            $vehicle->transmission = $vehicle->variant ? $vehicle->variant->transmission : '';
            $vehicle->upholestry = $vehicle->variant ? $vehicle->variant->upholestry : '';
            $vehicle->import_type = $vehicle->document ? $vehicle->document->import_type : '';
            $vehicle->owership = $vehicle->document ? $vehicle->document->owership : '';
            $vehicle->document_with = $vehicle->document ? $vehicle->document->document_with : '';
            $vehicle->bl_number = $vehicle->document ? $vehicle->document->bl_number : '';
            $vehicle->bl_dms_uploading = $vehicle->document ? $vehicle->document->bl_dms_uploading : '';
            $vehicle->bl_dms_uploading = $vehicle->document ? $vehicle->document->bl_dms_uploading : '';
            $vehicle->warehousename = $vehicle->warehouse ? $vehicle->warehouse->name : '';
            $vehicle->interiorcolours = $vehicle->interior ? $vehicle->interior->name : '';
            $vehicle->exteriorcolour = $vehicle->exterior ? $vehicle->exterior->name : '';
            $vehicle->latest_remark_sales = $vehicle->latestRemarkSales ? $vehicle->latestRemarkSales->remarks : '';
            $vehicle->latest_remark_warehouse = $vehicle->latestRemarkWarehouse ? $vehicle->latestRemarkWarehouse->remarks : '';
            $salespersonName = '';
            if ($vehicle->so && $vehicle->so->salesperson) {
                $salespersonName = $vehicle->so->salesperson->name;
            }
            $vehicle->salespersonname = $salespersonName;
            return $vehicle;
        });
        return response()->json($vehicles);
    }
    public function getUpdatedVehicle($id)
    {
        $query = Vehicles::with(['So', 'PurchasingOrder', 'Grn', 'Gdn', 'MovementGrn', 'variant', 'document', 'warehouse', 'interior', 'exterior', 'variant.brand', 'variant.master_model_lines', 'So.salesperson', 'latestRemarkSales', 'latestRemarkWarehouse'])
            ->select('id', 'status', 'vin', 'latest_location', 'ex_colour', 'int_colour', 'varaints_id', 'so_id', 'purchasing_order_id', 'grn_id', 'movement_grn_id', 'gdn_id', 'documents_id', 'estimation_date', 'netsuit_grn_number', 'netsuit_grn_date', 'inspection_date', 'grn_remark', 'qc_remarks', 'reservation_start_date', 'reservation_start_date', 'pdi_date', 'pdi_remarks', 'conversion', 'engine', 'extra_features', 'ppmmyyy', 'territory', 'price')
            ->where('id', $id);

        $vehicle = $query->first();

        if (!$vehicle) {
            return response()->json(['message' => 'Vehicle not found'], 404);
        }
        // Modify the vehicle data here as needed...
        $vehicle->so_number = $vehicle->so ? $vehicle->so->so_number : '';
        $vehicle->so_date = $vehicle->so ? $vehicle->so->so_date : '';
        $vehicle->po_number = $vehicle->purchasingOrder ? $vehicle->purchasingOrder->po_number : '';
        $vehicle->po_date = $vehicle->purchasingOrder ? $vehicle->purchasingOrder->po_date : '';
        $vehicle->grn_date = $vehicle->grn ? $vehicle->movementGrn->Movementrefernce->date : '';
        $vehicle->grn_number = $vehicle->grn ? $vehicle->movementGrn->grn_number : '';
        $vehicle->gdn_date = $vehicle->gdn ? $vehicle->gdn->date : '';
        $vehicle->gdn_number = $vehicle->gdn ? $vehicle->gdn->gdn_number : '';
        $vehicle->variantname = $vehicle->variant ? $vehicle->variant->name : '';
        $vehicle->variantdetail = $vehicle->variant ? $vehicle->variant->detail : '';
        $vehicle->variantmy = $vehicle->variant ? $vehicle->variant->my : '';
        $vehicle->variantsteering = $vehicle->variant ? $vehicle->variant->steering : '';
        $vehicle->variantseat = $vehicle->variant ? $vehicle->variant->seat : '';
        $vehicle->model_detail = $vehicle->variant ? $vehicle->variant->model_detail : '';
        $vehicle->variantfuel_type = $vehicle->variant ? $vehicle->variant->fuel_type : '';
        $vehicle->transmission = $vehicle->variant ? $vehicle->variant->transmission : '';
        $vehicle->upholestry = $vehicle->variant ? $vehicle->variant->upholestry : '';
        $vehicle->import_type = $vehicle->document ? $vehicle->document->import_type : '';
        $vehicle->owership = $vehicle->document ? $vehicle->document->owership : '';
        $vehicle->document_with = $vehicle->document ? $vehicle->document->document_with : '';
        $vehicle->bl_number = $vehicle->document ? $vehicle->document->bl_number : '';
        $vehicle->bl_dms_uploading = $vehicle->document ? $vehicle->document->bl_dms_uploading : '';
        $vehicle->bl_dms_uploading = $vehicle->document ? $vehicle->document->bl_dms_uploading : '';
        $vehicle->warehousename = $vehicle->warehouse ? $vehicle->warehouse->name : '';
        $vehicle->interiorcolours = $vehicle->interior ? $vehicle->interior->name : '';
        $vehicle->exteriorcolour = $vehicle->exterior ? $vehicle->exterior->name : '';
        $vehicle->latest_remark_sales = $vehicle->latestRemarkSales ? $vehicle->latestRemarkSales->remarks : '';
        $vehicle->latest_remark_warehouse = $vehicle->latestRemarkWarehouse ? $vehicle->latestRemarkWarehouse->remarks : '';
        $salespersonName = '';
        if ($vehicle->so && $vehicle->so->salesperson) {
            $salespersonName = $vehicle->so->salesperson->name;
        }
        $vehicle->salespersonname = $salespersonName;
        return response()->json($vehicle);
    }
    public function statuswise(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Stock Status Wise";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        // Variant detail computation
        $sales_persons = ModelHasRoles::join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('model_has_roles.role_id', 7)
                    ->orWhere('model_has_roles.model_id', 17);
            })
            ->orwhere('users.id', 40)
            ->orderBy('users.name', 'asc')
            ->get();
        $variants = Varaint::with(['variantItems.model_specification', 'variantItems.model_specification_option'])
            ->orderBy('id', 'DESC')
            ->whereNot('category', 'Modified')
            ->get();
        $sequence = ['COO', 'SFX', 'Wheels', 'Seat Upholstery', 'HeadLamp Type', 'infotainment type', 'Speedometer Infotainment Type', 'Speakers', 'sunroof'];
        $normalizationMap = [
            'COO' => 'COO',
            'SFX' => 'SFX',
            'Wheels' => ['wheel', 'Wheel', 'Wheels', 'Wheel type', 'wheel type', 'Wheel size', 'wheel size'],
            'Seat Upholstery' => ['Upholstery', 'Seat', 'seats', 'Seat Upholstery'],
            'HeadLamp Type' => 'HeadLamp Type',
            'infotainment type' => 'infotainment type',
            'Speedometer Infotainment Type' => 'Speedometer Infotainment Type',
            'Speakers' => 'Speakers',
            'sunroof' => 'sunroof'
        ];
        foreach ($variants as $variant) {
            if ($variant->category != 'Modified') {
                $details = [];
                $otherDetails = [];
                foreach ($variant->variantItems as $item) {
                    $modelSpecification = $item->model_specification;
                    $modelSpecificationOption = $item->model_specification_option;
                    if ($modelSpecification && $modelSpecificationOption) {
                        $name = $modelSpecification->name;
                        $optionName = $modelSpecificationOption->name;
                        $normalized = null;
                        foreach ($normalizationMap as $key => $values) {
                            if (is_array($values)) {
                                if (in_array($name, $values)) {
                                    $normalized = $key;
                                    break;
                                }
                            } elseif ($name === $values) {
                                $normalized = $key;
                                break;
                            }
                        }

                        if ($normalized) {
                            $name = $normalized;
                        }
                        if (in_array(strtolower($optionName), ['yes', 'no'])) {
                            if (strtolower($optionName) === 'yes') {
                                $optionName = $name;
                            } else {
                                continue;
                            }
                        }
                        if (in_array($name, $sequence)) {
                            $index = array_search($name, $sequence);
                            $details[$index] = $optionName;
                        } else {
                            $otherDetails[] = $optionName;
                        }
                    }
                }
                ksort($details);
                $variant->detail = implode(', ', array_merge($details, $otherDetails));
                $variant->save();
            }
        }
        if ($request->ajax()) {
            $status = $request->input('status');
            $filters = $request->input('filters', []);
            if ($status === "allstock") {
                $data = Vehicles::select([
                    'vehicles.id',
                    'vehicles.movement_grn_id',
                    'vehicles.gdn_id',
                    'vehicles.gp',
                    'vehicles.sales_remarks',
                    'vehicles.estimation_date',
                    'vehicles.territory',
                    'vehicles.inspection_status',
                    'vehicles.ownership_type',
                    'vehicles.inspection_date',
                    'vehicles.custom_inspection_number',
                    'vehicles.custom_inspection_status',
                    'vehicles.so_id',
                    'vehicles.minimum_commission',
                    'vehicles.reservation_end_date',
                    'vehicles.vehicle_document_status',
                    'warehouse.name as location',
                    'purchasing_order.po_date',
                    'vehicles.ppmmyyy',
                    'vehicles.vin as vin',
                    'inspection_grn.id as grn_inspectionid',
                    'inspection_pdi.id as pdi_inspectionid',
                    'vehicles.engine',
                    'vehicles.price',
                    'countries.name as fd',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.id as variant_id',
                    'varaints.model_detail',
                    'varaints.detail',
                    'varaints.seat',
                    'varaints.detail as variant_detail',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'varaints.gearbox',
                    'so.so_number',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'documents.import_type',
                    'documents.owership',
                    'documents.document_with',
                    'movement_grns.grn_number',
                    'gdn.gdn_number',
                    'bp.name as bpn',
                    'sp.name as spn',
                    DB::raw("DATE_FORMAT(work_orders.date, '%Y-%m-%d') as work_order_date"),
                    DB::raw("(SELECT COUNT(*) FROM stock_message WHERE stock_message.vehicle_id = vehicles.id) as message_count"),
                    'so.so_date',
                    'movements_reference.date',
                    'gdn.date as gdndate',
                    DB::raw("
                        COALESCE(
                            (SELECT FORMAT(CAST(cost AS UNSIGNED), 0) FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1),
                            (SELECT FORMAT(CAST(unit_price AS UNSIGNED), 0) FROM vehicle_purchasing_cost WHERE vehicle_purchasing_cost.vehicles_id = vehicles.id LIMIT 1),
                            ''
                        ) as costprice,
                        (SELECT netsuite_link FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1) as netsuite_link
                    ")

                ])
                    ->leftJoin('w_o_vehicles', 'vehicles.id', '=', 'w_o_vehicles.vehicle_id')
                    ->leftJoin('work_orders', 'w_o_vehicles.work_order_id', '=', 'work_orders.id')
                    ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                    ->leftJoin('countries', 'purchasing_order.fd', '=', 'countries.id')
                    ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                    // ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                    ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                    ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                    ->leftJoin('gdn', 'vehicles.gdn_id', '=', 'gdn.id')
                    ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                    ->leftJoin('users as sp', 'so.sales_person_id', '=', 'sp.id') // Join for sales person
                    ->leftJoin('users as bp', 'vehicles.booking_person_id', '=', 'bp.id') // Join for booking person
                    ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                    ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                    ->leftJoin('inspection as inspection_grn', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_grn.vehicle_id')
                            ->where('inspection_grn.stage', '=', 'GRN');
                    })
                    ->leftJoin('documents', 'documents.id', '=', 'vehicles.documents_id')
                    ->leftJoin('inspection as inspection_pdi', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_pdi.vehicle_id')
                            ->where('inspection_pdi.stage', '=', 'PDI');
                    })
                    ->where('vehicles.status', 'Approved');
                foreach ($filters as $columnName => $values) {
                    if (in_array('__NULL__', $values)) {
                        $data->whereNull($columnName); // Filter for NULL values
                    } elseif (in_array('__Not EMPTY__', $values)) {
                        $data->whereNotNull($columnName); // Filter for non-empty values
                    } else {
                        $data->whereIn($columnName, $values); // Regular filtering for selected values
                    }
                }
                $data = $data->groupBy('vehicles.id');
            }
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('vehicles.stock', ['salesperson' => $sales_persons]);
    }
    public function generategrnPDF(Request $request)
    {
        $vehicleId = $request->vehicle_id;
        $vehicle = Vehicles::with(['interior', 'exterior'])->where('id', $vehicleId)->first();
        $grn = MovementGrn::where('id', $vehicle->movement_grn_id)->first();
        $variant = Varaint::with(['master_model_lines', 'brand'])->where('id', $vehicle->varaints_id)->first();
        // get the data from variant request
        $variantitems = VariantItems::with(['model_specification', 'model_specification_option'])->where('varaint_id', $variant->id)->get();
        $vehicleitems = VehicleExtraItems::where('vehicle_id', $vehicleId)->get();
        $inspection = Inspection::where('vehicle_id', $vehicleId)->where('stage', 'GRN')->first();
        $incident = Incident::where('vehicle_id', $vehicleId)->where('inspection_id', $inspection->id)->first();
        $createdby = User::where('id', $inspection->created_by)->pluck('name')->first();
        if (!$vehicle) {
            abort(404);
        }
        $data = [
            'vehicle' => $vehicle,
            'grn_date' => $grn->Movementrefernce->date ?? '',
            'variant' => $variant,
            'inspection' => $inspection,
            'variantitems' => $variantitems,
            'vehicleItems' => $vehicleitems,
            'created_by' => $createdby,
            'incident' => $incident,
        ];
        $pdf = PDF::loadView('Reports.Grn', $data);
        return $pdf->stream('vehicle-details.pdf');
    }
    public function fetchData(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::with('variant', 'exterior')->findOrFail($vehicleId);
        $variant = str_replace(' ', '', $vehicle->variant->name);
        $post = $this->fetchPost($variant, $vehicle->exterior ? $vehicle->exterior->name : null);

        if (!$post) {
            // Remove the first letter of the variant name and try again
            $variant = substr($variant, 1);
            $post = $this->fetchPost($variant, $vehicle->exterior ? $vehicle->exterior->name : null);
        }

        if ($post) {
            $galleryMeta = DB::connection('wordpress')->table('mm_postmeta')
                ->where('post_id', $post->ID)
                ->where('meta_key', 'gallery')
                ->first();

            $galleryIds = unserialize($galleryMeta->meta_value);

            $imageUrls = [];
            foreach ($galleryIds as $id) {
                $imagePost = DB::connection('wordpress')->table('mm_posts')
                    ->where('ID', $id)
                    ->first();

                if ($imagePost) {
                    $imageUrls[] = $imagePost->guid;
                }
            }

            return response()->json([
                'gallery' => $imageUrls
            ]);
        } else {
            return response()->json(['message' => 'No post found'], 404);
        }
    }

    private function fetchPost($variant, $exteriorColor)
    {
        $query = DB::connection('wordpress')->table('mm_posts')
            ->join('mm_postmeta as variant_meta', 'mm_posts.ID', '=', 'variant_meta.post_id')
            ->join('mm_postmeta as color_meta', 'mm_posts.ID', '=', 'color_meta.post_id')
            ->where('variant_meta.meta_key', 'Car ID')
            ->where('variant_meta.meta_value', $variant)
            ->where('mm_posts.post_status', 'publish');

        if ($exteriorColor) {
            $query->where('color_meta.meta_key', 'color')
                ->where('color_meta.meta_value', $exteriorColor);
        }

        return $query->select('mm_posts.ID', 'mm_posts.post_title', 'mm_posts.post_name')
            ->first();
    }
    public function currentstatus()
    {
        return view('vehicles.currentstatus');
    }
    public function statussreach(Request $request)
    {
        $searchQuery = $request->input('search');
        $vehicles = Vehicles::where('vin', 'LIKE', "%{$searchQuery}%")->get();
        $data = [];
        foreach ($vehicles as $vehicle) {
            $status = $vehicle->status;
            $previous_status = '';
            $current_status = '';
            $next_stage = '';
            switch ($status) {
                case 'Approved':
                    $previous_status = 'Pending Approval From Vehicle Procurement Manager';
                    $current_status = 'Vehicle is Approved For Initiated Payment';
                    $next_stage = 'Initiated Payment By Vehicle Procurement Executive';
                    break;
                case 'Not Approved':
                    $previous_status = 'Created PO By Vehicle Procurement Executive';
                    $current_status = 'Vehicle is Not Approved By the Vehicle Procurement Manager';
                    $next_stage = 'Approved Vehicle By Procurement Manager';
                    break;
                case 'Request for Payment':
                    $previous_status = 'Approved Vehicle By Procurement Manager';
                    $current_status = 'Initiated Payment By Vehicle Procurement Executive';
                    $next_stage = 'Procurement Manager Forward Request for Payment To Finance Department';
                    break;
                case 'Payment Requested':
                    $previous_status = 'Procurement Manager Forward Request for Payment To Finance Department';
                    $current_status = 'Finance Department Forward Request to CEO Office For Payment Release';
                    $next_stage = 'CEO Office Payment Released';
                    break;
                case 'Payment Completed':
                    $previous_status = 'CEO Office Payment Released';
                    $current_status = 'Finance Department Complete the Payments';
                    $next_stage = 'Vehicle Procurement Executive Will Confirm Vendor Received Payment and Vehicle is Incoming';
                    break;
                case 'Payment Rejected':
                    $previous_status = 'Request to CEO Office for Payment Release';
                    $current_status = 'Payment Rejected By CEO Office';
                    $next_stage = 'Procurement Manager Forward Again Request for Payment To Finance Department';
                    break;
                default:
                    break;
            }
            if ($vehicle->status == 'Payment Requested' && $vehicle->payment_status == 'Payment Initiated') {
                $previous_status = 'Finance Department Forward Request to CEO Office For Payment Release';
                $current_status = 'Request to CEO Office for Payment Release';
                $next_stage = 'CEO Office Payment Released';
            }
            if ($vehicle->status == 'Approved' && $vehicle->movement_grn_id == NULL) {
                $previous_status = 'Vehicle Procurement Executive Will Confirm Vendor Received Payment and Vehicle is Incoming';
                $current_status = 'Incoming Vehicles / Pending GRN';
                $next_stage = 'GRN Done';
            }
            if ($vehicle->movement_grn_id != NULL && $vehicle->inspection_date == null) {
                $previous_status = 'GRN Done';
                $current_status = 'Pending Inspection';
                $next_stage = 'Available Stock';
            }
            if ($vehicle->inspection_date != NULL) {
                $previous_status = 'Inspection Done';
                $current_status = 'Available Stock';
                $next_stage = 'Create SO, Pending PDI, Pending GDN';
            }
            if ($vehicle->pdi_date == NULL && $vehicle->gdn == NULL && $vehicle->so_id != NULL) {
                $previous_status = 'Available Stock';
                $current_status = 'Pending PDI Inspection';
                $next_stage = 'GDN';
            }
            if ($vehicle->gdn_id != NULL) {
                $previous_status = 'PDI Inspection';
                $current_status = 'GDN Done';
                $next_stage = '';
            }
            $data[] = [
                'previous_status' => $previous_status,
                'current_status' => $current_status,
                'next_stage' => $next_stage
            ];
        }
        return response()->json(['data' => $data]);
    }
    public function generatepfiPDF(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "Open The PDI Inspection";
        $useractivities->users_id = Auth::id();
        $useractivities->save();

        $vehicleId = $request->vehicle_id;
        $inspection = Inspection::where('vehicle_id', $vehicleId)->where('stage', 'PDI')->first();
        if (!$inspection) {
            return response()->json(['message' => 'Inspection not found'], 404);
        }

        $PdiInspectionData = Pdi::select('checking_item', 'reciving', 'status')
            ->where('inspection_id', $inspection->id)
            ->get();

        $additionalInfo = Vehicles::select(
            'master_model_lines.model_line',
            'vehicles.vin',
            'int_color.name as int_colour',
            'ext_color.name as ext_colour',
            'warehouse.name as location'
        )
            ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
            ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
            ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
            ->leftJoin('color_codes as ext_color', 'vehicles.ex_colour', '=', 'ext_color.id')
            ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
            ->where('vehicles.id', $inspection->vehicle_id)
            ->first();

        $incident = Incident::where('inspection_id', $inspection->id)->first();
        $vehicle = Vehicles::find($inspection->vehicle_id);

        $grnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN')->pluck('vehicle_picture_link')->first();
        $secgrnpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'GRN-2')->pluck('vehicle_picture_link')->first();
        $PDIpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'PDI')->pluck('vehicle_picture_link')->first();
        $modificationpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Modification')->pluck('vehicle_picture_link')->first();
        $Incidentpicturelink = VehiclePicture::where('vehicle_id', $inspection->vehicle_id)->where('category', 'Incident')->pluck('vehicle_picture_link')->first();
        $createdby = User::where('id', $inspection->created_by)->pluck('name')->first();
        $variantitems = [];
        if ($vehicle) {
            $variantitems = VariantItems::with(['model_specification', 'model_specification_option'])
                ->where('varaint_id', $vehicle->varaints_id)->get();
        }
        $data = [
            'inspection' => $inspection,
            'PdiInspectionData' => $PdiInspectionData,
            'additionalInfo' => $additionalInfo,
            'grnpicturelink' => $grnpicturelink,
            'secgrnpicturelink' => $secgrnpicturelink,
            'PDIpicturelink' => $PDIpicturelink,
            'modificationpicturelink' => $modificationpicturelink,
            'Incidentpicturelink' => $Incidentpicturelink,
            'incident' => $incident,
            'remarks' => $inspection->remarks,
            'created_by' => $createdby,
            'variantitems' => $variantitems
        ];

        $pdf = PDF::loadView('Reports.pdi', $data);
        return $pdf->stream('vehicle-details-pdi.pdf');
    }
    public function hold(Request $request, $id)
    {
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicle = Vehicles::find($id);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Hold Vehicle';
        $vehicleslog->vehicles_id = $id;
        $vehicleslog->field = "Vehicle Status";
        $vehicleslog->old_value =  $vehicle->status;
        $vehicleslog->new_value = $request->status;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->save();
        $purchasingOrder = PurchasingOrder::where('id', $vehicle->purchasing_order_id)->first();
        $purchasingOrder->status = "Pending Approval";
        $purchasingOrder->save();
        if ($request->status === 'hold') {
            $vehicle->status = 'Hold';
        } else {
            $vehicle->status = 'Not Approved';
        }
        $vehicle->save();
        return response()->json(['success' => true]);
    }
    public function allvariantprice(Request $request)
    {
        $useractivities =  new UserActivities();
        $useractivities->activity = "Open All Variant Price";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
            $data = Vehicles::select([
                'brands.brand_name',
                'vehicles.gp',
                'vehicles.minimum_commission',
                'vehicles.varaints_id',
                'vehicles.int_colour',
                'vehicles.ex_colour',
                'vehicles.price',
                'varaints.name',
                'master_model_lines.model_line',
                'int_color.name as interior_color',
                'ex_color.name as exterior_color',
            ])
                ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->groupBy('varaints.id', 'int_color.id', 'ex_color.id');

            return DataTables::of($data)
                ->editColumn('price', function ($data) {
                    return number_format($data->price, 0, '.', ',');
                })
                ->editColumn('minimum_commission', function ($data) {
                    return number_format($data->minimum_commission, 0, '.', ',');
                })
                ->toJson();
        }
        return view('variant-prices.allindex');
    }
    public function allvariantpriceupdate(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make(
            $request->all(),
            [
                'varaints_id' => 'required|integer|exists:varaints,id',
                'field' => 'required|string|in:price,gp,minimum_commission',
                'value' => 'required|string'
            ],
            ['value.required' => 'Valid amount value is required']
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        // Find the vehicle by varaints_id, and optionally by int_colour and ex_colour
        $query = Vehicles::where('varaints_id', $request->varaints_id);


        if (!empty($request->int_colour)) {
            $query->where('int_colour', $request->int_colour);
        } else {
            $query->whereNull('int_colour');
        }

        if (!empty($request->ex_colour)) {
            $query->where('ex_colour', $request->ex_colour);
        } else {
            $query->whereNull('ex_colour');
        }

        $vehicles = $query->get();

        if ($vehicles->count() <= 0) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        foreach ($vehicles as $vehicle) {
            $oldValue = $vehicle->{$request->field};
            $field = $request->field;
            $value = $request->value;
            if ($field == 'price') {
                $value = str_replace(',', '', $value);
            }
            if ($field == 'minimum_commission') {
                $value = str_replace(',', '', $value);
            }
            $vehicle->$field = $value ?? 0;
            $vehicle->save();
            $currentDateTime = Carbon::now();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicles Selling Price / GP';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = $field;
            $vehicleslog->old_value = $oldValue;
            $vehicleslog->new_value = $value;
            $vehicleslog->category = "Sales";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        }

        return response()->json(['success' => 'Vehicle updated successfully']);
    }
    public function custominspectionupdate(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'custom_inspection_number' => 'required|numeric',
            'custom_inspection_status' => 'required|in:pending,done,start',
        ]);
        $vehicle = Vehicles::findOrFail($request->input('vehicle_id'));
        $vehicle->custom_inspection_number = $request->input('custom_inspection_number');
        $vehicle->custom_inspection_status = $request->input('custom_inspection_status');
        $vehicle->save();
        return redirect()->route('vehicles.statuswise', ['status' => 'allstock']);
    }
    public function getReservation(Request $request)
    {
        $vehicle_id = $request->input('vehicle_id');
        $reservation = DB::table('vehicles')
            ->where('id', $vehicle_id)
            ->whereDate('reservation_end_date', '>=', Carbon::today())
            ->first();
        return response()->json($reservation);
    }
    public function saveenhancement(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $variantId = $request->input('variant_id');
        $vehicle = Vehicles::find($vehicleId);
        $oldValue = $vehicle->varaints_id;
        if ($vehicle) {
            $vehicle->varaints_id = $variantId;
            $vehicle->save();
            if ($oldValue != $variantId) {
                Log::info('Variant Change Detected 13. Vehicle varaints_id updated (saveenhancement)', [
                    'vehicle_id' => $vehicle->id,
                    'vin' => $vehicle->vin,
                    'old_varaints_id' => $oldValue,
                    'new_varaints_id' => $variantId,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name ?? 'N/A',
                    'source' => 'saveenhancementinvehiclecontroller',
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        }
        $currentDateTime = Carbon::now();
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Update Vehicle Variant After Enhancement';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = "Variant";
        $vehicleslog->old_value = $oldValue;
        $vehicleslog->new_value = $variantId;
        $vehicleslog->category = "Enhancement";
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
        return redirect()->route('vehicles.statuswise', ['status' => 'allstock']);
    }
    public function getVariants(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId);
        if ($vehicle) {
            $variantId = $vehicle->varaints_id;
            $varaiant = Varaint::find($variantId);
            $masterModelLinesId = $varaiant->master_model_lines_id;
            $variants = Varaint::where('master_model_lines_id', $masterModelLinesId)
                ->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'variants' => $variants,
                    'vin' => $vehicle->vin,  // Assuming the VIN is stored in the vehicle model
                ]
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Vehicle not found']);
    }
    public function getcolours(Request $request)
    {
        $vehicleId = $request->get('vehicle_id');
        $vehicle = Vehicles::find($vehicleId);
        $intColorOptions = ColorCode::where('belong_to', 'int')->get();
        $extColorOptions = ColorCode::where('belong_to', 'ex')->get();
        $vin = $vehicle->vin;
        $intColorSelect = '<option value="">Select Interior Color</option>';
        foreach ($intColorOptions as $color) {
            $selected = $vehicle->int_colour == $color->id ? 'selected' : '';
            $intColorSelect .= '<option value="' . $color->id . '" ' . $selected . '>' . $color->name . '</option>';
        }
        $extColorSelect = '<option value="">Select Exterior Color</option>';
        foreach ($extColorOptions as $color) {
            $selected = $vehicle->ex_colour == $color->id ? 'selected' : '';
            $extColorSelect .= '<option value="' . $color->id . '" ' . $selected . '>' . $color->name . '</option>';
        }
        return response()->json([
            'intColorOptions' => $intColorSelect,
            'extColorOptions' => $extColorSelect,
            'vin' => $vin,
        ]);
    }
    public function saveenhancementcolor(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $int_color = $request->input('int_color_dropdown');
        $ext_color = $request->input('ext_color_dropdown');
        $vehicle = Vehicles::find($vehicleId);
        $oldValueint = $vehicle->int_colour;
        $oldValueex = $vehicle->ex_colour;
        if ($vehicle) {
            $vehicle->int_colour = $int_color;
            $vehicle->ex_colour = $ext_color;
            $vehicle->save();
        }
        if ($oldValueint != $int_color) {
            $currentDateTime = Carbon::now();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicle Interior Colours';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Interior Colour";
            $vehicleslog->old_value = $oldValueint;
            $vehicleslog->new_value = $int_color;
            $vehicleslog->category = "Enhancement";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        }
        if ($oldValueex != $ext_color) {
            $currentDateTime = Carbon::now();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicle Exterior Colours';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Exterior Colour";
            $vehicleslog->old_value = $oldValueex;
            $vehicleslog->new_value = $ext_color;
            $vehicleslog->category = "Enhancement";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        }
        return redirect()->route('vehicles.statuswise', ['status' => 'allstock']);
    }
    public function getCustomInspectionData(Request $request)
    {
        // Find the vehicle by ID and fetch its custom inspection details
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId); // Assume you have a Vehicle model

        if ($vehicle) {
            // Return the custom inspection number and status
            return response()->json([
                'custom_inspection_number' => $vehicle->custom_inspection_number, // Replace with the actual column
                'custom_inspection_status' => $vehicle->custom_inspection_status  // Replace with the actual column
            ]);
        } else {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }
    public function availablevehicles(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Stock Status Wise";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        // Variant detail computation
        $sales_persons = ModelHasRoles::join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('model_has_roles.role_id', 7)
                    ->orWhere('model_has_roles.model_id', 17);
            })
            ->orderBy('users.name', 'asc')
            ->get();

        $variants = Varaint::with(['variantItems.model_specification', 'variantItems.model_specification_option'])
            ->orderBy('id', 'DESC')
            ->whereNot('category', 'Modified')
            ->get();
        $sequence = ['COO', 'SFX', 'Wheels', 'Seat Upholstery', 'HeadLamp Type', 'infotainment type', 'Speedometer Infotainment Type', 'Speakers', 'sunroof'];
        $normalizationMap = [
            'COO' => 'COO',
            'SFX' => 'SFX',
            'Wheels' => ['wheel', 'Wheel', 'Wheels', 'Wheel type', 'wheel type', 'Wheel size', 'wheel size'],
            'Seat Upholstery' => ['Upholstery', 'Seat', 'seats', 'Seat Upholstery'],
            'HeadLamp Type' => 'HeadLamp Type',
            'infotainment type' => 'infotainment type',
            'Speedometer Infotainment Type' => 'Speedometer Infotainment Type',
            'Speakers' => 'Speakers',
            'sunroof' => 'sunroof'
        ];
        foreach ($variants as $variant) {
            if ($variant->category != 'Modified') {
                $details = [];
                $otherDetails = [];
                foreach ($variant->variantItems as $item) {
                    $modelSpecification = $item->model_specification;
                    $modelSpecificationOption = $item->model_specification_option;
                    if ($modelSpecification && $modelSpecificationOption) {
                        $name = $modelSpecification->name;
                        $optionName = $modelSpecificationOption->name;
                        $normalized = null;
                        foreach ($normalizationMap as $key => $values) {
                            if (is_array($values)) {
                                if (in_array($name, $values)) {
                                    $normalized = $key;
                                    break;
                                }
                            } elseif ($name === $values) {
                                $normalized = $key;
                                break;
                            }
                        }

                        if ($normalized) {
                            $name = $normalized;
                        }
                        if (in_array(strtolower($optionName), ['yes', 'no'])) {
                            if (strtolower($optionName) === 'yes') {
                                $optionName = $name;
                            } else {
                                continue;
                            }
                        }
                        if (in_array($name, $sequence)) {
                            $index = array_search($name, $sequence);
                            $details[$index] = $optionName;
                        } else {
                            $otherDetails[] = $optionName;
                        }
                    }
                }
                ksort($details);
                $variant->detail = implode(', ', array_merge($details, $otherDetails));
                $variant->save();
            }
        }
        if ($request->ajax()) {
            $status = $request->input('status');
            $filters = $request->input('filters', []);
            \Log::info("Received Filters: ", $filters);
            if ($status === "Available Stock") {
                $data = Vehicles::select([
                    'vehicles.id as id',
                    'warehouse.name as location',
                    'purchasing_order.po_date',
                    'vehicles.estimation_date',
                    'vehicles.sales_remarks',
                    'vehicles.ppmmyyy',
                    DB::raw("DATE_FORMAT(vehicles.reservation_start_date, '%d-%b-%Y') as reservation_start_date"),
                    'vehicles.reservation_end_date',
                    'vehicles.vin',
                    'vehicles.ownership_type',
                    'vehicles.inspection_date',
                    'vehicles.inspection_status',
                    'vehicles.engine',
                    'vehicles.minimum_commission',
                    'vehicles.custom_inspection_number',
                    'vehicles.custom_inspection_status',
                    'vehicles.gp',
                    'inspection_grn.id as grn_inspectionid',
                    'vehicles.territory',
                    'vehicles.price as price',
                    'inspection_pdi.id as pdi_inspectionid',
                    'vehicles.grn_remark',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.model_detail',
                    'varaints.id as variant_id',
                    'varaints.detail as variant_detail',
                    'countries.name as fd',
                    'varaints.seat',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'varaints.gearbox',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'so.so_number',
                    'purchasing_order.po_number',
                    'movement_grns.grn_number',
                    'sp.name as spn',
                    'documents.import_type',
                    'documents.owership',
                    'documents.document_with',
                    'bp.name as bpn',
                    'so.so_date',
                    'movements_reference.date',
                    DB::raw("(SELECT COUNT(*) FROM stock_message WHERE stock_message.vehicle_id = vehicles.id) as message_count"),
                    DB::raw("DATE_FORMAT(work_orders.date, '%Y-%m-%d') as work_order_date"),
                    DB::raw("
                        COALESCE(
                            (SELECT FORMAT(CAST(cost AS UNSIGNED), 0) FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1),
                            (SELECT FORMAT(CAST(unit_price AS UNSIGNED), 0) FROM vehicle_purchasing_cost WHERE vehicle_purchasing_cost.vehicles_id = vehicles.id LIMIT 1),
                            ''
                        ) as costprice,
                        (SELECT netsuite_link FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1) as netsuite_link
                    ")

                ])
                    ->leftJoin('w_o_vehicles', 'vehicles.id', '=', 'w_o_vehicles.vehicle_id')
                    ->leftJoin('work_orders', 'w_o_vehicles.work_order_id', '=', 'work_orders.id')
                    ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                    ->leftJoin('booking', 'vehicles.id', '=', 'booking.vehicle_id')
                    ->leftJoin('countries', 'purchasing_order.fd', '=', 'countries.id')
                    ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                    // ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                    ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                    ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                    ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                    ->leftJoin('users as sp', 'so.sales_person_id', '=', 'sp.id') // Join for sales person
                    ->leftJoin('users as bp', 'vehicles.booking_person_id', '=', 'bp.id') // Join for booking person
                    ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                    ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                    ->leftJoin('inspection as inspection_grn', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_grn.vehicle_id')
                            ->where('inspection_grn.stage', '=', 'GRN');
                    })
                    ->leftJoin('documents', 'documents.id', '=', 'vehicles.documents_id')
                    ->leftJoin('inspection as inspection_pdi', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_pdi.vehicle_id')
                            ->where('inspection_pdi.stage', '=', 'PDI');
                    })
                    ->whereNull('vehicles.gdn_id')
                    ->where('vehicles.status', 'Approved');
                foreach ($filters as $columnName => $values) {
                    if (in_array('__NULL__', $values)) {
                        $data->whereNull($columnName); // Filter for NULL values
                    } elseif (in_array('__Not EMPTY__', $values)) {
                        $data->whereNotNull($columnName); // Filter for non-empty values
                    } else {
                        $data->whereIn($columnName, $values); // Regular filtering for selected values
                    }
                }
                $data = $data->groupBy('vehicles.id');
            }
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('vehicles.available', ['salesperson' => $sales_persons]);
    }
    public function deliveredvehicles(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Stock Status Wise";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        // Variant detail computation
        $sales_persons = ModelHasRoles::join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('model_has_roles.role_id', 7)
                    ->orWhere('model_has_roles.model_id', 17);
            })
            ->orderBy('users.name', 'asc')
            ->get();


        $variants = Varaint::with(['variantItems.model_specification', 'variantItems.model_specification_option'])
            ->orderBy('id', 'DESC')
            ->whereNot('category', 'Modified')
            ->get();
        $sequence = ['COO', 'SFX', 'Wheels', 'Seat Upholstery', 'HeadLamp Type', 'infotainment type', 'Speedometer Infotainment Type', 'Speakers', 'sunroof'];
        $normalizationMap = [
            'COO' => 'COO',
            'SFX' => 'SFX',
            'Wheels' => ['wheel', 'Wheel', 'Wheels', 'Wheel type', 'wheel type', 'Wheel size', 'wheel size'],
            'Seat Upholstery' => ['Upholstery', 'Seat', 'seats', 'Seat Upholstery'],
            'HeadLamp Type' => 'HeadLamp Type',
            'infotainment type' => 'infotainment type',
            'Speedometer Infotainment Type' => 'Speedometer Infotainment Type',
            'Speakers' => 'Speakers',
            'sunroof' => 'sunroof'
        ];
        foreach ($variants as $variant) {
            if ($variant->category != 'Modified') {
                $details = [];
                $otherDetails = [];
                foreach ($variant->variantItems as $item) {
                    $modelSpecification = $item->model_specification;
                    $modelSpecificationOption = $item->model_specification_option;
                    if ($modelSpecification && $modelSpecificationOption) {
                        $name = $modelSpecification->name;
                        $optionName = $modelSpecificationOption->name;
                        $normalized = null;
                        foreach ($normalizationMap as $key => $values) {
                            if (is_array($values)) {
                                if (in_array($name, $values)) {
                                    $normalized = $key;
                                    break;
                                }
                            } elseif ($name === $values) {
                                $normalized = $key;
                                break;
                            }
                        }

                        if ($normalized) {
                            $name = $normalized;
                        }
                        if (in_array(strtolower($optionName), ['yes', 'no'])) {
                            if (strtolower($optionName) === 'yes') {
                                $optionName = $name;
                            } else {
                                continue;
                            }
                        }
                        if (in_array($name, $sequence)) {
                            $index = array_search($name, $sequence);
                            $details[$index] = $optionName;
                        } else {
                            $otherDetails[] = $optionName;
                        }
                    }
                }
                ksort($details);
                $variant->detail = implode(', ', array_merge($details, $otherDetails));
                $variant->save();
            }
        }
        if ($request->ajax()) {
            $status = $request->input('status');
            $filters = $request->input('filters', []);
            if ($status === "Delivered") {
                $data = Vehicles::select([
                    'vehicles.id',
                    'warehouse.name as location',
                    'purchasing_order.po_date',
                    'vehicles.ppmmyyy',
                    'vehicles.reservation_end_date',
                    'vehicles.vin',
                    'vehicles.inspection_status',
                    'vehicles.ownership_type',
                    'vehicles.price',
                    'vehicles.territory',
                    'vehicles.sales_remarks',
                    'vehicles.minimum_commission',
                    'vehicles.custom_inspection_number',
                    'vehicles.vehicle_document_status',
                    'vehicles.custom_inspection_status',
                    'inspection_grn.id as grn_inspectionid',
                    'inspection_pdi.id as pdi_inspectionid',
                    'vehicles.engine',
                    'vehicles.gp',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.id as variant_id',
                    'varaints.model_detail',
                    'varaints.detail as variant_detail',
                    'countries.name as fd',
                    'varaints.seat',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'varaints.gearbox',
                    'so.so_number',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'documents.import_type',
                    'documents.owership',
                    'documents.document_with',
                    'movement_grns.grn_number',
                    'gdn.gdn_number',
                    'users.name',
                    'so.so_date',
                    'movements_reference.date',
                    DB::raw("DATE_FORMAT(work_orders.date, '%Y-%m-%d') as work_order_date"),
                    DB::raw("(SELECT COUNT(*) FROM stock_message WHERE stock_message.vehicle_id = vehicles.id) as message_count"),
                    'gdn.date as gdndate',
                    DB::raw("
                    COALESCE(
                        (SELECT FORMAT(CAST(cost AS UNSIGNED), 0) FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1),
                        (SELECT FORMAT(CAST(unit_price AS UNSIGNED), 0) FROM vehicle_purchasing_cost WHERE vehicle_purchasing_cost.vehicles_id = vehicles.id LIMIT 1),
                        ''
                    ) as costprice,
                    (SELECT netsuite_link FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1) as netsuite_link
                    ")

                ])
                    ->leftJoin('w_o_vehicles', 'vehicles.id', '=', 'w_o_vehicles.vehicle_id')
                    ->leftJoin('work_orders', 'w_o_vehicles.work_order_id', '=', 'work_orders.id')
                    ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                    ->leftJoin('countries', 'purchasing_order.fd', '=', 'countries.id')
                    ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                    // ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                    ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                    ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                    ->leftJoin('gdn', 'vehicles.gdn_id', '=', 'gdn.id')
                    ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                    ->leftJoin('users', 'so.sales_person_id', '=', 'users.id')
                    ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                    ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                    ->leftJoin('inspection as inspection_grn', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_grn.vehicle_id')
                            ->where('inspection_grn.stage', '=', 'GRN');
                    })
                    ->leftJoin('documents', 'documents.id', '=', 'vehicles.documents_id')
                    ->leftJoin('inspection as inspection_pdi', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_pdi.vehicle_id')
                            ->where('inspection_pdi.stage', '=', 'PDI');
                    })
                    ->whereNotNull('vehicles.inspection_date')
                    ->whereNotNull('vehicles.gdn_id')
                    ->whereNotNull('vehicles.movement_grn_id')
                    ->where('vehicles.status', 'Approved');
                foreach ($filters as $columnName => $values) {
                    if (in_array('__NULL__', $values)) {
                        // info($columnName);
                        $data->whereNull($columnName); // Filter for NULL values
                    } elseif (in_array('__Not EMPTY__', $values)) {
                        $data->whereNotNull($columnName); // Filter for non-empty values
                    } else {
                        $data->whereIn($columnName, $values); // Regular filtering for selected values
                    }
                }
                $data = $data->groupBy('vehicles.id');
            }
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('vehicles.delivered', ['salesperson' => $sales_persons]);
    }
    public function dpvehicles(Request $request)
    {
        $useractivities = new UserActivities();
        $useractivities->activity = "View the Stock Status Wise";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        // Variant detail computation
        $sales_persons = ModelHasRoles::join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('users.status', 'active')
            ->where(function ($query) {
                $query->where('model_has_roles.role_id', 7)
                    ->orWhere('model_has_roles.model_id', 17);
            })
            ->orderBy('users.name', 'asc')
            ->get();
        $variants = Varaint::with(['variantItems.model_specification', 'variantItems.model_specification_option'])
            ->orderBy('id', 'DESC')
            ->whereNot('category', 'Modified')
            ->get();
        $sequence = ['COO', 'SFX', 'Wheels', 'Seat Upholstery', 'HeadLamp Type', 'infotainment type', 'Speedometer Infotainment Type', 'Speakers', 'sunroof'];
        $normalizationMap = [
            'COO' => 'COO',
            'SFX' => 'SFX',
            'Wheels' => ['wheel', 'Wheel', 'Wheels', 'Wheel type', 'wheel type', 'Wheel size', 'wheel size'],
            'Seat Upholstery' => ['Upholstery', 'Seat', 'seats', 'Seat Upholstery'],
            'HeadLamp Type' => 'HeadLamp Type',
            'infotainment type' => 'infotainment type',
            'Speedometer Infotainment Type' => 'Speedometer Infotainment Type',
            'Speakers' => 'Speakers',
            'sunroof' => 'sunroof'
        ];
        foreach ($variants as $variant) {
            if ($variant->category != 'Modified') {
                $details = [];
                $otherDetails = [];
                foreach ($variant->variantItems as $item) {
                    $modelSpecification = $item->model_specification;
                    $modelSpecificationOption = $item->model_specification_option;
                    if ($modelSpecification && $modelSpecificationOption) {
                        $name = $modelSpecification->name;
                        $optionName = $modelSpecificationOption->name;
                        $normalized = null;
                        foreach ($normalizationMap as $key => $values) {
                            if (is_array($values)) {
                                if (in_array($name, $values)) {
                                    $normalized = $key;
                                    break;
                                }
                            } elseif ($name === $values) {
                                $normalized = $key;
                                break;
                            }
                        }

                        if ($normalized) {
                            $name = $normalized;
                        }
                        if (in_array(strtolower($optionName), ['yes', 'no'])) {
                            if (strtolower($optionName) === 'yes') {
                                $optionName = $name;
                            } else {
                                continue;
                            }
                        }
                        if (in_array($name, $sequence)) {
                            $index = array_search($name, $sequence);
                            $details[$index] = $optionName;
                        } else {
                            $otherDetails[] = $optionName;
                        }
                    }
                }
                ksort($details);
                $variant->detail = implode(', ', array_merge($details, $otherDetails));
                $variant->save();
            }
        }
        if ($request->ajax()) {
            $status = $request->input('status');
            $filters = $request->input('filters', []);
            if ($status === "dpvehicles") {
                $data = Vehicles::select([
                    'vehicles.id',
                    'vehicles.movement_grn_id',
                    'vehicles.gdn_id',
                    'vehicles.estimation_date',
                    'vehicles.sales_remarks',
                    'vehicles.gp',
                    'vehicles.territory',
                    'vehicles.ownership_type',
                    'vehicles.inspection_date',
                    'vehicles.custom_inspection_number',
                    'vehicles.inspection_status',
                    'vehicles.so_id',
                    'vehicles.reservation_end_date',
                    'warehouse.name as location',
                    'purchasing_order.po_date',
                    'vehicles.ppmmyyy',
                    'vehicles.vin as vin',
                    'vehicles.minimum_commission',
                    'inspection_grn.id as grn_inspectionid',
                    'inspection_pdi.id as pdi_inspectionid',
                    'vehicles.engine',
                    'vehicles.price',
                    'countries.name as fd',
                    'brands.brand_name',
                    'varaints.name as variant',
                    'varaints.detail as variant_detail',
                    'varaints.id as variant_id',
                    'varaints.model_detail',
                    'varaints.detail',
                    'varaints.seat',
                    'varaints.upholestry',
                    'varaints.steering',
                    'varaints.my',
                    'varaints.fuel_type',
                    'documents.import_type',
                    'documents.owership',
                    'documents.document_with',
                    'varaints.gearbox',
                    'so.so_number',
                    'master_model_lines.model_line',
                    'int_color.name as interior_color',
                    'ex_color.name as exterior_color',
                    'purchasing_order.po_number',
                    'movement_grns.grn_number',
                    'gdn.gdn_number',
                    'users.name',
                    DB::raw("(SELECT COUNT(*) FROM stock_message WHERE stock_message.vehicle_id = vehicles.id) as message_count"),
                    'so.so_date',
                    'movements_reference.date',
                    'gdn.date as gdndate',
                    DB::raw("DATE_FORMAT(work_orders.date, '%Y-%m-%d') as work_order_date"),
                    DB::raw("COALESCE(
                        (SELECT FORMAT(CAST(cost AS UNSIGNED), 0) FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1),
                        (SELECT FORMAT(CAST(unit_price AS UNSIGNED), 0) FROM vehicle_purchasing_cost WHERE vehicle_purchasing_cost.vehicles_id = vehicles.id LIMIT 1),
                        ''
                    ) as costprice,
                    (SELECT netsuite_link FROM vehicle_netsuite_cost WHERE vehicle_netsuite_cost.vehicles_id = vehicles.id LIMIT 1) as netsuite_link
                    ")

                ])
                    ->leftJoin('w_o_vehicles', 'vehicles.id', '=', 'w_o_vehicles.vehicle_id')
                    ->leftJoin('work_orders', 'w_o_vehicles.work_order_id', '=', 'work_orders.id')
                    ->leftJoin('purchasing_order', 'vehicles.purchasing_order_id', '=', 'purchasing_order.id')
                    ->leftJoin('countries', 'purchasing_order.fd', '=', 'countries.id')
                    ->leftJoin('warehouse', 'vehicles.latest_location', '=', 'warehouse.id')
                    // ->leftJoin('grn', 'vehicles.grn_id', '=', 'grn.id')
                    ->leftJoin('movement_grns', 'vehicles.movement_grn_id', '=', 'movement_grns.id')
                    ->leftJoin('movements_reference', 'movement_grns.movement_reference_id', '=', 'movements_reference.id')
                    ->leftJoin('gdn', 'vehicles.gdn_id', '=', 'gdn.id')
                    ->leftJoin('so', 'vehicles.so_id', '=', 'so.id')
                    ->leftJoin('users', 'so.sales_person_id', '=', 'users.id')
                    ->leftJoin('color_codes as int_color', 'vehicles.int_colour', '=', 'int_color.id')
                    ->leftJoin('color_codes as ex_color', 'vehicles.ex_colour', '=', 'ex_color.id')
                    ->leftJoin('varaints', 'vehicles.varaints_id', '=', 'varaints.id')
                    ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                    ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                    ->leftJoin('inspection as inspection_grn', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_grn.vehicle_id')
                            ->where('inspection_grn.stage', '=', 'GRN');
                    })
                    ->leftJoin('inspection as inspection_pdi', function ($join) {
                        $join->on('vehicles.id', '=', 'inspection_pdi.vehicle_id')
                            ->where('inspection_pdi.stage', '=', 'PDI');
                    })
                    ->leftJoin('documents', 'documents.id', '=', 'vehicles.documents_id')
                    ->where('vehicles.status', 'Approved')
                    ->where('purchasing_order.is_demand_planning_po', '=', '1');
                foreach ($filters as $columnName => $values) {
                    if (in_array('__NULL__', $values)) {
                        // info($columnName);
                        $data->whereNull($columnName); // Filter for NULL values
                    } elseif (in_array('__Not EMPTY__', $values)) {
                        $data->whereNotNull($columnName); // Filter for non-empty values
                    } else {
                        $data->whereIn($columnName, $values); // Regular filtering for selected values
                    }
                }
                $data = $data->groupBy('vehicles.id');
            }
            if ($data) {
                return DataTables::of($data)->toJson();
            }
        }
        return view('vehicles.demandplaninigstock', ['salesperson' => $sales_persons]);
    }
    public function savesalesremarks(Request $request)
    {
        $vehicle_id = $request->input('vehicle_remarks_id');
        $salesremarks = $request->input('salesremarks');
        $vehicle = vehicles::find($vehicle_id);
        if ($vehicle) {
            $vehicle->sales_remarks = $salesremarks;
            $vehicle->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Vehicle not found']);
        }
    }
    public function getsalesremarks(Request $request)
    {
        // Find the vehicle by ID and fetch its custom inspection details
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId); // Assume you have a Vehicle model
        if ($vehicle) {
            // Return the custom inspection number and status
            return response()->json([
                'sales_remarks' => $vehicle->sales_remarks, // Replace with the actual column
            ]);
        } else {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }
    public function getonwershipData(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $vehicle = Vehicles::find($vehicleId);
        if ($vehicle) {
            return response()->json([
                'ownership_type' => $vehicle->ownership_type,
            ]);
        } else {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }
    public function saveonwership(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'documentonwership' => 'required|string',
        ]);
        $vehicle = Vehicles::findOrFail($request->input('vehicle_id'));
        $vehicle->ownership_type = $request->input('documentonwership');
        $vehicle->save();
        return redirect()->route('vehicles.statuswise', ['status' => 'allstock']);
    }
    public function customdocumentstatusupdate(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'document_status' => 'required|in:On Hold,Released,N/A',
        ]);
        $vehicle = Vehicles::findOrFail($request->input('vehicle_id'));
        $vehicle->vehicle_document_status = $request->input('document_status');
        $vehicle->save();
        return redirect()->route('vehicles.statuswise', ['status' => 'allstock']);
    }
    public function checkVehicleQuantity(Request $request)
    {
        $quotationId = $request->input('quotation_id');
        $so = So::where('quotation_id', $quotationId)->first();
        $additionalData = $request->input('additional_data');
        $variant = Varaint::where('name', $additionalData)->first();
        if ($so) {
            $gdnCount = Vehicles::where('so_id', $so->id)->where('varaints_id', $variant->id)->whereNotNull('gdn_id')->count();
            // info($gdnCount);
            if ($gdnCount) {
                return response()->json([
                    'exists' => true,
                    'gdn_count' => $gdnCount
                ]);
            }
        }
        return response()->json(['exists' => false]);
    }
    public function Grnlist(Request $request)
    {

        $grns = Movement::select('id', 'vin', 'reference_id', 'movement_grn_id')
            ->with(['movementGrn:id,grn_number'])
            ->whereHas('MovementGrn', function ($query) {
                $query->select('id', 'grn_number')->whereNotNull('grn_number');
            })->get();

        return view('grn_list.index', compact('grns'));
    }

    public function updateEstimationDate(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'estimation_date' => 'required|date',
        ]);

        try {
            $vehicle = Vehicles::findOrFail($request->input('vehicle_id'));
            $vehicle->estimation_date = $request->input('estimation_date');
            $vehicle->save();

            // Log the change
            Vehicleslog::create([
                'vehicles_id' => $vehicle->id,
                'field' => 'estimation_date',
                'old_value' => $vehicle->getOriginal('estimation_date'),
                'status' => 'Updated',
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update estimation date']);
        }
    }
}
