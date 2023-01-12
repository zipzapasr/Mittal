<?php

namespace App\Http\Controllers;

use App\Models\CementIn;
use App\Models\Sites;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Http\Requests\CementInRequest;

class CementInController extends Controller
{
    private $today;
    private $yesterday;

    public function __construct()
    {
        $this->today = Carbon::today()->format("Y-m-d");
        $this->yesterday = Carbon::yesterday()->format("Y-m-d");
    }

    public function list()
    {
        // dd($this->today, $this->yesterday);
        $cementIns = CementIn::with(['getToSite', 'getFromSite'])
                        ->whereIn('date', [$this->today, $this->yesterday])
                        ->get();
        // dd($cementIns);
        $user = session('employee');
        return View('EmployeeHome.CementIn.list', compact('cementIns', 'user'));
    }

    public function create()
    {
        $user = session('employee');
        $role = $user->role;
        $allsites = Sites::orderBy('site_name')->get();
        if($role == '3'){
            $mysites = $user->sitesByProjectManagers;
        } else if ($role == '4') {
            $mysites = $user->sitesByEmployees;
        }
        $today = $this->today;
        $yesterday = $this->yesterday;
        return view('EmployeeHome.CementIn.create', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday'));
    }

    public function store(CementInRequest $request)
    {
        $requestData = $request->validated() + ['employee_id' => session('employee')->id];
        CementIn::create($requestData);
        return back()->with('message', 'New "Cement In" created successfully!');
    }

    public function edit($user, CementIn $cement_in)
    {   
        $user = session('employee');
        $role = $user->role;
        $allsites = Sites::orderBy('site_name')->get();
        if($role == '3'){
            $mysites = $user->sitesByProjectManagers;
        } else if ($role == '4') {
            $mysites = $user->sitesByEmployees;
        }

        $today = $this->today;
        $yesterday = $this->yesterday;
       return view('EmployeeHome.CementIn.edit', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday', 'cement_in'));
    }

    public function update(CementInRequest $request, $user, CementIn $cementIn)
    {
        // dd($request->all(), $user);
        
        $requestData = $request->validated() + ['employee_id' => session('employee')->id];
        // dd($requestData);
        $cementIn->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement In" updated successfully!');
    }

    public static function editCementIns(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'to_site_id' => 'required',
            'from_site_id' => 'required',
            'remark' => 'required'
        ]); // validation for Cement Purchase

        $request->validate([
            'curr_site' => 'required'
        ]); // curr site on which PM is editing

        CementIn::create(
            $request->all() + ['employee_id' => session('employee')->id]
        );
        return ; 
    }
}
