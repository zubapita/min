<?php
/**
 * コマンドラインプログラム用ユーティリティ・ライブラリを提供するクラス
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class CmdLibs
{
    /**
     * 現在のMinアプリの絶対ディレクトリパス
     * 
     * @see Dispatcher::dispatch()
     * @see CmdLibs::setDataBridge()
     */
    public static $APP_ROOT;

    /**
     * アプリのrootを返す。
     * app_root/lib の中にCmdLibs.phpがあるときは、$depth=1
     * 
     * @param $depth int
     * @return absolute file path of application root
     */
    public static function getAppRoot($depth)
    {
        $dir_path = __DIR__;
        $p = explode('/', $dir_path);
        for ($i=1; $i<=$depth; $i++) {
            $dmy = array_pop($p);
        }
        $APP_ROOT = implode('/', $p);
        return $APP_ROOT;
    }

    /**
     * 指定したディレクトリの親ディレクトリを取得
     * 
     * @param string $dir ディレクトリパス
     * @return absolute file path of parent of application root
     */
    public static function getParentPath($dir)
    {
        $pathArray = explode('/', $dir);
        $dmy = array_pop($pathArray);
        $parentPath = implode('/', $pathArray);
        return $parentPath;
    }


    /**
     * クラス間のデータ交換に使うDataBridgeクラスをインスタンス化し、
     * グローバル変数 dataBridgeに保存する。
     * またアプリのルートディレクトリなどを保存する
     * CmdLib.phpがあるディレクトリがアプリのルートの直下なら$depth=1
     * 
     * @param integer $depth=1 
     */
    public static function setDataBridge($depth=1)
    {
        global $dataBridge;
        $dataBridge = new DataBridge;
        $APP_ROOT = self::getAppRoot($depth);
        $dataBridge->APP_ROOT = $APP_ROOT;
        $dataBridge->dispatch_path = '/';
        $dataBridge->dispatch_class = 'CmdApp';
        $dataBridge->dispatch_action = 'main';
        
        self::$APP_ROOT = $APP_ROOT;
    }

    /**
     * コマンドラインの指定のパラメータの値を返す
     * 
     * @example $a = CmdLib::getParam('-a');
     * @param string $switch スイッチ名
     * @return (string|boolean) スイッチが指定されていればその値。なければfalse
     */
    public static function getParam($switch)
    {
        $argv = $_SERVER['argv'];
        $value = false;
        foreach ($argv as $key=>$param) {
            if ($param==$switch) {
                if (isset($argv[$key+1])) {
                    $value = $argv[$key+1];
                    break;
                }
            }
        }
        return $value;
    }

    /**
     * コマンドラインの指定のパラメータが存在するかを返す
     * 
     * @example if ($a = CmdLib::getParam('-a')) {...}
     * @param string $switch スイッチ名
     * @return boolean スイッチが指定されていればtrue。なければfalse
     */
    public static function checkParam($switch)
    {
        $argv = $_SERVER['argv'];
        $value = false;
        foreach ($argv as $key=>$param) {
            if ($param==$switch) {
                $value = true;
                break;
            }
        }
        return $value;
    }

    /**
     * 実行中のスクリプト名を返す
     * 
     * @return string 現在のスクリプトのファイル名
     */
    public static function scriptName()
    {
        $argv = $_SERVER['argv'];
        return $argv[0];
    }


    /**
     * http POST でURLのデータを送り結果を取得する
     * 
     * @param string $url データ送り先エンドポイント
     * @param mix $data 送信データ
     * @return mix 送信先サーバが返す結果
     */
    public static function postData($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_COOKIEJAR, 'cookie');
        curl_setopt($curl,CURLOPT_COOKIEFILE, 'tmp');
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE);

        $result = curl_exec($curl);
        
        return $result;
    }


    /**
     * メッセージバナー（大型）を標準エラー出力に表示
     * 
     * @param string $message 表示するメッセージ
     * @param boolean $STDERR trueなら標準エラーに出力
     * @return void
     */
    public static function bannerBig($message, $STDERR=true)
    {
        $banner = <<<EOS

====*====*====*====*====*====*====*====*====*====*====*====*
　　　$message
====*====*====*====*====*====*====*====*====*====*====*====*


EOS;
        if ($STDERR) {
            fputs(STDERR, $banner);
        } else {
            echo $banner;
        }
    }

    /**
     * メッセージバナー（中型）を標準エラー出力に表示
     * 
     * @param string $message 表示するメッセージ
     * @param boolean $STDERR trueなら標準エラーに出力
     * @return void
     */
    public static function bannerMid($message, $STDERR=true)
    {
        $banner = <<<EOS

***************************************
　　　$message
***************************************


EOS;
        if ($STDERR) {
            fputs(STDERR, $banner);
        } else {
            echo $banner;
        }
    }

    /**
     * メッセージバナー（小型）を標準エラー出力に表示
     * 
     * @param string $message 表示するメッセージ
     * @param boolean $STDERR trueなら標準エラーに出力
     * @return void
     */
    public static function bannerSmall($message, $STDERR=true)
    {
        $banner = <<<EOS

--- $message ---
　　　

EOS;
        if ($STDERR) {
            fputs(STDERR, $banner);
        } else {
            echo $banner;
        }
    }
}
