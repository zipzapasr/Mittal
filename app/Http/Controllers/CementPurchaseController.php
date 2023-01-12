<?php

namespace App\Http\Controllers;

use App\Models\CementPurchase;
use App\Models\CementSupplier;
use App\Models\Sites;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Http\Requests\CementPurchaseRequest;

class CementPurchaseController extends Controller
{
    private $today;
    private $yesterday;

    public function __construct()
    {
        $this->today = Carbon::today()->format("Y-m-d");
        $this->yesterday = Carbon::yesterday()->format("Y-m-d");
    }

    public function list($id)
    {
        // dd(Schema::getColumnListing('cement_purchase'));
        
        $cementPurchases = CementPurchase::where(['employee_id' => $id, 'status' => 1])
                            ->whereIn('date', [$this->today, $this->yesterday])
                            ->with('getSite', 'getSupplier', 'getEmployee')
                            ->get();
        // dd($cementPurchases);
        return View('EmployeeHome.CementPurchase.list', compact('cementPurchases'));
    }

    public function create()
    {
        $user = session('employee');
        $role = $user->role;
        $suppliers = CementSupplier::orderBy('name')->get();
        if($role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if ($role == '4') {
            $sites = $user->sitesByEmployees;
        }
        
        //dd($sites);
        $today = $this->today;
        $yesterday = $this->yesterday;
        return view('EmployeeHome.CementPurchase.create', compact('role', 'user', 'suppliers', 'sites', 'today', 'yesterday'));
    }

    public static function store(CementPurchaseRequest $req, $user)
    {
        $requestData = $req->validated() + ['employee_id' => $user];
        CementPurchase::create($requestData);
        // dd($new_purchase);
        return back()->with('message', 'New "Cement Purchase" created successfully!');
    }

    public function edit($user, CementPurchase $cement_purchase)
    {   
        // dd(Schema::getColumnListing('cement_purchase'));
        $user = session('employee');
        $role = $user->role;
        
        $suppliers = CementSupplier::orderBy('name')->get();
        if($role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if ($role == '4') {
            $sites = $user->sitesByEmployees;
        }
        $today = $this->today;
        $yesterday = $this->yesterday;
       return view('EmployeeHome.CementPurchase.edit', compact('role', 'user', 'suppliers', 'sites', 'today', 'yesterday', 'cement_purchase'));
    }

    public function update(CementPurchaseRequest $request, $user, CementPurchase $cement_purchase)
    {
        $requestData = $request->validated() + ['employee_id' => $user];
        // dd($requestData);
        $cement_purchase->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Purchase" updated successfully!');
    }

    public static function editCementPurchases(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'supplier_id' => 'required',
            'site_id' => 'required',
            'remark' => 'required'
        ]); // validation for Cement Purchase

        $request->validate([
            'curr_site' => 'required'
        ]); // curr site on which PM is editing

        CementPurchase::create(
            $request->all() + ['employee_id' => session('employee')->id]
        );
        return ;
        
    }
}
