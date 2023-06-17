<?php

use App\Http\Controllers\SalesPersonLanguagesController;
use App\Http\Controllers\VariantController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DailyleadsController;
use App\Http\Controllers\CallsController;
use App\Http\Controllers\LetterOfIndentController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\AddonController;
use App\Http\Controllers\BLformController;
use App\Http\Controllers\DemandListController;
use App\Http\Controllers\MonthlyDemandsController;
use App\Http\Controllers\SupplierInventoryController;
use App\Http\Controllers\VariatnsPicturesController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\HiringController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LOIItemsController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\LOIDocumentsController;
use App\Http\Controllers\Repeatedcustomers;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierAddonController;
use App\Http\Controllers\PFIController;
use App\Http\Controllers\DemandPlanningSupplierController;
use App\Http\Controllers\VehiclePicturesController;
use App\Http\Controllers\PurchasingOrderController;
use App\Http\Controllers\Movement;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\WarrantyController;

/*
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/d', function () {
    return view('addon.ff');
});
    Auth::routes();
    Route::group(['middleware' => ['auth','checkstatus']], function() {
    // Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('home');
    //Profile
    Route::resource('profile', ProfileController::class);
    Route::post('/update-login-info', [ProfileController::class, 'updateLoginInfo'])->name('profile.updateLoginInfo');
    Route::post('/update-email-info', [ProfileController::class, 'updateEmailInfo'])->name('profile.updateEmailInfo');
    Route::post('/update-picture-info', [ProfileController::class, 'updatepictureInfo'])->name('profile.updatepictureInfo');
    Route::delete('/update-delete-document/{id}', [ProfileController::class, 'deleteDocument'])->name('profile.deleteDocument');
    Route::delete('/update-skill-document/{id}', [ProfileController::class, 'skillDocument'])->name('profile.skillDocument');
    Route::delete('/update-history-delete/{id}', [ProfileController::class, 'historydelete'])->name('profile.historydelete');
    Route::post('/update-save-document', [ProfileController::class, 'saveDocument'])->name('profile.saveDocument');
    Route::post('/update-save-skill', [ProfileController::class, 'saveskill'])->name('profile.saveskill');
    Route::post('/update-history-info', [ProfileController::class, 'updatehistoryInfo'])->name('profile.updatehistoryInfo');
    // User
    Route::resource('users', UserController::class);
    Route::get('users/updateStatus/{id}', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::get('users/makeActive/{id}', [UserController::class, 'makeActive'])->name('users.makeActive');
    Route::get('users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::get('users/destroy/{id}', [UserController::class,'delete'])->name('users.delete');
    // Role
    Route::resource('roles', RoleController::class);
    Route::get('roles/destroy/{id}', [RoleController::class,'delete'])->name('roles.delete');
    // Addon
    Route::resource('addon', AddonController::class);
    Route::get('addons/details/edit/{id}', [AddonController::class,'editAddonDetails'])->name('addon.editDetails');
    Route::post('addons/details/update/{id}', [AddonController::class, 'updateAddonDetails'])->name('addon.updatedetails');
    Route::get('addons/existingImage/{id}', [AddonController::class, 'existingImage'])->name('addon.existingImage');
    Route::post('addonFilters', [AddonController::class, 'addonFilters'])->name('addon.addonFilters');
    Route::post('createMasterAddon', [AddonController::class, 'createMasterAddon'])->name('addon.createMasterAddon');
    Route::post('getAddonCodeAndDropdown', [AddonController::class, 'getAddonCodeAndDropdown'])->name('addon.getAddonCodeAndDropdown');
    Route::get('viewAddon/{id}', [AddonController::class, 'addonView'])->name('addon.view');
    Route::get('addons/brandModels/{id}', [AddonController::class, 'brandModels'])->name('addon.brandModels');
    Route::get('addons/{data}', [AddonController::class,'index'])->name('addon.list');
    Route::post('getModelDescriptionDropdown', [AddonController::class, 'getModelDescriptionDropdown'])->name('addon.getModelDescriptionDropdown');


    Route::get('get_student_data', [SupplierAddonController::class,'get_student_data'])->name('addon.get_student_data');
    Route::resource('student', SupplierAddonController::class);

    // Warranty
    Route::resource('warranty', WarrantyController::class);
    Route::post('getBranchForWarranty', [WarrantyController::class, 'getBranchForWarranty'])->name('addon.getBranchForWarranty');
    Route::post('warranty/details/update', [WarrantyController::class, 'updateWarranty'])->name('warranty.updateWarranty');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::post('supplierAddonExcelValidation', [SupplierController::class, 'supplierAddonExcelValidation'])->name('addon.supplierAddonExcelValidation');
    Route::get('suppliers/destroy/{id}', [SupplierController::class,'delete'])->name('suppliers.delete');
    Route::get('suppliers/makeActive/{id}', [SupplierController::class, 'makeActive'])->name('suppliers.makeActive');
    Route::get('suppliers/updateStatus/{id}', [SupplierController::class, 'updateStatus'])->name('suppliers.updateStatus');
    Route::post('suppliers/details/update', [SupplierController::class, 'updateDetails'])->name('suppliers.updatedetails');
    Route::get('supplier/addon/price/{id}', [SupplierController::class, 'addonprice'])->name('suppliers.addonprice');
    Route::post('createNewSupplierAddonPrice', [SupplierController::class, 'createNewSupplierAddonPrice'])->name('addon.createNewSupplierAddonPrice');
    // Demand & Planning Module

    // suppliers
    Route::resource('demand-planning-suppliers', DemandPlanningSupplierController::class);

    // Demands
    Route::get('demand-planning/get-sfx', [DemandController::class,'getSFX'])->name('demand.get-sfx');
    Route::get('demand-planning/get-variant', [DemandController::class,'getVariant'])->name('demand.get-variant');
    Route::resource('demands', DemandController::class);
    Route::resource('demand-lists', DemandListController::class);
    Route::resource('monthly-demands', MonthlyDemandsController::class);
    // Letter of Indent
    Route::get('letter-of-indents/get-customers', [LetterOfIndentController::class, 'getCustomers'])->name('letter-of-indents.get-customers');
    Route::get('letter-of-indents/generateLOI', [LetterOfIndentController::class, 'generateLOI'])->name('letter-of-indents.generate-loi');
    Route::post('letter-of-indents/status-change', [LetterOfIndentController::class, 'approve'])->name('letter-of-indents.status-change');
    Route::get('letter-of-indents/suppliers-LOIs', [LetterOfIndentController::class, 'getSupplierLOI'])->name('letter-of-indents.get-suppliers-LOIs');
    Route::get('letter-of-indents/milele-approval', [LOIItemsController::class, 'mileleApproval'])->name('letter-of-indents.milele-approval');

    Route::resource('letter-of-indent-documents', LOIDocumentsController::class);
    Route::resource('letter-of-indents', LetterOfIndentController::class);
    Route::resource('letter-of-indent-items', LOIItemsController::class);
    Route::post('letter-of-indent-item/approve', [LOIItemsController::class, 'approveLOIItem'])->name('approve-loi-items');

    // PFI
    Route::resource('pfi', PFIController::class);
    Route::get('add-pfi', [PFIController::class,'addPFI'])->name('add_pfi');

    // Supplier Inventories
    Route::resource('supplier-inventories', SupplierInventoryController::class)->except('show');
    Route::get('supplier-inventories/lists', [SupplierInventoryController::class,'lists'])->name('supplier-inventories.lists');
    Route::get('supplier-inventories/file-comparision', [SupplierInventoryController::class,'FileComparision'])->name('supplier-inventories.file-comparision');
    Route::get('supplier-inventories/file-comparision-report', [SupplierInventoryController::class,'FileComparisionReport'])
        ->name('supplier-inventories.file-comparision-report');
    Route::get('supplier-inventories/get-dates', [SupplierInventoryController::class,'getDate'])->name('supplier-inventories.get-dates');

    //BL Module
    Route::resource('blform', BlFormController::class);
    Route::post('store-data', [BlFormController::class, 'storeData'])->name('store.data');

    //Marketing
    Route::resource('calls', CallsController::class)
    ->parameters(['calls' => 'call']);
    Route::resource('sales_person_languages', SalesPersonLanguagesController::class);
    Route::resource('variant_pictures', VariatnsPicturesController::class);
    Route::get('/editreels/{id}', [VariatnsPicturesController::class, 'editreels'])->name('variant_pictures.editreels');
    Route::post('/uploadingreal', [VariatnsPicturesController::class, 'uploadingreal'])->name('variant_pictures.uploadingreal');
    Route::get('/editreels/videos/{filename}', function ($filename) {
        $path = storage_path('app/public/videos/' . $filename);
        if (file_exists($path)) {
            return response()->file($path);
        }
        abort(404);
    });
    Route::get('calls-get/getmodelline', [CallsController::class,'getmodelline'])->name('calls.get-modellines');
    Route::delete('/delete-video/{id}', [VariatnsPicturesController::class, 'deleteVideo'])->name('delete_video');
    Route::delete('/delete-reel/{id}', [VariatnsPicturesController::class, 'deleteReel'])->name('delete_reel');
    Route::resource('lead_source', LeadSourceController::class);
    Route::get('calls-bulk/createbulk', [CallsController::class,'createbulk'])->name('calls.createbulk');
    Route::post('/uploadingbulk', [CallsController::class, 'uploadingbulk'])->name('calls.uploadingbulk');
    Route::resource('strategy', StrategyController::class);
    Route::post('calls/check-existence', [CallsController::class, 'checkExistence'])->name('checkExistence');
    Route::get('customers/repeatedcustomers', [Repeatedcustomers::class, 'repeatedcustomers'])->name('repeatedcustomers');
    Route::put('/strategy-updates/{id}', [StrategyController::class, 'updaters'])->name('strategy.updaters');
    Route::get('/simplefile', [CallsController::class,'simplefile'])->name('calls.simplefile');
    Route::delete('/calls/{id}', [CallsController::class, 'destroy'])->name('calls.destroy');
    Route::post('/calls/removerow', [CallsController::class, 'removeRow'])->name('calls.removerow');
    Route::post('/calls/updaterow', [CallsController::class, 'updaterow'])->name('calls.updaterow');
    Route::post('/calls/updatehol', [CallsController::class, 'updatehol'])->name('calls.updatehol');
    Route::get('new-variants/createnewvarinats', [CallsController::class,'createnewvarinats'])->name('calls.createnewvarinats');
    Route::get('new-variants/varinatinfo', [CallsController::class, 'varinatinfo'])->name('calls.varinatinfo');
    Route::post('new-variants/storenewvarinats', [CallsController::class, 'storenewvarinats'])->name('calls.storenewvarinats');
    //Sales
    Route::resource('dailyleads', DailyleadsController::class);
    Route::get('quotation-data/get-my', [QuotationController::class,'getmy'])->name('quotation.get-my');
    Route::get('quotation-data/get-model-line', [QuotationController::class,'getmodelline'])->name('quotation.get-model-line');
    Route::get('quotation-data/get-sub-model', [QuotationController::class,'getsubmodel'])->name('quotation.get-sub-model');
    Route::resource('quotation', QuotationController::class);
    Route::post('quotation-data/vehicles-insert', [QuotationController::class,'addvehicles'])->name('quotation.vehicles-insert');
    Route::get('/get-vehicle-count/{userId}', function($userId) {
    $count = DB::table('vehiclescarts')->where('created_by', $userId)->count();
    return $count;
    });

    // vehicle pictures
     Route::get('vehicle-pictures/variant-details', [VehiclePicturesController::class,'getVariantDetail'])->name('vehicle-pictures.variant-details');
     Route::resource('vehicle-pictures', VehiclePicturesController::class);

     // Variants
    Route::resource('variants', VariantController::class);

    Route::get('/remove-vehicle/{id}', [QuotationController::class, 'removeVehicle'])->name('quotation.removeVehicle');
    // Route::get('/fetch-addon-data/{id}/{quotationId}/{VehiclesId}', [AddonController::class, 'fetchAddonData'])->name('fetch-addon-data');
    Route::post('quotation-data/addone-insert', [QuotationController::class,'addqaddone'])->name('quotation.addone-insert');
    // Route::get('/modal-data/{id}/{quotationId}/{VehiclesId}', [AddonController::class, 'fetchAddonData']);
    Route::get('/modal-data/{id}', [AddonController::class, 'fetchAddonData'])->name('modal.show');
    Route::name('calls.show')
    ->get('calls/{call}/{brand_id}/{model_line_id}/{location}/{days}/{custom_brand_model?}', [CallsController::class, 'show'])
    ->where([
        'call' => '[0-9]+',
        'brand_id' => '[0-9]+',
        'model_line_id' => '[0-9]+',
    ]);
    Route::post('dailyleads/processStep', [DailyleadsController::class, 'processStep'])->name('processStep');
    Route::get('dailyleads/prospecting/{id}', [DailyleadsController::class, 'prospecting'])->name('dailyleads.prospecting');

    Route::post('/update-qoutation-info', [DailyleadsController::class, 'qoutations'])->name('sales.qoutations');
    Route::post('/update-rejection-info', [DailyleadsController::class, 'rejection'])->name('sales.rejection');
    Route::post('/update-closed-info', [DailyleadsController::class, 'closed'])->name('sales.closed');

    // HR
    Route::resource('hiring', HiringController::class);
    // Route::POST('hiring', [HiringController::class, 'jobStore'])->name('jobStore');
    // Route::POST('hiring', [HiringController::class, 'jobUpdate'])->name('jobUpdate');

    //WareHouse
    Route::resource('purchasing-order', PurchasingOrderController::class);
    Route::resource('Vehicles', VehiclesController::class);
    Route::post('purchasing-order/check-po-number', [PurchasingOrderController::class, 'checkPONumber'])->name('purchasing-order.checkPONumber');
    Route::post('update-data/vehicles', [VehiclesController::class, 'updatevehiclesdata'])->name('vehicles.updatevehiclesdata');
    Route::post('fatch-data/variants', [VehiclesController::class, 'fatchvariantdetails'])->name('vehicles.fatchvariantdetails');
    Route::resource('movements', Movement::class);
});
