<?php

namespace App\Http\Controllers;

class AdministratorController extends Controller
{
    public function dashboard()
    {
        return view('administrator.dashboard');
    }
}
