<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Contractor;
use App\Http\Requests\ContractorRequest;

class ContractorController extends Controller
{

    public function list(){
        $contractor = Contractor::all();
        return View( 'Contractor.list' , compact('contractor'));
    }

    public function create()
    {
        //$contractortlist = Contractor::all();
        $identification_types = app(Contractor::class)->identification_types;
        return View('Contractor.create', compact('identification_types')); 

    }

    public function save(ContractorRequest $request){
        // dd($request->all());
        $request->validate([
            'identification' => 'required'
        ]);
        $identification = $request->identification->store('identifications', 'public');
        $requestData =  [
            'business_name' => $request->business_name,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'identification_type' => $request->identification_type,
            'details' => $request->details,
            'identification' => $identification,
            'status' => 1
        ];
        $contractor = Contractor::create($requestData);
        dd($contractor);
        Session::flash('message', 'Contractor created successfully!');
        return back();
    }

    public function editContractor(Contractor $contractor)
    {
        $identification_types = app(Contractor::class)->identification_types;
        return View( 'Contractor.edit', compact('contractor','identification_types'));
    }

    public function updateContractor(ContractorRequest $request)
    {
        $contractor = Contractor::findOrFail($request->contractorId);
        if($request->identification){
            $identification = $request->identification->store('identifications', 'public');
        } else {
            $identification = $contractor->identification;
        }
        $requestData =  [
            'business_name' => $request->business_name,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'identification_type' => $request->identification_type,
            'details' => $request->details,
            'identification' => $identification,
            'status' => 1
        ];
        $contractor->update($requestData);

        Session::flash('message' , 'Contractor updated successfully');
        return back();
    }

    public function changeStatus(Contractor $contractor){
        // dd($contractor);
        if( $contractor['status'] == 0 ){
            $contractor->update([ 'status' => 1 ]);
        }else{
            $contractor->update([ 'status' => 0 ]);
        }
        Session::flash('message' , 'Contractor updated successfully');
        return back();
    }
}
