<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sites;
use App\Models\User;
use App\Models\Activity;
use App\Models\Siteactivity;
use App\Models\SiteEntry;
use App\Models\Contractor;
use App\Models\SiteContractor;
use App\Models\AdminSiteLog;
use App\Models\EditAccess;
use Session;
use Carbon\Carbon;
use Mail;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    public function view(Sites $site)
    {
        return View('Site.view', compact('site'));
    }

    public function create()
    {
        $lastSiteDetails = [];
        $lastSite = Sites::orderBy('id', 'DESC')->get();
        $project_managers = User::where('role', '3')->get();
        $data_entry_operators = User::where('role', '4')->get();
        $contractors = Contractor::orderBy('name')->get();

        if ($lastSite->count() > 0) {
            $lastSiteDetails = $lastSite[0];
        }
        /*roles = [
        1 => 'Super Admin',
        2 => 'Admin',
        3 => 'Project Manager',
        4 => 'Site Data Entry',
        ];*/
        $activity = Activity::with(['getUnits'])->get();
        return View('Site.create', compact('lastSiteDetails', 'activity', 'project_managers', 'data_entry_operators', 'contractors'));
    }
    public function save(Request $request)
    {
        // dd($request->all());
        if (count(array_filter($request->estimate)) ==  0) {
            Session::flash('activityRequired', 'Atleast one activity Required');
            return back();
        };
        $request->validate([
            "serial_no" => "required|unique:site,serial_no",
            "site_name" => "required",
            "site_description" => "required",
            "site_location" => "required",
            "site_address" => 'required',
            "site_admin" => 'required',
        ]);

        $processedData = [
            'serial_no'         => $request->serial_no,
            'site_name'         => $request->site_name,
            'site_description'  => $request->site_description,
            'site_location'     => $request->site_location,
            'site_address'      => $request->site_address,
            'site_admin'        => $request->site_admin,
            'employees'         => ($request->employees > 0) ? ($request->employees) : ($request->site_admin)
        ];

        $lastUpdatedId = Sites::create($processedData);
        $projectManager = $lastUpdatedId->projectManager;
        $dataEntryOperator = ($request->employees != '0') ? ($lastUpdatedId->dataEntryOperator) : null;

        if (!empty($request->selectActivity)) {
            foreach ($request->selectActivity as $k => $v) {
                if ($v != 0) {
                    Siteactivity::create([
                        'site_id'       => $lastUpdatedId->id,
                        'activity_id'   => $v,
                        'qty'           => $request->estimate[$k],
                        'status'        => 1
                    ]);
                }
            }
        }

        // dd($lastUpdatedId);

        $data = array('name'=>$projectManager->name);
        $message = [];
        $mailTo = $projectManager->email;
        $mailMessage = "New Site {$lastUpdatedId->site_name} Assigned to you";
        $subject = 'A new site has been assigned to you as a project manager';
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";


        $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });

        //$response($message);
        // 

        if($dataEntryOperator != null) {
            $data = array('name'=>$dataEntryOperator->name);
            $message = [];
            $mailTo = $dataEntryOperator->email;
            $mailMessage = "New Site {$lastUpdatedId->site_name} Assigned to you";
            $subject = 'A new site has been assigned to you as a Data Entry Operator';
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";

            $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
        }

        
        if(count(array_filter($request->contractors)) != 0){
            foreach ($request->contractors as $k => $v) {
                if ($v != 0) {
                    SiteContractor::create([
                        'site_id' => $lastUpdatedId->id,
                        'contractor_id' => $v
                    ]);
                }
            }
        }
        
        Session::flash('message', 'Site Uploaded successfully');

        return back();
    }

    public function list()
    {
        $site = Sites::with('dataEntryOperator', 'projectManager', 'getSiteActivity')->get();
        //dd($site);
        return View('Site.list', compact('site'));
    }

    public function changeStatus(Sites $site)
    {
        if ($site['status'] == 0) {
            $site->update(['status' => 1]);
        } else {
            $site->update(['status' => 0]);
        }
        Session::flash('message', 'Site updated successfully');
        return back();
    }

    public function editSite($siteId)
    {
        $processedData = [];
        $site = Sites::where('id', $siteId)
                    ->with(['getSiteactivity' => function ($query) {
                        return $query->with(['getActivity' => function ($query) {
                            return $query->with('getunits')->get();
                    }])
                    ->get();
                    }])
                ->first();

        $activity = Activity::with(['getUnits'])->get();
        $project_managers = User::where('role', '3')->get();
        $data_entry_operators = User::where('role', '4')->get();
        $siteactivity = $site->getSiteactivity;
        $selectedContractors = SiteContractor::where(['site_id' => $siteId, 'status' => '1'])
                                ->get()
                                ->map(function ($query) {
                                    return $query->contractor_id;
                                })->toArray();
        // dd($selectedContractors);
        $contractors = Contractor::orderBy('name')->get();
        //dd($contractors);

        return View('Site.edit', compact('site', 'activity', 'processedData', 'project_managers', 'data_entry_operators', 'siteactivity', 'contractors', 'selectedContractors'));
    }

    public function updateSite(Request $request)
    {
        // dd($request);
        $site = Sites::findOrFail($request->siteId);

        $request->validate([
            "serial_no" => ["required", Rule::unique('site')->ignore($site->id)],
            "site_name" => "required",
            "site_description" => "required",
            "site_location" => "required",
            "site_address" => 'required',
            "site_admin" => 'required',
        ]);


        $site_id = $site->id;
        $prevProjectManager = $site->site_admin;
        $projectManagerChanged = False;
        $prevDataEntryOperator = $site->employees;
        $dataEntryOperatorChanged = False;

        if($request->site_admin != $prevProjectManager) {
            $projectManagerChanged = True;
        }

        if($request->employees != $prevDataEntryOperator) {
            $dataEntryOperatorChanged = True;
        }

        $processedData = [
            'serial_no'         => $request->serial_no,
            'site_name'         => $request->site_name,
            'site_description'  => $request->site_description,
            'site_location'     => $request->site_location,
            'site_address'      => $request->site_address,
            'site_admin'        => $request->site_admin,
            'employees'         => ($request->employees > 0) ? ($request->employees) : ($request->site_admin)
        ];

        $siteactivities = Siteactivity::where(['site_id' => $site_id])->get();

        $prevEst = [];
        foreach ($siteactivities as $key => $activity) {
            array_push($prevEst, $activity->qty);
            AdminSiteLog::create([
                'site_id' => $site_id,
                'activity_id' => $activity->activity_id,
                "updated_by_id" => auth()->user()->id,
                'remarks' => 'site_act_est_changed',
                'value' => $activity->qty,
                'date' => today()->format('Y-m-d')
            ]);
            $activity->delete();
        }

        if (!empty($request->selectActivity)) {
            foreach ($request->selectActivity as $k => $v) {
                if ($v != 0) {
                    Siteactivity::create([
                        'site_id'       => $site_id,
                        'activity_id'   => $v,
                        'qty'           => $request->estimate[$k],
                        'status'        => 1
                    ]);
                }
            }
        }

        $site->update($processedData);

        if($projectManagerChanged) {
            // dd(auth()->user());
            AdminSiteLog::create([
                'site_id' => $site->id,
                'activity_id' => 0,
                'updated_by_id' => auth()->user()->id,
                'remarks' => 'site_project_manager_changed',
                'value' => $prevProjectManager,
                'date' => today()->format('Y-m-d')
            ]);
            // $prev = User::where('id', $prevProjectManager)->first();
            $prev = User::findOrFail($prevProjectManager);
            $data = array('name' => $prev->name);
            $message = [];
            $mailTo = $prev->email;
            $mailMessage = "";
            $subject = "You have been removed from Site {$site->site_name} as project Manager";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
            //$response($message);

            $curr = User::findOrFail($site->site_admin);
            
            // dump($curr);
            $data = array('name' => $curr->name);
            $message = [];
            $mailTo = $curr->email;
            $mailMessage = "";
            $subject = "You have been assigned Site {$site->site_name} as project Manager";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
        }

        if($dataEntryOperatorChanged) {
            // dd(auth()->user());
            AdminSiteLog::create([
                'site_id' => $site->id,
                'activity_id' => 0,
                'updated_by_id' => auth()->user()->id,
                'remarks' => 'site_data_entry_operator_changed',
                'value' => $prevDataEntryOperator,
                'date' => today()->format('Y-m-d')
            ]);
            // $prev = User::where('id', $prevProjectManager)->first();
            $prev = User::findOrFail($prevDataEntryOperator);
            $data = array('name' => $prev->name);
            $message = [];
            $mailTo = $prev->email;
            $mailMessage = "";
            $subject = "You have been removed from Site {$site->site_name} as Data Entry Operator";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
            //$response($message);

            $curr = User::findOrFail($site->employees);
            
            // dump($curr);
            $data = array('name' => $curr->name);
            $message = [];
            $mailTo = $curr->email;
            $mailMessage = "";
            $subject = "You have been assigned Site {$site->site_name} as Data Entry Operator";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
        }

        //first deactivate all prev contractors of this site
        $prev = SiteContractor::where(['site_id' => $site_id, 'status' => '1'])->get();
        foreach($prev as $p) {
            $p->update([
                'status' => '0'
            ]);
        }

        //foreach contractor after edit, if this contractor is already in the table, activate it else create new entry
        foreach($request->contractors as $k => $v) {
            if($v != 0){
                $aval = SiteContractor::where(['site_id' => $site_id, 'contractor_id' => $v])->first();
                if($aval){
                    $aval -> update([
                        'status' => '1'
                    ]);
                } else {
                    SiteContractor::create([
                        'site_id' => $site_id,
                        'contractor_id' => $v,
                        'status' => '1'
                    ]);
                }
            }
        }

        Session::flash('message', 'Site updated successfully');
        return back();
    }

    public function getUsersByRole($roleId)
    {
        $users = User::where(['role' => $roleId])->get();
        return response()->json(['status' => true, 'data' => $users]);
    }

    public function filterSite(Request $request)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

        $site = Sites::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        return View('Site.list', compact('site'));
    }

    public function listSiteEntries(Sites $site)
    {
        $to = Carbon::today()->format('Y-m-d');
        $from = Carbon::today()->subDays(5)->format('Y-m-d');
        $site_id = $site->id;

        $siteActivity = Sites::where('id', $site_id)
                            ->with(['getSiteActivity'])
                            ->first()
                            ->getSiteActivity
                            ->groupBy('activity_id')
                            ->map(function($query){
                                return $query->sum('qty');
                            });
                           

        $entriesByDate = SiteEntry::where(['site_id' => $site_id])
                            ->with(['getFieldType', 'getContractor', 'getActivity' => function($query){
                                return $query->with('getUnits');
                            }])
                            ->whereIn('status', ['1', '2'])
                            ->get()
                            ->groupBy('progress_date');


        // $activity = Activity::with(['getUnits'])->get();
        
        return View('Site.listSiteEntries', compact('entriesByDate', 'siteActivity', 'from', 'to', 'site_id'));  
    }

    public function updateCementWastage(Request $request) {
        $entry = SiteEntry::findOrFail($request->site);
        $request->validate([
            'wastage' => 'required'
        ]);
        $updatedEntry = $entry->update([
            'wastage' => $request->wastage
        ]);
        // dd($updatedEntry);
        session()->flash('message', 'Cement Wastage for a site entry updated successfully');
        return back();
    }

    public function verify(Sites $site, $date)
    {
        $entries = SiteEntry::where([ // entries created by 'this site' 'today'
            'site_id' => $site->id,
        ])->whereDate('progress_date',  $date)->get();

        foreach ($entries as $entry) {
            $entry->update([
                'status' => '2'
            ]);
        }
        Session::flash('message', 'Site Entry(ies) Verified Successfully');
        return redirect()->route('list.siteEntries', ['site' => $site->id]);
    }

    public function filterSiteEntries(Request $request, Sites $site)
    {
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d');

        $entriesByDate = SiteEntry::where(['site_id' => $site->id])
                            ->with('getFieldType', 'getContractor')
                            ->whereIn('status', ['1', '2'])
                            ->get()
                            ->groupBy('progress_date');

        $site_id = $site->id;
        $siteActivity = Sites::where('id', $site_id)
                            ->with(['getSiteActivity'])
                            ->first()
                            ->getSiteActivity
                            ->groupBy('activity_id')
                            ->map(function($query){
                                return $query->sum('qty');
                            });
        return View('Site.listSiteEntries', compact('entriesByDate', 'siteActivity', 'from', 'to', 'site_id'));  
    }

    // public function giveEditAccess(Sites $site, $date)
    // {
    //     // dd($site, $date);
    //     $entries = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date])->get();

    //     $editAccess = EditAccess::create([
    //         'key' => 0,
    //         'value' => $site->id,
    //         'date' => $date
    //     ]);

    //     // public $keys = [
    //     //     'edit_site_entry_on_date',
    //     //     'edit_cement_purchase_on_date',
    //     //     'edit_cement_in_on_date',
    //     //     'edit_cement_out_on_date',
    //     //     'edit_cement_transfer_to_client_on_date'
    //     // ];

    //     foreach($entries as $entry){
    //         AdminSiteLog::create([
    //             'site_id' => $site->id,
    //             'activity_id' => $entry->activity_id,
    //             "updated_by_id" => auth()->user()->id,
    //             'remarks' => 'site_entry_changed',
    //             'value' => $entry->toJson(),
    //             'date' => $entry->progress_date
    //         ]);
    //         $entry->update([
    //             'status' => '0'
    //         ]);
            
    //     }
        

    //     $data = array(
    //         'name' => $site->projectManager->name,
    //         'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
    //     );
    //     $message = [];
    //     $mailTo = $site->projectManager->email;
    //     $mailMessage = "You have been given edit access for Site {$site->site_name} for date {$date}";
    //     $subject = "You have been given edit access for Site {$site->site_name} for date {$date}";
    //     $mailFrom = "surya@ripungupta.com";
    //     $mailFromName = "Surya Constructors";

    //     //Login to this page with your credentials // '/edit/key/site/date'

    //     $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
    //             $message->to($mailTo, $mailMessage)->subject($subject);
    //             $message->from($mailFrom,$mailFromName);
    //     });

    //     Session::flash('message', "You have given edit access for {$site->site_name} for date {$date}");

    //     return back();
    // }
}
