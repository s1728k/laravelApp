<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        Excel::load('file.xls', function($reader) {
            
        });
    }

    public function passportClients()
    {
        return view('passport.clients');
    }

    public function passportAuthorizeClients($id)
    {
        return view('vendor.passport.authorize');
    }
}
