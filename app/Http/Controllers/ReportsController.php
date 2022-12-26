<?php

namespace App\Http\Controllers;

use App\Models\CementPurchase;
use App\Models\CementIn;
use App\Models\CementOut;
use App\Models\CementTransferToClient;
use App\Models\SiteEntry;
use App\Models\Sites;
use App\Models\Contractor;
use Illuminate\Http\Request;
use Schema;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function viewSiteReport()
    {
        $sites = Sites::orderBy('site_name')->get();
        return View('Reports.SiteReport', compact('sites'));
    }

    public function viewSiteTotalReport()
    {
        $sites = Sites::orderBy('site_name')->get();
        return View('Reports.SiteTotalReport', compact('sites'));
    }

    public function viewContractorReport() {
        $contractors = Contractor::orderBy('business_name')->get();
        return View('Reports.ContractorReport', compact(('contractors')));
    }

    public function viewCementPurchaseReport() {
        // $cement_purchases = CementPurchase::orderBy('date')->get();
        return View('Reports.CementPurchaseReport');
    }

    public function viewSiteLedger() {
        $sites = Sites::orderBy('site_name')->get();
        return View('Ledgers.SiteLedger', compact('sites'));
    }

    public function viewAllLedger() {
        return View('Ledgers.Ledger');
    }

    public function filterSiteReport(Request $request)
    {
        //dd($request->all());
        /*$site = Sites::where('id', $request->site)->with(['getSiteEntries' => function ($query) use ($request) {
            return $query->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)
            ->get()
            ->groupBy(function($query){
                return $query->progress_date;
            });
        }])->first();*/
        $site = SiteEntry::where(['site_id' => $request->site, 'status' => '2'])
        // ->select(['qty', 'skilled_workers', 'skilled_workers_overtime', 'unskilled_workers', 'unskilled_workers_overtime'])
        ->with(['getActivity', 'getFieldType'])
        ->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)
        ->where('field_type_id', '!=', '4')
        ->get()
        ->groupBy(['activity_id', 'field_type_id', 'progress_date' ]);
        // ->groupBy(['activity_id' => function($query) {
        //     return $query->groupBy('field_type_id');
        // }])
        // ->map(function($query) {
        //     return $query->groupBy('field_type_id');
        // })
        // ->map(function ($query) {
        //     return $query->groupBy('progress_date')->map(function($nyQuery){
        //         return $nyQuery->groupBy('field_type_id');
        //     });
        // }) ;
        //dd($site);
        $site_name = Sites::where('id', $request->site)->first()->site_name;                        
        return response()->json(['data' => $site, 'request' => $request->all(), 'site_name' => $site_name], 200);
    }

    public function filterCementPurchaseReport(Request $request)
    {
        // dd(CementPurchase::all());
        $cement_purchases = CementPurchase::where('status', '1')->whereDate('date', '>=', $request->from)->whereDate('date', '<=', $request->to)->with(['getSite', 'getSupplier'])->orderBy('date')->get();
        // $site = SiteEntry::where(['site_id' => $request->site, 'status' => '2'])->with(['getActivity'])->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)->where('field_type_id', '!=', '4')->get()->groupBy('activity_id')->map(function ($query) {
        //     return $query->groupBy('progress_date');
        // });
        //dd($site);                        
        return response()->json(['data' => $cement_purchases, 'request' => $request->all()], 200);
    }

    public function filterSiteTotalReport(Request $request)
    {
        $entries = SiteEntry::where(['site_id' => $request->site, 'status' => '2'])->with(['getActivity'])->get()->groupBy('activity_id');
        //dd($site);
        $site = Sites::where('id', $request->site)->with('getSiteactivity')->first();
        $siteActivities = $site->getSiteactivity->groupBy(['activity_id'])->map(function($query){
            return $query->sum("qty");
        });
        $site_name = $site->site_name;
        $site_start_date = $site->created_at->format('Y-m-d');
        $today = date('Y-m-d');                        
        return response()->json(['data' => $entries, 'site_name' => $site_name, 'site_start_date' => $site_start_date, 'today' => $today, 'siteActivities' => $siteActivities], 200);
    }

    public function filterContractorReport(Request $request) {
        $entries = SiteEntry::where(['field_type_id' => '4', 'status' => '2'])
        ->with(['getSite', 'getActivity' => function($query){
            return $query->with('getUnits');
        }])
        ->where('contractor_id', $request->contractor)
        ->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)->get()
        ->groupBy('activity_id')
        ->map(function ($query) {
            return $query->groupBy('progress_date');
        });
        //dd($entries);
        $contractor_name = Contractor::where('id', $request->contractor)->first()->business_name; 
        return response()->json(['data' => $entries, 'request' => $request->all(), 'contractor_name' => $contractor_name], 200);
    }

    public function filterSiteLedger(Request $request) {
        //dump($request->site);
        // dd(
        //     Schema::getColumnListing('cement_purchase'),
        //     Schema::getColumnListing('cement_in'),
        //     Schema::getColumnListing('cement_out'),
        //     Schema::getColumnListing('site_entries')
        // );
        $site_id = $request->site;
        $site = Sites::where('id', $site_id)->first();
        // $site = Sites::where('id', $request->site)->with([
        //     'getCementPurchases' => function($query){
        //         return $query->groupBy('date'); 
        //     }, 
        //     'getCementIns' => function($query){
        //         return $query->groupBy('date');
        //     }, 
        //     'getCementOuts' => function($query){
        //         return $query->groupBy('date');
        //     }, 
        //     'getSiteEntries' => function($query){
        //         return $query->groupBy('progress_date');
        //     }
        //     ])->first();
        $dataByDates = [];
        $start_date = $site->created_at->format('Y-m-d');
        $today = Carbon::today()->format('Y-m-d');

        $begin = new \DateTime($start_date);
        $end = new \DateTime($today);


        for($i=$begin; $i<=$end; $i->modify('+1 day')){
            $date = $i->format('Y-m-d');

            $remarks = '';
            $remarks .= implode(',', CementPurchase::where(['site_id' => $site_id, 'date' => $date, 'status' => '1'])->pluck('remark')->toArray());
            $remarks .= ' ';
            $remarks .= implode(',', CementIn::where(['to_site_id' => $site_id, 'date' => $date, 'status' => '1'])->pluck('remark')->toArray());
            $remarks .= ' ';
            $remarks .= implode(',', CementOut::where(['from_site_id' => $site_id, 'date' => $date, 'status' => '1'])->pluck('remark')->toArray());

            $cement_purchases = CementPurchase::where(['site_id' => $site_id, 'date' => $date, 'status' => '1'])->sum('bags');
            $cement_ins = CementIn::where(['to_site_id' => $site_id, 'date' => $date, 'status' => '1'])->sum('bags');
            $cement_outs = CementOut::where(['from_site_id' => $site_id, 'date' => $date, 'status' => '1'])->sum('bags');
            $cement_consumption = SiteEntry::where(['site_id' => $site_id, 'progress_date' => $date, 'status' => '2'])->sum('cement_bags');
            $cement_transfers = CementTransferToClient::where(['site_id' => $site_id, 'date' => $date, 'status' => '1'])->sum('bags');
            $cement_wastage = SiteEntry::where(['site_id' => $site_id, 'progress_date' => $date, 'status' => '2'])->sum('wastage');


            $dataByDates[$date] = [
                'cement_purchases' => $cement_purchases,
                'cement_ins' => $cement_ins,
                'cement_outs' => $cement_outs,
                'cement_consumption' => $cement_consumption,
                'cement_transfers' => $cement_transfers,
                'cement_wastage' => $cement_wastage,
                'remarks' => $remarks
            ];
        }
        // dd($dataByDates);        
        return response()->json(['data' => $dataByDates, 'request' => $request->all(), 'site' => $site], 200);
        
    }

    public function filterAllLedger(Request $request) {
        // dd($request->all());
        $sites = Sites::orderBy('site_name')->get();
        $dataBySites = [];

        foreach($sites as $site){
            $dataBySites[$site->id]['site'] = $site;
            $start_date = $site->created_at->format('Y-m-d');
            $today = Carbon::today()->format('Y-m-d');

            $begin = new \DateTime($start_date);
            $end = new \DateTime($today);

            for($i=$begin; $i<=$end; $i->modify('+1 day')){

                $date = $i->format('Y-m-d');
                $cement_purchases = CementPurchase::where(['site_id' => $site->id, 'date' => $date, 'status' => '1'])->sum('bags');
                $cement_ins = CementIn::where(['to_site_id' => $site->id, 'date' => $date, 'status' => '1'])->sum('bags');
                $cement_outs = CementOut::where(['from_site_id' => $site->id, 'date' => $date, 'status' => '1'])->sum('bags');
                $cement_consumption = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date, 'status' => '2'])->sum('cement_bags');
                $cement_transfers = CementTransferToClient::where(['site_id' => $site->id, 'date' => $date, 'status' => '1'])->sum('bags');
                $cement_wastage = SiteEntry::where(['site_id' => $site->id, 'progress_date' => $date, 'status' => '2'])->sum('wastage');


                $dataBySites[$site->id][$date] = [
                    'cement_purchases' => $cement_purchases,
                    'cement_ins' => $cement_ins,
                    'cement_outs' => $cement_outs,
                    'cement_consumption' => $cement_consumption,
                    'cement_transfers' => $cement_transfers,
                    'cement_wastage' => $cement_wastage
                ];
            }
        }
        // dd($dataByDates);        
        return response()->json(['data' => $dataBySites, 'request' => $request->all()], 200);
        
    }
}
