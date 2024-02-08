<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {   
        return view('admin.dashboard');
        // $admin= Auth::guard('admin')->user();
        // echo 'hi'.$admin->email.' <a href="'.route('admin.logout').'">Logout</a>';
    }
    public function logOut()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
