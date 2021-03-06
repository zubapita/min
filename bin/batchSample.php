#!/usr/local/bin/php
<?php
/**
 * 
 * バッチコマンド サンプル
 * 
 */
require_once __DIR__.'/../lib/autoload.php';
$debugConsole = new Console;
class CmdApp extends AppCtl
{
    public function __construct()
    {
        parent::__construct();
        CmdLibs::setDataBridge();
    }

    public function main()
    {
        CmdLibs::bannerBig('Batch Sample');

        $_ = $this;

        // Smartyの初期化
        $_->initView();

        // DBオブジェクトとテーブルオブジェクトの生成
        // DB名と同じTABLE名は使用できない
        $DB = $_->getDB('TEST');
        
        if ($TABLE = $_->getTable($DB, 'TEST_TABLE')) {
            
            // データの取得と表示
            $TABLE->reset();
            $list = $TABLE->find()->fetchAll();
            $_->view->assign('list', $list);
            $template = $_->getTemplate();

            echo $_->view->fetch("string:$template");
        }
        
    }
    
    /**
     * Smartyによるテンプレート
     * 変数は$ではなく#で修飾する（ヒアドキュメントの状態で評価されないように）
     */
    public function getTemplate()
    {
        $template = <<<EOS
{foreach #list as #key=>#row}
		{#row['name']}
{/foreach}
EOS;
        return str_replace('#', '$', $template);
    }
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
