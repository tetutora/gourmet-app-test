<?php

namespace App\Http\Controllers;

use App\Constants\RoleType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    /**
     * 管理者ダッシュボード
     */
    public function dashboard()
    {
        $representatives = User::where('role_id', 2)->latest()->get();

        return view('administrator.dashboard', compact('representatives'));
    }
    /**
     * 店舗代表者作成画面
     */
    public function createRepresentative()
    {
        return view('administrator.create');
    }

    public function storeRepresentative(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('administrator.dashboard')->with('success', '店舗代表者を作成しました');
    }
}
