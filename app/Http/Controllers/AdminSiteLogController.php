<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminSiteLog;
use App\Models\Sites;

class AdminSiteLogController extends Controller

{
    private $remarks;
    
    public function __construct()
    {
        $this->remarks = app(AdminSiteLog::class)->remarks;
    }
    public function index()
    {
        dd(AdminSiteLog::all());
    }

    public function siteEstLog(Sites $site)

    {
        // dd($site->id);
        $estEditLogs = $site->getEditLogs->load(['getActivity' => function($query){
                        return $query->with('getUnits');
                    }])
                    ->where('remarks', $this->remarks[0])
                    ->sortByDesc('created_at');
        // dd($editLogs);
        $projectManagerEditLogs = $site->getEditLogs
                                ->where('remarks', $this->remarks[1])
                                ->sortByDesc('created_at');

        $dataEntryOperatorEditLogs = $site->getEditLogs
                                    ->where('remarks', $this->remarks[2])
                                    ->sortByDesc('created_at');
        return view('EditLogs.siteEstChangedLogs', compact('site', "estEditLogs", 'projectManagerEditLogs', 'dataEntryOperatorEditLogs'));
    }

    public function siteEntriesLog(Sites $site, $date) {
        $editLogs = $site->getEditLogs->load(['getActivity' => function($query){
                        return $query->with('getUnits');
                    }])
                    ->where('remarks', $this->remarks[3])
                    ->where('date', $date)
                    ->sortByDesc('created_at');
                    
        // dd($editLogs);
        return view('EditLogs.siteEntriesChangedLogs', compact('site', 'editLogs', 'date'));
    }
}
