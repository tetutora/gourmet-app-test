<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    /**
     * 管理者ダッシュボード
     */
    public function dashboard()
    {
        return view('administrator.dashboard');
    }
    /**
     * 店舗代表者作成画面
     */
    public function createRepresentative()
    {
        return view('administrator.create');
    }
}
