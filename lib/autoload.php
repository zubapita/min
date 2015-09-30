<?php
/**
 * 必要なクラスを自動的にロードする
 *
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP for PHP
 * @version	0.1
 */

// Composerのautoloder呼び出し
$APP_ROOT = dirname(__DIR__);
require_once $APP_ROOT.'/vendor/autoload.php';

// フレームワークの設定ファイルを読み込み
require_once $APP_ROOT.'/etc/conf.php';

/**
 * Classファイルをロードする
 * 
 * @param string $class クラス名
 * @param boolean Classのロードに成功したときtrue、失敗のときfalse
 */
function autoLoadClass($class)
{
	$classFile = $class.'.php';
	$appRoot = dirname(__DIR__);
	
	// クラスファイルを探索するディレクトリ
	$classDirs = array(
		'lib',
		'model',
		'controller',
	);
	
	foreach ($classDirs as $dir) {
		if (searchClassInDir("$appRoot/$dir", $classFile)) {
			return true;
		}
	}
	return false;
}


/**
 * リカーシブにディレクトリを探索してClassファイルをロードする
 * 
 * @param string $dir 探索するディレクトリ名
 * @param string $classFile クラスファイル名
 * @param boolean Classのロードに成功したときtrue、失敗のときfalse
 */
function searchClassInDir($dir, $classFile)
{
	$files = glob("$dir/*");
	if (!empty($files)) {
		foreach ($files as $subFilePath) {

			if (is_dir($subFilePath)) {
				if (searchClassInDir($subFilePath, $classFile)) {
					return true;
				}
			} else {
				$fileName = basename($subFilePath);
				
				if ($fileName==$classFile) {
					require_once $subFilePath;
					return true;
				}
			}
		}
	}
	return false;
}

/**
 * オートローディング関数を登録
 */
spl_autoload_register( "autoLoadClass");


/**
 * バッチ実行時に$dataBridgeをセット
 */
if(!isset($dataBridge)) {
	CmdLibs::setDataBridge();
}

