<?php

use App\Http\Controllers\SalesPersonLanguagesController;
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
    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Letter of Indent
    Route::get('letter-of-indents/get-customers', [LetterOfIndentController::class, 'getCustomers'])->name('letter-of-indents.get-customers');
    Route::get('letter-of-indents/generateLOI', [LetterOfIndentController::class, 'generateLOI'])->name('letter-of-indents.generate-loi');
    Route::post('letter-of-indents/status-change', [LetterOfIndentController::class, 'approve'])->name('letter-of-indents.status-change');
    Route::get('letter-of-indents/suppliers-LOIs', [LetterOfIndentController::class, 'getSupplierLOI'])->name('letter-of-indents.get-suppliers-LOIs');

    Route::resource('letter-of-indent-documents', LOIDocumentsController::class);
    Route::resource('letter-of-indents', LetterOfIndentController::class);
    Route::resource('letter-of-indent-items', LOIItemsController::class);

    // Demand & Planning
    Route::get('demand-planning/get-sfx', [DemandController::class,'getSFX'])->name('demand.get-sfx');
    Route::get('demand-planning/get-variant', [DemandController::class,'getVariant'])->name('demand.get-variant');
    Route::resource('demands', DemandController::class);
    Route::resource('demand-lists', DemandListController::class);
    Route::resource('monthly-demands', MonthlyDemandsController::class);

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


    Route::delete('/calls/{id}', [CallsController::class, 'destroy'])->name('calls.destroy');
    Route::post('/calls/removerow', [CallsController::class, 'removeRow'])->name('calls.removerow');
    Route::post('/calls/updaterow', [CallsController::class, 'updaterow'])->name('calls.updaterow');
    Route::post('/calls/updatehol', [CallsController::class, 'updatehol'])->name('calls.updatehol');
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

    // HR
    Route::resource('hiring', HiringController::class);
    // Route::POST('hiring', [HiringController::class, 'jobStore'])->name('jobStore');
    // Route::POST('hiring', [HiringController::class, 'jobUpdate'])->name('jobUpdate');
});
