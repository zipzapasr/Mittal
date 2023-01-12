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
    private $today;

    public function __construct() {
        $this->today = Carbon::today()->format('Y-m-d');
    }

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
        return View('Reports.ContractorReport', compact('contractors'));
    }

    public function viewCementPurchaseReport() {
        // $cement_purchases = CementPurchase::orderBy('date')->get();
        return View('Reports.CementPurchaseReport');
    }

    public function viewCementInReport() {
        // $cement_purchases = CementPurchase::orderBy('date')->get();
        return View('Reports.CementInReport');
    }

    public function viewCementOutReport() {
        // $cement_purchases = CementPurchase::orderBy('date')->get();
        return View('Reports.CementOutReport');
    }

    public function viewSiteLedger() {
        $sites = Sites::orderBy('site_name')->get();
        return View('Ledgers.SiteLedger', compact('sites'));
    }

    public function viewAllLedger() {
        return View('Ledgers.Ledger');
    }

    public function viewGodownPurchaseReport() { 
        return View('GodownReports.GodownPurchaseReport');
    }

    public function viewGodownOutReport() { 
        return View('GodownReports.GodownOutReport');
    }
    public function viewGodownInReport() { 
        return View('GodownReports.GodownInReport');
    }

    public function filterSiteReport(Request $request)
    {
        //dd($request->all());
        $site = SiteEntry::where(['site_id' => $request->site, 'status' => '2'])
                    ->with(['getActivity', 'getFieldType'])
                    ->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)
                    ->where('field_type_id', '!=', '4')
                    ->get()
                    ->groupBy(['activity_id', 'field_type_id', 'progress_date' ]);
        $site_name = Sites::where('id', $request->site)->first()->site_name;                        
        return response()->json(['data' => $site, 'request' => $request->all(), 'site_name' => $site_name], 200);
    }

    public function filterCementPurchaseReport(Request $request)
    {
        // dd(CementPurchase::all());
        $cement_purchases = CementPurchase::where('status', '1')
                                ->whereDate('date', '>=', $request->from)->whereDate('date', '<=', $request->to)
                                ->with(['getSite', 'getSupplier'])
                                ->orderBy('date')
                                ->get();
                               
        return response()->json(['data' => $cement_purchases, 'request' => $request->all()], 200);
    }

    public function filterCementInReport(Request $request)
    {
        // dd(CementIn::all());
        $cement_ins = CementIn::where('status', '1')
                                ->whereDate('date', '>=', $request->from)->whereDate('date', '<=', $request->to)
                                ->with(['getToSite', 'getFromSite'])
                                ->orderBy('date')
                                ->get();
        // dd($cement_ins);          
        return response()->json(['data' => $cement_ins, 'request' => $request->all()], 200);
    }

    public function filterCementOutReport(Request $request)
    {
        // dd(CementOut::all());
        $cement_outs = CementOut::where('status', '1')
                                ->whereDate('date', '>=', $request->from)->whereDate('date', '<=', $request->to)
                                ->with(['getToSite', 'getFromSite'])
                                ->orderBy('date')
                                ->get();
                               
        return response()->json(['data' => $cement_outs, 'request' => $request->all()], 200);
    }

    public function filterSiteTotalReport(Request $request)
    {
        $site = Sites::findOrFail($request->site);
        // dd($site);
        $entries = SiteEntry::where(['site_id' => $site->id, 'status' => '2'])
                    ->with(['getActivity'])
                    ->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)
                    ->get()
                    ->groupBy('activity_id');

        // dd($entries);
        
        $siteActivities = $site->getSiteactivity->groupBy(['activity_id'])->map(function($query){
            return $query->sum("qty");
        });
        $site_name = $site->site_name;
        $site_start_date = $site->created_at->format('Y-m-d');
        $today = $this->today;                        
        return response()->json(['data' => $entries, 'site_name' => $site_name, 'site_start_date' => $site_start_date, 'today' => $today, 'siteActivities' => $siteActivities, 'from' => $request->from, 'to' => $request->to], 200);
    }

    public function filterContractorReport(Request $request) {
        $contractor = Contractor::findOrFail($request->contractor);
        $entries = SiteEntry::where(['field_type_id' => '4', 'status' => '2', 'contractor_id' => $request->contractor])
                    ->with(['getSite', 'getActivity' => function($query){
                        return $query->with('getUnits');
                    }])
                    ->whereDate('progress_date', '>=', $request->from)->whereDate('progress_date', '<=', $request->to)
                    ->get()
                    ->groupBy('activity_id')
                    ->map(function ($query) {
                        return $query->groupBy('progress_date');
                    });
                    // To Refactor?
        //dd($entries);
        $contractor_name = $contractor->business_name; 
        return response()->json(['data' => $entries, 'request' => $request->all(), 'contractor_name' => $contractor_name], 200);
    }

    public function filterSiteLedger(Request $request) {
        
        $site_id = $request->site;
        $site = Sites::findOrFail($site_id);
        
        
        $dataByDates = [];
        $start_date = $site->created_at->format('Y-m-d');
        $today = $this->today;

        $begin = new \DateTime($start_date);
        $end = new \DateTime($today);


        for($i=$begin; $i<=$end; $i->modify('+1 day')){
            $date = $i->format('Y-m-d');

            $remarks = '';
            $remarks .= implode(',', CementPurchase::where(['site_id' => $site_id, 'date' => $date, 'status' => '1'])->pluck('remark')->toArray()) .' ';
            $remarks .= implode(',', CementIn::where(['to_site_id' => $site_id, 'date' => $date, 'status' => '1'])->pluck('remark')->toArray()) .' ';
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
            $today = $this->today;

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

    public function filterGodownPurchaseReport(Request $request)
    {
        // dd($request->all());
        $cement_purchases = CementPurchase::where(['status' => 1, 'site_id' => 0])->with(['getSupplier', 'getEmployee'])->get();                     
        return response()->json(['cement_purchases' => $cement_purchases, 'request' => $request->all()], 200);
    }

    public function filterGodownOutReport(Request $request)
    {
        // dd($request->all());
        $cement_outs = CementOut::where(['status' => 1, 'from_site_id' => 0])->with(['getToSite'])->get();                     
        return response()->json(['cement_outs' => $cement_outs, 'request' => $request->all()], 200);
    }

    public function filterGodownInReport(Request $request)
    {
        // dd($request->all());
        $cement_ins = CementIn::where(['status' => 1, 'from_site_id' => 0])->with(['getToSite'])->get();                     
        return response()->json(['cement_ins' => $cement_ins, 'request' => $request->all()], 200);
    }
}
