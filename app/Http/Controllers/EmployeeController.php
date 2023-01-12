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
        $roles = app(User::class)->roles;
        // dd($roles);

        return View('Employee.create', compact('roles'));
    }

    public function save(Request $request){
        $request->validate([
            "username" => "required",
            "email" => "required|unique:users",
            "mobile" => "required|min:10|max:10|unique:users",
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


        $response = Mail::send('mails.mail', $data, function($message) use ($mailTo, $mailMessage, $subject, $mailFrom, $mailFromName) {
                $message->to($mailTo, $mailMessage)->subject($subject);
                $message->from($mailFrom,$mailFromName);
        });
        //$response($message);

        return back();
    }
    public function list(){

        $users = User::all();
        return View( 'Employee.list' , compact('users'));
    }
    public function changeStatus(User $user){
        if( $user['status'] == 0 ){
            $user->update([ 'status' => 1 ]);
        }else{
            $user->update([ 'status' => 0 ]);
        }
        Session::flash('message' , 'User updated successfully');
        return back();
    }
    public function editEmployee(User $user)
    {
        return View( 'Employee.edit', compact('user'));
    }
    public function updateEmployee(Request $request)
    {
        // dd($request->all());
        $user = User::findOrFail($request->employeeId);
        $request->validate([
            "username" => "required",
            "email" => "required|unique:users,email,{$request->employeeId}",
            "mobile" => "required|min:10|max:10|unique:users,mobile,{$request->employeeId}",
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
        } else{
            $requestData = [
                'name' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'role' => $request->role
            ];
        }
        
        $user->update($requestData);
        Session::flash('message' , 'User updated successfully');
        return back();
    }
}