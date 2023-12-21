<?php
use App\Http\Controllers\Masters\MasterJobPositionController;
use App\Http\Controllers\Masters\MasterSpecificIndustryExperienceController;
use App\Http\Controllers\HRM\Hiring\EmployeeHiringRequestController;
use App\Http\Controllers\HRM\Hiring\EmployeeHiringQuestionnaireController;
use App\Http\Controllers\HRM\Hiring\JobDescriptionController;
use App\Http\Controllers\HRM\Hiring\PassportRequestController;
use App\Http\Controllers\HRM\Hiring\CandidatePersonalInfoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DemandPlanningPurchaseOrderController;
use App\Http\Controllers\MasterAddonController;
use App\Http\Controllers\MasterModelController;
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
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthOtpController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\ApprovalsController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\VariantRequests;
// use App\Http\Controllers\ModificationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\ApprovalAwaitingController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HRM\Hiring\EmployeeLeaveController;
use App\Http\Controllers\HRM\Hiring\EmployeeLiabilityController;
use App\Http\Controllers\HRM\Hiring\InterviewSummaryReportController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\SalesPersonStatusController;
use App\Http\Controllers\PortsController;

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
    Route::controller(AuthOtpController::class)->group(function(){
        Route::get('/otp/login', 'login')->name('otp.login');
        Route::post('/otp/generate', 'generate')->name('otp.generate');
        Route::post('/login/otp/generate', 'loginOtpGenerate')->name('otp.loginOtpGenerate');
        Route::get('/otp/verification/{user_id}/{email}/{password}', 'verification')->name('otp.verification');
    });
    Route::controller(ResetPasswordController::class)->group(function(){
        Route::get('/password/create/{email}', 'createPassword')->name('user.createPassword');
        Route::post('/password/store', 'storePassword')->name('password.store');
        Route::post('user-register', 'register')->name('users.register');
        Route::post('/createPassword/otp/generate', 'createPasswordOtpGenerate')->name('otp.createPasswordOtpGenerate');
        Route::get('createPassword/otp/verification/{user_id}/{email}/{password}/{password_confirmation}', 'verification')->name('createPassword.verification');
    });
    Route::post('/otp/login', [LoginController::class, 'loginWithOtp'])->name('otp.getlogin');
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

    // addon scroll list route

    Route::get('getAddonlists', [AddonController::class,'getAddonlists'])->name('addon.getAddonlists');


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
    Route::get('getRelatedModelLines', [AddonController::class,'getRelatedModelLines'])->name('addon.getRelatedModelLines');
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
    Route::post('kit/items/store', [KitCommonItemController::class, 'storeKitItems'])->name('kitItems.store');
    Route::get('purchasePriceHistory/{id}', [KitCommonItemController::class, 'purchasePriceHistory'])->name('kit.purchasePriceHistory');


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

    // Suppliers or vendor
    Route::resource('suppliers', SupplierController::class);
    Route::post('supplierAddonExcelValidation', [SupplierController::class, 'supplierAddonExcelValidation'])->name('addon.supplierAddonExcelValidation');
    Route::get('suppliers/destroy/{id}', [SupplierController::class,'delete'])->name('suppliers.delete');
    Route::post('suppliers/updateStatus', [SupplierController::class, 'updateStatus'])->name('suppliers.updateStatus');
    Route::post('suppliers/details/update', [SupplierController::class, 'updateDetails'])->name('suppliers.updatedetails');
    Route::get('/vendor/sub-categories', [SupplierController::class,'getVendorSubCategories'])->name('vendor.sub-categories');

    Route::get('supplier/addon/price/{id}', [SupplierController::class, 'addonprice'])->name('suppliers.addonprice');
    Route::post('createNewSupplierAddonPrice', [SupplierController::class, 'createNewSupplierAddonPrice'])->name('addon.createNewSupplierAddonPrice');
    Route::get('supplier/purchasepricehistory/{id}', [SupplierController::class, 'purchasepricehistory'])->name('suppliers.purchasepricehistory');
    Route::post('getAddonForSupplier', [SupplierController::class, 'getAddonForSupplier'])->name('addon.getAddonForSupplier');
    Route::post('getBrandForAddons', [AddonController::class, 'getBrandForAddons'])->name('addon.getBrandForAddons');
    Route::post('getModelLinesForAddons', [AddonController::class, 'getModelLinesForAddons']);

    Route::post('newSellingPriceRequest', [SupplierController::class, 'newSellingPriceRequest'])->name('addon.newSellingPriceRequest');
    Route::get('sellingPriceHistory/{id}', [SupplierController::class, 'sellingPriceHistory'])->name('suppliers.sellingPriceHistory');

      //Profoma Invoice
      Route::controller(ProformaInvoiceController::class)->group(function(){
        Route::get('/proforma_invoice/{callId}', 'proforma_invoice')->name('qoutation.proforma_invoice');
        Route::get('/get-model-lines/addon-booking/{brandId}/{type}', 'getaddonModels')->name('quotation.getaddonmodel');
        Route::get('/get-model-descriptions/addon-booking/{modelLineId}/{type}', 'getaddonModelDescriptions')->name('quotation.getmodeldescription');
        Route::get('/get-booking-accessories/{addonId}/{brandId}/{modelLineId}', 'getbookingAccessories')->name('booking.getbookingAccessories');
        Route::get('/get-booking-spare-parts/{addonId}/{brandId}/{modelLineId}/{ModelDescriptionId}', 'getbookingSpareParts')->name('booking.getbookingSpareParts');
        Route::get('/get-booking-kits/{addonId}/{brandId}/{modelLineId}/{ModelDescriptionId}', 'getbookingKits')->name('booking.getbookingKits');
        Route::get('/addons-modal-forqoutation/{modelLineId}', [ProformaInvoiceController::class, 'addonsModal'])->name('addonsquotation.modal');
    });
    // ApprovalAwaitingController
    Route::controller(ApprovalAwaitingController::class)->group(function(){
        Route::get('/addon-approval-awaiting/{type}', 'addonApprovalAwaiting')->name('addon.approval');
    });

    // HRM Module
    // Masters
    // Master Job Position
    Route::resource('master-job-position', MasterJobPositionController::class);
      // Master Specific Industry Experience
      Route::resource('industry-experience', MasterSpecificIndustryExperienceController::class);
    // Employee Hiring Requset
    Route::resource('employee-hiring-request', EmployeeHiringRequestController::class);
    Route::controller(EmployeeHiringRequestController::class)->group(function(){
        Route::get('employee-hiring-request-approval-awaiting', 'approvalAwaiting')->name('employee-hiring-request.approval-awaiting');
        Route::post('employee-hiring-request/request-action', 'requestAction')->name('employee-hiring-request.request-action');
        Route::get('employee-hiring-request/create-or-edit/{id}', 'createOrEdit')->name('employee-hiring-request.create-or-edit');
        Route::post('employee-hiring-request/store-or-update/{id}', 'storeOrUpdate')->name('employee-hiring-request.store-or-update');
        Route::post('employee-hiring-request/final-status', 'updateFinalStatus')->name('employee-hiring-request.final-status');
    });

    // Employee Hiring Questionnaire
    Route::resource('employee-hiring-questionnaire', EmployeeHiringQuestionnaireController::class);
    Route::controller(EmployeeHiringQuestionnaireController::class)->group(function(){
        Route::get('employee-hiring-questionnaire/create-or-edit/{id}', 'createOrEdit')->name('employee-hiring-questionnaire.create-or-edit');
        Route::post('employee-hiring-questionnaire/store-or-update/{id}', 'storeOrUpdate')->name('employee-hiring-questionnaire.store-or-update');
    });

        // Employee Job Description
    Route::resource('job_description', JobDescriptionController::class);
    Route::controller(JobDescriptionController::class)->group(function(){
        Route::get('employee-hiring-job-description/create-or-edit/{id}/{hiring_id}', 'createOrEdit')->name('employee-hiring-job-description.create-or-edit');
        Route::post('employee-hiring-job-description/store-or-update/{id}', 'storeOrUpdate')->name('employee-hiring-job-description.store-or-update');
        Route::post('employee-hiring-job-description/request-action', 'requestAction')->name('employee-hiring-job-description.request-action');
        Route::get('employee-hiring-job-description-approval-awaiting', 'approvalAwaiting')->name('employee-hiring-job-description.approval-awaiting');
    });
    // Interview Summary Report
    Route::resource('interview-summary-report', InterviewSummaryReportController::class);
    Route::controller(InterviewSummaryReportController::class)->group(function(){
        Route::get('interview-summary-report/create-or-edit/{id}', 'createOrEdit')->name('interview-summary-report.create-or-edit');
        Route::post('interview-summary-report/store-or-update/{id}', 'storeOrUpdate')->name('interview-summary-report.store-or-update');
        Route::post('interview-summary-report/request-action', 'requestAction')->name('interview-summary-report.request-action');
        Route::post('interview-summary-report/round-summary', 'updateRoundSummary')->name('interview-summary-report.round-summary');
        Route::post('interview-summary-report/final-evaluation', 'finalEvaluation')->name('interview-summary-report.final-evaluation');
        Route::post('interview-summary-report/salary', 'salary')->name('interview-summary-report.salary');
        Route::get('interview-summary-report-approval-awaiting', 'approvalAwaiting')->name('interview-summary-report.approval-awaiting');
    });
    // Candidate Personal Information Form
    Route::resource('personal-info', CandidatePersonalInfoController::class);
    Route::controller(CandidatePersonalInfoController::class)->group(function(){
        Route::post('personal-info/send-email', 'sendEmail')->name('personal-info.send-email');
    });
    // Employee Passport Request
    Route::resource('passport_request', PassportRequestController::class);
    Route::controller(PassportRequestController::class)->group(function(){
        Route::get('employee-passport_request/create-or-edit/{id}', 'createOrEdit')->name('employee-passport_request.create-or-edit');
        Route::post('employee-passport_request/store-or-update/{id}', 'storeOrUpdate')->name('employee-passport_request.store-or-update');
    });

    // Employee Liability
    Route::resource('employee_liability', EmployeeLiabilityController::class);
    Route::controller(EmployeeLiabilityController::class)->group(function(){
        Route::get('employee-liability/create-or-edit/{id}', 'createOrEdit')->name('employee-liability.create-or-edit');
        Route::post('employee-liability/store-or-update/{id}', 'storeOrUpdate')->name('employee-liability.store-or-update');
    });

    // Employee Leave
    Route::resource('employee_leave', EmployeeLeaveController::class);
    Route::controller(EmployeeLeaveController::class)->group(function(){
        Route::get('employee-leave/create-or-edit/{id}', 'createOrEdit')->name('employee-leave.create-or-edit');
        Route::post('employee-leave/store-or-update/{id}', 'storeOrUpdate')->name('employee-leave.store-or-update');
    });


    // Demand & Planning Module

    // suppliers
//    Route::resource('demand-planning-suppliers', DemandPlanningSupplierController::class);

    // Demands
    Route::get('demand-planning/get-sfx', [DemandController::class,'getSFX'])->name('demand.get-sfx');
    Route::get('demand-planning/get-model-year', [DemandController::class,'getModelYear'])->name('demand.get-model-year');
    Route::get('demand-planning/get-loi-description', [DemandController::class,'getLOIDescription'])->name('demand.get-loi-description');
    Route::get('demand-planning/getMasterModel', [DemandController::class,'getMasterModel'])->name('demand.getMasterModel');

    Route::resource('demands', DemandController::class);
    Route::resource('demand-lists', DemandListController::class);
    Route::resource('monthly-demands', MonthlyDemandsController::class);
    // Letter of Indent
    Route::get('letter-of-indents/get-customers', [LetterOfIndentController::class, 'getCustomers'])->name('letter-of-indents.get-customers');
    Route::get('letter-of-indents/generateLOI', [LetterOfIndentController::class, 'generateLOI'])->name('letter-of-indents.generate-loi');
    Route::post('letter-of-indents/status-change', [LetterOfIndentController::class, 'approve'])->name('letter-of-indents.status-change');
    Route::get('letter-of-indents/suppliers-LOIs', [LetterOfIndentController::class, 'getSupplierLOI'])->name('letter-of-indents.get-suppliers-LOIs');
    Route::post('letter-of-indents/supplier-approval', [LOIItemsController::class, 'supplierApproval'])->name('letter-of-indents.supplier-approval');
    Route::get('letter-of-indents/milele-approval', [LOIItemsController::class, 'mileleApproval'])->name('letter-of-indents.milele-approval');

    Route::resource('letter-of-indent-documents', LOIDocumentsController::class);
    Route::resource('letter-of-indents', LetterOfIndentController::class);
    Route::resource('letter-of-indent-items', LOIItemsController::class);
    Route::post('letter-of-indent-item/approve', [LOIItemsController::class, 'approveLOIItem'])->name('approve-loi-items');

    // PFI
    Route::post('/reference-number-unique-check',[PFIController::class,'uniqueCheckPfiReferenceNumber']);
    Route::resource('pfi', PFIController::class);
    Route::get('add-pfi', [PFIController::class,'addPFI'])->name('add_pfi');
    Route::resource('demand-planning-purchase-orders', DemandPlanningPurchaseOrderController::class);

    // Supplier Inventories
    Route::resource('supplier-inventories', SupplierInventoryController::class)->except('show');
    Route::get('supplier-inventories/lists', [SupplierInventoryController::class,'lists'])->name('supplier-inventories.lists');
    Route::get('supplier-inventories/file-comparision', [SupplierInventoryController::class,'FileComparision'])->name('supplier-inventories.file-comparision');
    Route::get('supplier-inventories/file-comparision-report', [SupplierInventoryController::class,'FileComparisionReport'])
        ->name('supplier-inventories.file-comparision-report');
    Route::get('supplier-inventories/get-dates', [SupplierInventoryController::class,'getDate'])->name('supplier-inventories.get-dates');
//    Route::get('supplier-inventories/get-supplier-inventories', [SupplierInventoryController::class,'getSupplierInventories'])->name('supplier-inventories.get-child-rows');

    //BL Module
    Route::resource('blform', BlFormController::class);
    Route::post('store-data', [BlFormController::class, 'storeData'])->name('store.data');

    //Marketing
    Route::resource('calls', CallsController::class)
    ->parameters(['calls' => 'call']);
    Route::get('callsinprocess', [CallsController::class,'inprocess'])->name('calls.inprocess');
    Route::get('callsconverted', [CallsController::class,'converted'])->name('calls.converted');
    Route::get('callsrejected', [CallsController::class,'rejected'])->name('calls.rejected');
    Route::get('callsdatacenter', [CallsController::class,'datacenter'])->name('calls.datacenter');
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
    Route::post('/update-priority', [StrategyController::class, 'updatePriority'])->name('strategy.updatePriority');
    Route::get('/simplefile', [CallsController::class,'simplefile'])->name('calls.simplefile');
    Route::delete('/calls/{id}', [CallsController::class, 'destroy'])->name('calls.destroy');
    Route::post('/calls/removerow', [CallsController::class, 'removeRow'])->name('calls.removerow');
    Route::post('/calls/updaterow', [CallsController::class, 'updaterow'])->name('calls.updaterow');
    Route::post('/calls/updatehol', [CallsController::class, 'updatehol'])->name('calls.updatehol');
    Route::get('new-variants/createnewvarinats', [CallsController::class,'createnewvarinats'])->name('calls.createnewvarinats');
    Route::get('new-variants/varinatinfo', [CallsController::class, 'varinatinfo'])->name('calls.varinatinfo');
    Route::get('new-leads/addnewleads', [CallsController::class, 'addnewleads'])->name('calls.addnewleads');
    Route::post('new-leads/storeleads', [CallsController::class, 'storeleads'])->name('calls.storeleads');
    Route::post('new-variants/storenewvarinats', [CallsController::class, 'storenewvarinats'])->name('calls.storenewvarinats');
    Route::resource('sale_person_status', SalesPersonStatusController::class);
    //Sales
    Route::resource('dailyleads', DailyleadsController::class);
    Route::get('quotation-data/get-my', [QuotationController::class,'getmy'])->name('quotation.get-my');
    Route::get('quotation-data/get-model-line', [QuotationController::class,'getmodelline'])->name('quotation.get-model-line');
    Route::get('quotation-data/get-sub-model', [QuotationController::class,'getsubmodel'])->name('quotation.get-sub-model');
    Route::resource('quotation-items', QuotationController::class);
    Route::post('quotation-data/vehicles-insert', [QuotationController::class,'addvehicles'])->name('quotation.vehicles-insert');
    Route::get('/quotation/shipping-port', [QuotationController::class,'getShippingPort'])->name('quotation.shipping_ports');
    Route::get('/quotation/shipping-charges', [QuotationController::class,'getShippingCharges'])->name('quotation.shipping_charges');

        Route::get('/get-vehicle-count/{userId}', function($userId) {
    $count = DB::table('vehiclescarts')->where('created_by', $userId)->count();
    return $count;
    });

    // vehicle pictures
     Route::get('vehicle-pictures/variant-details', [VehiclePicturesController::class,'getVariantDetail'])->name('vehicle-pictures.variant-details');
     Route::resource('vehicle-pictures', VehiclePicturesController::class);
     Route::post('getVinForVehicle', [VehiclePicturesController::class, 'getVinForVehicle']);
     Route::get('vehicle_pictures/pending', [VehiclePicturesController::class,'pending'])->name('vehicle_pictures.pending');
     Route::post('vehicle_pictures/saving', [VehiclePicturesController::class,'saving'])->name('vehicle_pictures.saving');


     // Variants
    Route::resource('variants', VariantController::class);
    Route::get('variant-prices/{id}/edit/type/{type}', [VariantPriceController::class,'edit'])->name('variant-price.edit');
    Route::resource('variant-prices', VariantPriceController::class);
    Route::get('/getSpecificationDetails/{id}', [VariantController::class, 'getSpecificationDetails']);
    Route::get('/remove-vehicle/{id}', [QuotationController::class, 'removeVehicle'])->name('quotation.removeVehicle');
    // Route::get('/fetch-addon-data/{id}/{quotationId}/{VehiclesId}', [AddonController::class, 'fetchAddonData'])->name('fetch-addon-data');
    Route::post('quotation-data/addone-insert', [QuotationController::class,'addqaddone'])->name('quotation.addone-insert');
    // Route::get('/modal-data/{id}/{quotationId}/{VehiclesId}', [AddonController::class, 'fetchAddonData']);
    Route::get('/modal-data/{id}', [AddonController::class, 'fetchAddonData'])->name('modal.show');
    Route::get('model-lines/specification/{id}', [VariantController::class, 'specification'])->name('model-lines.specification');
    Route::get('model-lines/viewspec/{id}', [VariantController::class, 'viewSpecification'])->name('model-lines.viewspec');
    Route::post('/variants/save-option', [VariantController::class, 'saveOption'])->name('variants.saveOption');
    Route::post('/variants/savespecification', [VariantController::class, 'savespecification'])->name('variants.savespecification');
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
    Route::post('/update-closed-negotiation', [DailyleadsController::class, 'savenegotiation'])->name('sales.savenegotiation');

    // HR
    Route::resource('hiring', HiringController::class);
    Route::resource('employee', EmployeeController::class);
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
    Route::post('/update-warehouse-remarks', [WarehouseController::class, 'updatewarehouseremarks'])->name('warehouse.updatewarehouseremarks');
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
    Route::get('/get-vehicles-data-for-movementso', [MovementController::class, 'getVehiclesDataformovementso'])->name('vehicles.getVehiclesDataformovementso');
    Route::post('movemnet/get-vehicles-detailsaspo', [MovementController::class, 'vehiclesdetailsaspo'])->name('vehicles.vehiclesdetailsaspo');
    Route::post('movemnet/get-vehicles-detailsasso', [MovementController::class, 'vehiclesdetailsasso'])->name('vehicles.vehiclesdetailsasso');
    Route::post('/update-prospecting-info', [DailyleadsController::class, 'saveprospecting'])->name('sales.saveprospecting');
    Route::post('/update-demand-info', [DailyleadsController::class, 'savedemand'])->name('sales.savedemand');
    Route::get('vehicles/viewall', [VehiclesController::class, 'viewall'])->name('vehicles.viewall');
    Route::get('vehicles/viewalls', [VehiclesController::class, 'viewalls'])->name('vehicles.viewalls');
    Route::get('/get-updated-vehicle/{id}', [VehiclesController::class, 'getUpdatedVehicle'])->name('getUpdatedVehicle');
    Route::get('/getBrandsAndModelLines', [PurchasingOrderController::class, 'getBrandsAndModelLines']);
    //booking
    Route::get('booking/create/{call_id}', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/get-model-lines/booking/{brandId}', [BookingController::class, 'getModelLines'])->name('booking.getmodel');
    Route::get('/get-variants/booking/{modelLineId}', [BookingController::class, 'getVariants'])->name('booking.getvariant');
    Route::get('/get-interior-colors/{variantId}', [BookingController::class, 'getInteriorColors'])->name('booking.getInteriorColors');
    Route::get('/get-exterior-colors/{variantId}', [BookingController::class, 'getExteriorColors'])->name('booking.getExteriorColors');
    Route::get('/get-booking-vehicles/{variantId}/{interiorColorId?}/{exteriorColorId?}', [BookingController::class, 'getbookingvehicles'])->name('booking.getbookingvehicles');
    Route::get('/get-booking-vehiclesbb/{variantId}/{exteriorColorId?}/{interiorColorId?}', [BookingController::class, 'getbookingvehiclesbb'])->name('booking.getbookingvehiclesbb');
    Route::post('/submit-booking-request', [BookingController::class, 'store'])->name('booking.store');
    Route::get('booking/info', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/submit-approval', [BookingController::class, 'approval'])->name('booking.approval');
    Route::get('booking/checking-so', [BookingController::class, 'checkingso'])->name('booking.checkingso');
    Route::post('/submit-extended', [BookingController::class, 'extended'])->name('booking.extended');
    Route::get('booking/{calls_id}', [DailyleadsController::class, 'leadspage'])->name('booking.leadspage');

    //Inspection
    Route::resource('inspection', InspectionController::class);
    Route::get('reinspection/reshow/{id}', [InspectionController::class, 'reshow'])->name('reinspection.reshow');
    Route::get('inspection/instock/{id}', [InspectionController::class, 'instock'])->name('inspection.instock');
    Route::get('inspection/pdi/{id}', [InspectionController::class, 'pdiinspectionf'])->name('inspection.pdiinspection');
    Route::resource('approvalsinspection', ApprovalsController::class);
    Route::resource('incident', IncidentController::class);
    Route::post('incident/saving', [IncidentController::class,'updatestatus'])->name('incidentupdate.updatestatus');
    Route::resource('variantrequests', VariantRequests::class);
    Route::get('/check-variant', [VariantRequests::class, 'checkVariant']);
    Route::post('approvalsinspection/update-status', [ApprovalsController::class, 'updateStatus'])->name('approvalsinspection.updateStatus');
    Route::post('approvalsinspection/updateinspectionupdates', [ApprovalsController::class, 'updateinspectionupdates'])->name('approvalsinspection.updateinspectionupdates');
    Route::post('approvalsinspection/updateextraitems', [ApprovalsController::class, 'updateextraitems'])->name('approvalsinspection.updateextraitems');
    Route::post('approvalsinspection/updateincident', [ApprovalsController::class, 'updateincident'])->name('approvalsinspection.updateincident');
    Route::post('approvalsinspection/savevariantsd', [ApprovalsController::class, 'savevariantsd'])->name('approvalsinspection.savevariantsd');
    Route::get('/check-org-variant', [VariantRequests::class, 'checkVariantorg']);
    Route::post('/approve-inspection', [ApprovalsController::class, 'approveInspection'])->name('approveInspection');
    Route::post('/reinspectionrequest', [InspectionController::class, 'reinspectionrequest'])->name('reinspectionrequest');
    Route::put('/resinspection/{id}', [InspectionController::class, 'reupdate'])->name('inspection.reupdate');
    Route::put('/resinspectionspec/{id}', [InspectionController::class, 'reupdatespec'])->name('inspection.reupdatespec');
    Route::get('approvalsinspection/reshow/{approvalsreinspection}', [ApprovalsController::class, 'approvalsreinspection'])->name('reinspection.approvalsreinspection');
    Route::get('incident/showre/{id}', [IncidentController::class, 'showre'])->name('incident.showre');
    Route::post('incident/reinspectionsforapp', [IncidentController::class, 'reinspectionsforapp'])->name('incident.reinspectionsforapp');
    Route::get('/get-incident-works/{incidentId}', [IncidentController::class, 'getIncidentWorks']);
    Route::post('incident/approvals', [IncidentController::class,'approvals'])->name('incidentupdate.approvals');
    Route::put('/dailyinspection/{vehicle}', [InspectionController::class, 'routineUpdate'])->name('dailyinspection.routainupdate');
    Route::get('/routine-inspection/{vehicleId}', [ApprovalsController::class, 'getRoutineInspectionData']);
    Route::post('inspectionretuinapp/approvals', [ApprovalsController::class,'approvalsrotein'])->name('inspectionapprovalroten.approvalsrotein');
    Route::post('pdiinspection', [InspectionController::class,'pdiinspection'])->name('pdi.pdiinspection');
    Route::get('inspection/reinspectionspec/{id}', [InspectionController::class, 'reinspectionspec'])->name('inspection.reinspectionspec');
    Route::get('/get-vehicle-extra-items/{vehicle_id}', [InspectionController::class, 'getVehicleExtraItems']);
    Route::get('/pdi-inspection/{vehicleId}', [ApprovalsController::class, 'getpdiInspectionData']);
    Route::post('inspectionpdiapp/approvals', [ApprovalsController::class,'approvalspdi'])->name('inspectionapprovalpdi.approvalspdi');
    Route::post('inspectionpdiappin/approvals', [ApprovalsController::class,'approvedincidentsonly'])->name('inspectionapprovalpdi.approvedincidentsonly');
    Route::get('incidents/updatevehicledetails', [IncidentController::class, 'updatevehicledetails'])->name('incident.updatevehicledetails');
    Route::post('incident/createincidents', [IncidentController::class,'createincidents'])->name('incident.createincidents');
    // Route::resource('modification', ModificationController::class);
    Route::post('incident/reinspectionsforre', [IncidentController::class, 'reinspectionsforre'])->name('incident.reinspectionsforre');
    Route::post('incident/reinspectionsforrem', [IncidentController::class, 'reinspectionsforrem'])->name('incident.reinspectionsforrem');
    Route::get('/get-pdi-inspection/{incidentId}', [IncidentController::class,'getPdiInspection']);
    Route::get('/get-incident-details/{incidentId}', [IncidentController::class,'getIncidentDetails']);
    Route::get('inspectionedit/edit/{id}', [ApprovalsController::class, 'inspectionedit'])->name('inspectionedit.edit');
    Route::post('/update-routine-inspection', [ApprovalsController::class, 'updateRoutineInspection']);
    Route::post('/update-pdi-inspection', [ApprovalsController::class, 'updatepdiInspectionedit']);
    // Vendors

    Route::get('/vendor/unique-check', [SupplierController::class, 'vendorUniqueCheck'])->name('vendor.vendorUniqueCheck');

    Route::post('/vehicles/updatelogistics', [VehiclesController::class, 'updatelogistics'])->name('vehicles.updatelogistics');
    Route::get('/view-pictures-details/{id}', [VehiclesController::class, 'viewpictures'])->name('vehiclespictures.viewpictures');
    Route::get('/view-remarks-details/{id}', [VehiclesController::class, 'viewremarks'])->name('vehiclesremarks.viewremarks');
    Route::post('/vehicles/updatewarehouse', [VehiclesController::class, 'updatewarehouse'])->name('vehicles.updatewarehouse');

    Route::get('/listUsers',[LoginActivityController::class, 'listUsers'])->name('listUsers');
    Route::post('/listUsersget-data',[LoginActivityController::class, 'listUsersgetdata'])->name('listUsersgetdata');
    Route::post('/listUsersget-dataac', [LoginActivityController::class, 'listUsersgetdataac'])->name('listUsersgetdataac');
    Route::get('/user/{id}/{date}', [UserController::class, 'showUseractivities'])->name('user.showUseractivitie');
    // vehicle stock report

    Route::get('/stock-count-filter',[VehiclesController::class, 'stockCountFilter'])->name('vehicle-stock-report.filter');
    // Master Data

    Route::resource('brands', BrandController::class);
    Route::resource('model-lines', ModelLinesController::class);
    Route::resource('master-addons', MasterAddonController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('modules', ModuleController::class);
    Route::resource('prospecting', ProspectingController::class);
    Route::resource('master-models', MasterModelController::class);
    Route::resource('dm-customers', CustomerController::class);
    Route::get('master-model/getLoiDescription', [MasterModelController::class,'getLoiDescription'])
        ->name('master-model.get-loi-description');
    Route::post('quotation/new-model-line', [ModelLinesController::class,'StoreModellineOrBrand'])->name('modelline-or-brand.store');

    // DASHBOARD PARTS AND PROCURMENT

    Route::get('addon-dashboard/sellingPriceFilter',[HomeController::class, 'sellingPriceFilter'])->name('addon-dashboard.filter');

    //Logistics
    Route::resource('logisticsdocuments', DocumentController::class);
    Route::post('logisticsdocuments/sending', [DocumentController::class, 'updatedoc'])->name('logisticsdocuments.updatedoc');
    Route::post('logisticsdocuments/sendingbl', [DocumentController::class, 'updatedocbl'])->name('logisticsdocuments.updatedocbl');
    Route::resource('Shipping', ShippingController::class);
    Route::post('shipping/updateprice', [ShippingController::class, 'updateprice'])->name('shipping.updateprice');
    Route::get('shipping_medium/{id}', [ShippingController::class, 'openmedium'])->name('shipping_medium.openmedium');
    Route::get('/shipping_rates/{id}', [ShippingController::class, 'shippingrates'])->name('shipping_medium.shippingrates');
    Route::get('shipping_medium_create/{id}', [ShippingController::class, 'openmediumcreate'])->name('shipping_medium.openmedium_create');
    Route::get('shipping_rates_create/{id}', [ShippingController::class, 'shippingratescreate'])->name('shipping_rate.shippingrates_create');
    Route::post('/storeportrates', [ShippingController::class, 'storeportrates'])->name('Shipping.storeportrates');
    Route::post('/storevendorrates', [ShippingController::class, 'storevendorrates'])->name('Shipping.storevendorrates');
    Route::post('/select-shipping-rate/{id}', [ShippingController::class, 'selectShippingRate']);
    Route::get('/getShippingRateDetails/{id}', [ShippingController::class, 'getShippingRateDetails']);
    Route::post('/updateShippingRate', [ShippingController::class, 'updateShippingRate']);
    Route::resource('ports', PortsController::class);
    //Agents
    Route::resource('agents', AgentsController::class);
    Route::get('/get-agent-names', [AgentsController::class, 'getAgentNames'])->name('agents.getAgentNames');
    });
    Route::get('candidate/personal_info/{id}', [CandidatePersonalInfoController::class, 'sendForm'])->name('candidate_personal_info.send_form');
    Route::post('candidate/store_personal_info', [CandidatePersonalInfoController::class, 'storePersonalinfo'])->name('candidate.storePersonalinfo');
    Route::get('candidate/success_personal_info', [CandidatePersonalInfoController::class, 'successPersonalinfo'])->name('candidate.successPersonalinfo');
