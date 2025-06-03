<?php

use App\Http\Controllers\Backend as Backend;
use App\Http\Controllers\Backend\AddonController;
use App\Http\Controllers\Backend\AttachmentController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\CityController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\ContractController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\EstimateController;
use App\Http\Controllers\Backend\InsurancesController;
use App\Http\Controllers\Backend\LocationController;
use App\Http\Controllers\Backend\MeasurementUnitController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReminderController;
use App\Http\Controllers\Backend\RequireDocumentController;
use App\Http\Controllers\Backend\Settings\ContractTemplateController;
use App\Http\Controllers\Backend\Settings\EmailTemplateController;
use App\Http\Controllers\Backend\Settings\LeadSourceController;
use App\Http\Controllers\Backend\Settings\LeadStatusController;
use App\Http\Controllers\Backend\StorageTypeController;
use App\Http\Controllers\Backend\StorageUnitLevelController;
use App\Http\Controllers\Backend\StorageUnitsController;
use App\Http\Controllers\Backend\StorageUnitSizeController;
use App\Http\Controllers\Backend\TaskController;
use App\Http\Controllers\Backend\TermLengthController;
use App\Http\Controllers\Backend\WarehouseController;
use App\Http\Controllers\Frontend\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\InvoiceController;
use App\Http\Controllers\Backend\MoveInRequestController;
use App\Http\Controllers\Backend\MoveInController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\MoveOutController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\Settings\AppSettingsController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Backend\EmailController;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use App\Http\Controllers\Backend\QuickBooksWebhookController;
use App\Http\Controllers\Backend\ReportsController;

require __DIR__.'/auth.php';

    // Admin Routes
    Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [Backend\DashboardController::class, 'index'])->name('admin.index');
    Route::get('/get-lead-stat', [Backend\DashboardController::class, 'getLeadStat'])->name('admin.lead-stat');

    Route::resource('profile', Backend\ProfileController::class);
    Route::any('edit-profile', [Backend\ProfileController::class,'editProfile']);
    Route::any('update-profile', [Backend\ProfileController::class,'updateProfile']);
    Route::any('update-password', [Backend\ProfileController::class,'updatePassword']);

    //roles routes
    Route::resource('roles', Backend\RolesController::class);
    Route::get('roles-dt', [Backend\RolesController::class, 'dataTable'])->name('roles-datatable');
    Route::get('get-role', [Backend\RolesController::class, 'getRoles'])->name('get-role');
//  Route::any('save-role', [Backend\RolesController::class, 'saveRole'])->name('save-role');
    Route::any('save-role', [\App\Http\Controllers\Core\Auth\Role\RoleController::class, 'storesk'])->name('save-role');
    Route::any('edit-role/{id}', [Backend\RolesController::class, 'editRole'])->name('edit-role');
    Route::any('assign-role', [Backend\RolesController::class, 'assignRole'])->name('assign-role');
    Route::any('get-assign-user', [Backend\RolesController::class, 'getAssignedUsers'])->name('get-assign-user');
    Route::any('deattach-role', [Backend\RolesController::class, 'deattachRole'])->name('deattach-role');
//  Route::any('edit-role/{id}', [\App\Http\Controllers\Core\Auth\Role\RoleController::class, 'editsk'])->name('edit-role');
//  Route::any('update-role', [Backend\RolesController::class, 'updateRole'])->name('update-role');
    Route::any('update-role', [\App\Http\Controllers\Core\Auth\Role\RoleController::class, 'updatesk'])->name('update-role');
    Route::any('delete-role', [Backend\RolesController::class, 'deleteRole'])->name('delete-role');
    Route::any('delete-role/{id}', [\App\Http\Controllers\Core\Auth\Role\RoleController::class, 'destroysk'])->name('delete-role');

    //users routes
    Route::resource('users', Backend\UserController::class);
    Route::get('users-dt', [Backend\UserController::class, 'dataTable'])->name('users-datatable');
    Route::any('get-users', [Backend\UserController::class,'getUser']);
    Route::any('save-user', [Backend\UserController::class,'saveUser']);
    Route::any('delete-user', [Backend\UserController::class,'deleteUser']);
    Route::any('edit-user', [Backend\UserController::class,'editUser']);
    Route::any('update-user', [Backend\UserController::class,'updateUser']);

    //country routes
    Route::any('country', [CountryController::class,'index'])->name('country.index');
    Route::any('save-country', [CountryController::class,'saveCountry']);
    Route::any('get-countries', [CountryController::class,'getCountries']);
    Route::any('delete-country', [CountryController::class,'deleteCountry']);
    Route::any('edit-country', [CountryController::class,'editCountry']);
    Route::any('update-country', [CountryController::class,'updateCountry']);

    //city routes
    Route::any('city', [CityController::class,'index'])->name('city.index');
    Route::any('getAllCity', [CityController::class,'getAllCity']);
    Route::any('saveCity', [CityController::class,'saveCity']);
    Route::any('delete-city', [CityController::class,'deleteCity']);
    Route::any('edit-city', [CityController::class,'editCity']);
    Route::any('update-city', [CityController::class,'updateCity']);
    Route::any('get-country-base-city', [CityController::class,'getCountryBaseCity']);

    //location routes
    Route::any('location', [LocationController::class,'index'])->name('location.index');
    Route::any('save-location', [LocationController::class,'saveLocation']);
    Route::any('get-locations', [LocationController::class,'getAllLocations']);
    Route::any('delete-location', [LocationController::class,'deleteLocation']);
    Route::any('edit-location', [LocationController::class,'editLocation']);
    Route::any('update-location', [LocationController::class,'updateLocation']);

    //coupon routes
    Route::any('coupon', [CouponController::class,'index'])->name('coupon.index');
    Route::any('get-coupons', [CouponController::class,'getAllCoupons']);
    Route::any('save-coupon', [CouponController::class,'saveCoupons']);
    Route::any('delete-coupon', [CouponController::class,'deleteCoupon']);
    Route::any('edit-coupon', [CouponController::class,'editCoupon']);
    Route::any('update-coupon', [CouponController::class,'updateCoupon']);

    //addon routes
    Route::any('addon', [AddonController::class,'index'])->name('addon.index');
    Route::any('save-addon', [AddonController::class,'saveAddon']);
    Route::any('get-addon', [AddonController::class,'getAddon']);
    Route::any('delete-addon', [AddonController::class,'deleteAddon']);
    Route::any('edit-addon', [AddonController::class,'editAddon']);
    Route::any('update-addon', [AddonController::class,'updateAddon']);



    //warehouse routes
    Route::any('warehouse', [WarehouseController::class,'index'])->middleware('can:view_warehouse')->name('warehouse.index');
    Route::any('save-warehouse', [WarehouseController::class,'saveWarehouse']);
    Route::any('get-wh', [WarehouseController::class,'getAllWareHouse']);
    Route::any('delete-wh', [WarehouseController::class,'deleteWh']);
    Route::any('edit-wh', [WarehouseController::class,'editWh']);
    Route::any('update-wh', [WarehouseController::class,'updateWh']);

    //storage-unit-leve routes
    Route::any('storage-unit-level', [StorageUnitLevelController::class,'index'])->name('storage-unit-level.index');
    Route::any('save-storage-level', [StorageUnitLevelController::class,'saveStorageLevel']);
    Route::any('get-storage-level', [StorageUnitLevelController::class,'getStorageLevel']);
    Route::any('delete-storage-level', [StorageUnitLevelController::class,'deleteStorageLevel']);
    Route::any('edit-storage-level', [StorageUnitLevelController::class,'editStorageLevel']);
    Route::any('update-storage-level', [StorageUnitLevelController::class,'updateStorageLevel']);

    //storage-size routes
    Route::any('storage-size', [StorageUnitSizeController::class,'index'])->name('storage_size.index');
    Route::any('save-storage-size', [StorageUnitSizeController::class,'saveStorageSize']);
    Route::any('get-storage-size', [StorageUnitSizeController::class,'getStorageSize']);
    Route::any('delete-storage-size', [StorageUnitSizeController::class,'deleteStorageSize']);
    Route::any('edit-storage-size', [StorageUnitSizeController::class,'editStorageSize']);
    Route::any('update-storage-size', [StorageUnitSizeController::class,'updateStorageSize']);


    //storage-type routes
    Route::any('storage-type', [StorageTypeController::class,'index'])->name('storage_type.index');
    Route::any('save-storage-type', [StorageTypeController::class,'saveStorageType']);
    Route::any('get-storage-type', [StorageTypeController::class,'getStorageType']);
    Route::any('delete-storage-type', [StorageTypeController::class,'deleteStorageType']);
    Route::any('edit-storage-type', [StorageTypeController::class,'editStorageType']);
    Route::any('update-storage-type', [StorageTypeController::class,'updateStorageType']);

    //storage-unit routes
    Route::any('storage-unit', [StorageUnitsController::class,'index'])->name('storage_unit.index');
    Route::any('save-storage-unit', [StorageUnitsController::class,'saveStorageUnit']);
    Route::any('get-storage-unit', [StorageUnitsController::class,'getStorageUnit']);
    Route::any('delete-storage-unit', [StorageUnitsController::class,'deleteStorageUnit']);
    Route::any('edit-storage-unit', [StorageUnitsController::class,'editStorageUnit']);
    Route::any('update-storage-unit', [StorageUnitsController::class,'updateStorageUnit']);

    //insurance routes
    Route::any('insurance', [InsurancesController::class,'index'])->name('insurance.index');
    Route::any('save-insurance', [InsurancesController::class,'saveInsurance']);
    Route::any('get-insurance', [InsurancesController::class,'getInsurance']);
    Route::any('delete-insurance', [InsurancesController::class,'deleteInsurance']);
    Route::any('edit-insurance', [InsurancesController::class,'editInsurance']);
    Route::any('update-insurance', [InsurancesController::class,'updateInsurance']);

    //product routes
    Route::any('product', [ProductController::class,'index'])->name('product.index');
    Route::any('save-product', [ProductController::class,'saveProduct']);
    Route::any('get-product', [ProductController::class,'getProduct']);
    Route::any('get-product-detail', [ProductController::class,'getProductDetail']);
    Route::any('delete-product', [ProductController::class,'deleteProduct']);
    Route::any('edit-product', [ProductController::class,'editProduct']);
    Route::any('update-product', [ProductController::class,'updateProduct']);

    //blog routes
    Route::any('blog', [BlogController::class,'index'])->name('blog.index');
    Route::any('create-blog', [BlogController::class,'create'])->name('blog.create');
    Route::any('save-blog', [BlogController::class,'saveBlog']);
    Route::any('get-blog', [BlogController::class,'getBlog']);
    Route::any('delete-blog', [BlogController::class,'deleteBlog']);
    Route::any('edit-blog/{id}', [BlogController::class,'editBlog']);
    Route::any('update-blog', [BlogController::class,'updateBlog']);


    //measurement-unit routes
    Route::any('measurement-unit', [MeasurementUnitController::class,'index'])->name('measurement-unit.index');
    Route::any('save-measurement-unit', [MeasurementUnitController::class,'saveMeasurementUnit']);
    Route::any('get-measurement-unit', [MeasurementUnitController::class,'getMeasurementUnit']);
    Route::any('delete-measurement-unit', [MeasurementUnitController::class,'deleteMeasurementUnit']);
    Route::any('edit-measurement-unit', [MeasurementUnitController::class,'editMeasurementUnit']);
    Route::any('update-measurement-unit', [MeasurementUnitController::class,'updateMeasurementUnit']);


    //leads routes
    Route::any('leads', [LeadController::class,'index'])->name('leads.index')->middleware('can_access:view_lead');
    Route::any('create-lead', [LeadController::class,'create'])->name('lead.create')->middleware('can_access:create_lead');
    Route::any('create-lead-customer/{id}', [LeadController::class,'createLeadCustomer'])->name('lead.create.customer');
    Route::any('get-leads', [LeadController::class,'getLeads']);
    Route::any('get-customer-leads', [LeadController::class,'getCustomerLeads']);
    Route::any('save-lead', [LeadController::class, 'saveLeadBackend'])->name('save-lead');
    Route::any('edit-lead/{id}', [LeadController::class,'editLead']);
    Route::any('update-lead', [LeadController::class,'updateLead']);
    Route::any('show-lead', [LeadController::class,'showLead']);
    Route::any('delete-lead', [LeadController::class,'deleteLead']);
    Route::any('lead/profile/{id}', [LeadController::class,'viewLead'])->name('lead-profile');
    Route::any('change-lead-status', [LeadController::class,'changeStatus']);
    Route::any('change-lead-source', [LeadController::class,'changeSource']);
    Route::any('change-lead-assignee', [LeadController::class,'changeAssignee']);
    Route::any('lead/tasks/{id}', [LeadController::class,'showTasks'])->name('lead-tasks');
    Route::any('lead/attachments/{id}', [LeadController::class,'showAttachments'])->name('lead-attachments');
    Route::any('lead/reminders/{id}', [LeadController::class,'showReminders'])->name('lead-reminders');


    //estimate routes
    Route::any('estimate', [EstimateController::class,'index'])->name('estimate.index');
    Route::get('/estimate/{id}', [EstimateController::class, 'bookingEstimate'])->name('booking-estimate');
    Route::get('create-estimate', [EstimateController::class, 'create'])->name('create-estimate');
    Route::any('approve-estimate', [EstimateController::class, 'approveSendEstimate'])->name('approve-estimate');
    Route::any('decline-estimate', [EstimateController::class, 'declineSendEstimate'])->name('decline-estimate');
    Route::any('create-customer-estimate/{id}', [EstimateController::class, 'createCustomerEstimate'])->name('customer-estimate');
    Route::any('estimatetocustomer', [EstimateController::class, 'estimateToCustomer2'])->name('estimate-customer2');
    Route::any('save-estimate', [EstimateController::class, 'saveEstimate'])->name('save-estimate');
    Route::any('add-estimate', [EstimateController::class, 'addEstimate'])->name('add-estimate');
    Route::any('get-estimates', [EstimateController::class,'getEstimates']);
    Route::any('get-estimate', [EstimateController::class,'getEstimate']);
    Route::any('delete-estimate', [EstimateController::class,'deleteEstimate']);
    Route::any('get-customer-estimates', [EstimateController::class,'getCustomerEstimates']);
    Route::any('show-estimate', [EstimateController::class,'showEstimates']);
    Route::any('estimate/detail/{id}', [EstimateController::class, 'detailEstimate'])->name('detail-estimate');
    Route::any('estimate/attachments/{id}', [EstimateController::class,'showAttachment'])->name('estimate-attachments');
    Route::any('estimate/tasks/{id}', [EstimateController::class,'showTasks'])->name('estimate-tasks');
    Route::any('estimate/reminders/{id}', [EstimateController::class,'showReminders'])->name('estimate-reminders');
    Route::any('estimate/notes/{id}', [EstimateController::class,'showNotes'])->name('estimate-notes');

    Route::any('get-estimate-attachments',[AttachmentController::class,'getRelatedAttachment']);
    Route::any('estimatePdf/{id}', [EstimateController::class,'estimateToCustomerPDF'])->name('estimate-tasks');
    Route::any('estimatePdfview/{id}', [EstimateController::class,'pdftest'])->name('estimate-tasks');
    Route::any('dropboxupload', [AttachmentController::class,'fileStore']);
    Route::any('deleteDropbox', [AttachmentController::class,'fileDestroy']);
    Route::get('/export-pdf', [EstimateController::class, 'exportPdf']);


    //Contract routes
    Route::any('estimate-contract/{id}',[ContractController::class,'createContract']);
    Route::any('contract', [ContractController::class,'index'])->name('contract.index');
    Route::any('create-contract',[ContractController::class,'createContract'])->name('create-contract');
    Route::any('approve-contract',[ContractController::class,'approveContract'])->name('approve-contract');
    Route::any('decline-contract',[ContractController::class,'declineContract'])->name('decline-contract');
    Route::any('save-contract',[ContractController::class,'saveContract']);
    Route::any('get-contracts',[ContractController::class,'getAllContracts']);
    Route::any('contract-estimate',[ContractController::class,'contractEstimate']);
    Route::any('edit-contract/{id}',[ContractController::class,'editContract']);
    Route::any('update-contract',[ContractController::class,'updateContract']);
    Route::any('update-contract-id',[ContractController::class,'updateContractId']);
    Route::any('delete-contract',[ContractController::class,'deleteContract']);
    Route::any('contract/detail/{id}', [ContractController::class, 'detailContract'])->name('detail-contract');
    Route::any('get-contract-template', [ContractController::class, 'getContractTemplateAjax'])->name('get-contract-template');
    Route::any('get-customer-contracts', [ContractController::class,'getCustomerContracts'])->name('get-customer-contracts');
    Route::any('contract/tasks/{id}', [ContractController::class,'showTasks'])->name('contract-tasks');
    Route::any('contract/attachments/{id}', [ContractController::class,'showAttachments'])->name('contract-attachments');
    Route::any('contract/reminders/{id}', [ContractController::class,'showReminders'])->name('contract-reminders');
    Route::any('contract/notes/{id}', [ContractController::class,'showNotes'])->name('contract-notes');
    Route::any('contract/templates/{id}', [ContractController::class,'showTemplates'])->name('contract-templates');
    Route::any('contract-pdf/{id}', [ContractController::class,'contractPdf'])->name('contract-pdf');

    //Lead Status routes
    Route::any('lead-status', [LeadStatusController::class,'index'])->name('lead_status.index');
    Route::any('save-lead-status', [LeadStatusController::class,'saveLeadStatus']);
    Route::any('get-lead-status', [LeadStatusController::class,'getLeadStatus']);
    Route::any('delete-lead-status', [LeadStatusController::class,'deleteLeadStatus']);
    Route::any('edit-lead-status', [LeadStatusController::class,'editLeadStatus']);
    Route::any('update-lead-status', [LeadStatusController::class,'updateLeadStatus']);


    //Lead Source routes
    Route::any('lead-source', [LeadSourceController::class,'index'])->name('lead_source.index');
    Route::any('save-lead-source', [LeadSourceController::class,'saveLeadSource']);
    Route::any('get-lead-source', [LeadSourceController::class,'getLeadSource']);
    Route::any('delete-lead-source', [LeadSourceController::class,'deleteLeadSource']);
    Route::any('edit-lead-source', [LeadSourceController::class,'editLeadSource']);
    Route::any('update-lead-source', [LeadSourceController::class,'updateLeadSource']);

    // Require documents
    Route::any('require-document', [RequireDocumentController::class,'index'])->name('require_document.index');
    Route::any('save-require-document', [RequireDocumentController::class,'saveRequireDocument']);
    Route::any('get-require-document', [RequireDocumentController::class,'getRequireDocument']);
    Route::any('delete-require-document', [RequireDocumentController::class,'deleteRequireDocument']);
    Route::any('edit-require-document', [RequireDocumentController::class,'editRequireDocument']);
    Route::any('update-require-document', [RequireDocumentController::class,'updateRequireDocument']);


    //Email Template routes
    Route::any('email-template', [EmailTemplateController::class,'index'])->name('email-template.index');
    Route::any('save-email-template', [EmailTemplateController::class,'saveEmailTemplate']);
    Route::any('get-email-template', [EmailTemplateController::class,'getEmailTemplate']);
    Route::any('delete-email-template', [EmailTemplateController::class,'deleteEmailTemplate']);
    Route::any('edit-email-template/{id}', [EmailTemplateController::class,'editEmailTemplate']);
    Route::any('update-email-template', [EmailTemplateController::class,'updateEmailTemplate']);


    //Contract Template routes
    Route::any('contract-template', [ContractTemplateController::class,'index'])->name('contract-template.index');
    Route::any('save-contract-template', [ContractTemplateController::class,'saveContractTemplate']);
    Route::any('get-contract-templates', [ContractTemplateController::class,'getContractTemplates']);
    Route::any('get-contract-template', [ContractTemplateController::class,'getContractTemplate']);
    Route::any('delete-contract-template', [ContractTemplateController::class,'deleteContractTemplate']);
    Route::any('edit-contract-template/{id}', [ContractTemplateController::class,'editContractTemplate']);
    Route::any('update-contract-template', [ContractTemplateController::class,'updateContractTemplate']);


    //Customer routes
    Route::any('customer', [CustomerController::class,'index'])->name('customer.index');
    Route::any('create-customer', [CustomerController::class,'create'])->name('customer.create');
    Route::any('save-customer', [CustomerController::class,'saveCustomer']);
    Route::any('get-customer', [CustomerController::class,'getAllCustomer']);
    Route::any('delete-customer', [CustomerController::class,'deleteCustomer']);
    Route::any('is-customer', [CustomerController::class,'isCustomer']);
    Route::any('edit-customer/{id}', [CustomerController::class,'editCustomer']);
    Route::any('update-customer', [CustomerController::class,'updateCustomer']);
    Route::any('customer/profile/{id}', [CustomerController::class,'showCustomer'])->name('customer-profile');
    Route::any('convert-customer', [CustomerController::class,'convertCustomer']);
    Route::any('customer/tasks/{id}', [CustomerController::class,'showTasks'])->name('customer-tasks');
    Route::any('customer/contracts/{id}', [CustomerController::class,'showContracts'])->name('customer-contracts');
    Route::any('customer/attachments/{id}', [CustomerController::class,'showAttachments'])->name('customer-attachments');
    Route::any('customer/contacts/{id}', [CustomerController::class,'showContacts'])->name('customer-contacts');
    Route::any('customer/leads/{id}', [CustomerController::class,'showLeads'])->name('customer-leads');
    Route::any('customer/estimates/{id}', [CustomerController::class,'showEstimates'])->name('customer-estimates');
    Route::any('customer/reminders/{id}', [CustomerController::class,'showReminders'])->name('customer-reminders');


    //Customer contact routes
    Route::any('save-contact', [ContactController::class,'saveContact']);
    Route::any('get-contacts', [ContactController::class,'getContacts']);
    Route::any('delete-contact', [ContactController::class,'deleteContact']);
    Route::any('edit-contact', [ContactController::class,'editContact']);
    Route::any('update-contact', [ContactController::class,'updateContact']);

    //Task routes
    Route::any('save-task',[TaskController::class,'saveTask']);
    Route::any('get-realted-tasks',[TaskController::class,'getRelatedTasks']);
    Route::any('get-tasks',[TaskController::class,'getTasks']);
    Route::any('edit-task',[TaskController::class,'editTask']);
    Route::any('update-task',[TaskController::class,'updateTask']);
    Route::any('delete-task',[TaskController::class,'deleteTask']);

    //attachment routes
   Route::any('save-common-attachment',[AttachmentController::class,'saveCommonAttach']);

    //Reminders routes
   Route::any('save-reminder', [ReminderController::class,'saveReminder']);
   Route::any('get-realted-reminders', [ReminderController::class,'getRelatedReminders']);
   Route::any('edit-reminder', [ReminderController::class,'editReminder']);
   Route::any('update-reminder', [ReminderController::class,'updateReminder']);
   Route::any('delete-reminder', [ReminderController::class,'deleteReminder']);


   //notes routes
   Route::any('get-realted-notes', [Backend\NoteController::class,'getRelatedNotes']);
   Route::any('save-note', [Backend\NoteController::class,'saveNote']);
   Route::any('edit-note', [Backend\NoteController::class,'editNote']);
   Route::any('update-note', [Backend\NoteController::class,'updateNote']);
   Route::any('delete-note', [Backend\NoteController::class,'deleteNote']);


   // Term Length routes
   Route::any('term-length', [TermLengthController::class,'index'])->name('term-length.index');
   Route::any('save-term-length', [TermLengthController::class,'saveTermLength']);
   Route::any('get-term-length', [TermLengthController::class,'getTermLength']);
   Route::any('delete-term-length', [TermLengthController::class,'deleteTermLength']);
   Route::any('edit-term-length', [TermLengthController::class,'editTermLength']);
   Route::any('update-term-length', [TermLengthController::class,'updateTermLength']);


   //Invoices routes
   Route::any('invoices', [InvoiceController::class,'index'])->name('invoice.index');
   Route::any('create-invoice',[InvoiceController::class,'createInvoice'])->name('create-invoice');
   Route::any('save-invoice',[InvoiceController::class,'saveInvoice']);
   Route::any('convert-invoice/{id}',[InvoiceController::class,'convertInvoice']);
   Route::any('get-invoices',[InvoiceController::class,'getAllInvoices']);
   Route::any('edit-invoice/{id}',[InvoiceController::class,'editInvoice']);
   Route::any('get-invoice-items',[InvoiceController::class,'getInvoiceItems']);
   Route::any('update-invoice',[InvoiceController::class,'updateInvoice']);
//   Route::any('update-contract-id',[ContractController::class,'updateContractId']);
//   Route::any('delete-contract',[ContractController::class,'deleteContract']);
   Route::any('delete-invoice',[InvoiceController::class,'deleteInvoice']);
   Route::any('print-invoice/{id}',[InvoiceController::class,'printInvoice']);
   Route::any('pdf-invoice/{id}',[InvoiceController::class,'pdfInvoice']);
   Route::any('invoice/detail/{id}', [InvoiceController::class, 'detailInvoice'])->name('detail-invoice');
   Route::any('invoice/payment/{id}', [InvoiceController::class, 'paymentInvoice'])->name('payment-invoice');
   Route::any('invoice/payments/{id}', [InvoiceController::class, 'invoicePayments'])->name('payments-invoice');
   Route::any('estimate/detail/{id}', [EstimateController::class, 'detailEstimate'])->name('detail-estimate');


   //Move In Requests
   Route::any('move-in-request', [MoveInRequestController::class,'index'])->name('move-in-request.index');
   Route::any('save-move-in-request', [MoveInRequestController::class,'saveMoveInRequest'])->name('move-in-request.save');
   Route::any('get-move-in-request',[MoveInRequestController::class,'getAllMoveInRequest']);
   Route::any('edit-move-in-request', [MoveInRequestController::class,'editMoveInRequest']);
   Route::any('update-move-in-request', [MoveInRequestController::class,'updateMoveInRequest']);
   Route::any('delete-move-in-request', [MoveInRequestController::class,'deleteMoveInRequest']);
   Route::any('barcode-label', [MoveInRequestController::class,'barcodeLabel']);
   Route::any('delete-barcode-label', [MoveInRequestController::class,'deleteBarcodeLabel']);
   Route::any('get-barcode-label', [MoveInRequestController::class,'getBarcodeLable']);
   Route::any('view-barcode-labels/{id}', [MoveInRequestController::class,'viewBarcodeLabels']);
   Route::any('print-barcode-labels/{id}', [MoveInRequestController::class,'printBarcodeLabels']);
   Route::any('reprint-barcode-labels/{id}', [MoveInRequestController::class,'reprintBarcodeLabels']);

   //Move In routes
   Route::any('move-in', [MoveInController::class,'index'])->name('move-in.index');
   Route::any('create-move-in', [MoveInController::class,'createMoveIn'])->name('move-in.create');
   Route::any('save-move-in', [MoveInController::class,'saveMoveIn'])->name('move-in.save');
   Route::any('get-move-in', [MoveInController::class,'getAllMoveIn'])->name('move-in.get');
   Route::any('edit-move-in', [MoveInController::class,'editMoveIn'])->name('move-in.edit');
   Route::any('update-move-in', [MoveInController::class,'updateMoveIn'])->name('move-in.update');
   Route::any('edit-move-in/{id}', [MoveInController::class,'getAllMoveIn'])->name('move-in.get');
   Route::any('delete-move-in', [MoveInController::class,'deleteMoveIn'])->name('move-in.delete');
   Route::any('view-move-in-items/{id}', [MoveInController::class,'viewMoveInItems']);


   //Payments routes
   Route::any('save-payment', [PaymentController::class,'savePayment'])->name('payment.save');
   Route::any('get-invoice-payments', [PaymentController::class,'invoiceWisePayments'])->name('payment.get');
   Route::any('edit-payment', [PaymentController::class,'editPayment'])->name('payment.edit');
   Route::any('update-payment', [PaymentController::class,'updatePayment'])->name('payment.update');
   Route::any('delete-payment', [PaymentController::class,'deletePayment'])->name('payment.delete');

   //Move Out routes
        Route::any('move-out', [MoveOutController::class,'index'])->name('move-out.index');
        Route::any('create-move-out', [MoveOutController::class,'createMoveOut'])->name('move-out.create');
        Route::any('save-move-out', [MoveOutController::class,'saveMoveOut'])->name('move-out.save');
        Route::any('get-move-out', [MoveOutController::class,'getAllMoveOut'])->name('move-out.get');
        Route::any('edit-move-out/{id}', [MoveOutController::class,'getAllMoveOut'])->name('move-out.get');
        Route::any('delete-move-out', [MoveOutController::class,'deleteMoveOut'])->name('move-out.delete');
        Route::any('get-barcode-label-moved', [MoveInRequestController::class,'getBarcodeLableMoved']);

    //Notification Routes
        Route::any('notification', [NotificationController::class,'index'])->name('notification.index');
        Route::any('markAsRead', [NotificationController::class,'markAsAllRead'])->name('notification.markAsRead');

        //App Settings routes
        Route::any('app-settings', [AppSettingsController::class,'index'])->name('app-settings.index');
        Route::any('update-app-settings', [AppSettingsController::class,'update'])->name('app-settings.update');
        Route::any('get-app-settings', [AppSettingsController::class,'getAppsettings'])->name('app-settings.get');

    //Orders
        Route::any('order', [OrderController::class,'index'])->name('order.index');
        Route::any('get-orders', [OrderController::class,'getOrders'])->name('order.get');
        Route::any('order/detail/{id}', [OrderController::class, 'detailOrder'])->name('detail-order');
        Route::any('generate/invoice/{id}', [InvoiceController::class, 'createOrderInvoice'])->name('order-invoice');
        Route::any('print-order/{id}',[OrderController::class,'printOrder']);
        Route::any('get-customer-orders', [OrderController::class,'getCustomerOrders'])->name('get-customer-orders');
        Route::any('get-order-products', [OrderController::class,'getOrderProducts'])->name('get-order-products');



        Route::any('sync-product-quickbook', [ProductController::class,'syncProductQuickbook'])->name('sync-product-quickbook');
        Route::any('sync-customer-quickbook', [ContactController::class,'syncCustomerQuickbook'])->name('sync-customer-quickbook');

        Route::post('/webhooks/quickbooks', [QuickBooksWebhookController::class, 'handle']);

        Route::any('get-gmails', [Backend\GoogleServiceGmailController::class,'getAllGmails'])->name('get-gmails');
        Route::any('get-gmails', [Backend\GoogleServiceGmailController::class,'getAllGmails'])->name('email.index1');


        //Emails
        Route::any('emails', [EmailController::class,'index'])->name('email.index');
        Route::any('get-email', [EmailController::class,'getEmails'])->name('email.get');
        Route::any('send-email', [EmailController::class,'sendEmail'])->name('email.send');
        Route::any('get-email-detail/{id}', [EmailController::class,'getEmailDetail'])->name('email.detail');

        //Warehouse Report
        Route::any('view-warehouse-report', [ReportsController::class,'viewWarehouseReport'])->name('report.warehouse');
        Route::any('filter-warehouse-report', [ReportsController::class,'getWarehouseReport'])->name('report.get-warehouse-report');

        //Leads Report
        Route::any('view-lead-report', [ReportsController::class,'viewLeadReport'])->name('report.lead');
        Route::any('filter-lead-report', [ReportsController::class,'getLeadReport'])->name('report.get-lead-report');

        //Estiamte Report
        Route::any('view-estimate-report', [ReportsController::class,'viewEstimateReport'])->name('report.estimate');
        Route::any('filter-estimate-report', [ReportsController::class,'getEstimateReport'])->name('report.get-estimate-report');

        //Contract Report
        Route::any('view-contract-report', [ReportsController::class,'viewContractReport'])->name('report.contract');
        Route::any('filter-contract-report', [ReportsController::class,'getContractReport'])->name('report.get-contract-report');

        //Invoice Report
        Route::any('view-invoice-report', [ReportsController::class,'viewInvoiceReport'])->name('report.invoice');
        Route::any('filter-invoice-report', [ReportsController::class,'getInvoiceReport'])->name('report.get-invoice-report');

        //Payment Report
        Route::any('view-payment-report', [ReportsController::class,'viewPaymentReport'])->name('report.payment');
        Route::any('filter-payment-report', [ReportsController::class,'getPaymentReport'])->name('report.get-payment-report');



    });


Route::any('invoice-to-customer/{id}',[InvoiceController::class,'viewAsCustomerInvoice']);

Route::get('/oauth/gmail', function (){
    return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function (){
    LaravelGmail::makeToken();
    return redirect()->to('/admin/emails');
});

Route::get('/oauth/gmail/logout', function (){
    LaravelGmail::logout(); //It returns exception if fails
    return redirect()->to('/admin/emails');
});
