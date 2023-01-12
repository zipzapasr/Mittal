<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sites;
use App\Models\SiteEntry;
use App\Models\CementIn;
use App\Models\CementOut;
use App\Models\CementPurchase;
use App\Models\CementTransferToClient;
use App\Models\EditAccess;
use App\Models\AdminSiteLog;
use App\Models\User;
use Session;
use Carbon\Carbon;
use Mail;

class HomeController extends Controller
{
    private $today;
    private $yesterday;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->today = Carbon::today()->format("Y-m-d");
        $this->yesterday = Carbon::yesterday()->format("Y-m-d");
    }
    // public $keys = [
        //     'edit_site_entry_on_date',
        //     'edit_cement_purchase_on_date',
        //     'edit_cement_in_on_date',
        //     'edit_cement_out_on_date',
        //     'edit_cement_transfer_to_client_on_date'
        // ];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $afterSiteGroup = (SiteEntry::where('progress_date', '!=', '')
                            ->where('status', 1)
                            ->with(['getSite' => function ($query) {
                                return $query->with(['projectManager', 'getCementInsSelf', 'getCementInsOther', 'getCementOutsSelf', 'getCementOutsOther'])->get();
                            }])
                            ->get()
                            ->groupBy('progress_date'))
                            ->map(function ($query) {
                                return $query->groupBy('site_id');
                            });

        // $afterSiteGroup = $entriesByDate->map(function ($query) {
        //     return $query->groupBy('site_id');
        // });
        $sites = Sites::all();
        // $sitesData = $sites->map(function($site){
        //     return CementIn::where('to_site_id', $site->id)->sum('bags') - CementOut::where('to_site_id', $site->id)->sum('bags'); 
        // });
        $sitesData = $sites->map(function($site){
            $arr = ['received' => 0, 'sent' => 0];
            $arr['received'] = ($site->getCementInsSelf->where('status',1)->sum('bags')) - ($site->getCementInsOther->where('status',1)->sum('bags'));
            $arr['sent'] = ($site->getCementOutsSelf->where('status',1)->sum('bags')) - ($site->getCementOutsOther->where('status',1)->sum('bags'));

            
            return $arr;
        });
        // dd($sitesData);

        return View('home', compact('afterSiteGroup', 'sites', 'sitesData'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }

    public function changePassword(Request $request, User $user) {
        return view('changePassword', compact('user', 'request'));
    }

    public function authenticate_and_change_password(Request $request, User $user) {
        //dd($request, $user);
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        if($request->new_password != $request->confirm_password) {
            return back()->with('message', 'New Passwords do not match');
        }
        if(!Hash::check($request->old_password, $user->password)){
            return back()->with('message', 'Old Password is incorrect');
        }
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);
        return redirect()->route('home')->with('message', 'Password Changed Successfully');
    }

    public function viewEditAccess()
    {
        $allEditAccess = EditAccess::with('getSite.projectManager')->get();
        $currEditAccess = EditAccess::with('getSite.projectManager')->where('status', 0)->get();
        // dd($editAccess, $activeEditAccess);
        $keys = app(EditAccess::class)->keys;
        return view('viewEditAccess', compact('allEditAccess', "currEditAccess", 'keys'));
    }

    public function viewEditLogs()
    {
        $remarks = app(AdminSiteLog::class)->remarks;
        $logs = (AdminSiteLog::with(["getActivity.getUnits", 'getEmployee', 'getSite','getProjectManager' ,"getDataEntryOperator"])
                ->get()
                ->groupBy('remarks'))
                ->toArray();

        
        // dd($logs);

        return view('viewEditLogs', compact('remarks', 'logs'));

    }

    public function giveEditAccessView() {
        $sites = Sites::orderBy('site_name')->get();
        return view('giveEditAccess', compact('sites'));
    }

    public function giveEditAccess(Request $request) {
        /*
        access_for = [
            cement_purchase,
            cement_in,
            cement_out,
            cement_transfer_to_client
        ]
        */
        // dd($request->all());
        $request->validate([
            'access_for' => 'required',
            'site' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $site = Sites::findOrFail($request->site);

        if($request->access_for == '0') {
            return $this-> giveEditAccessCementPurchase($site, $request->date);
        } else if($request->access_for == '1') {
            return $this-> giveEditAccessCementIn($site, $request->date);
        } else if($request->access_for == '2') {
            return $this-> giveEditAccessCementOut($site, $request->date);
        } else if($request->access_for == '3') {
            return $this-> giveEditAccessCementTransferToClient($site, $request->date);
        }

    }

    public function revokeEditAccess(EditAccess $editAccess)
    {
        // dd($editAccess);
        $editAccess->update([
            'status' => 1
        ]);
        return back()->with('message', "Edit Access for Site {$editAccess->getSite->site_name} for date {$editAccess->date} has been revoked");
    }

    public function giveEditAccessSiteEntries(Sites $site, $date)
    {
        // dd($site, $date);
        $entries = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date])->get();

        $editAccess = EditAccess::create([
            'key' => 0,
            'value' => $site->id,
            'date' => $date
        ]);


        foreach($entries as $entry){
            AdminSiteLog::create([
                'site_id' => $site->id,
                'activity_id' => $entry->activity_id,
                "updated_by_id" => auth()->user()->id,
                'remarks' => 'site_entry_changed',
                'value' => $entry->toJson(),
                'date' => $entry->progress_date
            ]);
            $entry->update([
                'status' => '0'
            ]);
        }
        

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

        Session::flash('message', "You have given edit access for {$site->site_name} for date {$date}");

        return back();
    }

    public function giveEditAccessCementPurchase(Sites $site, $date)
    {
        // dd($site, $date);
        // dd($site->projectManager);
        // $cement_purchases = CementPurchase::where(['site_id' => $site->id, 'date' => $date])->get();

        $editAccess = EditAccess::create([
            'key' => 1,
            'value' => $site->id,
            'date' => $date
        ]);

        // foreach($entries as $entry){
        //     AdminSiteLog::create([
        //         'site_id' => $site->id,
        //         'activity_id' => $entry->activity_id,
        //         "updated_by_id" => auth()->user()->id,
        //         'remarks' => 'site_entry_changed',
        //         'value' => $entry->toJson(),
        //         'date' => $entry->progress_date
        //     ]);
        //     $entry->update([
        //         'status' => '0'
        //     ]);
        // }
        
        $data = array(
            'name' => $site->projectManager->name,
            'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
        );
        $message = [];
        $mailTo = $site->projectManager->email;
        $mailMessage = "You have been given edit access for Cement Purchases for Site {$site->site_name} for date {$date}";
        $subject = "You have been given edit access for Cement Purchases for Site {$site->site_name} for date {$date}";
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";

        //Login to this page with your credentials // '/edit/key/site/date'

        $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });

        // dump($response);

        Session::flash('message', "You have given edit access for Cement Purchases for {$site->site_name} for date {$date}");

        return back();
    }

    public function giveEditAccessCementOut(Sites $site, $date)
    {
        // $cement_outs = CementOut::where(['site_id' => $site->id, 'date' => $date])->get();

        $editAccess = EditAccess::create([
            'key' => 3,
            'value' => $site->id,
            'date' => $date
        ]);


        // foreach($entries as $entry){
        //     AdminSiteLog::create([
        //         'site_id' => $site->id,
        //         'activity_id' => $entry->activity_id,
        //         "updated_by_id" => auth()->user()->id,
        //         'remarks' => 'site_entry_changed',
        //         'value' => $entry->toJson(),
        //         'date' => $entry->progress_date
        //     ]);
        //     $entry->update([
        //         'status' => '0'
        //     ]);
        // }
        

        $data = array(
            'name' => $site->projectManager->name,
            'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
        );
        $message = [];
        $mailTo = $site->projectManager->email;
        $mailMessage = "You have been given edit access for Cement Outs for Site {$site->site_name} for date {$date}";
        $subject = "You have been given edit access for Cement Outs for Site {$site->site_name} for date {$date}";
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";

        //Login to this page with your credentials // '/edit/key/site/date'

        $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });

        Session::flash('message', "You have given edit access for Cement Outs for {$site->site_name} for date {$date}");

        return back();
    }

    public function giveEditAccessCementIn(Sites $site, $date)
    {
        // $cement_ins = CementIn::where(['site_id' => $site->id, 'date' => $date])->get();

        $editAccess = EditAccess::create([
            'key' => 2,
            'value' => $site->id,
            'date' => $date
        ]);


        // foreach($entries as $entry){
        //     AdminSiteLog::create([
        //         'site_id' => $site->id,
        //         'activity_id' => $entry->activity_id,
        //         "updated_by_id" => auth()->user()->id,
        //         'remarks' => 'site_entry_changed',
        //         'value' => $entry->toJson(),
        //         'date' => $entry->progress_date
        //     ]);
        //     $entry->update([
        //         'status' => '0'
        //     ]);
        // }
        

        $data = array(
            'name' => $site->projectManager->name,
            'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
        );
        $message = [];
        $mailTo = $site->projectManager->email;
        $mailMessage = "You have been given edit access for Cement Ins for Site {$site->site_name} for date {$date}";
        $subject = "You have been given edit access for for Cement Ins Site {$site->site_name} for date {$date}";
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";

        //Login to this page with your credentials // '/edit/key/site/date'

        $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });

        Session::flash('message', "You have given edit access for Cement Ins for {$site->site_name} for date {$date}");

        return back();
    }

    public function giveEditAccessCementTransferToClient(Sites $site, $date)
    {
        // $cement_transfer_to_client = CementTransferToClient::where(['site_id' => $site->id, 'date' => $date])->get();

        $editAccess = EditAccess::create([
            'key' => 4,
            'value' => $site->id,
            'date' => $date
        ]);

        // foreach($entries as $entry){
        //     AdminSiteLog::create([
        //         'site_id' => $site->id,
        //         'activity_id' => $entry->activity_id,
        //         "updated_by_id" => auth()->user()->id,
        //         'remarks' => 'site_entry_changed',
        //         'value' => $entry->toJson(),
        //         'date' => $entry->progress_date
        //     ]);
        //     $entry->update([
        //         'status' => '0'
        //     ]);  
        // }

        $data = array(
            'name' => $site->projectManager->name,
            'link' => "https://ripungupta.com/mittal/public/edit/{$editAccess->key}/{$editAccess->value}/{$editAccess->date}"
        );
        $message = [];
        $mailTo = $site->projectManager->email;
        $mailMessage = "You have been given edit access for Cement Transfers To Client for Site {$site->site_name} for date {$date}";
        $subject = "You have been given edit access for Cement Transfers To Client Site {$site->site_name} for date {$date}";
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructors";

        //Login to this page with your credentials // '/edit/key/site/date'

        $response = Mail::send("mails.editAccess", $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });

        Session::flash('message', "You have given edit access Cement Transfers To Client for {$site->site_name} for date {$date}");

        return back();
    }
}
