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
// Route::get('/', function () {
//     return view('welcome');
// });
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
    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Letter of Indent
    Route::resource('letter-of-indents', LetterOfIndentController::class);

    // Demand & Planning
    Route::get('demand-planning/get-sfx', [DemandController::class,'getSFX'])->name('demand.get-sfx');
    Route::get('demand-planning/get-variant', [DemandController::class,'getVariant'])->name('demand.get-variant');
    Route::resource('demands', DemandController::class);
    Route::resource('demand-lists', DemandListController::class);
    Route::resource('monthly-demands', MonthlyDemandsController::class);

    // Supplier Inventories
    Route::resource('supplier-inventories', SupplierInventoryController::class);

    //BL Module
    Route::resource('blform', BlFormController::class);
    Route::post('store-data', [BlFormController::class, 'storeData'])->name('store.data');

    //Marketing
    Route::resource('calls', CallsController::class);
    Route::resource('sales_person_languages', SalesPersonLanguagesController::class);
    Route::resource('variant_pictures', VariatnsPicturesController::class);

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
});
