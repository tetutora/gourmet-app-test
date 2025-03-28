<?php

namespace App\Http\Controllers;

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
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('index');
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
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return back()->withErrors(['email' => '認証が失敗しました。']);
        }
        return redirect()->route('index');
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
