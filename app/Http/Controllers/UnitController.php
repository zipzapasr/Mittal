<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Session;

class UnitController extends Controller
{
    public function create(){
        return View('Unit.create');
    }
    
    public function list(){
        $unit = Unit::all();
        return View('Unit.list' , compact('unit'));
    }
    
    public function changeStatus($unitId){
        $unit = Unit::where('id' , $unitId)->get();
        if( $unit->count() > 0 ){
            if($unit[0]['status'] == 0){
                Unit::where('id' , $unitId)->update([ 'status' => 1]);
            }else{
                Unit::where('id' , $unitId)->update(['status' => 0]);
            }
        }
        Session::flash('message' , 'Unit status successfully');
        return back();
    }
    
    public function editunit($unitId){
        $unit = Unit::where('id' , $unitId)->first();
        return View('Unit.edit' , compact('unit'));
    }
    
    public function updateunit( Request $request ){
        Unit::where(['id' => $request->id])->update(['title' => $request->title]);
        Session::flash('message' , 'Unit Updated Successfully');
        return back();
    }
    
    public function save(Request $request){
        $request->validate([
            'title' => 'required|unique:unit'
        ]);
        
        Unit::create([
            'title' => $request->title
        ]);
        
        Session::flash('message' , 'Unit added successfully');
        return back();
    }
}