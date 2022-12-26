<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sites;
use App\Models\SiteEntry;
use App\Models\CementIn;
use App\Models\CementOut;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $entriesByDate = (SiteEntry::where('progress_date', '!=', '')->where('status', 1)->with(['getSite' => function ($query) {
            return $query->with(['projectManager'])->get();
        }])->get()->groupBy('progress_date'));
        $afterSiteGroup = $entriesByDate->map(function ($query) {
            return $query->groupBy('site_id');
        });
        /*$sites = Sites::with(['getSiteactivity', 'getSiteEntries' => function ($query) {
            return $query->where('status', '1')->get()->groupBy(function ($item) {
                return $item->progress_date;
            });
        }, 'projectManager'])->get();*/
        // dd($sites);
        //$siteEntries = SiteEntry::whereDate('created_at', '=', date('Y-m-d'))->get();
        $sites = Sites::all();
        $sitesData = $sites->map(function($site){
            return CementIn::where('to_site_id', $site->id)->sum('bags') - CementOut::where('to_site_id', $site->id)->sum('bags'); 
        });
        // dd($sitesData);

        return View('home', compact('afterSiteGroup', 'sites', 'sitesData'));
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }
}
