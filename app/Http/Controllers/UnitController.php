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
    
    public function changeStatus(Unit $unit){
        // dd($unit);
        if($unit['status'] == 0){
            $unit->update([ 'status' => 1]);
        }else{
            $unit->update(['status' => 0]);
        }
        Session::flash('message' , 'Unit status updated successfully');
        return back();
    }
    
    public function editunit(Unit $unit){
        return View('Unit.edit' , compact('unit'));
    }
    
    public function updateunit(Request $request ){
        $unit = Unit::findOrFail($request->id);
        $unit->update(['title' => $request->title]);
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