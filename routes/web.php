<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\MasterAddonController;
use App\Http\Controllers\ModelLinesController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SalesPersonLanguagesController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\VariantPriceController;
use App\Http\Controllers\VehiclePendingApprovalRequestController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorDocumentController;
use App\Http\Controllers\WarrantyBrandsController;
use App\Http\Controllers\WarrantyPriceHistoriesController;
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
use App\Http\Controllers\MovementController;
use App\Http\Controllers\VehiclesController;
use App\Http\Controllers\WarrantyController;
use App\Http\Controllers\ColorCodesController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\KitCommonItemController;
use App\Http\Controllers\ProspectingController;


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
Route::get('/react-page', function () {
    return view('react-app.index');
});
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
    Route::get('getAddonDescription', [AddonController::class, 'getAddonDescription'])->name('addon.getAddonDescription');
    Route::get('getUniqueAccessories', [AddonController::class, 'getUniqueAccessories'])->name('addon.getUniqueAccessories');
    Route::get('getUniqueSpareParts', [AddonController::class, 'getUniqueSpareParts'])->name('addon.getUniqueSpareParts');
    Route::get('getUniqueAddonDescription', [AddonController::class, 'getUniqueAddonDescription'])->name('addon.getUniqueAddonDescription');
    Route::get('getUniqueKits', [AddonController::class, 'getUniqueKits'])->name('addon.getUniqueKits');

    Route::post('getAddonCodeAndDropdown', [AddonController::class, 'getAddonCodeAndDropdown'])->name('addon.getAddonCodeAndDropdown');
    Route::get('addons/brandModels/{id}', [AddonController::class, 'brandModels'])->name('addon.brandModels');
    Route::get('addons/{data}', [AddonController::class,'index'])->name('addon.list');
    Route::post('getModelDescriptionDropdown', [AddonController::class, 'getModelDescriptionDropdown'])->name('addon.getModelDescriptionDropdown');
    Route::get('addon/kitItems/{id}', [AddonController::class, 'kitItems'])->name('addon.kitItems');
    Route::post('addon-selling-price/status-change', [AddonController::class, 'statusChange'])->name('addon-selling-price.status-change');
    Route::post('update-addon-selling-price/{id}', [AddonController::class, 'updateSellingPrice'])->name('addon.UpdateSellingPrice');
    Route::post('getSupplierForAddon', [AddonController::class, 'getSupplierForAddon'])->name('get-addon-supplier');
    Route::get('supplier-change-addon-type', [AddonController::class, 'getSupplierForAddonType']);
    Route::post('createSellingPrice/{id}', [AddonController::class, 'createSellingPrice'])->name('addon.createSellingPrice');
    Route::post('addon/status-change', [AddonController::class, 'addonStatusChange'])->name('addon.status-change');
    Route::post('getKitItemsForAddon', [AddonController::class, 'getKitItemsForAddon']);
    Route::get('get_student_data', [SupplierAddonController::class,'get_student_data'])->name('addon.get_student_data');
    Route::resource('student', SupplierAddonController::class);

    // Kit
    Route::resource('kit', KitCommonItemController::class);
    Route::get('kit-suppliers/{id}', [KitCommonItemController::class,'kitSuppliers'])->name('kit.suppliers');
    Route::get('kit-edit-suppliers/{id}', [KitCommonItemController::class,'editKitSuppliers'])->name('kit.editsuppliers');
    Route::get('kits/details/edit/{id}', [KitCommonItemController::class,'editAddonDetails'])->name('kit.editDetails');
    Route::post('kit/suppliers/update/{id}', [KitCommonItemController::class, 'updateKitSupplier'])->name('kit.updateKitSupplier');
    Route::get('kit/kitItems/{id}', [KitCommonItemController::class, 'kitItems'])->name('kit.kitItems');
    Route::get('getCommonKitItems', [KitCommonItemController::class, 'getCommonKitItems'])->name('getCommonKitItems');
    Route::post('kit/priceStore', [KitCommonItemController::class, 'priceStore'])->name('kit.priceStore');
    Route::get('getPartNumbers', [KitCommonItemController::class, 'getPartNumbers'])->name('getPartNumbers');
    Route::post('addon/spare-part/price-update', [AddonController::class, 'addNewPurchasePrice'])->name('spare-part-price-update');


    // Warranty
    Route::resource('warranty', WarrantyController::class);
    Route::resource('warranty-brands', WarrantyBrandsController::class);
    Route::resource('warranty-price-histories', WarrantyPriceHistoriesController::class);
    Route::get('warranty-selling-price-histories', [WarrantyPriceHistoriesController::class, 'listSellingPrices'])
            ->name('warranty-selling-price-histories.index');
    Route::post('warranty-brands/status-change', [WarrantyController::class, 'statusChange'])->name('warranty-brands.status-change');
    Route::post('warranty-brands/update-selling-price', [WarrantyBrandsController::class, 'updateSellingPrice'])
            ->name('warranty-brands.update-selling-price');
    Route::get('warranty-sales-views', [WarrantyController::class, 'view'])->name('warranty.view');
    Route::get('warranty-lists', [WarrantyController::class, 'list'])->name('warranty.list');
    Route::post('getBranchForWarranty', [WarrantyController::class, 'getBranchForWarranty'])->name('addon.getBranchForWarranty');
    Route::get('getBrandForWarranty', [WarrantyController::class, 'getBrandForWarranty'])->name('addon.getBrandForWarranty');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);
    Route::post('supplierAddonExcelValidation', [SupplierController::class, 'supplierAddonExcelValidation'])->name('addon.supplierAddonExcelValidation');
    Route::get('suppliers/destroy/{id}', [SupplierController::class,'delete'])->name('suppliers.delete');
    Route::post('suppliers/updateStatus', [SupplierController::class, 'updateStatus'])->name('suppliers.updateStatus');
    Route::post('suppliers/details/update', [SupplierController::class, 'updateDetails'])->name('suppliers.updatedetails');

    Route::get('supplier/addon/price/{id}', [SupplierController::class, 'addonprice'])->name('suppliers.addonprice');
    Route::post('createNewSupplierAddonPrice', [SupplierController::class, 'createNewSupplierAddonPrice'])->name('addon.createNewSupplierAddonPrice');
    Route::get('supplier/purchasepricehistory/{id}', [SupplierController::class, 'purchasepricehistory'])->name('suppliers.purchasepricehistory');
    Route::post('getAddonForSupplier', [SupplierController::class, 'getAddonForSupplier'])->name('addon.getAddonForSupplier');
    Route::post('getBrandForAddons', [AddonController::class, 'getBrandForAddons'])->name('addon.getBrandForAddons');
    Route::post('getModelLinesForAddons', [AddonController::class, 'getModelLinesForAddons']);

    Route::post('newSellingPriceRequest', [SupplierController::class, 'newSellingPriceRequest'])->name('addon.newSellingPriceRequest');
    Route::get('sellingPriceHistory/{id}', [SupplierController::class, 'sellingPriceHistory'])->name('suppliers.sellingPriceHistory');

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
    Route::get('download/rejected/{filename}', [CallsController::class,'downloadRejected'])->name('download.rejected');
    Route::delete('/delete-video/{id}', [VariatnsPicturesController::class, 'deleteVideo'])->name('delete_video');
    Route::delete('/delete-reel/{id}', [VariatnsPicturesController::class, 'deleteReel'])->name('delete_reel');
    Route::resource('lead_source', LeadSourceController::class);
    Route::get('calls-bulk/createbulk', [CallsController::class,'createbulk'])->name('calls.createbulk');
    Route::post('/uploadingbulk', [CallsController::class, 'uploadingbulk'])->name('calls.uploadingbulk');
    Route::resource('strategy', StrategyController::class);
    Route::post('calls/check-existence', [CallsController::class, 'checkExistence'])->name('checkExistence');
    Route::post('calls/check-checkExistenceupdatecalls', [CallsController::class, 'checkExistenceupdatecalls'])->name('checkExistenceupdatecalls');
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
     Route::post('getVinForVehicle', [VehiclePicturesController::class, 'getVinForVehicle']);


     // Variants
    Route::resource('variants', VariantController::class);
    Route::get('variant-prices/{id}/edit/type/{type}', [VariantPriceController::class,'edit'])->name('variant-price.edit');
    Route::resource('variant-prices', VariantPriceController::class);

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
    Route::get('vehicles/filter', [VehiclesController::class, 'index'])->name('vehicles.filter');
    // Route::get('/search-data', [VehiclesController::class, 'searchData'])->name('vehicles.search-data');
    Route::post('purchasing-order/check-po-number', [PurchasingOrderController::class, 'checkPONumber'])->name('purchasing-order.checkPONumber');
    Route::post('update-data/vehicles', [VehiclesController::class, 'updatevehiclesdata'])->name('vehicles.updatevehiclesdata');
    Route::post('fatch-data/variants', [VehiclesController::class, 'fatchvariantdetails'])->name('vehicles.fatchvariantdetails');
    Route::get('view-details/purchasing-order/{id}', [PurchasingOrderController::class, 'viewdetails'])->name('purchasing-order.viewdetails');
    Route::post('/vehicles/updatedata', [VehiclesController::class, 'updatedata'])->name('vehicles.updatedata');
    Route::resource('movement', MovementController::class);
    Route::get('/last-reference/{currentId}', [MovementController::class, 'lastReference'])->name('movement.lastReference');
    Route::post('movemnet/get-vehicles-details', [MovementController::class, 'vehiclesdetails'])->name('vehicles.vehiclesdetails');
    Route::get('purchasing-order/{id}/delete', [PurchasingOrderController::class, 'deletes'])->name('purchasing-order.deletes');
    Route::post('/vehicles/updateso', [VehiclesController::class, 'updateso'])->name('vehicles.updateso');

    Route::resource('vehicle-detail-approvals', VehiclePendingApprovalRequestController::class);
    Route::post('vehicle-detail/approve', [VehiclePendingApprovalRequestController::class,'ApproveOrRejectVehicleDetails'])
         ->name('vehicle-detail.update');
    Route::get('/vehicles/getVehicleDetails', [VehiclesController::class, 'getVehicleDetails'])->name('vehicles.getVehicleDetails');

    Route::get('vehiclesde/{id}', [VehiclesController::class, 'deletes'])->name('vehiclesde.deletes');
    Route::get('grnlist/netsuitgrn', [MovementController::class, 'grnlist'])->name('grnlist.create');
    Route::get('grnlist/grnsimplefile', [MovementController::class,'grnsimplefile'])->name('grnlist.grnsimplefile');
    Route::post('grnlist/post-file', [MovementController::class, 'grnfilepost'])->name('grnlist.grnfilepost');
    Route::post('/check-create-vins', [PurchasingOrderController::class, 'checkcreatevins'])->name('vehicles.check-create-vins');
    Route::patch('/check-edit-vins', [PurchasingOrderController::class, 'checkeditvins'])->name('vehicles.check-edit-vins');
    Route::patch('/check-edit-create-vins', [PurchasingOrderController::class, 'checkeditcreate'])->name('vehicles.check-edit-create');
    Route::get('users/update-role/{roleId}', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/view-log-details/{id}', [VehiclesController::class, 'viewLogDetails'])->name('vehicleslog.viewdetails');
    Route::resource('colourcode', ColorCodesController::class);
    Route::post('/update-purchasing-data', [PurchasingOrderController::class, 'updatepurchasingData'])->name('purchasing.updateData');
    Route::post('/update-purchasing-status', [PurchasingOrderController::class, 'purchasingupdateStatus'])->name('purchasing.updateStatus');
    Route::resource('warehouse', WarehouseController::class);
    Route::get('vehicles/payment-confirm/{id}', [PurchasingOrderController::class, 'confirmPayment'])->name('vehicles.paymentconfirm');
    Route::get('/vehicles/cancel/{id}', [PurchasingOrderController::class, 'cancel'])->name('vehicles.cancel');
    Route::get('/vehicles/rejecteds/{id}', [PurchasingOrderController::class, 'rejecteds'])->name('vehicles.rejecteds');
    Route::get('/vehicles/unrejecteds/{id}', [PurchasingOrderController::class, 'unrejecteds'])->name('vehicles.unrejecteds');
    Route::get('/vehicles/deletevehicles/{id}', [PurchasingOrderController::class, 'deletevehicles'])->name('vehicles.deletevehicles');
    Route::get('vehicles/paymentintconfirm/{id}', [PurchasingOrderController::class, 'paymentintconfirm'])->name('vehicles.paymentintconfirm');
    Route::get('vehicles/paymentreleaserejected/{id}', [PurchasingOrderController::class, 'paymentreleaserejected'])->name('vehicles.paymentreleaserejected');
    Route::get('vehicles/paymentreleaseconfirm/{id}', [PurchasingOrderController::class, 'paymentreleaseconfirm'])->name('vehicles.paymentreleaseconfirm');
    Route::get('vehicles/paymentrelconfirm/{id}', [PurchasingOrderController::class, 'paymentrelconfirm'])->name('vehicles.paymentrelconfirm');
    Route::get('vehicles/paymentreleasesconfirm/{id}', [PurchasingOrderController::class, 'paymentreleasesconfirm'])->name('vehicles.paymentreleasesconfirm');
    Route::get('vehicles/paymentreleasesrejected/{id}', [PurchasingOrderController::class, 'paymentreleasesrejected'])->name('vehicles.paymentreleasesrejected');
    Route::get('vehicles/paymentrelconfirmdebited/{id}', [PurchasingOrderController::class, 'paymentrelconfirmdebited'])->name('vehicles.paymentrelconfirmdebited');
    Route::post('/update-purchasing-allstatus', [PurchasingOrderController::class, 'purchasingallupdateStatus'])->name('purchasing.updateallStatus');
    Route::get('vehicles/paymentrelconfirmvendors/{id}', [PurchasingOrderController::class, 'paymentrelconfirmvendors'])->name('vehicles.paymentrelconfirmvendors');
    Route::get('vehicles/paymentrelconfirmincoming/{id}', [PurchasingOrderController::class, 'paymentrelconfirmincoming'])->name('vehicles.paymentrelconfirmincoming');

    Route::get('/purcahsing-order-filter/{status}', [PurchasingOrderController::class, 'filter'])->name('purchasing.filter');
    Route::get('/purcahsing-order-filterpayment/{status}', [PurchasingOrderController::class, 'filterpayment'])->name('purchasing.filterpayment');
    Route::get('/purcahsing-order-filterpaymentrel/{status}', [PurchasingOrderController::class, 'filterpaymentrel'])->name('purchasing.filterpaymentrel');
    Route::get('/purcahsing-order-filterintentreq/{status}', [PurchasingOrderController::class, 'filterintentreq'])->name('purchasing.filterintentreq');
    Route::get('/purcahsing-order-filterpendingrelease/{status}', [PurchasingOrderController::class, 'filterpendingrelease'])->name('purchasing.filterpendingrelease');
    Route::get('/purcahsing-order-filterpendingdebits/{status}', [PurchasingOrderController::class, 'filterpendingdebits'])->name('purchasing.filterpendingdebits');
    Route::get('/purcahsing-order-filterpendingfellow/{status}', [PurchasingOrderController::class, 'filterpendingfellow'])->name('purchasing.filterpendingfellow');
    Route::post('/update-purchasing-allstatusrel', [PurchasingOrderController::class, 'purchasingallupdateStatusrel'])->name('purchasing.updateallStatusrel');
    Route::post('/update-purchasing-allpaymentreqss', [PurchasingOrderController::class, 'allpaymentreqss'])->name('purchasing.allpaymentreqss');
    Route::post('/update-purchasing-allpaymentreqssfin', [PurchasingOrderController::class, 'allpaymentreqssfin'])->name('purchasing.allpaymentreqssfin');
    Route::post('/update-purchasing-allpaymentreqssfinpay', [PurchasingOrderController::class, 'allpaymentreqssfinpay'])->name('purchasing.allpaymentreqssfinpay');
    Route::post('/update-purchasing-allpaymentreqssfinpaycomp', [PurchasingOrderController::class, 'allpaymentreqssfinpaycomp'])->name('purchasing.allpaymentreqssfinpaycomp');
    Route::post('/update-purchasing-allpaymentintreqpocomp', [PurchasingOrderController::class, 'allpaymentintreqpocomp'])->name('purchasing.allpaymentintreqpocomp');
    Route::post('/update-purchasing-allpaymentintreqpocompin', [PurchasingOrderController::class, 'allpaymentintreqpocompin'])->name('purchasing.allpaymentintreqpocompin');
    Route::get('/purcahsing-order-filterapproved/{status}', [PurchasingOrderController::class, 'filterapproved'])->name('purchasing.filterapproved');
    Route::get('/purcahsing-order-filterapprovedonly/{status}', [PurchasingOrderController::class, 'filterapprovedonly'])->name('purchasing.filterapprovedonly');
    Route::get('/purcahsing-order-filterincomings/{status}', [PurchasingOrderController::class, 'filterincomings'])->name('purchasing.filterincomings');
    Route::get('/vehicleinspectionapprovals', [VehiclesController::class, 'pendingapprovals'])->name('vehicleinspectionapprovals.pendingapprovals');
    Route::get('/vehicleinspectionpending', [VehiclesController::class, 'pendinginspection'])->name('vehicleinspectionpending.pendinginspection');
    Route::get('/vehicleincomingstock', [VehiclesController::class, 'incomingstocks'])->name('vehiclesincoming.stock');
    Route::get('/get-model-lines/{brandId}', [VehiclesController::class, 'getModelLines']);
    Route::get('/get-vehicles-data-for-movement', [MovementController::class, 'getVehiclesDataformovement'])->name('vehicles.getVehiclesDataformovement');
    Route::get('/vehicleincomingpendingpdis', [VehiclesController::class, 'incomingpendingpdis'])->name('vehiclesincoming.pendingpdis');
    Route::post('/checkEntry', [VehiclesController::class, 'checkEntry'])->name('vehicles.checkEntry');
    Route::get('/vehiclesoldvehss', [VehiclesController::class, 'soldvehss'])->name('vehiclesincoming.soldvehss');
    Route::get('/vehicleavalibless', [VehiclesController::class, 'avalibless'])->name('vehiclesincoming.avalibless');
    Route::get('/vehiclependinggrnnetsuilt', [VehiclesController::class, 'pendinggrnnetsuilt'])->name('vehiclesincoming.pendinggrnnetsuilt');
    Route::get('/vehiclebookedstocked', [VehiclesController::class, 'bookedstocked'])->name('vehiclesincoming.bookedstocked');
    Route::get('/vehiclependingapprovalssales', [VehiclesController::class, 'pendingapprovalssales'])->name('vehicleinspectionpending.pendingapprovalssales');
    Route::post('/marekting/update-charts', [HomeController::class, 'marketingupdatechart'])->name('homemarketing.update-charts');
    Route::post('/marekting/lead-distribution', [HomeController::class, 'leaddistruition'])->name('homemarketing.leaddistruition');
    Route::get('/marekting/lead-distribution-detail', [HomeController::class, 'leaddistruitiondetail'])->name('homemarketing.leaddistruitiondetails');
    // Vendors

    Route::get('/vendor/unique-check', [SupplierController::class, 'vendorUniqueCheck'])->name('vendor.vendorUniqueCheck');

    Route::post('/vehicles/updatelogistics', [VehiclesController::class, 'updatelogistics'])->name('vehicles.updatelogistics');
    Route::get('/view-pictures-details/{id}', [VehiclesController::class, 'viewpictures'])->name('vehiclespictures.viewpictures');
    Route::get('/view-remarks-details/{id}', [VehiclesController::class, 'viewremarks'])->name('vehiclesremarks.viewremarks');
    Route::post('/vehicles/updatewarehouse', [VehiclesController::class, 'updatewarehouse'])->name('vehicles.updatewarehouse');

    Route::get('/listUsers',[LoginActivityController::class, 'listUsers'])->name('listUsers');

    // vehicle stock report

    Route::get('/stock-count-filter',[VehiclesController::class, 'stockCountFilter'])->name('vehicle-stock-report.filter');
    // Master Data

    Route::resource('brands', BrandController::class);
    Route::resource('model-lines', ModelLinesController::class);
    Route::resource('master-addons', MasterAddonController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('modules', ModuleController::class);
    Route::resource('prospecting', ProspectingController::class);
});
