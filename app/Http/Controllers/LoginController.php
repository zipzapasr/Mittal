<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;

class LoginController extends Controller
{
    public function data_entry_operator() {
        return View('Login.data_entry_operator');
    }

    public function project_manager() {
        return View('Login.project_manager');
    }

    public function authenticate_data_entry_operator(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        $aval = User::where(['email' => $request->email,'role'=> '4'])->first();
        if($aval == null) {
            Session::flash('message', 'Invalid Details');
            return back();
        }
        $pass = Hash::check($request->password, $aval->password);
        if($pass) {
            session(['employee' => $aval]);
            return redirect("/employee/home")->with('message', 'Logged in as Data Entry Operator'); 
        }
        else {
            return back()->with('message', 'Invalid Details');
        }
    }

    public function authenticate_project_manager(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        $aval = User::where(['email' => $request->email,'role'=> '3'])->first();
        if($aval == null) {
            Session::flash('message', 'Invalid Details');
            return back();
        }
        $pass = Hash::check($request->password, $aval->password);
        if($pass) {
            session(['employee' => $aval]);
            return redirect("/employee/home")->with('message', 'Logged in as Project Manager');
        }
        else {
            return back()->with('message', 'Invalid Details');
        }
    }

    public function changePassword(Request $request, User $user) {
        $role = $user->role;
        return view('EmployeeHome.changePassword', compact('user', 'request', 'role'));
    }

    public function authenticate_and_change_password(Request $request, User $user) {
        //dd($request, $user);
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        if($request->new_password != $request->confirm_password) {
            return back()->with('message', 'New Passwords do not match');
        }
        if(!Hash::check($request->old_password, $user->password)){
            return back()->with('message', 'Old Password is incorrect');
        }
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);
        return redirect()->route('employee.home')->with('message', 'Password Changed Successfully');
    }

    public function logout() {
        $role = session('employee')->role;
        session(['employee' => null]);
        if($role == '3') {
            return redirect()->route('login.project_manager');
        } else if($role == '4'){
            return redirect()->route('login.data_entry_operator');
        }
        return back();
    }
}
