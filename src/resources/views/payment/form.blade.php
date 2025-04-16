@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>決済フォーム</h2>

    <form action="{{ route('payment.process') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="amount" value="1000">
        <div class="form-group">
            <label for="card-element">カード情報</label>
            <div id="card-element">
                <!-- Stripeのカード入力フィールドがここに表示される -->
            </div>
        </div>

        <button type="submit" class="btn btn-primary">支払う</button>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ env('STRIPE_KEY') }}');
    var elements = stripe.elements();
    
    var card = elements.create('card');
    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // エラーが発生した場合はメッセージを表示
                alert(result.error.message);
            } else {
                // トークンをフォームに追加して送信
                var tokenInput = document.createElement('input');
                tokenInput.setAttribute('type', 'hidden');
                tokenInput.setAttribute('name', 'stripeToken');
                tokenInput.setAttribute('value', result.token.id);
                form.appendChild(tokenInput);

                form.submit();
            }
        });
    });
</script>
@endsection
