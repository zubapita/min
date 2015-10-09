<?php
/**
 * excel 操作model
 * 
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class ExcelModel extends AppCtl
{

	private $EXCEL;

	/**
	 * ExcelModel の初期化
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$_ = $this;
		
		$_->Excel = new ExcelWriter;

	}	

	/**
	 * 結果を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function get($filePath)
	{
		$_ = $this;
		
		$_->Excel->load($_->APP_ROOT."/$filePath");
		
		$periodColumn = 0;
		$salesVolumeColumn = 1;
		$profitColumn = 2;
		$performance = [];
		for ($row=6; $row<=16; $row++) {
			$period = $_->Excel->getCellValue($periodColumn, $row);
			$salesVolume = $_->Excel->getCellValue($salesVolumeColumn, $row);
			$profit = $_->Excel->getCellValue($profitColumn, $row);
			$performance[$period]['sales'] = $salesVolume;
			$performance[$period]['profit'] = $profit;
		}
		
		
		if ($_->dispatch_trace) {
			Console::log('ExcelModel::get');
			Console::log($performance);
		}

		return $performance;
	}




}

