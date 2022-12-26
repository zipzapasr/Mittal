<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\SiteTransfer;
use App\Models\Sites;

class SiteTransferController extends Controller
{

    public function list()
    {
        $sitetransfer = SiteTransfer::with(['siteFromDetails', 'siteToDetails'])->get();

        //dd($sitetransfer);
        return View('SiteTransfer.list', ['sitetransfer' => $sitetransfer]);
    }

    public function create()
    {
        //$contractortlist = Contractor::all();
        $sitetransferlist = Sites::orderBy('site_name')->get();
        return View('SiteTransfer.create', compact('sitetransferlist'));
    }

    public function save(Request $request)
    {
        $request->validate([
            "date" => "required",
            "site_from" => "required",
            "site_to" => 'required',
            "num_bags" => 'required'
        ]);

        $requestData = [
            'date' => $request->date,
            'site_from' => $request->site_from,
            'site_to' => $request->site_to,
            "num_bags" => $request->num_bags,
            'status' => 1
        ];
        SiteTransfer::create($requestData);
        Session::flash('message', 'This is a message!');
        return back();
    }

    public function editSiteTransfer($sitetransferId)
    {
        $sitetransfer = SiteTransfer::where('id', $sitetransferId)->first();
        $sitetransferlist = Sites::all();
        return View('SiteTransfer.edit', compact('sitetransfer', 'sitetransferlist'));
    }

    public function updateSiteTransfer(Request $request)
    {
        $request->validate([
            "date" => "required",
            "site_from" => "required",
            "site_to" => 'required',
            "num_bags" => 'required'
        ]);
        $requestData = [
            'date' => $request->date,
            'site_from' => $request->site_from,
            'site_to' => $request->site_to,
            "num_bags" => $request->num_bags
        ];
        SiteTransfer::where('id', $request->sitetransferId)->update($requestData);

        Session::flash('message', 'Site Transfer updated successfully');
        return back();
    }

    public function changeStatus($sitetransferId)
    {

        $sitetransfer = SiteTransfer::where('id', $sitetransferId)->get();
        if ($sitetransfer->count() > 0) {
            if ($sitetransfer[0]['status'] == 0) {
                SiteTransfer::where('id', $sitetransferId)->update(['status' => 1]);
            } else {
                SiteTransfer::where('id', $sitetransferId)->update(['status' => 0]);
            }
        }
        Session::flash('message', 'Site Transfer updated successfully');
        return back();
    }
}
