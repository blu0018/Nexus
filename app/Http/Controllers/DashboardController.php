<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function login(Request $request){
        print_r($request->input());die('ddf');

    }

    public function signup(Request $request){
        return view ('signup');

    }
}
