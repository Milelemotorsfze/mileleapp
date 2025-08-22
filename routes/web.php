<?php

use App\Http\Controllers\LoiCountryCriteriasController;
use App\Http\Controllers\LOIMappingCriteriaController;
use App\Http\Controllers\LoiRestrictedCountriesController;
use App\Http\Controllers\Masters\MasterJobPositionController;
use App\Http\Controllers\Masters\MasterSpecificIndustryExperienceController;
use App\Http\Controllers\Masters\DesignationApprovalsController;
use App\Http\Controllers\Masters\DivisionController;
use App\Http\Controllers\Masters\DepartmentController;
use App\Http\Controllers\HRM\Hiring\EmployeeHiringRequestController;
use App\Http\Controllers\HRM\Hiring\EmployeeHiringQuestionnaireController;
use App\Http\Controllers\HRM\Hiring\JobDescriptionController;
use App\Http\Controllers\HRM\Hiring\CandidatePersonalInfoController;
use App\Http\Controllers\HRM\Hiring\InterviewSummaryReportController;
use App\Http\Controllers\HRM\Employee\PassportRequestController;
use App\Http\Controllers\HRM\Employee\PassportReleaseController;
use App\Http\Controllers\HRM\Employee\EmployeeLeaveController;
use App\Http\Controllers\HRM\Employee\EmployeeLiabilityController;
use App\Http\Controllers\HRM\Employee\BirthDayGiftPOController;
use App\Http\Controllers\HRM\Employee\TicketAllowancePOController;
use App\Http\Controllers\HRM\Employee\InsuranceController;
use App\Http\Controllers\HRM\Employee\IncrementController;
use App\Http\Controllers\HRM\Employee\OverTimeController;
use App\Http\Controllers\HRM\Employee\SeparationController;
use App\Http\Controllers\HRM\OnBoarding\JoiningReportController;
use App\Http\Controllers\HRM\OnBoarding\AssetAllocationController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\WorkOrderExportController;
use App\Http\Controllers\WOApprovalsController;
use App\Http\Controllers\WoDocsStatusController;
use App\Http\Controllers\WoStatusController;
use App\Http\Controllers\WoVehicleController;
use App\Http\Controllers\WoPDIStatusController;
use App\Http\Controllers\WOVehicleDeliveryStatusController;
use App\Http\Controllers\BOEPenaltyController;
use App\Http\Controllers\WOBOEClaimsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DemandPlanningPurchaseOrderController;
use App\Http\Controllers\MasterAddonController;
use App\Http\Controllers\MasterModelController;
use App\Http\Controllers\ModelLinesController;
use App\Http\Controllers\ModelYearCalculationCategoriesController;
use App\Http\Controllers\ModelYearCalculationRuleController;
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
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\Repeatedcustomers;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierAddonController;
use App\Http\Controllers\PFIController;
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
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\SalesPersonStatusController;
use App\Http\Controllers\PortsController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\PaymentTermsController;
use App\Http\Controllers\SalesTargetsController;
use App\Http\Controllers\ClientAccountTransitionController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\PostingRecordsController;
use App\Http\Controllers\MarketingPurchasingPaymentsController;
use App\Http\Controllers\LeadsNotificationsController;
use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\MigrationDataCheckController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\LOIExpiryConditionController;
use App\Http\Controllers\LOIItemController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\DepartmentNotificationsController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\AchievementCertificateController;
use App\Http\Controllers\LetterRequestController;
use App\Http\Controllers\SalaryCertificateController;
use App\Http\Controllers\VehicleNetsuiteCostController;
use App\Http\Controllers\StockMessageController;
use App\Http\Controllers\VehicleInvoiceController;
use App\Http\Controllers\LeadChatController;
use App\Exports\UAEVehicleStockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BelgiumVehicleStockExport;
use App\Http\Controllers\ModeldescriptionController;
use App\Http\Controllers\MasterGradeController;
use App\Http\Controllers\CompanyDomainController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MasterChargesController;
use App\Http\Controllers\SoFinalizationController;
use App\Models\Gdn;
use App\Models\Grn;

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
Route::get('/session-status', [SessionController::class, 'status']);
Route::get('/auth/google', [GoogleOAuthController::class, 'redirectToGoogle']);
Route::get('/callback', [GoogleOAuthController::class, 'handleGoogleCallback']);
Route::get('clientsignature/{uniqueNumber}/{quotationId}', [QuotationController::class, 'showBySignature'])->name('quotation.showBySignature');
Route::post('/submit-signature', [QuotationController::class, 'submitSignature']);
Route::match(['get', 'post'], '/whatsapp/receive', [WebhookController::class, 'sendMessage']);
Route::get('/react-page', function () {
    return view('react-app.index');
});
Route::get('/d', function () {
    return view('addon.ff');
});


    Auth::routes();
    Route::middleware(['web'])->controller(AuthOtpController::class)->group(function(){
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
    Route::get('/getUser/{id}', [UserController::class, 'getUserById']);
    Route::get('/users-search', [UserController::class, 'searchUsers']);
    Route::get('users/updateStatus/{id}', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::get('users/makeActive/{id}', [UserController::class, 'makeActive'])->name('users.makeActive');
    Route::get('users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::get('users/destroy/{id}', [UserController::class,'delete'])->name('users.delete');
    Route::controller(UserController::class)->group(function() {
        Route::post('user/email-unique-check', 'uniqueEmail')->name('user.uniqueEmail');
        Route::post('user/create-access-request', 'createAccessRequest')->name('user.createAccessRequest');
        Route::get('user/create-password-request/{id}','createLogin')->name('users.createLogin');
    });
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
        Route::get('/proforma_invoice_edit/{callId}', 'proforma_invoice_edit')->name('qoutation.proforma_invoice_edit');
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
    // Master Division and head
    Route::resource('division', DivisionController::class);
    Route::controller(DivisionController::class)->group(function(){
        Route::post('master/division-unique-check', 'uniqueDivision')->name('master.uniqueDivision');
    });
    // Master Department and head
    Route::resource('department', DepartmentController::class);
    Route::controller(DepartmentController::class)->group(function(){
        Route::post('master/department-unique-check', 'uniqueDepartment')->name('master.uniqueDepartment');
    });
    // Designation Approvals
    Route::resource('designation-approvals', DesignationApprovalsController::class);

    // Master Job Position
    Route::resource('master-job-position', MasterJobPositionController::class);
      // Master Specific Industry Experience
      Route::resource('industry-experience', MasterSpecificIndustryExperienceController::class);
    // Employee
    Route::resource('employee', EmployeeController::class);
    Route::controller(EmployeeController::class)->group(function(){
        Route::post('employee/passport-unique-check', 'uniquePassport')->name('employee.uniquePassport');
        Route::post('employee/employee-code-unique-check', 'uniqueCandidateEmpCode')->name('employee.uniqueCandidateEmpCode');
    });
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
    Route::get('candidate/listingInfo', [CandidatePersonalInfoController::class, 'getCandidatePersonalInfo'])->name('candidate.listingInfo');
    Route::get('candidate/listDocs', [CandidatePersonalInfoController::class, 'getCandidateDocsInfo'])->name('candidate.listDocs');
    Route::get('candidate/listOfferLetter', [CandidatePersonalInfoController::class, 'getOfferLetterList'])->name('candidate.listOfferLetter');
    Route::get('candidate-offer-letter/send/{id}', [CandidatePersonalInfoController::class, 'sendJobOfferLetter'])->name('candidate-offer-letter.send');
    Route::resource('personal-info', CandidatePersonalInfoController::class);
    Route::controller(CandidatePersonalInfoController::class)->group(function(){
        Route::post('personal-info/send-email', 'sendEmail')->name('personal-info.send-email');
        Route::post('personal-info/create-offer-letter', 'createOfferLetter')->name('personal-info.create-offer-letter');
        Route::post('docs/send-email', 'sendDocsEmail')->name('docs.send-email');
        Route::post('personal-info/verified', 'personalInfoVerified')->name('personal-info.verified');
        Route::post('docs/verified', 'docsVerified')->name('docs.verified');
        Route::post('offer-letter-sign/verified', 'offerLetterSignVerified')->name('offer_letter_sign.verified');
    });

    // Joining Report
    Route::resource('joining_report', JoiningReportController::class)->only('store','update','show','edit');
    // Route::resource('enquires', EnquiresController::class)
    Route::controller(JoiningReportController::class)->group(function(){
        Route::post('joining_report_action', 'requestAction')->name('joiningReport.action');
        Route::get('joining_report_approval_awaiting', 'approvalAwaiting')->name('joiningReport.approvalAwaiting');
        Route::get('employee_joining_report/{type}','index')->name('employee_joining_report.index');
        Route::get('create_joining_report/{type}','create')->name('create_joining_report.create');
        Route::post('checkTempDateExist', 'checkTempDateExist')->name('temptransfer.checkTempDateExist');
        Route::post('candidate/joining-report-unique-check', 'uniqueJoiningReport')->name('candidate.uniqueJoiningReport');
    });
    Route::get('joining_report_employee_verification/{id}', [JoiningReportController::class, 'employeeVerification'])->name('employee_joining_report.verification');
    Route::post('employee_joining_report/verified', [JoiningReportController::class, 'employeeVerified'])->name('employee_joining_report.verified');

    // Asset Allocation
    Route::resource('asset_allocation', AssetAllocationController::class);
    // Employee Passport Request
    Route::resource('passport_request', PassportRequestController::class);
    Route::controller(PassportRequestController::class)->group(function(){
        Route::get('employee-passport_request/create-or-edit/{id}', 'createOrEdit')->name('employee-passport_request.create-or-edit');
        Route::post('employee-passport_request/store-or-update/{id}', 'storeOrUpdate')->name('employee-passport_request.store-or-update');
        Route::post('employee-passport-submit/request-action', 'requestAction')->name('employee-passport-submit.request-action');
        Route::get('employee-passport-submit-approval-awaiting', 'approvalAwaiting')->name('passportSubmit.approvalAwaiting');
    });
    // Employee Passport Release
    Route::resource('passport_release', PassportReleaseController::class);
    Route::controller(PassportReleaseController::class)->group(function(){
        Route::post('employee-passport-release/request-action', 'requestAction')->name('employee-passport-release.request-action');
        Route::get('employee-passport-release-approval-awaiting', 'approvalAwaiting')->name('passportRelease.approvalAwaiting');
    });
    // Employee Liability
    Route::resource('employee_liability', EmployeeLiabilityController::class);
    Route::controller(EmployeeLiabilityController::class)->group(function(){
        Route::get('employee-liability/create-or-edit/{id}', 'createOrEdit')->name('employee-liability.create-or-edit');
        Route::post('employee-liability/store-or-update/{id}', 'storeOrUpdate')->name('employee-liability.store-or-update');
        Route::post('liability_request_action', 'requestAction')->name('liabilityRequest.action');
        Route::get('liability_approval_awaiting', 'approvalAwaiting')->name('liability.approvalAwaiting');
    });

    // Employee Leave
    Route::resource('employee_leave', EmployeeLeaveController::class);
    Route::controller(EmployeeLeaveController::class)->group(function(){
        Route::get('employee-leave/create-or-edit/{id}', 'createOrEdit')->name('employee-leave.create-or-edit');
        Route::post('employee-leave/store-or-update/{id}', 'storeOrUpdate')->name('employee-leave.store-or-update');
        Route::post('leave_request_action', 'requestAction')->name('leaveRequest.action');
        Route::get('leave_approval_awaiting', 'approvalAwaiting')->name('leave.approvalAwaiting');
        Route::post('checkLeaveDateAlreadyExist', 'checkLeaveDateAlreadyExist')->name('leave.checkLeaveDateAlreadyExist');
    });

    // Employee Birthday Gift PO
    Route::resource('birthday_gift', BirthDayGiftPOController::class);

    // Employee Ticket Allowance PO
    Route::resource('ticket_allowance', TicketAllowancePOController::class);

    // Employee Insurance
    Route::resource('insurance', InsuranceController::class);
    // Employee Increment
    Route::resource('increment', IncrementController::class);
    // Employee Overtime Application
    Route::resource('overtime', OverTimeController::class);
    Route::controller(OverTimeController::class)->group(function(){
        Route::post('checkOvertimeAlreadyExist', 'checkOvertimeAlreadyExist')->name('overtime.checkOvertimeAlreadyExist');
        Route::post('overtime_request_action', 'requestAction')->name('overtimeRequest.action');
        Route::get('overtime_approval_awaiting', 'approvalAwaiting')->name('overtime.approvalAwaiting');
    });
     // Employee Overtime Application
     Route::resource('separation-handover', SeparationController::class);

    // Work Order Module
    Route::resource('work-order', WorkOrderController::class)->only([
        'show','store','edit','update','create'
    ]);
    Route::get('/export-work-orders', [WorkOrderExportController::class, 'export']);
    // Route::get('/comments/{workOrderId}', [WorkOrderController::class, 'getComments']);
    Route::get('/comments/{workOrderId}', [WorkOrderController::class, 'getComments'])->name('comments.get');
    Route::delete('/workorder/{id}', [WorkOrderController::class, 'destroy'])->name('workorder.destroy');
    Route::post('/is-exist-in-sales-order', [WorkOrderController::class, 'isExistInSalesOrder'])->name('work-order.is-exist-in-sales-ordder');
    Route::controller(WorkOrderController::class)->group(function(){
        Route::get('work-order-create/{type}', 'workOrderCreate')->name('work-order-create.create');
        Route::get('work-order-info/{type}', 'index')->name('work-order.index');
        Route::post('/fetch-addons', [WorkOrderController::class, 'fetchAddons'])->name('fetch-addons');
        Route::post('/comments', [WorkOrderController::class, 'storeComments'])->name('comments.store');
        Route::post('work-order/so-unique-check', 'uniqueSO')->name('work-order.uniqueSO');
        Route::post('work-order/wo-unique-check', 'uniqueWO')->name('work-order.uniqueWO');
        Route::get('work-order-vehicle/data-history/{id}','vehicleDataHistory')->name('wo-vehicles.data-history');
        Route::get('work-order-vehicle-addon/data-history/{id}','vehicleAddonDataHistory')->name('wo-vehicle-addon.data-history');
        Route::post('work-order/sales-approval', 'salesApproval')->name('work-order.sales-approval');
        Route::post('work-order/finance-approval', 'financeApproval')->name('work-order.finance-approval');
        Route::post('work-order/coe-office-approval', 'coeOfficeApproval')->name('work-order.coe-office-approval');
        Route::post('work-order/revert-sales-approval', 'revertSalesApproval')->name('work-order.revert-sales-approval');
        Route::post('/save-filters', 'saveFilters')->name('save.filters');
        Route::post('/check-so-number', 'checkSONumber')->name('wo.checkSONumber');
    });
    Route::controller(WoDocsStatusController::class)->group(function(){
        Route::post('/update-wo-doc-status', 'updateDocStatus')->name('wo.updateDocStatus');
        Route::get('/wo-doc-status-history/{id}', 'docStatusHistory')->name('docStatusHistory');
    });
    Route::controller(WoStatusController::class)->group(function(){
        Route::post('/update-wo-status', 'updateStatus')->name('wo.updateStatus');
        Route::get('/wo-status-history/{id}', 'woStatusHistory')->name('woStatusHistory');
    });
    Route::controller(WoVehicleController::class)->group(function(){
        Route::post('/update-vehicle-modification-status', 'updateVehModiStatus')->name('wo.updateVehModiStatus');
        Route::get('/vehicle-modification-status-log/{id}', 'vehModiStatusHistory')->name('vehModiStatusHistory');
        Route::post('/fetch-boe-number', 'fetchBoeNumber')->name('fetch.boe_number');
    });
    Route::controller(WoPDIStatusController::class)->group(function(){
        Route::post('/update-vehicle-pdi-status', 'updateVehPdiStatus')->name('wo.updateVehPdiStatus');
        Route::get('/vehicle-pdi-status-log/{id}', 'vehPdiStatusHistory')->name('vehPdiStatusHistory');
    });
    Route::controller(WOVehicleDeliveryStatusController::class)->group(function(){
        Route::post('/update-vehicle-delivery-status', 'updateVehDeliveryStatus')->name('wo.updateVehDeliveryStatus');
        Route::get('/vehicle-delivery-status-log/{id}', 'vehDeliveryStatusHistory')->name('vehDeliveryStatusHistory');
    });
    Route::controller(BOEPenaltyController::class)->group(function(){
        Route::get('/boe-penalty-report', 'getBOEPenaltyReport')->name('getBOEPenaltyReport');
        Route::get('/cleared-penalty-report', 'getClearedPenalties')->name('getClearedPenalties');
        Route::get('/no-penalty-report', 'getNoPenalties')->name('getNoPenalties');
        Route::post('/vehicle-penalty/storeOrUpdate', 'storeOrUpdate')->name('penalty.storeOrUpdate');
    });
    Route::controller(WOBOEClaimsController::class)->group(function(){
        Route::get('/pending-boe-claims', 'getPendingClaims')->name('getPendingClaims');
        Route::get('/cleared-submitted-claims', 'getSubmittedClaims')->name('getSubmittedClaims');
        Route::get('/cleared-approved-claims', 'getApprovedClaims')->name('getApprovedClaims');
        Route::get('/cleared-cancelled-claims', 'getCancelledClaims')->name('getCancelledClaims');
        Route::get('/claims-log/{id}', 'getClaimsLog')->name('claim.log');
        Route::post('/boe-claims/storeOrUpdate', 'storeOrUpdate')->name('claim.storeOrUpdate');
        Route::post('/boe-claims/updateStatus', 'updateStatus')->name('claim.updateStatus');
    });
    Route::get('/finance-approval-history/{id}', [WOApprovalsController::class, 'fetchFinanceApprovalHistory'])->name('fetchFinanceApprovalHistory');
    // Route::get('/finance-approval-history-page/{id}', [WOApprovalsController::class, 'showFinanceApprovalHistoryPage'])->name('showFinanceApprovalHistoryPage');

    Route::get('/coo-approval-history/{id}', [WOApprovalsController::class, 'fetchCooApprovalHistory'])->name('fetchCooApprovalHistory');
    // Route::get('/coo-approval-history-page/{id}', [WOApprovalsController::class, 'showCooApprovalHistoryPage'])->fetch('showCooApprovalHistoryPage');


    // Company Domains
    Route::get('companyDomains/create', [CompanyDomainController::class, 'create'])->name('companyDomains.create');
    Route::get('companyDomains/{id}/edit', [CompanyDomainController::class, 'edit'])->name('companyDomains.edit');
    Route::post('companyDomains', [CompanyDomainController::class, 'store'])->name('companyDomains.store');
    Route::put('companyDomains/{id}', [CompanyDomainController::class, 'update'])->name('companyDomains.update');
    Route::delete('companyDomains/{id}', [CompanyDomainController::class, 'destroy'])->name('companyDomains.destroy');
    Route::get('companyDomains', [CompanyDomainController::class, 'index'])->name('companyDomains.index');

    // GRN List
    Route::get('/grn-list', [VehiclesController::class, 'Grnlist'])->name('grn.index');
    // Demand & Planning Module

    // Demands
    Route::get('demand-planning/get-sfx', [DemandController::class,'getSFX'])->name('demand.get-sfx');
    Route::get('demand-planning/get-loi-description', [DemandController::class,'getLOIDescription'])->name('demand.get-loi-description');
    Route::get('demand-planning/getMasterModel', [DemandController::class,'getMasterModel'])->name('demand.getMasterModel');

    Route::resource('demands', DemandController::class);
    Route::resource('demand-lists', DemandListController::class);
    Route::resource('monthly-demands', MonthlyDemandsController::class);
    // Letter of Indent
    Route::get('letter-of-indents/get-customers', [LetterOfIndentController::class, 'getCustomers'])->name('letter-of-indents.get-customers');
    Route::get('letter-of-indents/generateLOI', [LetterOfIndentController::class, 'generateLOI'])->name('letter-of-indents.generate-loi');
    Route::post('letter-of-indents/supplier-approval', [LetterOfIndentController::class, 'supplierApproval'])->name('letter-of-indents.supplier-approval');
    Route::resource('loi-country-criterias', LoiCountryCriteriasController::class);
    Route::post('loi-country-criterias/active-inactive', [LoiCountryCriteriasController::class,'statusChange'])->name('loi-country-criterias.active-inactive');
    Route::get('loi-country-criteria-check', [LoiCountryCriteriasController::class, 'CheckCountryCriteria'])->name('loi-country-criteria.check');
    Route::post('letter-of-indent/request-approval', [LetterOfIndentController::class, 'RequestApproval'])
        ->name('letter-of-indent.request-approval');
    Route::post('letter-of-indent/update-comment', [LetterOfIndentController::class, 'updateComment'])
    ->name('update-loi-comment');

    Route::resource('letter-of-indents', LetterOfIndentController::class);
    Route::resource('loi-mapping-criterias', LOIMappingCriteriaController::class);
    Route::post('utilization-quantity/update/{id}', [LetterOfIndentController::class, 'utilizationQuantityUpdate'])->name('utilization-quantity-update');
    Route::resource('loi-expiry-conditions', LOIExpiryConditionController::class);
    Route::resource('letter-of-indent-items', LOIItemController::class)->only('index');

    Route::post('letter-of-indents/status-update/{id}', [LetterOfIndentController::class, 'statusUpdate'])
                ->name('letter-of-indents.status-update');
    Route::post('letter-of-indents/loi-expiry-status-update/{id}', [LetterOfIndentController::class, 'ExpiryStatusUpdate'])
    ->name('letter-of-indents.loi-expiry-status-update');
    Route::get('loi/get-customer-documents', [LetterOfIndentController::class,'getCustomerDocuments'])->name('loi.customer-documents');
    Route::get('loi/get-is-editable', [LetterOfIndentController::class,'getIsLOIEditable'])->name('loi.get-is-editable');

    // PFI
    Route::post('/reference-number-unique-check',[PFIController::class,'uniqueCheckPfiReferenceNumber']);
    Route::get('pfi/pfi-document', [PFIController::class,'generatePFIDocument'])->name('pfi.pfi-document');
    Route::get('pfi/get-PFI-brand', [PFIController::class,'getPfiBrand'])->name('pfi.get-pfi-brand');
    Route::resource('pfi', PFIController::class);

    Route::get('pfi-item/list', [PFIController::class,'PFIItemList'])->name('pfi-item.list');
    // Route::post('pfi-payment-status/update/{id}', [PFIController::class, 'paymentStatusUpdate'])->name('pfi-payment-status-update');
    Route::post('pfi-released-amount/update', [PFIController::class, 'relaesedAmountUpdate'])->name('pfi-released-amount-update');
    Route::get('pfi-item/get-loi-item', [PFIController::class,'getLOIItemCode'])->name('loi-item-code');
    Route::get('pfi-item/get-loi-item-details', [PFIController::class,'getLOIItemDetails'])->name('loi-item-details');
    Route::get('pfi-item/get-models', [PFIController::class,'getModels'])->name('pfi-item.models');
    Route::get('pfi-item/get-master-models', [PFIController::class,'getMasterModel'])->name('pfi-item.master-model-ids');
    Route::get('pfi-item/get-brand', [PFIController::class,'getBrand'])->name('pfi-item.get-brand');
    Route::get('pfi-item/get-customer-countries', [PFIController::class,'getCustomerCountries'])->name('pfi-item.customer-countries');
    // PO
    Route::resource('demand-planning-purchase-orders', DemandPlanningPurchaseOrderController::class)->only('create');
    Route::get('dp-purchase-order/check-inventory-colour', [DemandPlanningPurchaseOrderController::class,'checkInventoryColour'])
                                                                ->name('dp-purchase-order.inventory-check');
    Route::get('dp-purchasing-order/check-po-number', [DemandPlanningPurchaseOrderController::class, 'uniqueCheckPONumber'])->name('dp-purchasing-order.checkPONumber');



    // Supplier Inventories
    Route::resource('supplier-inventories', SupplierInventoryController::class)->except('show');
    Route::get('supplier-inventories/createNew', [SupplierInventoryController::class,'createNew'])->name('supplier-inventories.createNew');
    Route::post('supplier-inventories/excel-update', [SupplierInventoryController::class,'ExcelUpdate'])->name('supplier-inventories.excel-update');
    Route::get('supplier-inventories/lists', [SupplierInventoryController::class,'lists'])->name('supplier-inventories.lists');
    Route::get('supplier-inventories/file-comparision', [SupplierInventoryController::class,'FileComparision'])->name('supplier-inventories.file-comparision');
    Route::get('supplier-inventories/file-comparision-report', [SupplierInventoryController::class,'FileComparisionReport'])
        ->name('supplier-inventories.file-comparision-report');
    Route::get('supplier-inventories/get-dates', [SupplierInventoryController::class,'getDate'])->name('supplier-inventories.get-dates');
    Route::get('/viewall-supplier-inventories', [SupplierInventoryController::class,'viewAll'])->name('supplier-inventories.view-all');
    Route::post('supplier-inventories/update-inventory', [SupplierInventoryController::class,'updateInventory'])->name('update-inventory');
    Route::get('/check-unique-chasis', [SupplierInventoryController::class,'checkChasisUnique'])->name('supplier-inventories.unique-chasis');
    Route::get('/check-production-month', [SupplierInventoryController::class,'checkProductionMonth'])->name('supplier-inventories.checkProductionMonth');
    Route::get('/isExistColorCode', [SupplierInventoryController::class,'isExistColorCode'])->name('supplier-inventories.isExistColorCode');
    Route::get('/unique-production-month', [SupplierInventoryController::class,'uniqueProductionMonth'])->name('supplier-inventories.uniqueProductionMonth');
    Route::get('inventory-logs/{id}', [SupplierInventoryController::class,'inventoryLogs'])->name('inventory-logs.lists');
    Route::get('/check-delivery-note', [SupplierInventoryController::class,'checkDeliveryNote'])->name('supplier-inventories.check-delivery-note');


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
    Route::get('leadsexport', [CallsController::class,'leadsexport'])->name('calls.leadsexport');
    Route::post('exportsleadsform', [CallsController::class,'exportsleadsform'])->name('calls.exportsleadsform');
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
    Route::post('/summernote/upload', [CallsController::class, 'upload'])->name('summernote.upload');

    Route::resource('strategy', StrategyController::class);
    Route::post('calls/check-existence', [CallsController::class, 'checkExistence'])->name('checkExistence');
    Route::post('calls/check-checkExistenceupdatecalls', [CallsController::class, 'checkExistenceupdatecalls'])->name('checkExistenceupdatecalls');
    Route::get('customers/repeatedcustomers', [Repeatedcustomers::class, 'repeatedcustomers'])->name('repeatedcustomers');
    Route::put('/strategy-updates/{id}', [StrategyController::class, 'updaters'])->name('strategy.updaters');
    Route::post('/update-priority', [StrategyController::class, 'updatePriority'])->name('strategy.updatePriority');
    Route::get('/bulkLeadsDataUplaodExcel', [CallsController::class,'bulkLeadsDataUplaodExcel'])->name('calls.bulkLeadsDataUplaodExcel');
    Route::post('/calls/removerow', [CallsController::class, 'removeRow'])->name('calls.removerow');
    Route::post('/calls/updaterow', [CallsController::class, 'updaterow'])->name('calls.updaterow');
    Route::post('/calls/updatehol', [CallsController::class, 'updatehol'])->name('calls.updatehol');
    Route::get('new-variants/createnewvarinats', [CallsController::class,'createnewvarinats'])->name('calls.createnewvarinats');
    Route::get('new-variants/varinatinfo', [CallsController::class, 'varinatinfo'])->name('calls.varinatinfo');
    Route::get('new-leads/addnewleads', [CallsController::class, 'addnewleads'])->name('calls.addnewleads');
    Route::post('new-leads/storeleads', [CallsController::class, 'storeleads'])->name('calls.storeleads');
    Route::post('new-variants/storenewvarinats', [CallsController::class, 'storenewvarinats'])->name('calls.storenewvarinats');
    Route::resource('sale_person_status', SalesPersonStatusController::class);
    Route::get('postingrecords/{id}', [PostingRecordsController::class, 'postingrecords'])->name('postingrecords');
    Route::get('createposting/{leadssource_id}', [PostingRecordsController::class, 'createposting'])->name('posting.createposting');
    Route::post('storeposting/{leadssource_id}', [PostingRecordsController::class, 'storeposting'])->name('posting.storeposting');
    Route::resource('marketingpurchasingpayments', MarketingPurchasingPaymentsController::class);

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
    Route::post('dailyleads/getvins', [QuotationController::class, 'getvinsqoutation'])->name('dailyleads.getvinsqoutation');
    Route::post('dailyleads/getlink', [QuotationController::class, 'getqoutationlink'])->name('dailyleads.getqoutationlink');
    // vehicle pictures
     Route::get('vehicle-pictures/variant-details', [VehiclePicturesController::class,'getVariantDetail'])->name('vehicle-pictures.variant-details');
     Route::resource('vehicle-pictures', VehiclePicturesController::class);
     Route::post('getVinForVehicle', [VehiclePicturesController::class, 'getVinForVehicle']);
     Route::get('vehicle_pictures/pending', [VehiclePicturesController::class,'pending'])->name('vehicle_pictures.pending');
     Route::post('vehicle_pictures/saving', [VehiclePicturesController::class,'saving'])->name('vehicle_pictures.saving');


     // Variants
    Route::resource('variants', VariantController::class);
    Route::post('variants/modifications', [VariantController::class,'variantmodifications'])->name('variants.variantmodifications');
    Route::get('/variants/addons/{id}', [VariantController::class, 'variantsaddons'])->name('variants.variantsaddons');
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
    Route::name('calls.showcalls')
    ->get('calls/{call}/{brand_id}/{model_line_id}/{location}/{days}/{custom_brand_model?}', [CallsController::class, 'showcalls'])
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
    // Route::POST('hiring', [HiringController::class, 'jobStore'])->name('jobStore');
    // Route::POST('hiring', [HiringController::class, 'jobUpdate'])->name('jobUpdate');
    // Salary Certificate Routes
    Route::prefix('employee-relation/salary-certificate')->group(function () {
        Route::get('/create', [SalaryCertificateController::class, 'create'])->name('employeeRelation.salaryCertificate.create');
        Route::post('/store', [SalaryCertificateController::class, 'store'])->name('employeeRelation.salaryCertificate.store');
        Route::get('/index', [SalaryCertificateController::class, 'index'])->name('employeeRelation.salaryCertificate.index');
        Route::get('/{id}/generate', [SalaryCertificateController::class, 'generateSalaryCertificate'])->name('employeeRelation.salaryCertificate.generateSalaryCertificate');

        Route::get('/{id}/show', [SalaryCertificateController::class, 'show'])->name('employeeRelation.salaryCertificate.show');
        Route::get('/{id}/edit', [SalaryCertificateController::class, 'edit'])->name('employeeRelation.salaryCertificate.edit');
        Route::post('/{id}/update', [SalaryCertificateController::class, 'update'])->name('employeeRelation.salaryCertificate.update');
        Route::get('/{id}', [SalaryCertificateController::class, 'downloadCertificate'])->name('employeeRelation.salaryCertificate.downloadCertificate');
    });

    // Achievement Certificate Routes
    Route::prefix('employee-relation/achievement-certificate')->group(function () {
        Route::get('/create', [AchievementCertificateController::class, 'create'])->name('employeeRelation.achievementCertificate.create');
        Route::post('/store', [AchievementCertificateController::class, 'store'])->name('employeeRelation.achievementCertificate.store');
        Route::get('/index', [AchievementCertificateController::class, 'index'])->name('employeeRelation.achievementCertificate.index');
        Route::get('/{id}/show', [AchievementCertificateController::class, 'show'])->name('employeeRelation.achievementCertificate.show');
        Route::post('/{id}/update', [AchievementCertificateController::class, 'update'])->name('employeeRelation.achievementCertificate.update');
    });

    // Letter Request Routes
    Route::prefix('employee-relation/letter-request')->group(function () {
        Route::get('/create', [LetterRequestController::class, 'create'])->name('employeeRelation.letterRequest.create');
        Route::post('/store', [LetterRequestController::class, 'store'])->name('employeeRelation.letterRequest.store');
        Route::get('/index', [LetterRequestController::class, 'index'])->name('employeeRelation.letterRequest.index');
        Route::get('/{id}/show', [LetterRequestController::class, 'show'])->name('employeeRelation.letterRequest.show');
        Route::post('/{id}/update', [LetterRequestController::class, 'update'])->name('employeeRelation.letterRequest.update');
    });
    //WareHouse
    Route::resource('purchasing-order', PurchasingOrderController::class);
    Route::resource('Vehicles', VehiclesController::class);
    Route::get('vehicles/filter', [VehiclesController::class, 'index'])->name('vehicles.filter');
    Route::match(['get', 'post'], 'vehicles/statuswise', [VehiclesController::class, 'statuswise'])->name('vehicles.statuswise');
    Route::get('vehicles/currentstatus', [VehiclesController::class, 'currentstatus'])->name('vehicles.currentstatus');
    Route::post('vehicles/statussreach', [VehiclesController::class, 'statussreach'])->name('vehicles.statussearch');
    // Route::get('/search-data', [VehiclesController::class, 'searchData'])->name('vehicles.search-data');
    Route::post('purchasing-order/check-po-number', [PurchasingOrderController::class, 'checkPONumber'])->name('purchasing-order.checkPONumber');
    Route::post('purchasing-order/updatebasicdetails', [PurchasingOrderController::class, 'updatebasicdetails'])->name('purchasing-order.updatebasicdetails');
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
    // Route::get('grnlist/netsuitgrn', [MovementController::class, 'grnlist'])->name('grnlist.create'); // not using anywhere
    // Route::get('grnlist/grnsimplefile', [MovementController::class,'grnsimplefile'])->name('grnlist.grnsimplefile');
    // Route::post('grnlist/post-file', [MovementController::class, 'grnfilepost'])->name('grnlist.grnfilepost'); // not using anywhere
    Route::post('/check-create-vins', [PurchasingOrderController::class, 'checkcreatevins'])->name('vehicles.check-create-vins');
    Route::post('/check-create-vins-inside', [PurchasingOrderController::class, 'checkcreatevinsinside'])->name('vehicles.check-create-vins-inside');
    Route::patch('/check-edit-vins', [PurchasingOrderController::class, 'checkeditvins'])->name('vehicles.check-edit-vins');
    Route::patch('/check-edit-create-vins', [PurchasingOrderController::class, 'checkeditcreate'])->name('vehicles.check-edit-create');
    Route::get('users/update-role/{roleId}', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::get('/view-log-details/{id}', [VehiclesController::class, 'viewLogDetails'])->name('vehicleslog.viewdetails');
    Route::resource('colourcode', ColorCodesController::class);
    Route::post('/update-purchasing-data', [PurchasingOrderController::class, 'updatepurchasingData'])->name('purchasing.updateData');
    Route::post('/update-purchasing-status', [PurchasingOrderController::class, 'purchasingupdateStatus'])->name('purchasing.updateStatus');
    Route::resource('warehouse', WarehouseController::class);
    Route::resource('countries', CountryController::class);
    Route::post('/update-warehouse-remarks', [WarehouseController::class, 'updatewarehouseremarks'])->name('warehouse.updatewarehouseremarks');
    Route::get('vehicles/payment-confirm/{id}', [PurchasingOrderController::class, 'confirmPayment'])->name('vehicles.paymentconfirm');
    Route::get('vehicles/payment-paymentremanings/{id}', [PurchasingOrderController::class, 'paymentremanings'])->name('vehicles.paymentremanings');
    Route::post('purchasing_order/cancel/{id}', [PurchasingOrderController::class, 'cancel'])->name('vehicles.cancel');
    Route::get('/vehicles/rejecteds/{id}', [PurchasingOrderController::class, 'rejecteds'])->name('vehicles.rejecteds');
    Route::get('/vehicles/approvedcancel/{id}', [PurchasingOrderController::class, 'approvedcancel'])->name('vehicles.approvedcancel');
    Route::get('/vehicles/unrejecteds/{id}', [PurchasingOrderController::class, 'unrejecteds'])->name('vehicles.unrejecteds');
    Route::get('/vehicles/deletevehicles/{id}', [PurchasingOrderController::class, 'deletevehicles'])->name('vehicles.deletevehicles');
    Route::get('vehicles/paymentintconfirm/{id}', [PurchasingOrderController::class, 'paymentintconfirm'])->name('vehicles.paymentintconfirm');
    Route::get('vehicles/paymentintconfirmrej/{id}', [PurchasingOrderController::class, 'paymentintconfirmrej'])->name('vehicles.paymentintconfirmrej');
    Route::get('vehicles/repaymentintiation/{id}', [PurchasingOrderController::class, 'repaymentintiation'])->name('vehicles.repaymentintiation');
    Route::get('vehicles/paymentreleaserejected/{id}', [PurchasingOrderController::class, 'paymentreleaserejected'])->name('vehicles.paymentreleaserejected');
    Route::get('vehicles/paymentreleaseconfirm/{id}', [PurchasingOrderController::class, 'paymentreleaseconfirm'])->name('vehicles.paymentreleaseconfirm');
    Route::get('vehicles/paymentrelconfirm/{id}', [PurchasingOrderController::class, 'paymentrelconfirm'])->name('vehicles.paymentrelconfirm');
    Route::get('vehicles/paymentreleasesconfirm/{id}', [PurchasingOrderController::class, 'paymentreleasesconfirm'])->name('vehicles.paymentreleasesconfirm');
    Route::post('vehicles/paymentreleasesrejected/{id}', [PurchasingOrderController::class, 'paymentreleasesrejected'])->name('vehicles.paymentreleasesrejected');
    Route::post('vehicles/paymentrelconfirmdebited/{id}', [PurchasingOrderController::class, 'paymentrelconfirmdebited'])->name('vehicles.paymentrelconfirmdebited');
    Route::post('/update-purchasing-allstatus', [PurchasingOrderController::class, 'purchasingallupdateStatus'])->name('purchasing.updateallStatus');
    Route::get('vehicles/paymentrelconfirmvendors/{id}', [PurchasingOrderController::class, 'paymentrelconfirmvendors'])->name('vehicles.paymentrelconfirmvendors');
    Route::get('vehicles/paymentrelconfirmincoming/{id}', [PurchasingOrderController::class, 'paymentrelconfirmincoming'])->name('vehicles.paymentrelconfirmincoming');

    Route::get('/purcahsing-order-filter/{status}', [PurchasingOrderController::class, 'filter'])->name('purchasing.filter');
    Route::get('/purcahsing-order-filter-cancel/{status}', [PurchasingOrderController::class, 'filtercancel'])->name('purchasing.filtercancel');
    Route::get('/purcahsing-order-filterpayment/{status}', [PurchasingOrderController::class, 'filterpayment'])->name('purchasing.filterpayment');
    Route::get('/purcahsing-order-filterpaymentrejectioned/{status}', [PurchasingOrderController::class, 'filterpaymentrejectioned'])->name('purchasing.filterpaymentrejectioned');
    Route::get('/purcahsing-order-filterpaymentrel/{status}', [PurchasingOrderController::class, 'filterpaymentrel'])->name('purchasing.filterpaymentrel');
    Route::get('/purcahsing-order-filterintentreq/{status}', [PurchasingOrderController::class, 'filterintentreq'])->name('purchasing.filterintentreq');
    Route::get('/purcahsing-order-filterpendingrelease/{status}', [PurchasingOrderController::class, 'filterpendingrelease'])->name('purchasing.filterpendingrelease');
    Route::get('/purcahsing-order-filterpendingdebits/{status}', [PurchasingOrderController::class, 'filterpendingdebits'])->name('purchasing.filterpendingdebits');
    Route::get('/purcahsing-order-filterpendingfellow/{status}', [PurchasingOrderController::class, 'filterpendingfellow'])->name('purchasing.filterpendingfellow');
    Route::get('/purcahsing-order-payment-initiation/{status}', [PurchasingOrderController::class, 'paymentinitiation'])->name('purchasing.paymentinitiation');
    Route::get('/purcahsing-order-payment-confirmation-incoming/{status}', [PurchasingOrderController::class, 'filterconfirmation'])->name('purchasing.filterconfirmation');
    Route::get('/purcahsing-order-pending-vins/{status}', [PurchasingOrderController::class, 'pendingvins'])->name('purchasing.pendingvins');
    Route::post('/update-purchasing-allstatusrel', [PurchasingOrderController::class, 'purchasingallupdateStatusrel'])->name('purchasing.updateallStatusrel');
    Route::post('/update-purchasing-allpaymentreqss', [PurchasingOrderController::class, 'allpaymentreqss'])->name('purchasing.allpaymentreqss');
    Route::post('/update-purchasing-allpaymentreqssfin', [PurchasingOrderController::class, 'allpaymentreqssfin'])->name('purchasing.allpaymentreqssfin');
    Route::post('/update-purchasing-allpaymentreqssfinremainig', [PurchasingOrderController::class, 'allpaymentreqssfinremainig'])->name('purchasing.allpaymentreqssfinremainig');
    Route::post('/update-purchasing-allpaymentreqssfinpay', [PurchasingOrderController::class, 'allpaymentreqssfinpay'])->name('purchasing.allpaymentreqssfinpay');
    Route::post('/update-purchasing-rerequestpayment', [PurchasingOrderController::class, 'rerequestpayment'])->name('purchasing.rerequestpayment');
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
    Route::post('movement/unique-check',[MovementController::class,'checkDuplicateMovement'])->name('movement.unique-check');

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
    Route::get('/incident-inspection/{vehicleId}', [ApprovalsController::class, 'getincidentInspectionData']);
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
    Route::resource('model-year-calculation-rules', ModelYearCalculationRuleController::class);
    Route::resource('model-year-calculation-categories', ModelYearCalculationCategoriesController::class);
    // Variant Attributes
    Route::get('fetch-model-spectifications', [VariantController::class,'fetchModelSpecifications'])
        ->name('fetch.model_spectifications');
    Route::get('fetch-model-spectification-options', [VariantController::class,'fetchModelSpecificationOptions'])
    ->name('fetch.model_spectification_options');

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
    Route::get('candidate/documents/{id}', [CandidatePersonalInfoController::class, 'sendForm'])->name('candidate_documents.send_form');
    Route::post('candidate/store_docs', [CandidatePersonalInfoController::class, 'storeDocs'])->name('candidate.storeDocs');
    Route::get('candidate/success_docs', [CandidatePersonalInfoController::class, 'successDocs'])->name('candidate.successDocs');
    Route::get('candidate/personal_info/{id}', [CandidatePersonalInfoController::class, 'sendPersonalForm'])->name('candidate_personal_info.send_form');
    Route::post('candidate/store_personal_info', [CandidatePersonalInfoController::class, 'storePersonalinfo'])->name('candidate.storePersonalinfo');
    Route::get('candidate/success_personal_info', [CandidatePersonalInfoController::class, 'successPersonalinfo'])->name('candidate.successPersonalinfo');
    Route::get('candidate-offer-letter/sign/{id}', [CandidatePersonalInfoController::class, 'signJobOfferLetter'])->name('candidate-offer-letter.sign');
    Route::post('offer-letter/signed', [CandidatePersonalInfoController::class, 'signedOfferLetter'])->name('offerletter.signed');


    //Payment Terms
    Route::resource('paymentterms', PaymentTermsController::class);
    Route::resource('salestargets', SalesTargetsController::class);

    //Customers
    Route::get('sales/customers', [CustomerController::class, 'salescustomers'])->name('salescustomers.index');
    Route::get('sales/customers/create', [CustomerController::class, 'createcustomers'])->name('salescustomers.create');
    Route::post('sales/customers/store', [CustomerController::class, 'storecustomers'])->name('salescustomers.store');
    Route::get('sales/customers/view-history/{clientId}', [CustomerController::class, 'viewHistory'])->name('salescustomers.viewHistory');
    Route::get('sales/customers/view/{clientId}', [CustomerController::class, 'viewcustomers'])->name('salescustomers.viewcustomers');
    Route::get('sales/customers/leadsview/{clientId}', [CustomerController::class, 'viewleads'])->name('salescustomers.viewleads');
    Route::get('sales/customers/qoutationview/{clientId}', [CustomerController::class, 'qoutationview'])->name('salescustomers.qoutationview');
    Route::get('/clienttransitions/{client_id}', [ClientAccountTransitionController::class, 'clienttransitionsview'])->name('clienttransitions.clienttransitions');
    Route::resource('clienttransitions', ClientAccountTransitionController::class);

    //Sales Order
    Route::get('/saleorder/{callId}', [SalesOrderController::class, 'createsalesorder'])->name('salesorder.createsalesorder');
    Route::get('/preorder/{callId}', [PreOrderController::class, 'createpreorder'])->name('preorder.createpreorder');
    Route::post('/saleorderstore/{QuotationId}', [SalesOrderController::class, 'storesalesorder'])->name('salesorder.storesalesorder');
    Route::post('/preorderstore/{QuotationId}', [PreOrderController::class, 'storepreorder'])->name('preorder.storespreorder');
    Route::get('/variants_details/{id}', [VariantController::class, 'getvariantsdetails'])->name('variants.getvariantsdetails');
    Route::post('/get-vehicles-vin', [QuotationController::class, 'getVehiclesvins']);
    Route::post('/get-vehicles-vin-first', [QuotationController::class, 'getVehiclesvinsfirst']);
    Route::get('/salesorder/update/{id}', [SalesOrderController::class, 'updatesalesorder'])->name('salesorder.updatesalesorder');
    Route::get('/customer-quotation-direct/{id}', [QuotationController::class, 'directquotationtocustomer']);

    Route::prefix('so-finalizations')->group(function () {
        Route::get('/', [SoFinalizationController::class, 'index'])->name('so_finalizations.index');
        Route::post('/', [SoFinalizationController::class, 'store'])->name('so_finalizations.store');
        Route::get('/edit/{so_number}', [SoFinalizationController::class, 'edit'])->name('so_finalizations.edit');
    });

    //Pre Order
    Route::resource('preorder', PreOrderController::class);
    Route::get('/get-po-for-presale', [PreOrderController::class, 'getpoforpreorder']);
    Route::post('/update-followup-info', [DailyleadsController::class, 'savefollowup'])->name('sales.savefollowup');
    Route::post('/update-followup-info-update', [DailyleadsController::class, 'savefollowupdate'])->name('sales.savefollowupdate');
    Route::get('/update-followup-info-data/{id}', [DailyleadsController::class, 'followupgetdata'])->name('sales.followupgetdata');

    //Leads Notifications
    Route::resource('leadsnotifications', LeadsNotificationsController::class);
    Route::get('/leads/{call_id}', [LeadsNotificationsController::class, 'viewLead'])->name('view_lead');
    Route::post('/update-notifications-status', [LeadsNotificationsController::class, 'updateStatus'])->name('update_notifications_status');
    Route::get('/viewgrnreport/method', [VehiclesController::class, 'generategrnPDF']);

    Route::get('/countries/{id}/neighbors', [ProformaInvoiceController::class, 'getNeighbors']);


    Route::post('/upload-quotation-file', [QuotationController::class, 'uploadingquotation'])->name('uploadingquotation.update');
    Route::get('/get-agents/{quotationId}', [QuotationController::class, 'getAgentsByQuotationId']);
    Route::post('/fetchData', [VehiclesController::class, 'fetchData'])->name('fetchData');
    Route::post('purchasing_order/cancelpo/{id}', [PurchasingOrderController::class, 'cancelpo'])->name('purchasing_order.cancelpo');
    Route::post('/update-purchasing-status-cancel', [PurchasingOrderController::class, 'purchasingupdateStatuscancel'])->name('purchasing.updateStatuscancel');
    Route::post('/check-authorization', [DailyleadsController::class, 'checkAuthorization'])->name('checkAuthorization');

    //Vendor Accounts
    Route::resource('vendoraccount', VendorAccountController::class);
    Route::get('/account/{id}', [VendorAccountController::class, 'view'])->name('vendoraccount.view');
    Route::get('/get-supplier-and-amount/{orderId}', [PurchasingOrderController::class, 'getSupplierAndAmount']);
    Route::post('/transition/action', [VendorAccountController::class, 'handleAction'])->name('transition.action');

    //Price Update Purchased Order
    Route::post('vehicles/update-dp-prices', [PurchasingOrderController::class, 'updatePOPrices'])->name('vehicles.updateDPPrices');
    Route::get('purchasedorder/vehicles-data/{id}', [PurchasingOrderController::class, 'vehiclesdatagetting'])->name('vehicles.vehiclesdatagetting');
    Route::post('vehicles/update-prices', [PurchasingOrderController::class, 'updatePrices'])->name('vehicles.updatePrices');
    Route::post('/messagespurchased', [PurchasingOrderController::class, 'storeMessages']);
    Route::post('/repliespurchased', [PurchasingOrderController::class, 'storeReply']);
    Route::get('/messagespurchased/{purchaseOrderId}', [PurchasingOrderController::class, 'indexmessages']);
    Route::get('purchasedorder/vehicles-data-variants/{id}', [PurchasingOrderController::class, 'vehiclesdatagettingvariants'])->name('vehicles.vehiclesdatagettingvariants');
    Route::post('/vehicles/updateVariants', [PurchasingOrderController::class, 'updateVariants'])->name('vehicles.updateVariants');
    Route::get('/viewpdireport/method', [VehiclesController::class, 'generatepfiPDF']);

    //Bank Accounts
    Route::resource('bankaccounts', BankAccountsController::class);
    Route::post('/bankaccounts/update_balance', [BankAccountsController::class, 'updateBalance'])->name('bankaccounts.update_balance');
    Route::get('/bankaccount/{id}', [BankAccountsController::class, 'show'])->name('bankaccount.show');
    Route::resource('banks', BanksController::class);


    // Migration Data check Route
    Route::resource('migrations', MigrationDataCheckController::class);

    //Addinational Payments
    Route::post('/request-additional-payment', [PurchasingOrderController::class, 'requestAdditionalPayment']);
    Route::post('/request-initiated-payment', [PurchasingOrderController::class, 'requestinitiatedPayment']);
    Route::post('/request-released-payment', [PurchasingOrderController::class, 'requestreleasedPayment']);
    Route::post('/update-purchasing-additionalpaymentcomplete', [PurchasingOrderController::class, 'completedadditionalpayment'])->name('purchasing.completedadditionalpayment');
    Route::get('netsuitegrn/addingnetsuitegrn', [ApprovalsController::class, 'addingnetsuitegrn'])->name('netsuitegrn.addingnetsuitegrn');
    Route::post('netsuitegrn/submit', [ApprovalsController::class, 'submitGrn'])->name('netsuitegrn.submit');
    // Route::post('netsuitegrn/add', [ApprovalsController::class, 'addGrn'])->name('netsuitegrn.add');
    Route::get('/get-vehicles/{purchaseOrderId}', [PurchasingOrderController::class, 'getVehiclesByPurchaseOrderId']);
    Route::get('/getVehicles/{purchaseOrderId}', [PurchasingOrderController::class, 'getVehicles']);
    Route::get('/getVehicleDetails/{vehicleId}', [PurchasingOrderController::class, 'getVehicleDetails']);
    Route::post('/savePaymentDetails', [PurchasingOrderController::class, 'savePaymentDetails']);
    Route::post('/submitPaymentDetails', [PurchasingOrderController::class, 'submitPaymentDetails']);
    Route::post('/transition/actioninitiate', [PurchasingOrderController::class, 'handleActioninitiate'])->name('transition.actioninitiate');
    Route::get('/get-vendor-and-balance/{purchaseOrderId}', [PurchasingOrderController::class, 'getVendorAndBalance']);
    Route::post('/transition/submitforpayment', [PurchasingOrderController::class, 'submitforpayment'])->name('transition.submitforpayment');
    Route::post('/submit-payment', [PurchasingOrderController::class, 'submitPayment']);
    Route::post('/approve-transition', [PurchasingOrderController::class, 'approveTransition'])->name('approve.transition');
    Route::post('/reject-transition', [PurchasingOrderController::class, 'rejectTransition']);
    Route::post('/reject-transition-linitiate', [PurchasingOrderController::class, 'rejectTransitionlinitiate']);
    Route::post('/upload-swift-file', [PurchasingOrderController::class, 'uploadSwiftFile'])->name('uploadSwiftFile');
    Route::get('/get-swift-details/{id}', [PurchasingOrderController::class, 'getSwiftDetails'])->name('getSwiftDetails');
    Route::post('/vehicles/hold/{id}', [VehiclesController::class, 'hold'])->name('vehicles.hold');
    Route::post('/transition/paymentconfirm', [PurchasingOrderController::class, 'paymentconfirm'])->name('transition.paymentconfirm');
    Route::get('/getdata', [PurchasingOrderController::class, 'getdata'])->name('purchased.getdata');


Route::get('/check-trashed-gdn', function () {
    $trashed = Gdn::onlyTrashed()->get();
    dd($trashed);
});

    //Netsuite GDN
    Route::get('netsuitegdn/addingnetsuitegdn', [ApprovalsController::class, 'addingnetsuitegdn'])->name('netsuitegdn.addingnetsuitegdn');
    Route::post('netsuitegdn/submit', [ApprovalsController::class, 'submitGdn'])->name('netsuitegdn.submit');
    Route::post('netsuitegdn/add', [ApprovalsController::class, 'addGdn'])->name('netsuitegdn.add');
    Route::resource('departmentnotifications', DepartmentNotificationsController::class);
    Route::post('/save-dn-numbers', [PurchasingOrderController::class, 'saveDnNumbers'])->name('save.dnNumbers');
    Route::get('/getVehiclesdn/{purchaseOrderId}', [PurchasingOrderController::class, 'getVehiclesdn']);
    Route::post('/saleorderstoreupdate/{QuotationId}', [SalesOrderController::class, 'storesalesorderupdate'])->name('salesorder.storesalesorderupdate');
    Route::get('/not-access', [AccessController::class, 'notAccessPage'])->name('not_access_page');

    Route::resource('vehiclenetsuitecost', VehicleNetsuiteCostController::class);
    Route::post('/booking/savedirectly', [BookingController::class, 'storedirect'])->name('booking.savedirectly');
    Route::get('/salesorder/cancel/{id}', [SalesOrderController::class, 'cancel'])->name('salesorder.cancel');

    //Stock Messages
    Route::get('/stockmessages/{vehicleId}', [StockMessageController::class, 'stockgetMessages'])->name('stockmessages.get');
    Route::post('/stockmessages', [StockMessageController::class, 'stocksendMessage'])->name('stockmessages.send');
    Route::post('/stockreplies', [StockMessageController::class, 'stocksendReply'])->name('stockreplies.send');
    Route::get('/vehicle-details-dp', [StockMessageController::class, 'getVehicleDetailsdp'])->name('vehicle.detailsdp');
    Route::get('/vehicle-details-dpbelgium', [StockMessageController::class, 'getVehicleDetailsdpbelgium'])->name('vehicle.detailsdpbelgium');
    Route::post('/vehiclenetsuitecost/upload', [VehicleNetSuiteCostController::class, 'upload'])->name('vehiclenetsuitecost.upload');
    Route::get('/all-variant-prices', [VehiclesController::class, 'allvariantprice'])->name('variantprices.allvariantprice');
    Route::post('/all-variant-prices-update', [VehiclesController::class, 'allvariantpriceupdate'])->name('variantprices.allvariantpriceupdate');
    Route::post('/custom-inspection-update', [VehiclesController::class, 'custominspectionupdate'])->name('vehicles.savecustominspection');
    Route::post('/booking/canceling', [BookingController::class, 'canceling'])->name('booking.canceling');
    Route::post('/get-reservation', [VehiclesController::class, 'getReservation'])->name('get.reservation');
    Route::post('/movement/revised/{id}', [MovementController::class, 'revise'])->name('movement.revised');
    Route::post('/enhancement', [VehiclesController::class, 'saveenhancement'])->name('enhancement.save');
    Route::get('/enhancement/getVariants', [VehiclesController::class, 'getVariants'])->name('enhancement.getVariants');
    Route::get('/enhancement/getcolours', [VehiclesController::class, 'getcolours'])->name('get.color.data');
    Route::post('/enhancementcolour', [VehiclesController::class, 'saveenhancementcolor'])->name('enhancement.savecolour');
    Route::post('/vehicles/uploadVinFile', [MovementController::class, 'uploadVinFile'])->name('vehicles.uploadVinFile');
    Route::get('/get-custom-inspection-data', [VehiclesController::class, 'getCustomInspectionData']);
    Route::match(['get', 'post'], 'vehicles/available', [VehiclesController::class, 'availablevehicles'])->name('vehicles.availablevehicles');
    Route::match(['get', 'post'], 'vehicles/delivered', [VehiclesController::class, 'deliveredvehicles'])->name('vehicles.deliveredvehicles');
    Route::match(['get', 'post'], 'vehicles/dpvehicles', [VehiclesController::class, 'dpvehicles'])->name('vehicles.dpvehicles');
    Route::post('/sales-remarks', [VehiclesController::class, 'savesalesremarks'])->name('vehicles.savesalesremarks');
    Route::get('/get-sales-remarks', [VehiclesController::class, 'getsalesremarks']);
    Route::resource('salesorder', SalesOrderController::class);
    Route::get('/sales-summary/{sales_person_id}/{count_type}', [SalesOrderController::class, 'showSalesSummary'])->name('sales.summary');
    Route::resource('vehicleinvoice', VehicleInvoiceController::class);
    Route::post('/get-vehicles-by-so', [VehicleInvoiceController::class, 'getVehiclesBySO'])->name('getVehiclesBySO');
    Route::get('/viewinvoicereport/method', [VehicleInvoiceController::class, 'generateinvoicePDF']);
    Route::get('/salesperson-commissions/{sales_person_id}', [SalesOrderController::class, 'showSalespersonCommissions'])->name('salesperson.commissions');
    Route::get('/salesperson/vehicles/{vehicle_invoice_id}', [SalesOrderController::class, 'showVehicles'])->name('salesperson.vehicles');
    Route::post('/update-call-client', [DailyleadsController::class, 'updateCallClient'])->name('update-call-client');
    Route::get('/callsdeatilspage/{id}', [DailyleadsController::class, 'leaddetailpage'])->name('calls.leaddetailpage');
    Route::post('/leads/update', [DailyleadsController::class, 'leaddeupdate'])->name('calls.leaddeupdate');
    Route::post('/add-model-line', [DailyleadsController::class, 'addModelLine']);
    Route::delete('/remove-model-line/{requirementId}', [DailyleadsController::class, 'removeModelLine']);
    Route::post('/messages', [DailyleadsController::class, 'storeMessages']);
    Route::post('/replies', [DailyleadsController::class, 'storeReply']);
    Route::get('/messages/{leadid}', [DailyleadsController::class, 'indexmessages']);
    Route::get('/get-model-lines/{brandId}', [DailyleadsController::class, 'getModelLines']);
    Route::get('/get-trim-variants/{modelLineId}', [DailyleadsController::class, 'getTrimAndVariants']);
    Route::post('/upload-file', [DailyleadsController::class, 'fileupload'])->name('leadsfile.upload');
    Route::delete('/remove-file', [DailyleadsController::class, 'removeFile'])->name('leadsfile.remove');
    Route::post('/store-log', [DailyleadsController::class, 'storeLog'])->name('store.log');
    Route::get('/get-logs/{lead_id}', [DailyleadsController::class, 'getLogs'])->name('get.logs');
    Route::post('/store-task', [DailyleadsController::class, 'storeTask'])->name('taskstore.task');
    Route::post('/update-task', [DailyleadsController::class, 'updateTask'])->name('taskupdate.task');
    Route::get('/get-tasks/{lead_id}', [DailyleadsController::class, 'getTasks'])->name('get.tasks');
    Route::post('/tasks/update', [DailyleadsController::class, 'tasksupdateStatus'])->name('leads-tasks.update');
    Route::post('/leads/{leadId}/update-status', [DailyleadsController::class, 'updateStatus']);
    Route::post('/marekting/leadstatuswise', [HomeController::class, 'leadstatuswise'])->name('homemarketing.leadstatuswise');
    Route::get('/reasondata', [HomeController::class, 'getFilteredData']);
    Route::get('/show_leads_rejection', [HomeController::class, 'showRejectedLeads'])->name('leads.showrejection');
    Route::get('/export-uae-vehicle-stock', function () {
        return Excel::download(new UAEVehicleStockExport, 'uae_vehicle_stock.xlsx');
    });
    Route::get('/export-belgium-vehicle-stock', function () {
        return Excel::download(new BelgiumVehicleStockExport, 'belgium_vehicle_stock.xlsx');
    });
    Route::get('/get-onwership-data', [VehiclesController::class, 'getonwershipData']);
    Route::post('/onwership-update', [VehiclesController::class, 'saveonwership'])->name('vehicles.saveonwership');
    Route::post('/purchasing-order/check-po-number-edit', [PurchasingOrderController::class, 'checkPoNumberedit'])->name('purchasing-order.checkPoNumberedit');
    Route::post('/custom-documentstatus-update', [VehiclesController::class, 'customdocumentstatusupdate'])->name('vehicles.customdocumentstatusupdate');
    Route::get('/variants/{id}/editvar', [VariantController::class, 'editvar'])->name('variants.editvar');
    Route::post('/variants/storevar/{variant}', [VariantController::class, 'storevar'])->name('variants.storevar');
    Route::resource('modeldescription', ModeldescriptionController::class);
    Route::resource('mastergrade', MasterGradeController::class);
    Route::resource('master-charges', MasterChargesController::class);
    Route::get('/transfer_copy/send-email-to-supplier', [PurchasingOrderController::class, 'sendTransferCopy'])
    ->name('send-transfer-copy.email');
    Route::get('/swift_copy/send-email-to-supplier', [PurchasingOrderController::class, 'sendSwiftCopy'])
    ->name('send-swift-copy.email');
    Route::post('/check-vehicle-quantity', [VehiclesController::class, 'checkVehicleQuantity'])->name('check.vehicle.quantity');
    Route::get('/salespersons/list', [SalesOrderController::class, 'getSalespersons'])->name('salespersons.list');
    Route::post('/salesorder/updateSalesperson', [SalesOrderController::class, 'updateSalesperson'])->name('salesorder.updateSalesperson');
    Route::post('po-payment-adjustment', [PurchasingOrderController::class, 'paymentAdjustment'])->name('po-payment-adjustment');
    Route::get('so-quotation-versions/{id}', [SalesOrderController::class, 'viewQuotations'])->name('so.quotation-versions');
    Route::post('so-approve-reject', [SalesOrderController::class, 'approveOrRejectSO'])->name('so.approveOrReject');
    Route::get('so-unique-check', [SalesOrderController::class, 'checkUniqueSoNumber'])->name('so.uniqueSoNumber');
    Route::get('so-variants', [SalesOrderController::class, 'getVariants'])->name('so.getVariants');
    Route::get('so-vins', [SalesOrderController::class, 'getVins'])->name('so.getVins');
});
