<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
    public function showPaymentForm()
    {
        return view('payment.form');
    }

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $amount = $request->input('amount');

            Charge::create([
                'amount' => $amount,
                'currency' => 'jpy',
                'source' => $request->stripeToken,
                'description' => 'テスト決済',
            ]);

            return back()->with('success', '支払いが成功しました！');
        } catch (\Exception $e) {
            return back()->with('error', '支払いに失敗しました: ' . $e->getMessage());
        }
    }
}
