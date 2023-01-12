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
        return View('Contractor.create'); //compact('contractorlist')
    }

    public function save(ContractorRequest $request){
        $requestData = $request->validated() + [
            'details' => $request->details,
            'status' => 1
        ];
        Contractor::create($requestData);
        Session::flash('message', 'Contractor created successfully!');
        return back();
    }

    public function editContractor(Contractor $contractor)
    {
        return View( 'Contractor.edit', compact('contractor'));
    }

    public function updateContractor(ContractorRequest $request)
    {
        $contractor = Contractor::findOrFail($request->contractorId);
        $requestData = $request->validated() + [
            'details' => $request->details,
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
