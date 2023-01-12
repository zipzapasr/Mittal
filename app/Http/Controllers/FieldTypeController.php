<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FieldType;
use Session;
use Illuminate\Support\Facades\DB;
use Schema;

class FieldTypeController extends Controller
{
    public function create(){
        return View('Fields.create');
    }

    public function list(){
        //dd(Schema::getColumnListing('field_types'));
        $field_types = FieldType::all();
        // dd($field_types);
        return View('Fields.list' , compact('field_types'));
    }

    public function changeStatus(FieldType $field_type){
        if($field_type['status'] == 0){
            $field_type->update([ 'status' => 1]);
        }else{
            $field_type->update(['status' => 0]);
        }
        Session::flash('message' , 'FieldType status changed successfully');
        return back();
    }
    
    public function editFieldType(FieldType $field_type){
        return View('Fields.edit' , compact('field_type'));
    }
    
    public function updateFieldType( Request $request ){
        $field_type = FieldType::findOrFail($request->id);
        $field_type->update(['title' => $request->title]);
        Session::flash('message' , 'FieldType Updated Successfully');
        return back();
    }
    
    public function save(Request $request){
        //dd($request->all());
        $request->validate([
            'title' => 'required'
        ]);
        $field_data = [
            'title' => $request->title
        ];
        FieldType::create($field_data);
        Session::flash('message' , 'FieldType added successfully');
        return back();
    }
}
