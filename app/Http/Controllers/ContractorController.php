<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Contractor;

class ContractorController extends Controller
{

    public function list(){
        $contractor = Contractor::all();
        return View( 'Contractor.list' ,['contractor' => $contractor]);
    }

    public function create()
    {
        //$contractortlist = Contractor::all();
        return View('Contractor.create'); //compact('contractorlist')
    }

    public function save(Request $request){
        $request->validate([
            "business_name" => "required",
            "name" => "required",
            "mobile" => "required|min:10|max:10",
        ]);

        $requestData = [
            'name' => $request->name,
            'business_name' => $request->business_name,
            'mobile' => $request->mobile,
            'details' => $request->details,
            'status' => 1
        ];
        Contractor::create($requestData);
        Session::flash('message', 'Contractor created successfully!');
        return back();
    }

    public function editContractor($contractorId)
    {
        $contractor = Contractor::where('id' , $contractorId)->first();
        return View( 'Contractor.edit', compact('contractor'));
    }

    public function updateContractor(Request $request)
    {
        $request->validate([
            "business_name" => "required",
            "name" => "required",
            "mobile" => "required|min:10|max:10",
        ]);
        $requestData = [
            'name' => $request->name,
            'business_name' => $request->business_name,
            'mobile' => $request->mobile,
            'details' => $request->details
        ];
        Contractor::where(['id' => $request->contractorId ])->update($requestData);

        Session::flash('message' , 'Contractor updated successfully');
        return back();
    }

    public function changeStatus($contractorId){

        $contractor = Contractor::where('id' , $contractorId)->get();
        if( $contractor->count() > 0 ){
            if( $contractor[0]['status'] == 0 ){
                Contractor::where('id' , $contractorId)->update([ 'status' => 1 ]);
            }else{
                Contractor::where('id' , $contractorId)->update([ 'status' => 0 ]);
            }
        }
        Session::flash('message' , 'Contractor updated successfully');
        return back();
    }
}
