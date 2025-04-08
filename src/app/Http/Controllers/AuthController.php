<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = User::createAndLogin($request);

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
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            switch ($user->role_id) {
                case 1:
                    return redirect()->route('administrator.dashboard');
                case 2:
                    return redirect()->route('representative.dashboard');
                case 3:
                    return redirect()->route('mypage');
            }
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
