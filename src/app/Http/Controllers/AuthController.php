<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
    *会員登録画面表示
    */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
    *会員登録処理
    */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('thanks');
    }

    /**
    *ログイン画面表示
    */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
    *ログイン処理
    */
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['email' => '認証が失敗しました。']);
        }

        $user = Auth::user();

        switch ($user->role_id) {
            case Constants::ROLE_ADMIN:
                return redirect()->route('administrator.dashboard');
            case Constants::ROLE_REPRESENTATIVE:
                return redirect()->route('representative.dashboard');
            case Constants::ROLE_USER:
                return redirect()->route('index');
        }
        return back()->withErrors(['email' => '認証が失敗しました。']);
    }

    /**
    *ログアウト処理
    */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}