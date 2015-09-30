#!/bin/sh
# PHPUnit用テストテスト実行ファイルを使ってテストする
#
# usage: test.sh テストクラス名
# exmple: $ test.sh firstTestList
#

bin=$(cd $(dirname $0); pwd) # bin=この実行ファイルが置かれているディレクトリ
appRoot=${bin%/*} # app_root=binの親ディレクトリ
echo ""
# autoloaderの設定
lib="$appRoot/lib"
autoloader="$lib/autoload.php"
if [ -f "$autoloader" ]; then
	echo "autoloader is OK.\n"
else
	echo "autoloader is not found. =>$autoloader\n"
	exit
fi

# テスト実行ファイルの確認
if [ -f "$1.php" ]; then
	echo "test file $1.php is OK.\n"
else
	echo "test file $1.php is not found.\n"
	exit
fi

# テストの実行
phpunit --bootstrap="$autoloader" $1
echo "\n"

