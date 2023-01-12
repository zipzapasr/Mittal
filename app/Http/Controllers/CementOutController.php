<?php

namespace App\Http\Controllers;

use App\Models\CementOut;
use App\Models\Sites;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Http\Requests\CementOutRequest;

class CementOutController extends Controller
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
        
        $cementOuts = CementOut::with(['getToSite', 'getFromSite'])
                        ->whereIn('date', [$this->today, $this->yesterday])
                        ->get();
        //dd($cementIns);
        return View('EmployeeHome.CementOut.list', compact('cementOuts'));
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
        return view('EmployeeHome.CementOut.create', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday'));
    }

    public function store(CementOutRequest $request)
    {
        $requestData = $request->validated() + ['employee_id' => session('employee')->id];
        CementOut::create($requestData);
        return back()->with('message', 'New "Cement Out" created successfully!');
    }

    public function edit($user, $id)
    {   
        $cement_out = CementOut::findOrFail($id);
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
       return view('EmployeeHome.CementOut.edit', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday', 'cement_out'));
    }

    public function update(CementOutRequest $request, $user, CementOut $cementOut)
    {
        // dd($request->all(), $user);
        
        $requestData = $request->validated() + ['employee_id' => session('employee')->id];
        // dd($requestData);
        $cementOut->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Out" updated successfully!');
    }

    public static function editCementOuts(Request $request)
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

        CementOut::create(
            $request->all() + ['employee_id' => session('employee')->id]
        );
        return ; 
    }
}
