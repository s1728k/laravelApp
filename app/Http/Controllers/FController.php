<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FController extends Controller
{
    
    public function __construct()
    {
        
    }

    public function welcome()
    {
        return view('f.welcome');
    }
    
}
