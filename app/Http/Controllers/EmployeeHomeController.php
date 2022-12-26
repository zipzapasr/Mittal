<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sites;
use App\Models\User;
use App\Models\Activity;
use App\Models\CementIn;
use App\Models\CementOut;
use App\Models\CementPurchase;
use App\Models\CementTransferToClient;
use App\Models\SiteEntry;
use App\Models\CementSupplier;
use App\Models\FieldType;
use App\Models\SiteContractor;
use App\Models\EditAccess;
use Illuminate\Validation\Rule;
use Session;
use Carbon\Carbon;
use Schema;

class EmployeeHomeController extends Controller
{
    public function index()
    {
        $employee = session('employee'); // User // "hvb"
        $role = $employee->role;
        $mysites = [];
        $dataBySite = [];

        if ($role == '3') { // project Manager
            $mysites = (User::where('id', $employee->id)->with(['sitesByProjectManagers' => function ($query) {
                return $query->with([
                    'getSiteactivity',
                    'getSiteEntries' => function($query){
                        $query->where('status', '2');
                    },
                    'getCementPurchases',
                    'getCementIns',
                    'getCementOuts',
                    'getCementTransfersToClient'
                ])->get();
            }])->first())->sitesByProjectManagers;

        } else if ($role == '4') { //data entry operator
            $mysites = (User::where('id', $employee->id)->with(['sitesByEmployees' => function ($query) {
                return $query->with([
                    'getSiteactivity',
                    'getSiteEntries' => function($query){
                        $query->where('status', '2');
                    },
                    'getCementPurchases',
                    'getCementIns',
                    'getCementOuts',
                    'getCementTransfersToClient'
                ])->get();
            }])->first())->sitesByEmployees;
        }
        foreach($mysites as $site){
            $cement_purchase = $site->getCementPurchases->sum('bags');
            $cement_in = $site->getCementIns->sum('bags');
            $cement_out = $site->getCementOuts->sum('bags');
            $cement_consumption = $site->getSiteEntries->sum('cement_bags');
            $cement_transfer = $site->getCementTransfersToClient->sum('bags');

            $dataBySite[$site->id] = [
                'cement_purchase' => $cement_purchase,
                'cement_in' => $cement_in,
                'cement_out' => $cement_out,
                'cement_consumption' => $cement_consumption,
                'cement_transfer' => $cement_transfer
            ];
        }
        // $dataBySite = json_encode($dataBySite);
        return View('EmployeeHome.home', compact('role', 'mysites', 'dataBySite'));
    }

    public function mysites()
    {
        
        $employee = session('employee'); // User // "hvb"
        $mysites = [];

        if ($employee->role == '3') { // project Manager
            $mysites = (User::where('id', $employee->id)->with(['sitesByProjectManagers' => function ($query) {
                return $query->with(['getSiteactivity'])->get();
            }])->first())->sitesByProjectManagers;

            // $mysites = $employee->where('id', $employee->id)->with('sitesByProjectManagers')->first(); // sites belonging to the curr User
        } else if ($employee->role == '4') {
            $mysites = (User::where('id', $employee->id)->with('sitesByEmployees')->first())->sitesByEmployees;

            // $mysites = $employee->where('id', $employee->id)->with('sitesByEmployees')->first(); // sites belonging to the curr User
        }
        // dd($mysites);
        return View('EmployeeHome.mysites', compact('mysites', 'employee'));
    }

    public function siteview(Sites $site)
    {
        // dd(FieldType::where('title', 'By Contractor')->first()); "4"
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $submitted = array();

        $site = Sites::where('id', $site->id)->with(['projectManager', 'dataEntryOperator'])->first();
        $field_types = FieldType::orderBy('title')->get();

        $activity = (Sites::where('id', $site->id)->with(['getSiteActivity' => function ($query) {
            return $query->with(['getActivity' => function ($query) {
                return $query->With('getUnits')->get();
            }])->get(); // activities that are selected by the admin for this site
        }])->first())->getSiteActivity;

        $entriesT = SiteEntry::with(['getActivity' => function($query) {
            return $query->with('getUnits')->get();
        }])->where([ // entries created by this site today
            'site_id' => $site->id,
        ])
            ->whereDate('progress_date', '=', $today)
            ->get();

        $entriesY = SiteEntry::with(['getActivity' => function($query) {
            return $query->with('getUnits')->get();
        }])->where([ // entries created by this site today
            'site_id' => $site->id,
        ])
            ->whereDate('progress_date', '=', $yesterday)
            ->get();

        $submittedEntriesToday = SiteEntry::where('site_id', $site->id)
            ->whereDate('progress_date', '=', $today)
            ->whereIn('status', ['1','2']) // submitted or verified
            ->get();
        $submittedEntriesYesterday = SiteEntry::where('site_id', $site->id)
            ->whereDate('progress_date', '=', $yesterday)
            ->whereIn('status', ['1','2']) // submitted or verified
            ->get();

        if ($submittedEntriesToday->count() > 0) {
            $submitted['today'] = 'true';
        } else {
            $submitted['today'] = 'false';
        }

        if ($submittedEntriesYesterday->count() > 0) {
            $submitted['yesterday'] = 'true';
        } else {
            $submitted['yesterday'] = 'false';
        }

        $employee = session('employee');

        $contractors = SiteContractor::where(['site_id' => $site->id, 'status' => '1'])->with('getContractor')->get();
        // dd($contractors);

        return View('EmployeeHome.site_view', compact('site', 'activity', 'entriesT', 'entriesY', 'submitted', 'employee', 'field_types', 'today', 'yesterday', 'contractors'));
    }

    public function savesiteentry(Request $request, Sites $site, $day)
    {
        // dd($request->all());
        if($day != 'today' && $day != 'yesterday') {
            $reqDate = $day;
        } else {
            $reqDate = ($day == 'today') ? (Carbon::today()->format('Y-m-d')) : (Carbon::yesterday()->format('Y-m-d'));
        }

        $site_id = $site->id;
        if (count(array_filter($request->qty)) ==  0) {
            Session::flash('activityRequired', 'Atleast one activity Required');
            return back();
        };
        if (count(array_filter($request->selectActivity)) !=  count($request->selectActivity)) {
            Session::flash('activityRequired', 'Activity Required');
            return back();
        };
        if (count(array_filter($request->sw)) ==  0) {
            Session::flash('activityRequired', 'Atleast one activity Required');
            return back();
        };
        if (count(array_filter($request->remark)) ==  0) {
            Session::flash('activityRequired', 'Atleast one activity Required');
            return back();
        };

        $entries = SiteEntry::where([
            'site_id' => $site_id,
        ])->whereDate('progress_date', '=', $reqDate)->get();

        foreach ($entries as $entry) {
            $entry->delete();
        }

        foreach ($request->selectActivity as $k => $v) {
            //dd($savedEntries);
            $imageIndex = "images" . $k;
            $imagesStorage = [];
            if(array_key_exists($imageIndex, $request->all())) {
                foreach($request[$imageIndex] as $img) {
                    array_push($imagesStorage, $img->store('images', 'public'));
                };
            }
            // dd($imagesStorage);

            SiteEntry::create([
                'activity_id' => $v,
                'site_id' => $site_id,
                'qty' => $request->qty[$k],
                'field_type_id' => $request->field_type[$k],
                'contractor_id' => $request->contractor[$k] ?? '0',
                'skilled_workers' => $request->sw[$k],
                'skilled_workers_overtime' => $request->swo[$k],
                'unskilled_workers' => $request->usw[$k],
                'unskilled_workers_overtime' => $request->uswo[$k],
                'cement_bags' => $request->cement_bags[$k] ?? '0',
                'images' => implode(',', $imagesStorage),
                // 'images' => $request->images[$k],
                'remark' => $request->remark[$k],
                'status' => '0',
                'progress_date' => $reqDate
            ]);
        }
        Session::flash('message', 'Site Entry(ies) Saved Successfully');
        return back();
    }
    public function submit(Sites $site, $day)
    {
        if($day != 'today' && $day != 'yesterday') {
            $reqDate = $day;
            $editAccess = EditAccess::where(['key' => '0', 'value' => $site->id, 'status' => '0', 'date' => $day])->first();
            if(!$editAccess){
                return abort(403);
            }
            $editAccess->update([
                'status' => '1'
            ]);
        } else {
            $reqDate = ($day == 'today') ? (Carbon::today()->format('Y-m-d')) : (Carbon::yesterday()->format('Y-m-d'));
        }
        //dd(SiteEntry::all());
        //$employee_id = (Session::get('employee')->id);
        $site_id = $site->id;
        $entries = SiteEntry::where([ // entries created by 'this site' 'today'
            'site_id' => $site_id,
        ])->whereDate('progress_date', '=', $reqDate)->get();
        //dd($entries);

        foreach ($entries as $entry) {
            $entry->update([
                'status' => '1'
            ]);
        }
        
        Session::flash('message', 'Site Entry(ies) Saved and Submitted Successfully');
        return back();
    }

    public function edit($key, Sites $site, $date){
        // if a user comes to the edit link, first we have make sure he is the PM of that site
        $currUser = session('employee');
        $editAccess = EditAccess::where(['value' => $site->id, 'status' => '0', 'date' => $date])->first();
        // if($editAccess){
        //     dd($editAccess);
        // }

        if(!$currUser or $site->projectManager->id != $currUser->id or !$editAccess){
            return abort(403);
        }
        
        if($key == 0) {
            return $this->editSiteEntries($site, $date);
        }
    }

    public function editSiteEntries(Sites $site, $date)
    {
        // dd($site, $date);
        $entries = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date])->get();
        // dd($entries);
        // dump("abc");
        $employee = session('employee');
        $contractors = SiteContractor::where(['site_id' => $site->id, 'status' => '1'])->with('getContractor')->get();
        $activity = (Sites::where('id', $site->id)->with(['getSiteActivity' => function ($query) {
            return $query->with(['getActivity' => function ($query) {
                return $query->With('getUnits')->get();
            }])->get(); // activities that are selected by the admin for this site
        }])->first())->getSiteActivity;
        $field_types = FieldType::orderBy('title')->get();

        return View('EmployeeHome.editEntries', compact('entries', 'employee', 'site', 'date', 'contractors','activity', 'field_types'));    
    }

    public function deleteImage(SiteEntry $entry, $ind)
    {
        dd($entry, $ind);
    }

    public function listCementPurchase($id)
    {
        // dd($id);
        // dd(Schema::getColumnListing('cement_purchase'));
        $today = Carbon::today()->format("Y-m-d");
        $yesterday = Carbon::yesterday()->format("Y-m-d");
        // dump($yesterday);

        // dd(CementPurchase::where('employee_id', $id)->get());
        
        $cementPurchases = CementPurchase::where('employee_id', $id)
                            ->where('date', $today)->orWhere('date', $yesterday)
                            ->with('getSite', 'getSupplier', 'getEmployee')->get();
        // dd($cementPurchases);
        return View('EmployeeHome.CementPurchase.list', compact('cementPurchases'));
    }

    public function createCementPurchase()
    {
        // dd(
        //     Schema::getColumnListing('cement_purchase'),
        //     Schema::getColumnListing('cement_in'),
        //     Schema::getColumnListing('cement_out'),
        // );
        
        $role = Session::get('employee')->role;
        $user = session('employee');
        $suppliers = CementSupplier::orderBy('name')->get();
        $sites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        //dd($sites);
        return view('EmployeeHome.CementPurchase.create', compact('role', 'user', 'suppliers', 'sites', 'today', 'yesterday'));
    }

    public function storeCementPurchase(Request $request, $user)
    {
        // dd($request->all(), $user);
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'supplier' => 'required',
            'site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'supplier_id' => $request->supplier,
            'employee_id' => $user,
            'site_id' => $request->site,
            'remark' => $request->remark
        ];
        // dd($requestData);
        CementPurchase::create($requestData);
        // dd($new_purchase);
        return back()->with('message', 'New "Cement Purchase" created successfully!');
    }

    public function editCementPurchase($user, $id)
    {   
        // dd(Schema::getColumnListing('cement_purchase'));
        // dd(CementPurchase::all());
        // dd($id);
        $cement_purchase = CementPurchase::where('id', $id)->first();
        // $cement_purchase = CementPurchase::find($id);
        // dd($cement_purchase);
        $role = Session::get('employee')->role;
        $user = session('employee');
        $suppliers = CementSupplier::orderBy('name')->get();
        $sites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
       return view('EmployeeHome.CementPurchase.edit', compact('role', 'user', 'suppliers', 'sites', 'today', 'yesterday', 'cement_purchase'));
    }

    public function updateCementPurchase(Request $request, $user, $id)
    {
        // dd($request->all(), $user);
        $cementPurchase = CementPurchase::find($id);
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'supplier' => 'required',
            'site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'supplier_id' => $request->supplier,
            'employee_id' => $user,
            'site_id' => $request->site,
            'remark' => $request->remark
        ];
        // dd($requestData);
        $cementPurchase->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Purchase" updated successfully!');
    }


    public function listCementIn()
    {
        $today = Carbon::today()->format("Y-m-d");
        $yesterday = Carbon::yesterday()->format("Y-m-d");
        $cementIns = CementIn::with(['getToSite', 'getFromSite'])
                        ->where('date', $today)
                        ->orWhere('date', $yesterday)
                        ->get();
        //dd($cementIns);
        $user = session('employee');
        return View('EmployeeHome.CementIn.list', compact('cementIns', 'user'));
    }

    public function createCementIn()
    {
        //dd(CementIn::all());
        $role = Session::get('employee')->role;
        $user = session('employee');
        $allsites = Sites::orderBy('site_name')->get();
        $mysites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        //dd($sites);
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        return view('EmployeeHome.CementIn.create', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday'));
    }

    public function storeCementIn(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'from_site' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'from_site_id' => $request->from_site,
            'to_site_id' => $request->to_site,
            'remark' => $request->remark,
            'employee_id' => session('employee')->id,
        ];
        CementIn::create($requestData);
        return back()->with('message', 'New "Cement In" created successfully!');
    }

    public function editCementIn($user, $id)
    {   
        $cement_in = CementIn::where('id', $id)->first();
        $role = Session::get('employee')->role;
        $user = session('employee');
        $allsites = Sites::orderBy('site_name')->get();
        $mysites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
       return view('EmployeeHome.CementIn.edit', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday', 'cement_in'));
    }

    public function updateCementIn(Request $request, $user, $id)
    {
        // dd($request->all(), $user);
        $cementIn = CementIn::find($id);
        
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'from_site' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'from_site_id' => $request->from_site,
            'to_site_id' => $request->to_site,
            'remark' => $request->remark,
            'employee_id' => $user,
        ];
        // dd($requestData);
        $cementIn->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement In" updated successfully!');
    }

    public function listCementOut()
    {
        $today = Carbon::today()->format("Y-m-d");
        $yesterday = Carbon::yesterday()->format("Y-m-d");
        $cementOuts = CementOut::with(['getToSite', 'getFromSite'])
                        ->where('date', $today)
                        ->orWhere('date', $yesterday)
                        ->get();
        //dd($cementIns);
        return View('EmployeeHome.CementOut.list', compact('cementOuts'));
    }

    public function createCementOut()
    {
        //dd(CementIn::all());
        $role = Session::get('employee')->role;
        $user = session('employee');
        $allsites = Sites::orderBy('site_name')->get();
        $mysites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        //dd($sites);
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        return view('EmployeeHome.CementOut.create', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday'));
    }

    public function storeCementOut(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'from_site' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'from_site_id' => $request->from_site,
            'to_site_id' => $request->to_site,
            'remark' => $request->remark,
            'employee_id' => session('employee')->id
        ];
        CementOut::create($requestData);
        return back()->with('message', 'New "Cement Out" created successfully!');
    }

    public function editCementOut($user, $id)
    {   
        $cement_out = CementOut::where('id', $id)->first();
        $role = Session::get('employee')->role;
        $user = session('employee');
        $allsites = Sites::orderBy('site_name')->get();
        $mysites = (User::where('id', $user->id)->with(['sitesByProjectManagers'])->first())->sitesByProjectManagers;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
       return view('EmployeeHome.CementOut.edit', compact('role', 'user', 'allsites', 'mysites', 'today', 'yesterday', 'cement_out'));
    }

    public function updateCementOut(Request $request, $user, $id)
    {
        // dd($request->all(), $user);
        $cementOut = CementOut::find($id);
        
        $request->validate([
            'date' => 'required',
            'bags' => 'required',
            'from_site' => 'required',
            'to_site' => 'required',
            'remark' => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'bags' => $request->bags,
            'from_site_id' => $request->from_site,
            'to_site_id' => $request->to_site,
            'remark' => $request->remark,
            'employee_id' => $user,
        ];
        // dd($requestData);
        $cementOut->update($requestData);
        // dd($new_purchase);
        return back()->with('message', ' "Cement Out" updated successfully!');
    }

    public function listCementTransfer($id)
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
        $today = Carbon::today()->format("Y-m-d");
        $yesterday = Carbon::yesterday()->format("Y-m-d");
        $cementTransfers = CementTransferToClient::whereIn('site_id', $siteIds)
                            ->where('date', $today)
                            ->orwhere('date', $yesterday)
                            ->with('getSite', 'getEmployee')
                            ->get();
        return View('EmployeeHome.CementTransferToClient.list', compact('cementTransfers'));
    }

    public function createCementTransfer($user)
    {                
        $user = User::where('id', $user)->with(['sitesByProjectManagers', 'sitesByEmployees'])->first();
        $role = $user->role;
        if($role == '3'){
            $sites = $user->sitesByProjectManagers;
        } else if($role == '4'){
            $sites = $user->sitesByEmployees;
        }
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        //dd($sites);
        return view('EmployeeHome.CementTransferToClient.create', compact('role', 'user', 'sites', 'today', 'yesterday'));
    }

    public function storeCementTransfer(Request $request, $user)
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

    public function editCementTransfer($user, $id)
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
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
       return view('EmployeeHome.CementTransferToClient.edit', compact('role', 'user', 'sites', 'today', 'yesterday', 'cement_transfer'));
    }

    public function updateCementTransfer(Request $request, $user,  $id)
    {
        // dd($request->all(), $user);
        $cement_transfer = CementTransferToClient::where('id', $id)->first();
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

}
