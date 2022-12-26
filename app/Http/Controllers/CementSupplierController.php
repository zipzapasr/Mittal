<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\CementSupplier;

class CementSupplierController extends Controller{

    public function list(){
        $cementsupplier = CementSupplier::all();
        return View( 'CementSupplier.list' ,['cementsupplier' => $cementsupplier]);
    }

    public function create()
    {
        //$contractortlist = Contractor::all();
        return View('CementSupplier.create'); //compact('contractorlist')
    }

    public function save(Request $request){
        $request->validate([
            "name" => "required",
            "mobile" => "required|min:10|max:10",   
        ]);
        
        $requestData = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'details' => $request->details,
            'status' => 1
        ];
        CementSupplier::create($requestData);
        Session::flash('message', 'New Cement Supplier Created!'); 
        return back();
    }

    public function editCementSupplier($cementsupplierId)
    {
        $cementsupplier = CementSupplier::where('id' , $cementsupplierId)->first();
        return View( 'CementSupplier.edit', compact('cementsupplier'));
    }

    public function updateCementSupplier(Request $request)
    {
        $request->validate([
            "name" => "required",
            "mobile" => "required|min:10|max:10",
        ]);
        $requestData = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'details' => $request->details
        ];
        CementSupplier::where('id', $request->cementsupplierId )->update($requestData);
        
        Session::flash('message' , 'Cement Supplier updated successfully');
        return back();
    }

    public function changeStatus($cementsupplierId){
        
        $cementsupplier = CementSupplier::where('id' , $cementsupplierId)->get();
        if( $cementsupplier->count() > 0 ){
            if( $cementsupplier[0]['status'] == 0 ){
                CementSupplier::where('id' , $cementsupplierId)->update([ 'status' => 1 ]);
            }else{
                CementSupplier::where('id' , $cementsupplierId)->update([ 'status' => 0 ]);
            }
        }
        Session::flash('message' , 'Cement Supplier updated successfully');
        return back();
    }
}

