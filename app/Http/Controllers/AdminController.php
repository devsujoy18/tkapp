<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Hash;
use Session;

class AdminController extends Controller
{
    public function index()
    {
        if( session()->has('admin_login')){
            return redirect('admin/dashboard');
        }else{
            return view('admin.login');
        }
        
    }

    public function create()
    {
        return view('admin.register');
    }
    public function store(Request $request)
    {
        //
         $request->validate([
            'name'         =>   'required',
            'email'        =>   'required|email|unique:admins',
            'password'     =>   'required|confirmed|min:6'
        ]);

        $data = $request->all();

        Admin::create([
            'name'  =>  $data['name'],
            'email' =>  $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $data['type']
        ]);

        return redirect('admin')->with('success', 'Registration is Completed, now you can login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' =>  'required',
            'password'  =>  'required'
        ]);

        $result = Admin::where('email', $request->email)->first();

        if($result){
            if(Hash::check($request->password, $result->password)){
                $request->session()->put('admin_login', true);
                $request->session()->put('logged_in_admin', $result);
                return redirect('admin/dashboard');
            }else{
                 return redirect('admin')->with('error', 'Password is not correct');
            }
        }else{
            return redirect('admin')->with('error', 'Login details are not valid');
        }
    }

    function dashboard()
    {
         return view('admin.dashboard');
    }
}
