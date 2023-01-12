<?php

namespace App\Http\Controllers;

use App\Models\CementTransferToClient;
use App\Models\Sites;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

class CementTransferController extends Controller
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
        // dd(CementTransferToClient::all());
        $user = User::where('id', $id)->with(['sitesByEmployees', 'sitesByProjectManagers'])->first();
        // dd($user);
        if($user->role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if($user->role == '4'){
            $sites = $user->sitesByEmployees;
        }
        // dd($sites);
        $siteIds = $sites->pluck('id');
        // dd($siteIds);
        $cementTransfers = CementTransferToClient::whereIn('site_id', $siteIds)
                            ->where('date', $this->today)
                            ->orwhere('date', $this->yesterday)
                            ->with('getSite', 'getEmployee')
                            ->get();
        return View('EmployeeHome.CementTransferToClient.list', compact('cementTransfers'));
    }

    public function create($user)
    {                
        $user = User::where('id', $user)->with(['sitesByProjectManagers', 'sitesByEmployees'])->first();
        $role = $user->role;
        if($role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if($role == '4'){
            $sites = $user->sitesByEmployees;
        }
        //dd($sites);
        $today = $this->today;
        $yesterday = $this->yesterday;
        return view('EmployeeHome.CementTransferToClient.create', compact('role', 'user', 'sites', 'today', 'yesterday'));
    }

    public function store(Request $request, $user)
    {
        // dd($request->all(), $user);
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'site' => 'required',
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'employee_id' => $user,
            'site_id' => $request->site,
            'remark' => $request->remark ?? ''
        ];
        // dd($requestData);
        CementTransferToClient::create($requestData);
        // dd($new_purchase);
        return back()->with('message', 'New "Cement Transfer to Client" created successfully!');
    }

    public function edit($user, $id)
    {   
        // dd(Schema::getColumnListing('cement_purchase'));
        $cement_transfer = CementTransferToClient::where('id', $id)->first();
        // dd($cement_purchase);
        
        $user = User::where('id', $user)->with(['sitesByProjectManagers', 'sitesByEmployees'])->first();
        $role = $user->role;
        if($role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if($role == '4'){
            $sites = $user->sitesByEmployees;
        }
        $today = $this->today;
        $yesterday = $this->yesterday;
       return view('EmployeeHome.CementTransferToClient.edit', compact('role', 'user', 'sites', 'today', 'yesterday', 'cement_transfer'));
    }

    public function update(Request $request, $user,  $id)
    {
        // dd($request->all(), $user);
        $cement_transfer = CementTransferToClient::findOrFail($id);
        // dd($cement_transfer);
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'site' => 'required',
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'employee_id' => $user,
            'site_id' => $request->site,
            'remark' => $request->remark ?? ''
        ];
        // dd($requestData);
        $cement_transfer->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Transfer To Client" updated successfully!');
    }

    public static function editCementTransfers(Request $request)
    {
        // 'site_id', 'bags', 'date', 'remark', 'status', 'employee_id'
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'site_id' => 'required',
            'remark' => 'required'
        ]); // validation for Cement Purchase

        $request->validate([
            'curr_site' => 'required'
        ]); // curr site on which PM is editing

        CementTransferToClient::create(
            $request->all() + ['employee_id' => session('employee')->id]
        );
        return ; 
    }
}
