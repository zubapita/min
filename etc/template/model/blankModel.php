<?php
/**
 * {$pageName} 操作model
 * 
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class {$className} extends AppCtl
{



	/**
	 * {$className} の初期化
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$_ = $this;

	}	

	/**
	 * 結果を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function get($conditions)
	{
		$_ = $this;
		
		$result = [];
		
		if ($_->dispatch_trace) {
			Console::log('{$className}::get');
		}

		return $result;
	}




}

