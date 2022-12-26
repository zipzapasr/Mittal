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
use App\Models\EditAccess;
use Session;
use Carbon\Carbon;
use Mail;

class SiteController extends Controller
{
    public function view($siteId)
    {
        $site = Sites::where('id', $siteId)->first();
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
            "serial_no" => "required|unique:Sites,serial_no",
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
        $projectManager = (Sites::where('id', $lastUpdatedId->id)->with('projectManager')->first())->projectManager;
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


        $response = Mail::send('mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });
        //$response($message);

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

    public function changeStatus($siteId)
    {

        $Site = Sites::where('id', $siteId)->get();
        if ($Site->count() > 0) {
            if ($Site[0]['status'] == 0) {
                Sites::where('id', $siteId)->update(['status' => 1]);
            } else {
                Sites::where('id', $siteId)->update(['status' => 0]);
            }
        }
        Session::flash('message', 'Site updated successfully');
        return back();
    }

    public function editSite($siteId)
    {
        $processedData = [];
        $site = Sites::where('id', $siteId)->with(['getSiteactivity' => function ($query) {
            return $query->with(['getActivity' => function ($query) {
                return $query->with('getunits')->get();
            }])->get();
        }])->first();

        $activity = Activity::with(['getUnits'])->get();
        $project_managers = User::where('role', '3')->get();
        $data_entry_operators = User::where('role', '4')->get();
        $siteactivity = $site->getSiteactivity;
        $selectedContractors = SiteContractor::where(['site_id' => $siteId, 'status' => '1'])->get()->map(function ($query) {
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
        $site_id = $request->siteId;
        $site = Sites::where(['id' => $site_id])->first();
        $prevProjectManager = $site->site_admin;
        $projectManagerChanged = False;

        $request->validate([
            "serial_no" => "required|unique:Sites,serial_no,{$site_id}",
            "site_name" => "required",
            "site_description" => "required",
            "site_location" => "required",
            "site_address" => 'required',
            "site_admin" => 'required',
            //"employees" => 'required'
        ]);

        if($request->site_admin != $prevProjectManager) {
            $projectManagerChanged = True;
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

        Siteactivity::where(['site_id' => $site_id])->delete();

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
            $prev = User::where('id', $prevProjectManager)->first();
            $data = array('name' => $prev->name);
            $message = [];
            $mailTo = $prev->email;
            $mailMessage = "";
            $subject = "You have been removed from Site {$site->site_name} as project Manager";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                    $message->to($mailTo, $mailMessage)->subject($subject);
                    $message->from($mailFrom,$mailFromName);
            });
            //$response($message);

            // dump($site->site_admin);
            $curr = User::find($site->site_admin);
            
            // dump($curr);
            $data = array('name' => $curr->name);
            $message = [];
            $mailTo = $curr->email;
            $mailMessage = "";
            $subject = "You have been assigned Site {$site->site_name} as project Manager";
            $mailFrom = "surya@ripungupta.com";
            $mailFromName = "Surya Constructors";


            $response = Mail::send('mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
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

        $site = Sites::whereDate('progress_date', '>=', $from)->whereDate('progress_date', '<=', $to)->get();
        return View('Site.list', compact('site'));
    }

    // public function listSiteEntries(Sites $site)
    // {
    //     $today = Carbon::today()->format('Y-m-d');
    //     //dump($today);
    //     $fivedaysago = Carbon::today()->subDays(5)->format('Y-m-d');
    //     $site = Sites::where('id', $site->id)->with('getSiteActivity')->first();

    //     $entriesByDate = SiteEntry::with('getFieldType', 'getContractor')->where(['site_id' => $site->id])->whereIn('status', ['1', '2'])->whereDate('progress_date', '>=', $fivedaysago)->whereDate('progress_date', '<=', $today)->get()->groupBy('progress_date');
    //     // $entriesByDate = SiteEntry::where(['site_id' => $site->id])->with(['getFieldType', 'getContractor',])->whereIn('status', ['1', '2'])->get()->groupBy('progress_date');

    //     //  'getActivity' => function($query){
    //     //     return $query->with('getUnits');
    //     // }

    //     $activity = Activity::with(['getUnits'])->get();
        
    //     return View('Site.listSiteEntries', compact('entriesByDate', 'activity', 'site'));  //'from', 'to'
    // }

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
        $entry = SiteEntry::find($request->site);
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
        ])->whereDate('progress_date', '=', $date)->get();

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

        $entriesByDate = SiteEntry::where(['site_id' => $site->id])->with('getFieldType', 'getContractor')->whereIn('status', ['1', '2'])->get()->groupBy('progress_date');
        //dd($entries);

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

    public function giveEditAccess(Sites $site, $date)
    {
        // dd($site, $date);
        $entries = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date])->get();

        foreach($entries as $entry){
            $entry->update([
                'status' => '0'
            ]);
        }

        // public $keys = [
        //     'edit_site_entry_on_date',
        //     'edit_cement_purchase_on_date',
        //     'edit_cement_in_on_date',
        //     'edit_cement_out_on_date',
        //     'edit_cement_transfer_to_client_on_date'
        // ];

        $editAccess = EditAccess::create([
            'key' => 0,
            'value' => $site->id,
            'date' => $date
        ]);

        $data = array(
            'name' => $site->projectManager->name,
            'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
        );
        $message = [];
        $mailTo = $site->projectManager->email;
        $mailMessage = "You have been given edit access for Site {$site->site_name} for date {$date}";
        $subject = "You have been given edit access for Site {$site->site_name} for date {$date}";
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";

        //Login to this page with your credentials // '/edit/key/site/date'

        $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });
        //$response($message);

        // dd($date, $entries);

        Session::flash('message', "You have given edit access for {$site->site_name} for date {$date}");

        return back();
    }
}
