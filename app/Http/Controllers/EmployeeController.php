<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Session;
use Mail;

class EmployeeController extends Controller
{
    public function create()
    {
        return View('Employee.create');
    }
    public function save(Request $request){
        $request->validate([
            "username" => "required",
            "email" => "required|unique:users",
            "mobile" => "required|min:10|max:10",
            "password" => "required|min:8",
            "role" => 'required'
        ]);

        $requestData = [
            'name' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            //'raw_password' => $request->password,
            'role' => $request->role,
            'status' => 1
        ];
        User::create($requestData);
        Session::flash('message', 'New User Created successfully'); 

        //Mail::to($request->email)->send(['name'=>$request->username, "password"=> $request->password]);

        $data = array('name'=>$request->username);
        $message = [];
        $mailTo = $request->email;
        $mailMessage = 'New Employee Created';
        $subject = 'New Employee Created';
        $mailFrom = "surya@ripungupta.com";
        $mailFromName = "Surya Constructions";


        $response = Mail::send('mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });
        //$response($message);

        return back();
    }
    public function list(){

        $user = User::all();
        // dd($user);
        return View( 'Employee.list' ,['users' => $user]);
    }
    public function changeStatus($employeeId){
        $user = User::where('id' , $employeeId)->get();
        if( $user->count() > 0 ){
            if( $user[0]['status'] == 0 ){
                User::where('id' , $employeeId)->update([ 'status' => 1 ]);
            }else{
                User::where('id' , $employeeId)->update([ 'status' => 0 ]);
            }
        }
        Session::flash('message' , 'User updated successfully');
        return back();
    }
    public function editEmployee($employeeId)
    {
        $user = User::where('id' , $employeeId)->first();
        return View( 'Employee.edit', compact('user'));
    }
    public function updateEmployee(Request $request)
    {
        $request->validate([
            "username" => "required",
            "email" => "required",
            "mobile" => "required|min:10|max:10",
            'role' => "required"

        ]);
        // dd($request->all());
        if( $request->password != null){
            $requestData = [
                'name' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                //'raw_password' => $request->password,
                'role' => $request->role
            ];
        }else{
            $requestData = [
                'name' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => $request->role
            ];
        }
        User::where(['id' => $request->employeeId ])->update($requestData);
        Session::flash('message' , 'User updated successfully');
        return back();
    }
}