<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\StoreRepresentativeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdministratorController extends Controller
{
    /**
     * 管理者ダッシュボード
     */
    public function dashboard()
    {
        $representatives = User::where('role_id', Constants::ROLE_REPRESENTATIVE)->latest()->get();

        return view('administrator.dashboard', compact('representatives'));
    }
    /**
     * 店舗代表者作成画面
     */
    public function createRepresentative()
    {
        return view('administrator.create');
    }

    public function storeRepresentative(StoreRepresentativeRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => Constants::ROLE_REPRESENTATIVE,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('administrator.dashboard')->with('success', '店舗代表者を作成しました');
    }

    /**
     * お知らせメール作成画面
     */
    public function notifyForm()
    {
        return view('administrator.notify');
    }

    /**
     * お知らせメール送信処理
     */
    public function sendNotification(SendNotificationRequest $request)
    {
        $users = User::where('role_id', Constants::ROLE_USER)->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotificationMail($request->message));
        }

        return redirect()->route('administrator.mail')->with('success', 'お知らせメールを送信しました');
    }
}
