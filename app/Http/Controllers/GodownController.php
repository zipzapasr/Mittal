<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\CementPurchase;
use App\Models\CementSupplier;
use App\Models\Sites;
use App\Models\User;
use App\Http\Requests\CementPurchaseRequest;
use App\Models\CementOut;
use App\Http\Requests\CementOutRequest;


class GodownController extends Controller

{
    private $today;
    private $yesterday;

    public function __construct()
    {
        $this->today = Carbon::now()->format("Y-m-d");
        $this->yesterday = Carbon::yesterday()->format("Y-m-d");
    }

    public function index()
    {
        $godown = session('godown');
        $cement_purchases = CementPurchase::where(['status' => 1, 'site_id' => 0])->get()->sum('bags');
        $cement_outs = CementOut::where(['status' => 1, 'from_site_id' => 0])->get()->sum('bags');

        $cement_available = $cement_purchases - $cement_outs;

        return View('Godown.home', compact('godown', 'cement_available'));
    }

    public function listCementPurchase($id)
    {
        // dd(Schema::getColumnListing('cement_purchase'));
        
        $cementPurchases = CementPurchase::where(['employee_id' => $id, 'status' => 1])
                            ->whereIn('date', [$this->today, $this->yesterday])
                            ->with('getSite', 'getSupplier', 'getEmployee')
                            ->get();
        // dd($cementPurchases);
        return View('Godown.CementPurchase.list', compact('cementPurchases'));
    }

    public function createCementPurchase()
    {
        $user = session('godown');
        $role = $user->role;
        $suppliers = CementSupplier::where(['status' => 1])->orderBy('name')->get();
        
        //dd($sites);
        $today = $this->today;
        $yesterday = $this->yesterday;
        return view('Godown.CementPurchase.create', compact('role', 'user', 'suppliers', 'today', 'yesterday'));
    }

    public static function storeCementPurchase(Request $req, $user)
    {
        // dd($req->all());
        $req->validate([
            'date' => 'required',
            'bags' => 'required',
            'supplier' => 'required',
            'remark' => 'required'
        ]);
        // $requestData = $req->validated() + ['employee_id' => $user];
        $requestData = [
            'date' => $req->date,
            'bags' => $req->bags,
            'supplier_id' => $req->supplier,
            'remark' => $req->remark,
            'site_id' => 0,
            'employee_id' => $user
        ];
        CementPurchase::create($requestData);
        // dd($new_purchase);
        return back()->with('message', 'New "Cement Purchase" created successfully!');
    }

    public function editCementPurchase($user, CementPurchase $cement_purchase)
    {   
        // dd(Schema::getColumnListing('cement_purchase'));
        $user = session('godown');
        $role = $user->role;
        
        $suppliers = CementSupplier::where(['status' => 1])->orderBy('name')->get();
        $today = $this->today;
        $yesterday = $this->yesterday;
       return view('Godown.CementPurchase.edit', compact('role', 'user', 'suppliers', 'today', 'yesterday', 'cement_purchase'));
    }

    public function updateCementPurchase(Request $req, $user, CementPurchase $cement_purchase)
    {
        // dd($request);
        // $requestData = $request->validated() + ['employee_id' => $user];
        $req->validate([
            'date' => 'required',
            'bags' => 'required',
            'supplier' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $req->date,
            'bags' => $req->bags,
            'supplier_id' => $req->supplier,
            'remark' => $req->remark,
            'site_id' => 0,
            'employee_id' => $user
        ];
        // dd($requestData);
        $cement_purchase->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Purchase" updated successfully!');
    }

    public function listCementOut()
    {
        
        $cementOuts = CementOut::with(['getToSite', 'getFromSite'])->where(['status' => 1])
                        ->whereIn('date', [$this->today, $this->yesterday])
                        ->get();
        //dd($cementIns);
        return View('Godown.CementOut.list', compact('cementOuts'));
    }

    public function createCementOut()
    {
        $user = session('godown');
        $role = $user->role;
        $allsites = Sites::where(['status' => 1])->orderBy('site_name')->get();
        $today = $this->today;
        $yesterday = $this->yesterday;
        return view('Godown.CementOut.create', compact('role', 'user', 'allsites', 'today', 'yesterday'));
    }

    public function storeCementOut(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        // $requestData = $request->validated() + ['employee_id' => session('godown')->id];
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'to_site_id' => $request->to_site,
            'from_site_id' => 0, // godown
            'remark' => $request->remark,
            'employee_id' => session('godown')->id
        ];
        CementOut::create($requestData);
        return back()->with('message', 'New "Cement Out" created successfully!');
    }

    public function editCementOut($user, $id)
    {   
        $cement_out = CementOut::findOrFail($id);
        $user = session('godown');
        $role = $user->role;
        $allsites = Sites::where(['status' => 1])->orderBy('site_name')->get();
        $today = $this->today;
        $yesterday = $this->yesterday;
       return view('Godown.CementOut.edit', compact('role', 'user', 'allsites', 'today', 'yesterday', 'cement_out'));
    }

    public function updateCementOut(Request $request, $user, CementOut $cementOut)
    {
        // dd($request->all(), $user);
        
        // $requestData = $request->validated() + ['employee_id' => session('godown')->id];
        // dd($requestData);
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        // $requestData = $request->validated() + ['employee_id' => session('godown')->id];
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'to_site_id' => $request->to_site,
            'from_site_id' => 0, // godown
            'remark' => $request->remark,
            'employee_id' => session('godown')->id
        ];
        $cementOut->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Out" updated successfully!');
    }


}
