<?php
/**
 * フロントコントローラ 
 * 
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
session_start();
require_once __DIR__.'/../lib/autoload.php';
$debugConsole = new Console;
Dispatcher::setTrace(true);
Dispatcher::dispatch();

