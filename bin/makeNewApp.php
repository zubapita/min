#!/usr/local/bin/php
<?php
/**
 * 新規 minアプリを生成
 */
require_once __DIR__.'/../lib/autoload.php';
class CmdApp extends MakeNewAppLib
{
    public function __construct()
    {
        CmdLibs::setDataBridge();
        parent::__construct();
    }
}
$CmdApp = new CmdApp();
$CmdApp->main();
exit;
