# min ユーザー認証セット
## これはなに
minでユーザーログインを簡単に実装できるセットです。    
以下の機能を提供します。
* ユーザー名とパスワードによるアカウント作成とログイン
* OAuthを使用した、Twitter、facebook、Googleアカウントによるユーザー登録とログイン

## 依存するライブラリ

HybridAuthに依存します。composer.jsonに以下を記述してください。

{    
    "require": {    
        "hybridauth/hybridauth": "2.5.0",    
    }    
}    

## 使用方法
1. データベースを作成し、makeDbClassFile.phpを実行しておきます。    
ここではデータベース名＝mydbとします。
2. bin/install.php を実行。
> $ ./install.php -d mydb
3. model/_def/api/以下のTwitterApiKey.phpなどにAPIのキーを設定します。

