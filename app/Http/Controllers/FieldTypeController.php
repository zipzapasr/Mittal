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

    public function changeStatus($field_type_Id){
        $field_type = FieldType::where('id' , $field_type_Id)->get();
        if( $field_type->count() > 0 ){
            if($field_type[0]['status'] == 0){
                FieldType::where('id' , $field_type_Id)->update([ 'status' => 1]);
            }else{
                FieldType::where('id' , $field_type_Id)->update(['status' => 0]);
            }
        }
        Session::flash('message' , 'FieldType status changed successfully');
        return back();
    }
    
    public function editFieldType($field_type_Id){
        $field_type = FieldType::where('id' , $field_type_Id)->first();
        return View('Fields.edit' , compact('field_type'));
    }
    
    public function updateFieldType( Request $request ){
        FieldType::where(['id' => $request->id])->update(['title' => $request->title]);
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
        $field_type = FieldType::create($field_data);
        //dd($field_type);

        Session::flash('message' , 'FieldType added successfully');
        return back();
    }
}
