# min
Minimam PHP Framework

## これは何？
PHP 5.5以上のためのフレームワークです。
既存の定番クラスライブラリをつなぎ合わせて、便利に使いこなすためのものです。
クラスライブラリのインストールにはComposerを使用します。
主に以下の3つのクラスライブラリに依存します。
* Smarty 3：テンプレートクラス
* validator/livr：汎用バリデータクラス
* php-console：Google Chromeのconsoleに出力できるPHPのデバッグ環境
* PDO：データベース抽象化クラス（PHP内蔵）

AjaxなWebアプリを簡単に作れるように、JQueryとPHPの通信をシンプルかつ確実にできるメソッドを提供します.    
データベースのCRUDもすべてAjaxです。    
Google ChromeのPHP-Consoleと組み合わせることで、デバッグが面倒なAjaxアプリ作りが楽になります。   

デザインにはBootstrap 3を採用しています。Bootstrap 3用のテンプレートも簡単に適用できます。

また独自の認証ライブラリを内蔵し、ログインフォームを安全・簡単に作れます。    


## 推奨環境

* Mac OS X or Linux
* Google Chrome（Web開発時）

Windowsでの利用は検証されていません。

## インストール

ZIPをダウンロードしもしくは、リポリトジをcloneして、任意のディレクトリに展開するだけです。

そのほかに

* PHP 5.5+
* Composer

を利用可能にしておいてください。

## 使用方法

bin以下にアプリの作成、利用のためのコマンド群があります。    
パラメータを付けないで実行すると、使い方の説明が表示されます。

>$ cd bin    
>$ ./makeNewApp.php
>
>make New Min Application.
>
>usage: ./makeNewApp.php
> -a [app name]
> -r [root dir name(option)] ex)/Users/user/workspace
>


### 新しいアプリを作成する
> $ ./makeNewApp.php -a testApp -r /Users/mydir/workspace
>
>make New Min Application.
>
>make application : testApp.
>parentPath=/Users/mydir/workspace
>
>
>Please run 'composer install' at new app root.
>

「/Users/mydir/workspace/testApp」が作成され、その下に必要なファイル一式がコピーされます。
アプリのディレクトリに移動して、Composerで必要なライブラリをインストールします。

>$ cd /Users/mydir/workspace/testApp    
>$ composer install

インストールするライブラリは、composer.jsonを編集して調整します。
Minアプリの動作に最低限必要なのは以下の3行です。
>"smarty/smarty": "v3.1.21",    
>"validator/livr": "dev-master",    
>"php-console/php-console": "3.*",

### データベースのモデルを作成する

MinではデフォルトではPDOをオーバーライドした独自のクラスでDBを利用します。
（もちろん自分の好みのDBクラスをインストールして使っても構いません）
この独自のDBクラスを利用するためには、
* DBのクラス
* 各テーブルのクラス
* 各テーブルを操作するモデルクラス
が必要で、それぞれコマンドで自動で生成できます。

#### データベースのクラスを作成する

MySQLのデータベース「test」のクラスを作成し、
* ユーザー名：testuser
* パスワード：gh67L*K0
の場合、

>$ ./makeDbClassFile.php -d test -s mysql -u testuser -p gh67L*K0

クラスファイルが
* model/_def/db/test.php
 に作成されます。

#### テーブルのクラスを作成する

* データベース「test」のすべてのテーブルのクラスを作成する。

> ./makeTableClassFiles.php -d test

クラスファイルが
* model/_def/db/test/
以下に作成されます。


* データベース「test」のテーブル「users」のクラスを作成する。

> ./makeTableClassFiles.php -d test -t users

クラスファイルが
* model/_def/db/test/users.php
に作成されます。

#### テーブルのモデルを作成する

* データベース「test」のテーブル「users」を操作するモデルクラスを作成する。

>$ ./makeModelClassFiles.php -d test -t users


クラスファイルが 
* model/test/UsersList.php
* model/test/UsersRecord.php
に作成されます。

model/test/UsersList.php は、usersテーブルから一覧データを取得したり、検索結果を取得するためのクラスです。

使用例：
>$UsersList = new UsersList();
>$list = $UsersList->get();
>var_dump($list);

model/test/UsersRecord.php は、usersテーブルにデータを挿入、更新、削除するためのクラスです。

使用例：
>$data['id'] = 1;
>$data['name'] = "Tom";
>$data['birthday'] = "2001-10-15";
>
>$UsersRecord = new UsersRecord();
>$UsersRecord->set($data);
>
>$data = $UsersRecord->get("id=1");
>var_dump($data);
>$data['name'] = "Tom Cat";
>$UsersRecord->set($data);

クラスファイル model/_def/db/test/users.php にキーとなるカラムの定義があり、
デフォルトではidカラムがキーとなっています。idを変えずに他のカラムの値を変更してset()を実行すれば
行がupdateされます。idがテーブルに存在しない値なら行がinsertされます。

#### バリデータを調整する

モデルを使ったデータの挿入がうまく行かない場合は、レコードモデル（model/test/UsersRecord.phpなど）のバリデータの設定を確認してください。
デフォルトでは、すべてのカラムに['required']（必須）が設定されています。

バリデータの記述ルールは以下を参照してください。
https://github.com/koorchik/LIVR


### Webのビューとコントローラーを作成する

**前提：このWebアプリのURLをhttp://test.mysite.jp/だとします。**

なお、Webアプリとして動かすためには、etc/local_vh.conf を参考にVirtualHostを設定してください。    
基本は、htdocsがDocumentRootになるようにして、etc/rewrite.conf をincludeすれば動くはずです。    
また、var/compiledに apacheが書き込めるようにしてください。


**基本**

ビューとコントローラーを作成するには、 makeNewCtlAndView.php に -m でモデル名を、-p でページ名を指定します。


**テーブル内のデータ一覧表示や検索を目的としたビューとコントローラーを作成する**

>$ ./makeCtlAndView.php -m UsersList -p users

コントローラーのクラスファイルが
* controller/users/usersCtl.php
に作成されます。

ビューのテンプレートが
* view/users/index.html
に作成されます。

テンプレートはSmartyのルールで記述されています。    
またテーブル表示のパーツが view/users/includes/ 内にあります。

このページには
* http://test.mysite.jp/users/
でアクセスできます。


**テーブルへのデータ挿入や更新を目的としたビューとコントローラーを作成する**

>$ ./makeCtlAndView.php -m UsersRecord -p users/record

コントローラーのクラスファイルが
* controller/users/record/usersRecordCtl.php
に作成されます。

ビューのテンプレートが
* view/users/record/index.html
* view/users/record/add.html
* view/users/record/edit.html
に作成されます。

テンプレートはSmartyのルールで記述されています。    
またフォーム表示のパーツが view/users/record/includes/ 内にあります。

これらのページには
* http://test.mysite.jp/users/record/
* http://test.mysite.jp/users/record/add.html
* http://test.mysite.jp/users/record/edit.html
でアクセスできます。

**テーブルを使用しないビューとコントローラーを作成する**

>$ ./makeCtlAndView.php -p about

コントローラーのクラスファイルが
* controller/about/aboutCtl.php
に作成されます。

ビューのテンプレートが
* view/about/index.html
に作成されます。

テンプレートはSmartyのルールで記述されています。

このページには
* http://test.mysite.jp/about/
でアクセスできます。




以下、更新予定

### Ajaxによるデータベース操作

### ログインフォームの作り方

### 画像のAjaxアップロード

### Google Maps連動

### Twitter連動

## ディレクトリ構造

* bin    
	アプリの作成使用する各種バッチコマンドの置き場所です。また自分でバッチコマンドを作成するための雛形もあります。
* contoroler    
	Webアプリのコントローラーの置き場所です。コントローラーはエンドポイントであるindex.phpから起動されます。
* etc    
	各種設定ファイルの置き場所です。
* htdocs    
	ドキュメントルートです。画像ファイルやfavicon、robots.txtなど静的ファイルの置き場所です。    
	htdocs/index.php がWebアプリの起点（エンドポイント）となり、URLに応じて各コントローラーを起動します。    
	画像ファイルはhtdocs/img/* や htdocs/*/img/* の下に置きます。このルールはetc/rewrite.confで設定できます。
* lib    
	minの動作に必要なクラスライブラリが置かれています。
* model    
	データベースを操作するモデルクラスの置き場所です。データベース以外のapi操作やExcel操作もモデル化してここに置きます。
	モデル内のデータベースの操作は独自のデータベース操作クラスにより、SQLを書かずにシンプルに操作を記述できます。
* test    
	テストファイルの置き場所です。
* var    
	一時ファイルの置き場所です。    
	var/compiledにビューのテンプレートがコンパイルされたphpが置かれます。var/compiledはapacheから書き込み可能に設定しておく必要があります。
* vendor    
	composerによってインストールされる各種クラスライブラリの置き場所です。
* view    
	HTMLファイルの置き場所です。    
	view/index.html がトップページのHTMLです。    
	HTMLはSmartyのテンプレートファイルになっています。Smartyはif〜else文による条件分岐やforeachなどのループ、変数の計算や代入などをサポートした高機能なテンプレートクラスです。    
	minでは、表示部分のプログラミングはSmartyとJQueryでほとんど行います。


##  データベース操作

minは以下の特徴を持つ、独自のデータベース操作クラスを内蔵しています。
* PDOをベースにしているため、高速。
* テーブルの操作を記述すると、内部ではプレースホルダを使用したSQLを自動生成してPDOを使ってデータベースを操作する。SQLインジェクションに強くて安全。
* テーブル上のユニークキーを決めておけば、挿入と更新を自動判断。
* メソッドチェインによるスマートな記述法。

### テーブル操作の基本
前提：データベース「testDB」に以下の構造を持つテーブル「books」があるとします。    
このbooksテーブルをインスタンス化して、テーブルの操作を行います。    

>CREATE TABLE `books` (    
>  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,    
>  `title` varchar(50) DEFAULT NULL,    
>  `author` varchar(50) DEFAULT NULL,    
>  `isbn` varchar(14) DEFAULT NULL,    
>  `price` int(11) DEFAULT NULL,    
>  `releaseDate` datetime DEFAULT NULL,    
>  PRIMARY KEY (`id`)    
>) ENGINE=InnoDB DEFAULT CHARSET=utf8;    

#### テーブル・インスタンスの取得
>$_ = $this;    
>$_->DB = $_->getDB('testDB');    
>$_->Books = $_->getTable($_->DB, 'books');    

$_->Booksにbooksテーブルのインスタンスが格納されます。

#### テーブルへの行挿入
>$_ = $this;    
>$bookData = [    
>	'title' = 'ハムレット',    
>	'author = 'シェークスピア',    
>	'isbn' = '978-4102020036',    
>	'price' = 497    ,
>	'releaseDate' = '1967/9/27'    ,
>	];    
>$result = $_->Books->saveSet($bookData);    

#### テーブルからの行取得と更新

titleが'ハムレット'の行を取得します。

>$_ = $this;    
>$columns = [    
>	'id',    
>	'title',    
>	'author',    
>	'price',    
>	];    
>$condition = ['title'=>'ハムレット'];    
>$bookData = $_->Books->select($columns)->find($condition)->fetch();    

$bookDataに結果行が格納されます。

>$bookData['author'] = 'ウイリアム・シェークスピア';    
>$result = $_->Books->saveSet($bookData);    

取得した行のauthorカラムが書き替えられます。    
デフォルトではidカラムがユニークラカムとなっているため、行を更新するにはidカラムを取得しておく必要があります。    
ユニークカラムの設定は、model/_def/(DB名)/(テーブル名).php内に記述されているので、必要に応じて書き替えられます。    

#### テーブルからすべての行を取得

>$_ = $this;    
>$columns = [    
>	'id',    
>	'title',    
>	'author',    
>	'price',    
>	];    
>$condition = [];    
>$bookDataRows = $_->Books->select($columns)->find($condition)->fetchAll();    



1行ずつ取得したいときは、getRows()を利用します。

>$rows = $_->Books->select($columns)->find($condition)->getRows();    
>while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {    
>	var_dump($row);
>}

getRows()はPDOstatementを返すので、以後はPDOのメソッドによる操作が可能になります。

#### limit、offset、order by
>$_ = $this;    
>$columns = [    
>	'id',    
>	'title',    
>	'author',    
>	'price',    
>	];    
>$_->Books->select($columns);    
>$_->Books->offset(0)->limit(10)->orderBy('price DESC');    
>$condition = [];    
>$_->Books->find($condition)->fetchAll();    

ORDER BYで複数のカラムを指定するときは、以下のように指定します。

1.文字列
>$order = 'price DESC, title ASC';    
>$_->Books->orderBy($order);    

2.配列
>$order = ['price DESC', 'title ASC'];
>$_->Books->orderBy($order);    


#### group by

>$_ = $this;    
>$columns = [    
>	'id',    
>	'title',    
>	'author',    
>	'price',    
>	];    
>$_->Books->select($columns);    
>$_->Books->->groupBy('author');    
>$condition = [];    
>$_->Books->find($condition)->fetchAll();    

GROUP BYで複数のカラムを指定するときは、以下のように指定します。

1.文字列
>$group = 'author, price';    
>$_->Books->groupBy($group);    

2.配列
>$group = ['author', 'price'];    
>$_->Books->groupBy($group);    


#### join

>$_->Sales = $_->getTable($_->DB, 'sales');    
>$_->Books->join($_->Sales)->on('books.isbn=sales.isbn');    

INNER JOINの場合は

>$_->Books->innerJoin($_->Sales)->on('books.isbn=sales.isbn');



