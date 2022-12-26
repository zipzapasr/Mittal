<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout.user');


Route::group(['middleware' => 'auth'], function() {
    
    // employee Routes
    Route::get('create/employee', [EmployeeController::class, 'create'])->name('create.employee');
    Route::get('list/employee', [EmployeeController::class, 'list'])->name('list.employee');
    Route::get('update/employee/status/{id}', [EmployeeController::class, 'changeStatus'])->name('change.status.employee');
    Route::get('edit/employee/{id}', [EmployeeController::class, 'editEmployee'])->name('edit.employee');
    Route::post('update/employee', [EmployeeController::class, 'updateEmployee'])->name('update.employee');
    Route::POST('save/employee', [EmployeeController::class, 'save'])->name('save.employee');


    // site Routes
    Route::get('list/site/{site}/verify/{date} ', [SiteController::class, 'verify'])->name('verify.site');
    Route::get('list/site/{site}/give-edit-access/{date} ', [SiteController::class, 'giveEditAccess'])->name('giveEditAccess.site');

    Route::post('list/site/{site}/filter', [SiteController::class, 'filterSiteEntries'])->name('list.site.filter');
    
    // Route::get('list/site/{site} ', [SiteController::class, 'listSiteEntries'])->name('list.siteEntries');
    Route::get('list/site/{site} ', [SiteController::class, 'listSiteEntries'])->name('list.siteEntries');
    Route::post('list/site/{site} ', [SiteController::class, 'filterSiteEntries'])->name('list.siteEntries.filter');
    Route::post('/mysites', [SiteController::class, 'updateCementWastage'])->name('mysites.updateCementWastage');

    Route::get('view/site/{siteId}', [SiteController::class, 'view'])->name('view.site');
    Route::get('create/site', [SiteController::class, 'create'])->name('create.site');
    Route::get('list/site', [SiteController::class, 'list'])->name('list.site');
    Route::get('update/site/status/{id}', [SiteController::class, 'changeStatus'])->name('change.status.site');
    Route::get('edit/site/{id}', [SiteController::class, 'editsite'])->name('edit.site');
    Route::post('update/site', [SiteController::class, 'updatesite'])->name('update.site');
    Route::POST('save/site', [SiteController::class, 'save'])->name('save.site');
    Route::POST('filter/site', [SiteController::class, 'filterSite'])->name('filter.site');

    Route::get('get/users/by/role/{roleid}', [SiteController::class, 'getUsersByRole'])->name('get.users.by.role');

    // Unit Route
    Route::get('create/unit', [UnitController::class, 'create'])->name('create.unit');
    Route::get('list/unit', [UnitController::class, 'list'])->name('list.unit');
    Route::get('update/unit/status/{id}', [UnitController::class, 'changeStatus'])->name('change.status.unit');
    Route::get('edit/unit/{id}', [UnitController::class, 'editunit'])->name('edit.unit');
    Route::post('update/unit', [UnitController::class, 'updateunit'])->name('update.unit');
    Route::POST('save/unit', [UnitController::class, 'save'])->name('save.unit');

    // Activity Route
    Route::get('create/activity', [ActivityController::class, 'create'])->name('create.activity');
    Route::get('list/activity', [ActivityController::class, 'list'])->name('list.activity');
    Route::get('update/activity/status/{id}', [ActivityController::class, 'changeStatus'])->name('change.status.activity');
    Route::get('edit/activity/{id}', [ActivityController::class, 'editActivity'])->name('edit.activity');
    Route::post('update/activity/{activity}', [ActivityController::class, 'updateActivity'])->name('update.activity');
    Route::POST('save/activity', [ActivityController::class, 'save'])->name('save.activity');

    // Field Type Route
    Route::get('create/field_type', [FieldTypeController::class, 'create'])->name('create.field_type');
    Route::get('list/field_type', [FieldTypeController::class, 'list'])->name('list.field_type');
    Route::get('update/field_type/status/{id}', [FieldTypeController::class, 'changeStatus'])->name('change.status.field_type');
    Route::get('edit/field_type/{id}', [FieldTypeController::class, 'editFieldType'])->name('edit.field_type');
    Route::post('update/field_type', [FieldTypeController::class, 'updateFieldType'])->name('update.field_type');
    Route::POST('save/field_type', [FieldTypeController::class, 'save'])->name('save.field_type');

    // Contractor Route
    Route::get('create/contractor', [ContractorController::class, 'create'])->name('create.contractor');
    Route::get('list/contractor', [ContractorController::class, 'list'])->name('list.contractor');
    Route::get('update/contractor/status/{id}', [ContractorController::class, 'changeStatus'])->name('change.status.contractor');
    Route::get('edit/contractor/{id}', [ContractorController::class, 'editContractor'])->name('edit.contractor');
    Route::post('update/contractor', [ContractorController::class, 'updateContractor'])->name('update.contractor');
    Route::POST('save/contractor', [ContractorController::class, 'save'])->name('save.contractor');

    //Reports Route
    Route::get('reports/siteReport', [ReportsController::class, 'viewSiteReport'])->name('view.siteReport');
    Route::post('reports/siteReport', [ReportsController::class, 'filterSiteReport'])->name('filter.siteReport');

    Route::get('reports/siteTotalReport', [ReportsController::class, 'viewSiteTotalReport'])->name('view.siteTotalReport');
    Route::post('reports/siteTotalReport', [ReportsController::class, 'filterSiteTotalReport'])->name('filter.siteTotalReport');

    Route::get('reports/contractorReport', [ReportsController::class, 'viewContractorReport'])->name('view.contractorReport');
    Route::post('reports/contractorReport', [ReportsController::class, 'filterContractorReport'])->name('filter.contractorReport');

    Route::get('reports/cementPurchaseReport', [ReportsController::class, 'viewCementPurchaseReport'])->name('view.cementPurchaseReport');
    Route::post('reports/cementPurchaseReport', [ReportsController::class, 'filterCementPurchaseReport'])->name('filter.cementPurchaseReport');

    // CementSupplier Route
    Route::get('create/cementsupplier', [CementSupplierController::class, 'create'])->name('create.cementsupplier');
    Route::get('list/cementsupplier', [CementSupplierController::class, 'list'])->name('list.cementsupplier');
    Route::get('update/cementsupplier/status/{id}', [CementSupplierController::class, 'changeStatus'])->name('change.status.cementsupplier');
    Route::get('edit/cementsupplier/{id}', [CementSupplierController::class, 'editCementSupplier'])->name('edit.cementsupplier');
    Route::post('update/cementsupplier', [CementSupplierController::class, 'updateCementSupplier'])->name('update.cementsupplier');
    Route::POST('save/cementsupplier', [CementSupplierController::class, 'save'])->name('save.cementsupplier');

    // SiteTransfer Route
    Route::get('create/sitetransfer', [SiteTransferController::class, 'create'])->name('create.sitetransfer');
    Route::get('list/sitetransfer', [SiteTransferController::class, 'list'])->name('list.sitetransfer');
    Route::get('update/sitetransfer/status/{id}', [SiteTransferController::class, 'changeStatus'])->name('change.status.sitetransfer');
    Route::get('edit/sitetransfer/{id}', [SiteTransferController::class, 'editSiteTransfer'])->name('edit.sitetransfer');
    Route::post('update/sitetransfer', [SiteTransferController::class, 'updateSiteTransfer'])->name('update.sitetransfer');
    Route::POST('save/sitetransfer', [SiteTransferController::class, 'save'])->name('save.sitetransfer');

    //Ledgers Route
    Route::get('ledger/siteLedger', [ReportsController::class, 'viewSiteLedger'])->name('view.siteLedger');
    Route::post('ledger/siteLedger', [ReportsController::class, 'filterSiteLedger'])->name('filter.siteLedger');
    Route::get('ledger/allLedger', [ReportsController::class, 'viewallLedger'])->name('view.allLedger');
    Route::post('ledger/allLedger', [ReportsController::class, 'filterAllLedger'])->name('filter.allLedger');
});

// Login Route
Route::get('/login/data-entry-operator', [LoginController::class, 'data_entry_operator'])->name('login.data_entry_operator');
Route::post('/authenticate/data-entry-operator', [LoginController::class, 'authenticate_data_entry_operator'])->name('authenticate.data_entry_operator');

Route::get('/login/project-manager', [LoginController::class, 'project_manager'])->name('login.project_manager');
Route::post('/authenticate/project-manager', [LoginController::class, 'authenticate_project_manager'])->name('authenticate.project_manager');


//Employee Home Route
Route::group(['middleware' => 'employeeAuth'], function() {
    Route::get('/employee/logout', [LoginController::class, 'logout'])->name('employee.logout');
    Route::get('/employee/home', [EmployeeHomeController::class, 'index'])->name('employee.home');
    Route::get('/employee/mysites/{site}', [EmployeeHomeController::class, 'siteview'])->name('employee.siteview');
    Route::post('/employee/mysites/{site}/{day}', [EmployeeHomeController::class, 'savesiteentry'])->name('employee.savesiteentry');
    Route::get('/employee/mysites/{site}/submit/{day}', [EmployeeHomeController::class, 'submit'])->name('employee.submitsiteentry');
    Route::get('/employee/mysites', [EmployeeHomeController::class, 'mysites'])->name('employee.mysites');
    Route::get('/employee/{user}/changePassword', [LoginController::class, 'changePassword'])->name('change.employee.password');
    Route::post('/employee/{user}/changePassword', [LoginController::class, 'authenticate_and_change_password'])->name('authenticate.employee.password');

    Route::get('delete/siteEntry/{entryId}/{id}', [EmployeeHomeController::class, 'deleteImage']);


    Route::get('/employee/{user}/cementIn', [EmployeeHomeController::class, 'createCementIn'])->name('create.cementIn');
    Route::get('/employee/{user}/list/cementIn', [EmployeeHomeController::class, 'listCementIn'])->name('list.cementIn');
    Route::post('/employee/{user}/cementIn', [EmployeeHomeController::class, 'storeCementIn'])->name('store.cementIn');
    Route::get('/employee/{user}/cementIn/{id}', [EmployeeHomeController::class, 'editCementIn'])->name('edit.cementIn');
    Route::post('/employee/{user}/cementIn/{id}', [EmployeeHomeController::class, 'updateCementIn'])->name('update.cementIn');

    Route::get('/employee/{user}/cementPurchase', [EmployeeHomeController::class, 'createCementPurchase'])->name('create.cementPurchase');
    Route::get('/employee/{user}/list/cementPurchase', [EmployeeHomeController::class, 'listCementPurchase'])->name('list.cementPurchase');
    Route::post('/employee/{user}/cementPurchase', [EmployeeHomeController::class, 'storeCementPurchase'])->name('store.cementPurchase');
    Route::get('/employee/{user}/cementPurchase/{id}', [EmployeeHomeController::class, 'editCementPurchase'])->name('edit.cementPurchase');
    Route::post('/employee/{user}/cementPurchase/{id}', [EmployeeHomeController::class, 'updateCementPurchase'])->name('update.cementPurchase');

    Route::get('/employee/{user}/cementTransfer', [EmployeeHomeController::class, 'createCementTransfer'])->name('create.cementTransfer');
    Route::get('/employee/{user}/list/cementTransfer', [EmployeeHomeController::class, 'listCementTransfer'])->name('list.cementTransfer');
    Route::post('/employee/{user}/cementTransfer', [EmployeeHomeController::class, 'storeCementTransfer'])->name('store.cementTransfer');
    Route::get('/employee/{user}/cementTransfer/{id}', [EmployeeHomeController::class, 'editCementTransfer'])->name('edit.cementTransfer');
    Route::post('/employee/{user}/cementTransfer/{id}', [EmployeeHomeController::class, 'updateCementTransfer'])->name('update.cementTransfer');
    

    Route::get('/employee/{user}/cementOut', [EmployeeHomeController::class, 'createCementOut'])->name('create.cementOut');
    Route::get('/employee/{user}/list/cementOut', [EmployeeHomeController::class, 'listCementOut'])->name('list.cementOut');
    Route::post('/employee/{user}/cementOut', [EmployeeHomeController::class, 'storeCementOut'])->name('store.cementOut');
    Route::get('/employee/{user}/cementOut/{id}', [EmployeeHomeController::class, 'editCementOut'])->name('edit.cementOut');
    Route::post('/employee/{user}/cementOut/{id}', [EmployeeHomeController::class, 'updateCementOut'])->name('update.cementOut');

    Route::get('/edit/{key}/{site}/{date}', [EmployeeHomeController::class, 'edit'])->name('edit');

});




