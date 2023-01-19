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
use App\Models\AdminSiteLog;
use Illuminate\Validation\Rule;
use Session;
use Carbon\Carbon;
use Schema;
use App\Http\Requests\CementPurchaseRequest;

class EmployeeHomeController extends Controller

{
    private $today;
    private $yesterday;
    private $remarks;

    public function __construct()
    {
        $this->today = Carbon::now()->format("Y-m-d");
        $this->yesterday = Carbon::yesterday()->format("Y-m-d");
        $this->remarks = app(AdminSiteLog::class)->remarks;
    }

    public function index()
    {
        $employee = session('employee'); // User // "hvb"
        $role = $employee->role;
        $mysites = [];
        $dataBySite = [];

        if ($role == '3') { // project Manager
            $mysites = (User::where('id', $employee->id)->with(['sitesByProjectManagers' => function ($query) {
                return $query->with([
                    'getSiteactivity' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getSiteEntries' => function($query){
                        return $query->where('status', '2')->get();
                    },
                    'getCementPurchases' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementInsSelf' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementOutsSelf' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementTransfersToClient' => function($query){
                        return $query->where('status', '1')->get();
                    }
                ])->get();
            }])->first())->sitesByProjectManagers;

        } else if ($role == '4') { //data entry operator
            $mysites = (User::where('id', $employee->id)->with(['sitesByEmployees' => function ($query) {
                return $query->with([
                    'getSiteactivity'=> function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getSiteEntries' => function($query){
                        return $query->where('status', '2')->get();
                    },
                    'getCementPurchases' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementInsSelf' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementOutsSelf' => function($query){
                        return $query->where('status', '1')->get();
                    },
                    'getCementTransfersToClient' => function($query){
                        return $query->where('status', '1')->get();
                    }
                ])->get();
            }])->first())->sitesByEmployees;
        }
        foreach($mysites as $site){
            $cement_purchase = $site->getCementPurchases->sum('bags');
            $cement_in = $site->getCementInsSelf->sum('bags');
            $cement_out = $site->getCementOutsSelf->sum('bags');
            $cement_consumption = $site->getSiteEntries->sum('cement_bags');
            $cement_transfer = $site->getCementTransfersToClient->sum('bags');

            $dataBySite[$site->id] = [
                'cement_purchase' => $cement_purchase,
                'cement_in' => $cement_in,
                'cement_out' => $cement_out,
                'cement_consumption' => $cement_consumption,
                'cement_transfer_to_client' => $cement_transfer
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

        } else if ($employee->role == '4') {
            $mysites = (User::where('id', $employee->id)->with(['sitesByEmployees' => function ($query) {
                return $query->with(['getSiteactivity'])->get();
            }])->first())->sitesByEmployees;

        }
        return View('EmployeeHome.mysites', compact('mysites'));
    }

    public function siteview(Sites $site)
    {
        // dd(FieldType::where('title', 'By Contractor')->first()); "4"
        
        $today = $this->today;
        $yesterday = $this->yesterday;
        $submitted = array();

        $site = Sites::where('id', $site->id)->with(['projectManager', 'dataEntryOperator'])->first();
        $field_types = FieldType::orderBy('title')->get();

        $activity = (Sites::where('id', $site->id)->with(['getSiteActivity' => function ($query) {
            return $query->with(['getActivity' => function ($query) {
                return $query->with('getUnits')->get();
            }])->get(); // activities that are selected by the admin for this site
        }])->first())->getSiteActivity;

        $entriesT = SiteEntry::with(['getActivity' => function($query) {
                        return $query->with('getUnits')->get();
                    }])->where([ // entries created by this site today
                        'site_id' => $site->id,
                    ])
                    ->whereDate('progress_date', $today)
                    ->get();

        $entriesY = SiteEntry::with(['getActivity' => function($query) {
                        return $query->with('getUnits')->get();
                    }])->where(['site_id' => $site->id]) // entries created by this site today
                    ->whereDate('progress_date', $yesterday)
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
            $reqDate = ($day == 'today') ? ($this->today) : ($this->yesterday);
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

        $entries = SiteEntry::where(['site_id' => $site_id,])
                    ->whereDate('progress_date', $reqDate)
                    ->get();

        $oldImages = [];

        foreach ($entries as $entry) {
            array_push($oldImages, $entry->images);
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
            
            if($k < count($oldImages)){
                if($imagesStorage){
                    $currImages = $oldImages[$k] .',' .implode(',', $imagesStorage);
                } else {
                    $currImages = $oldImages[$k];
                } 
            } else {
                $currImages = implode(',', $imagesStorage);
            }

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
                'images' => $currImages,
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
            $reqDate = ($day == 'today') ? ($this->today) : ($this->yesterday);
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

    public function deleteImage($entry, $ind)
    {
        $SiteEntry = SiteEntry::findOrFail($entry);
        $oldImages = explode(',', $SiteEntry->images);
        unset($oldImages[$ind]);
        $res = $SiteEntry->update([
            'images' => implode(',', $oldImages)
        ]);
        // dd($oldImages);

        return $res;
    }

    public function edit($key, Sites $site, $date){
        /* 
        public $keys = [
        'edit_site_entry_on_date',
        'edit_cement_purchase_on_date',
        'edit_cement_in_on_date',
        'edit_cement_out_on_date',
        'edit_cement_transfer_to_client_on_date'
    ];
        */
        // if a user comes to the edit link, first we make sure he is the PM of that site
        $currUser = session('employee');
        $editAccess = EditAccess::where(['value' => $site->id, 'key' => $key, 'status' => '0', 'date' => $date])->first();

        if(!$currUser or $site->projectManager->id != $currUser->id or !$editAccess){
            return abort(403);
        }
        
        if($key == 0) {
            return $this->editSiteEntries($site, $date);
        } else if($key == 1) {
            return $this->editCementPurchase($site, $date);
        } else if($key == 2) {
            return $this->editCementIn($site, $date);
        } else if($key == 3) {
            return $this->editCementOut($site, $date);
        } else if($key == 4) {
            return $this->editCementTransferToClient($site, $date);
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
        $activity = (Sites::where('id', $site->id)
                        ->with(['getSiteActivity' => function ($query) {
                            return $query->with(['getActivity' => function ($query) {
                                return $query->With('getUnits')->get();
                            }])
                            ->get(); // activities that are selected by the admin for this site
                        }])
                        ->first())
                        ->getSiteActivity;
        $field_types = FieldType::orderBy('title')->get();

        return View('EmployeeHome.Edit.Entries', compact('entries', 'employee', 'site', 'date', 'contractors','activity', 'field_types'));    
    }

    public function editCementPurchase(Sites $site, $date)
    {
        // dd($site, $date);
        $cement_purchases = CementPurchase::where(['site_id' => $site->id, 'date' => $date, 'status' => 1])->get();
        // dd($cement_purchases);
        $employee = session('employee');
        $suppliers = CementSupplier::where(['status' => 1])->orderBy('name')->get();
        $sites = Sites::where(['site_admin' => $site->projectManager->id, 'status' => 1])->get();

        return View('EmployeeHome.Edit.CementPurchase', compact('cement_purchases', 'employee', 'site', 'date', 'suppliers', 'sites'));    
    }
    public function editCementIn(Sites $site, $date)
    {
        // dd($site, $date);
        $cement_ins = CementIn::where(['date' => $date, 'status' => 1])->where(['to_site_id' => $site->id])->orWhere(['from_site_id' => $site->id])->get();
        $employee = session('employee');
        $allsites = Sites::where(['status' => 1])->orderBy('site_name')->get();
        $mysites = Sites::where(['site_admin' => $site->projectManager->id, 'status' => 1])->get();

        return View('EmployeeHome.Edit.CementIn', compact('cement_ins', 'employee', 'site', 'date', 'allsites', 'mysites'));    
    }

    public function editCementOut(Sites $site, $date)
    {
        // dd($site->id, $date);
        $collection = CementOut::where(['date' => $date, 'status' => 1])->get();
        // dd($collection);
        // $cement_outs = $collection->where(['to_site_id' => $site->id])->orWhere(['from_site_id' => $site->id])->get();
        $cement_outs = $collection->filter(function($query) use($site){
            return $query->from_site_id == $site->id || $query->to_site_id == $site->id;
        });
        // dd($cement_outs->pluck('date'));
        $employee = session('employee');
        $allsites = Sites::where(['status' => 1])->orderBy('site_name')->get();
        $mysites = Sites::where(['site_admin' => $site->projectManager->id, 'status' => 1])->get();

        return View('EmployeeHome.Edit.CementOut', compact('cement_outs', 'employee', 'allsites', 'date', 'mysites', 'site'));    
    }

    public function editCementTransferToClient(Sites $site, $date)
    {
        // dd($site, $date);
        $cement_transfers = CementTransferToClient::where(['site_id' => $site->id, 'date' => $date, 'status' => 1])->get();
        // dd($cement_purchases);
        $employee = session('employee');
        $sites = Sites::where(['site_admin' => $site->projectManager->id, 'status' => 1])->get();

        return View('EmployeeHome.Edit.CementTransferToClient', compact('cement_transfers', 'employee', 'site', 'date', 'sites'));    
    }


    public function saveEdits(Request $request, $key, $site, $date)
    {
        // dd($request->all(), $key, $site, $date);
        $request->validate([
            'bags' => "required|array"
        ]);

        if($key == 1) {
            CementPurchase::where(['site_id' => $site, 'date' => $date, 'status' => 1])->get()
                            ->each(function($cement_purchase) use($site, $date){
                                AdminSiteLog::create([
                                    'site_id' => $site,
                                    'updated_by_id' => session('employee')->id,
                                    'activity_id' => 0,
                                    'remarks' => $this->remarks[4],
                                    'status' => 1,
                                    "value" => $cement_purchase->toJson(),
                                    'date' => $date
                                ]);
                                $cement_purchase->update([
                                    'status' => 0
                                ]);
                            });
            
            foreach($request->bags as $k => $v) {
                $req = new Request([
                    'curr_site' => $site,
                    'date' => $date,
                    'bags' => $request->bags[$k],
                    'remark' => $request->remark[$k] ?? '',
                    'site_id' => $request->site[$k],
                    'supplier_id' => $request->supplier[$k]
                ]);
                // dd($req->validate($req->rules()));
                
                CementPurchaseController::editCementPurchases($req);
            }

            EditAccess::where(['value' => $site, 'date' => $date, 'status' => 0, 'key' => 1])
                        ->each(function($editAccess){
                            $editAccess->update([
                                'status' => 1
                            ]);
                        });

            session()->flash('message', 'Cement Purchases Edited Successfully');

        } else if($key == 2) {
            CementIn::where(['date' => $date, 'status' => 1])->where(['from_site_id' => $site])->orWhere(['to_site_id' => $site])->get()
            ->each(function($cement_in) use($site, $date){
                AdminSiteLog::create([
                    'site_id' => $site,
                    'updated_by_id' => session('employee')->id,
                    'activity_id' => 0,
                    'remarks' => $this->remarks[5],
                    'status' => 1,
                    "value" => $cement_in->toJson(),
                    'date' => $date
                ]);
                $cement_in->update([
                    'status' => 0
                ]);
            });

            foreach($request->bags as $k => $v) {
                $req = new Request([
                    'curr_site' => $site,
                    'date' => $date,
                    'bags' => $request->bags[$k],
                    'remark' => $request->remark[$k] ?? '',
                    'to_site_id' => $request->to_site[$k],
                    'from_site_id' => $request->from_site[$k]
                ]);
                // dd($req->validate($req->rules()));

                CementInController::editCementIns($req);
            }

            EditAccess::where(['value' => $site, 'date' => $date, 'status' => 0, 'key' => 2])
                    ->each(function($editAccess){
                        $editAccess->update([
                            'status' => 1
                        ]);
                    });

            session()->flash('message', 'Cement Ins Edited Successfully');
        } else if($key == 3) {
            CementOut::where(['date' => $date, 'status' => 1])->where(['from_site_id' => $site])->orWhere(['to_site_id' => $site])->get()
            ->each(function($cement_out) use($site, $date){
                AdminSiteLog::create([
                    'site_id' => $site,
                    'updated_by_id' => session('employee')->id,
                    'activity_id' => 0,
                    'remarks' => $this->remarks[6],
                    'status' => 1,
                    "value" => $cement_out->toJson(),
                    'date' => $date
                ]);
                $cement_out->update([
                    'status' => 0
                ]);
            });

            foreach($request->bags as $k => $v) {
                $req = new Request([
                    'curr_site' => $site,
                    'date' => $date,
                    'bags' => $request->bags[$k],
                    'remark' => $request->remark[$k] ?? '',
                    'to_site_id' => $request->to_site[$k],
                    'from_site_id' => $request->from_site[$k]
                ]);
                // dd($req->validate($req->rules()));

                CementOutController::editCementOuts($req);
            }

            EditAccess::where(['value' => $site, 'date' => $date, 'status' => 0, 'key' => 3])
                    ->each(function($editAccess){
                        $editAccess->update([
                            'status' => 1
                        ]);
                    });

            session()->flash('message', 'Cement Outs Edited Successfully');
        } else if($key == 4) {
            CementTransferToClient::where(['date' => $date, 'status' => 1])->where(['site_id' => $site])->get()
            ->each(function($cement_transfer) use($site, $date){
                AdminSiteLog::create([
                    'site_id' => $site,
                    'updated_by_id' => session('employee')->id,
                    'activity_id' => 0,
                    'remarks' => $this->remarks[7],
                    'status' => 1,
                    "value" => $cement_transfer->toJson(),
                    'date' => $date
                ]);
                $cement_transfer->update([
                    'status' => 0
                ]);
            });

            foreach($request->bags as $k => $v) {
                $req = new Request([
                    'curr_site' => $site,
                    'date' => $date,
                    'bags' => $request->bags[$k],
                    'remark' => $request->remark[$k] ?? '',
                    'site_id' => $request->site[$k],
                ]);
                // dd($req->validate($req->rules()));

                CementTransferController::editCementTransfers($req);
            }

            EditAccess::where(['value' => $site, 'date' => $date, 'status' => 0, 'key' => 4])
                    ->each(function($editAccess){
                        $editAccess->update([
                            'status' => 1
                        ]);
                    });

            session()->flash('message', 'Cement Transfers To Client Edited Successfully');
        }

        return redirect()->route("employee.home");
    }

}
