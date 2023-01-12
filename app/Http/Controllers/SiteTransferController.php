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
        return View('SiteTransfer.list', compact('sitetransfer'));
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

    public function editSiteTransfer(SiteTransfer $sitetransfer)
    {
        $sitetransferlist = Sites::orderBy('site_name')->get();
        return View('SiteTransfer.edit', compact('sitetransfer', 'sitetransferlist'));
    }

    public function updateSiteTransfer(Request $request)
    {
        $sitetransfer = SiteTransfer::findOrFail($request->sitetransferId);
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
        $sitetransfer->update($requestData);

        Session::flash('message', 'Site Transfer updated successfully');
        return back();
    }

    public function changeStatus(SiteTransfer $sitetransfer)
    {
        if ($sitetransfer['status'] == 0) {
            $sitetransfer->update(['status' => 1]);
        } else {
            $sitetransfer->update(['status' => 0]);
        }
        Session::flash('message', 'Site Transfer updated successfully');
        return back();
    }
}
