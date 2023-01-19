<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Auth;

Route::get('/', function () {
    if(auth()->check()){
        return redirect()->route('home');
    } else {
        return redirect('/login');
    }
    return view('welcome');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/logout', [HomeController::class, 'logout'])->name('logout.user');


Route::group(['middleware' => 'auth'], function() {

    //Password Change
    Route::get('/{user}/changePassword', [HomeController::class, 'changePassword'])->name('change.password');
    Route::post('/{user}/changePassword', [HomeController::class, 'authenticate_and_change_password'])->name('authenticate.password');
    
    // employee Routes
    Route::get('create/employee', [EmployeeController::class, 'create'])->name('create.employee');
    Route::get('list/employee', [EmployeeController::class, 'list'])->name('list.employee');
    Route::get('update/employee/status/{user}', [EmployeeController::class, 'changeStatus'])->name('change.status.employee');
    Route::get('edit/employee/{user}', [EmployeeController::class, 'editEmployee'])->name('edit.employee');
    Route::post('update/employee', [EmployeeController::class, 'updateEmployee'])->name('update.employee');
    Route::POST('save/employee', [EmployeeController::class, 'save'])->name('save.employee');


    // site Routes
    Route::get('list/site/{site}/verify/{date} ', [SiteController::class, 'verify'])->name('verify.site');
    Route::post('list/site/{site}/filter', [SiteController::class, 'filterSiteEntries'])->name('list.site.filter');
    
    Route::get('list/site/{site} ', [SiteController::class, 'listSiteEntries'])->name('list.siteEntries');
    Route::post('list/site/{site} ', [SiteController::class, 'filterSiteEntries'])->name('list.siteEntries.filter');
    Route::post('/mysites', [SiteController::class, 'updateCementWastage'])->name('mysites.updateCementWastage');

    Route::get('view/site/{siteId}', [SiteController::class, 'view'])->name('view.site');
    Route::get('create/site', [SiteController::class, 'create'])->name('create.site');
    Route::get('list/site', [SiteController::class, 'list'])->name('list.site');
    Route::get('update/site/status/{site}', [SiteController::class, 'changeStatus'])->name('change.status.site');
    Route::get('edit/site/{site}', [SiteController::class, 'editsite'])->name('edit.site');
    Route::post('update/site', [SiteController::class, 'updateSite'])->name('update.site');
    Route::POST('save/site', [SiteController::class, 'save'])->name('save.site');
    Route::POST('filter/site', [SiteController::class, 'filterSite'])->name('filter.site');

    Route::get('get/users/by/role/{roleid}', [SiteController::class, 'getUsersByRole'])->name('get.users.by.role');

    // Unit Route
    Route::get('create/unit', [UnitController::class, 'create'])->name('create.unit');
    Route::get('list/unit', [UnitController::class, 'list'])->name('list.unit');
    Route::get('update/unit/status/{unit}', [UnitController::class, 'changeStatus'])->name('change.status.unit');
    Route::get('edit/unit/{unit}', [UnitController::class, 'editunit'])->name('edit.unit');
    Route::post('update/unit', [UnitController::class, 'updateunit'])->name('update.unit');
    Route::POST('save/unit', [UnitController::class, 'save'])->name('save.unit');

    // Activity Route
    Route::get('create/activity', [ActivityController::class, 'create'])->name('create.activity');
    Route::get('list/activity', [ActivityController::class, 'list'])->name('list.activity');
    Route::get('update/activity/status/{activity}', [ActivityController::class, 'changeStatus'])->name('change.status.activity');
    Route::get('edit/activity/{activity}', [ActivityController::class, 'editActivity'])->name('edit.activity');
    Route::post('update/activity/{activity}', [ActivityController::class, 'updateActivity'])->name('update.activity');
    Route::POST('save/activity', [ActivityController::class, 'save'])->name('save.activity');

    // Field Type Route
    Route::get('create/field_type', [FieldTypeController::class, 'create'])->name('create.field_type');
    Route::get('list/field_type', [FieldTypeController::class, 'list'])->name('list.field_type');
    Route::get('update/field_type/status/{fieldType}', [FieldTypeController::class, 'changeStatus'])->name('change.status.field_type');
    Route::get('edit/field_type/{fieldType}', [FieldTypeController::class, 'editFieldType'])->name('edit.field_type');
    Route::post('update/field_type', [FieldTypeController::class, 'updateFieldType'])->name('update.field_type');
    Route::POST('save/field_type', [FieldTypeController::class, 'save'])->name('save.field_type');

    // Contractor Route
    Route::get('create/contractor', [ContractorController::class, 'create'])->name('create.contractor');
    Route::get('list/contractor', [ContractorController::class, 'list'])->name('list.contractor');
    Route::get('update/contractor/status/{contractor}', [ContractorController::class, 'changeStatus'])->name('change.status.contractor');
    Route::get('edit/contractor/{contractor}', [ContractorController::class, 'editContractor'])->name('edit.contractor');
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

    Route::get('reports/cementInReport', [ReportsController::class, 'viewCementInReport'])->name('view.cementInReport');
    Route::post('reports/cementInReport', [ReportsController::class, 'filterCementInReport'])->name('filter.cementInReport');

    Route::get('reports/cementOutReport', [ReportsController::class, 'viewCementOutReport'])->name('view.cementOutReport');
    Route::post('reports/cementOutReport', [ReportsController::class, 'filterCementOutReport'])->name('filter.cementOutReport');

    //Godown Reports
    Route::get('reports/godownPurchaseReport', [ReportsController::class, 'viewGodownPurchaseReport'])->name('view.godownPurchaseReport');
    Route::post('reports/godownPurchaseReport', [ReportsController::class, 'filterGodownPurchaseReport'])->name('filter.godownPurchaseReport');

    Route::get('reports/godownOutReport', [ReportsController::class, 'viewGodownOutReport'])->name('view.godownOutReport');
    Route::post('reports/godownOutReport', [ReportsController::class, 'filterGodownOutReport'])->name('filter.godownOutReport');

    Route::get('reports/godownInReport', [ReportsController::class, 'viewGodownInReport'])->name('view.godownInReport');
    Route::post('reports/godownInReport', [ReportsController::class, 'filterGodownInReport'])->name('filter.godownInReport');

    // CementSupplier Route
    Route::get('create/cementsupplier', [CementSupplierController::class, 'create'])->name('create.cementsupplier');
    Route::get('list/cementsupplier', [CementSupplierController::class, 'list'])->name('list.cementsupplier');
    Route::get('update/cementsupplier/status/{cementSupplier}', [CementSupplierController::class, 'changeStatus'])->name('change.status.cementsupplier');
    Route::get('edit/cementsupplier/{cementSupplier}', [CementSupplierController::class, 'editCementSupplier'])->name('edit.cementsupplier');
    Route::post('update/cementsupplier', [CementSupplierController::class, 'updateCementSupplier'])->name('update.cementsupplier');
    Route::POST('save/cementsupplier', [CementSupplierController::class, 'save'])->name('save.cementsupplier');

    // SiteTransfer Route
    Route::get('create/sitetransfer', [SiteTransferController::class, 'create'])->name('create.sitetransfer');
    Route::get('list/sitetransfer', [SiteTransferController::class, 'list'])->name('list.sitetransfer');
    Route::get('update/sitetransfer/status/{siteTransfer}', [SiteTransferController::class, 'changeStatus'])->name('change.status.sitetransfer');
    Route::get('edit/sitetransfer/{siteTransfer}', [SiteTransferController::class, 'editSiteTransfer'])->name('edit.sitetransfer');
    Route::post('update/sitetransfer', [SiteTransferController::class, 'updateSiteTransfer'])->name('update.sitetransfer');
    Route::POST('save/sitetransfer', [SiteTransferController::class, 'save'])->name('save.sitetransfer');

    //Ledgers Route
    Route::get('ledger/siteLedger', [ReportsController::class, 'viewSiteLedger'])->name('view.siteLedger');
    Route::post('ledger/siteLedger', [ReportsController::class, 'filterSiteLedger'])->name('filter.siteLedger');
    Route::get('ledger/allLedger', [ReportsController::class, 'viewallLedger'])->name('view.allLedger');
    Route::post('ledger/allLedger', [ReportsController::class, 'filterAllLedger'])->name('filter.allLedger');

    // Edit Access
    Route::get('give-edit-access', [HomeController::class, 'giveEditAccessView'])->name('giveEditAccessView');
    Route::post('give-edit-access', [HomeController::class, 'giveEditAccess'])->name('giveEditAccess');
    Route::get('site/{site}/give-edit-access/{date} ', [HomeController::class, 'giveEditAccessSiteEntries'])->name('giveEditAccess.site');
    Route::get('cementPurchase/{site}/give-edit-access/{date} ', [HomeController::class, 'giveEditAccessCementPurchase'])->name('giveEditAccess.cementPurchase');
    Route::get('cementIn/{site}/give-edit-access/{date} ', [HomeController::class, 'giveEditAccessCementIn'])->name('giveEditAccess.cementIn');
    Route::get('cementOut/{site}/give-edit-access/{date} ', [HomeController::class, 'giveEditAccessCementOut'])->name('giveEditAccess.cementOut');
    Route::get('cementTransferToClient/{site}/give-edit-access/{date} ', [HomeController::class, 'giveEditAccessCementTransferToClient'])->name('giveEditAccess.cementTransferToClient');

    Route::get('revoke-edit-access/{editAccess}', [HomeController::class, 'revokeEditAccess'])->name('edit.revokeAccess');

    //View Edit Access
    Route::get('view-edit-access', [HomeController::class, 'viewEditAccess'])->name('viewEditAccess');

    //Edit Logs
    Route::get('logs', [HomeController::class, 'viewEditLogs'])->name("logs.viewEditLogs");
    Route::get('logs/site/{site}', [AdminSiteLogController::class, 'siteEstLog'])->name("logs.site");
    Route::get('logs/siteEntry/{site}/{date}', [AdminSiteLogController::class, 'siteEntriesLog'])->name("logs.siteEntries");
});

// Login Route
Route::get('/login/data-entry-operator', [LoginController::class, 'data_entry_operator'])->name('login.data_entry_operator');
Route::post('/authenticate/data-entry-operator', [LoginController::class, 'authenticate_data_entry_operator'])->name('authenticate.data_entry_operator');

Route::get('/login/project-manager', [LoginController::class, 'project_manager'])->name('login.project_manager');
Route::post('/authenticate/project-manager', [LoginController::class, 'authenticate_project_manager'])->name('authenticate.project_manager');

Route::get('/login/godown', [LoginController::class, 'godown'])->name('login.godown');
Route::post('/authenticate/godown', [LoginController::class, 'authenticate_godown'])->name('authenticate.godown');


//Employee Home Route
Route::group(['middleware' => 'employeeAuth'], function() {
    Route::get('/employee/logout', [LoginController::class, 'logout'])->name('employee.logout');
    Route::get('/employee/home', [EmployeeHomeController::class, 'index'])->name('employee.home');
    Route::get('/employee/mysites/{site}', [EmployeeHomeController::class, 'siteview'])->name('employee.siteview');
    Route::post('/employee/mysites/{site}/{day}', [EmployeeHomeController::class, 'savesiteentry'])->name('employee.savesiteentry');
    // Route::post('/employee/mysites/{site}/{day}', [EmployeeHomeController::class, 'savesiteentryEdit'])->name('employee.savesiteentryEdit');
    Route::get('/employee/mysites/{site}/submit/{day}', [EmployeeHomeController::class, 'submit'])->name('employee.submitsiteentry');
    Route::get('/employee/mysites', [EmployeeHomeController::class, 'mysites'])->name('employee.mysites');

    //Password Change
    Route::get('/employee/{user}/changePassword', [LoginController::class, 'changePassword'])->name('change.employee.password');
    Route::post('/employee/{user}/changePassword', [LoginController::class, 'authenticate_and_change_password'])->name('authenticate.employee.password');

    Route::get('delete/siteEntry/{entryId}/{id}', [EmployeeHomeController::class, 'deleteImage']);


    Route::get('/employee/{user}/cementIn', [CementInController::class, 'create'])->name('create.cementIn');
    Route::get('/employee/{user}/list/cementIn', [CementInController::class, 'list'])->name('list.cementIn');
    Route::post('/employee/{user}/cementIn', [CementInController::class, 'store'])->name('store.cementIn');
    Route::get('/employee/{user}/cementIn/{cement_in}', [CementInController::class, 'edit'])->name('edit.cementIn');
    Route::post('/employee/{user}/cementIn/{cementIn}', [CementInController::class, 'update'])->name('update.cementIn');

    Route::get('/employee/{user}/cementPurchase', [CementPurchaseController::class, 'create'])->name('create.cementPurchase');
    Route::get('/employee/{user}/list/cementPurchase', [CementPurchaseController::class, 'list'])->name('list.cementPurchase');
    Route::post('/employee/{user}/cementPurchase', [CementPurchaseController::class, 'store'])->name('store.cementPurchase');
    Route::get('/employee/{user}/cementPurchase/{cement_purchase}', [CementPurchaseController::class, 'edit'])->name('edit.cementPurchase');
    Route::post('/employee/{user}/cementPurchase/{cement_purchase}', [CementPurchaseController::class, 'update'])->name('update.cementPurchase');

    Route::get('/employee/{user}/cementTransfer', [CementTransferController::class, 'create'])->name('create.cementTransfer');
    Route::get('/employee/{user}/list/cementTransfer', [CementTransferController::class, 'list'])->name('list.cementTransfer');
    Route::post('/employee/{user}/cementTransfer', [CementTransferController::class, 'store'])->name('store.cementTransfer');
    Route::get('/employee/{user}/cementTransfer/{cementTransfer}', [CementTransferController::class, 'edit'])->name('edit.cementTransfer');
    Route::post('/employee/{user}/cementTransfer/{cementTransfer}', [CementTransferController::class, 'update'])->name('update.cementTransfer');
    

    Route::get('/employee/{user}/cementOut', [CementOutController::class, 'create'])->name('create.cementOut');
    Route::get('/employee/{user}/list/cementOut', [CementOutController::class, 'list'])->name('list.cementOut');
    Route::post('/employee/{user}/cementOut', [CementOutController::class, 'store'])->name('store.cementOut');
    Route::get('/employee/{user}/cementOut/{cementOut}', [CementOutController::class, 'edit'])->name('edit.cementOut');
    Route::post('/employee/{user}/cementOut/{cementOut}', [CementOutController::class, 'update'])->name('update.cementOut');

    Route::get('/edit/{key}/{site}/{date}', [EmployeeHomeController::class, 'edit'])->name('edit');

    //Edits
    Route::post('/edit/{key}/{site}/{date}', [EmployeeHomeController::class, 'saveEdits'])->name('employee.saveEdits');


});

//Godown Route
Route::group(['middleware' => 'godownAuth'], function() {
    Route::get('/godown/logout', [LoginController::class, 'logout'])->name('godown.logout');
    Route::get('/godown/home', [GodownController::class, 'index'])->name('godown.home');


    //Cement Purchase
    Route::get('/godown/cementPurchase', [GodownController::class, 'createCementPurchase'])->name('create.godown.cementPurchase');
    Route::get('/godown/list/cementPurchase', [GodownController::class, 'listCementPurchase'])->name('list.godown.cementPurchase');
    Route::post('/godown/cementPurchase', [GodownController::class, 'storeCementPurchase'])->name('store.godown.cementPurchase');
    Route::get('/godown/cementPurchase/{cement_purchase}', [GodownController::class, 'editCementPurchase'])->name('edit.godown.cementPurchase');
    Route::post('/godown/cementPurchase/{cement_purchase}', [GodownController::class, 'updateCementPurchase'])->name('update.godown.cementPurchase');

    //Cement Out
    Route::get('/godown/cementOut', [GodownController::class, 'createCementOut'])->name('create.godown.cementOut');
    Route::get('/godown/list/cementOut', [GodownController::class, 'listCementOut'])->name('list.godown.cementOut');
    Route::post('/godown/cementOut', [GodownController::class, 'storeCementOut'])->name('store.godown.cementOut');
    Route::get('/godown/cementOut/{cementOut}', [GodownController::class, 'editCementOut'])->name('edit.godown.cementOut');
    Route::post('/godown/cementOut/{cementOut}', [GodownController::class, 'updateCementOut'])->name('update.godown.cementOut');

});




