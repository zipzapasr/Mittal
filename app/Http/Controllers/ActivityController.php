<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Unit;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{ 
    public function create()
    {
        $unitlist = Unit::all();
        $activity_types_list = DB::table('activity_types')->get();
        return View('Activity.create', compact('unitlist', 'activity_types_list'));
    }
    public function save(Request $request){
        //dd($request);
        $activityName = $request->activity_name;
        $activity_description = $request->activity_description;
        $unit = $request->unit;
        $activity_type = $request->activity_type;
        
        $processedData = [];
        foreach($activityName as $k => $v){
            if( $v != null ){
                if($activity_description[$k] != null){
                    $processedData[] = 
                    [ 
                        'activity_name' => $v ,
                        'activity_description' => $activity_description[$k] ,
                        'unit' => $unit[$k],
                        'activity_type' => ($activity_type[$k] != null) ? ($activity_type[$k]) : "1"
                    ];
                }
            }
        }
        Activity::insert($processedData);

        Session::flash('message' , 'Activities added successfully');
        return back();
    }
    public function list(){
        $activity = Activity::with('getUnits')->get();
        return View( 'Activity.list' , compact('activity'));
    }
    public function changeStatus(Activity $activity){
        
        if( $activity['status'] == 0 ){
            $activity->update([ 'status' => 1 ]);
        }else{
            $activity->update([ 'status' => 0 ]);
        }
        Session::flash('message' , 'Activity updated successfully');
        return back();
    }
    
    public function editActivity(Activity $activity)
    {
        $activity_types_list = DB::table('activity_types')->get();
        $unitlist = Unit::all();
        return View( 'Activity.edit', compact('activity', 'activity_types_list', 'unitlist'));
    }
    public function updateActivity(Request $request, Activity $activity)
    {
        $requestData = [
                'activity_name' => $request->activity_name,
                'activity_description' => $request->activity_description,
                'unit' => $request->unit,
                'activity_type' => $request->activity_type
        ];
        $activity->update($requestData);
        Session::flash('message' , 'Activity updated successfully');
        return back();
    }

}