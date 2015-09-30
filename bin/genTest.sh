#!/bin/sh
# PHPUnit用テストファイルを生成し、testディレクトリに移動する
#
# usage: genTest.sh テストクラス名 [移動先サブディレクトリ]
# exmple: $ genTest.sh firstTestList model/firstTest
# → テストファイル firstTestListTest.phpを生成し、test/model/firstTest 以下に移動する。
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

# テスト対象ファイルの確認
if [ -f "$1.php" ]; then
	echo "test class file $1.php is OK.\n"
else
	echo "test class file $1.php is not found.\n"
	exit
fi

# テスト実行ファイルの生成
phpunit-skelgen generate-test --bootstrap="$autoloader" --verbose $1
echo "\n"

# 移動先ディレクトリ
testDir="$appRoot/test"
if [ -n "$2" ]; then
	testDir="$testDir/$2/"
else
	testDir="$testDir/"
fi
echo "testDir=$testDir\n";
if [ -d "$testDir" ]; then
	echo "testDir is OK.\n"
else
	echo "testDir $testDir is not found."
	echo "mkdir -p $testDir\n"
	mkdir -p $testDir
fi

# テスト実行ファイル
testFile="$1Test.php"
echo "testFile=$testFile\n"
if [ -f "$testFile" ]; then
	echo "testFile is OK.\n"
else
	echo "testFile $testFile is not found.\n"
	exit
fi

# テスト実行ファイルと移動先ディレクトリを移動
if [ -f "$testDir/$testFile" ]; then
	echo "Test File under test dir is already exists. don't move to test dir.\n\n"
else
	echo "mv $testFile $testDir"
	mv $testFile $testDir
	echo "done.\n"
fi
