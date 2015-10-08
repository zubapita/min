<?php
/**
 * AmazonSearch 操作model
 * 
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class AmazonSearch extends AppCtl
{

	private $params = [];
	private $secretKey = '';

	/**
	 * AmazonSearch の初期化
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$_ = $this;
		
		$AmazonPA = $_->getAPI('AmazonPA');
		$_->params = $AmazonPA->getParams();
		$_->secretKey = $_->params['secret_key'];

	}	

	/**
	 * 検索結果を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function get($conditions)
	{
		$_ = $this;
		
		$AmazonSearch = new AmazonSearchLib;

		$params = array_merge($_->params, $conditions);

		$result = $AmazonSearch->get($params, $_->secretKey);
		if ($_->dispatch_trace) {
			Console::log('AmazonschModel::get');
			Console::log($result);
		}

		return $result;
	}


	/**
	 * IDによる検索結果を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function getById($conditions)
	{
		$_ = $this;
		
		$AmazonSearch = new AmazonSearchLib;

		$params = array_merge($_->params, $conditions);

		$result = $AmazonSearch->getById($params, $_->secretKey);
		if ($_->dispatch_trace) {
			Console::log('AmazonschModel::get');
			Console::log($result);
		}

		return $result;
	}

	/**
	 * BrowseNodeをTopSellersを取得して結果を配列で返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function getTopSellersByNode($conditions)
	{
		$_ = $this;
		
		$AmazonSearch = new AmazonSearchLib;

		$params = array_merge($_->params, $conditions);

		$result = $AmazonSearch->getTopSellersByNode($params, $_->secretKey);
		if ($_->dispatch_trace) {
			Console::log('AmazonschModel::get');
			Console::log($result);
		}

		return $result;
	}


}

