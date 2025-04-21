# gourmet-app-test

## 環境構築
1. Dockerを起動する
2. プロジェクト直下で以下のコマンドを実行する

```
make init
```
## メール認証
mailtrapというツールを使用しています。<br>
以下のリンクから会員登録をしてください。<br>
https://mailtrap.io/

.envファイルのMAIL_MAILERからMAIL_ENCRYPTIONまでの項目をコピー＆ペーストしてください。<br>
MAIL_FROM_ADDRESSは任意のメールアドレスを入力してください。

## Stripeについて
現金支払いとカード支払いのオプションがありますが、カード支払いを選択すると、マイページの該当の予約に決済画面に遷移するボタンがあります。<br>
それをクリックするとStripe決済画面に遷移しカード情報を入力すると決済が成功する想定です。<br>

また、StripeのAPIキーは以下のように設定をお願いいたします。
```
STRIPE_PUBLIC_KEY="パブリックキー"
STRIPE_SECRET_KEY="シークレットキー"
```

以下のリンクは公式ドキュメントです。<br>
https://docs.stripe.com/payments/checkout?locale=ja-JP


## 使用技術（実行環境）
- ・Laravel 11.44.2
- ・MySQL 10.11.11 (データベース)
- ・Nginx 1.27.3(Web サーバー)
- ・PHP 8.2.28 (PHP 実行環境)
- ・Docker (開発環境のコンテナ管理)

## ER図

![表示](./test.drawio.svg)

## URL
- 開発環境: http://localhost
