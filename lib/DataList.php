<?php
/**
 * テーブルの内容一覧を取得するためのモデルクラス
 *
 * このクラスは抽象クラスなので、実際の一覧の取得のためには
 * 各テーブルごとにこのクラスを継承したモデルクラスを作って使用する
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class DataList extends AppCtl
{

	public $DB;	
	public $TABLE;
	
	public $APP_ROOT;
	
	const MODEL_TYPE = 'List';

	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * テーブルを検索してデータ一覧を取得する
	 * 
	 * @param (array|string) $conditions テーブルの検索条件
	 * @param integer $currentPage 取得するページ
	 * @return array テーブルから取得したデータの配列
	 */
	public function get($conditions, $currentPage) {
		$_ = $this;
		
		// SELECT 条件の設定
		$_->TABLE->reset();
		$_->TABLE->select('*');
		$_->setLimit($currentPage, $this->maxItemsInPage);
		
		// SELECTの結果取得
		$results = $_->TABLE->find($conditions)->fetchAll();

		// ページャの生成
		$pagerParams['currentPage'] = $currentPage;
		$pagerParams['allItemsNum'] = $_->count($conditions);
		$pagerParams['maxItemsInPage'] = $_->maxItemsInPage;
		$_->pager = Pager::get($pagerParams);


		return $results;		
	}
	
	/**
	 * 条件にマッチするデータが何行あるかカウントする
	 * 
	 * @param (array|string) $conditions テーブルの検索条件
	 * @return integer マッチするデータの行数
	 */
	public function count($conditions) {
		$_ = $this;
		
		// SELECT 条件の設定
		$_->TABLE->reset();
		$_->TABLE->select(array('count(*)'));

		// SELECTの結果取得
		$results = $_->TABLE->find($conditions)->fetchColumn();


		return $results;		
	}


	/**
	 * LimitとOffsetの設定
	 * 
	 * @param integer $pageNum ページ位置
	 * @param integer $limit 最大取得行数
	 */
	protected function setLimit($pageNum, $limit) {
		$offset = ($pageNum - 1) * $limit;
		$this->TABLE->Limit($limit)->Offset($offset);
	}


	/**
	 * PagerCtlが生成した配列型のページャ
	 * @see PagerCtl::get()s
	 */
	protected $pager;
	
	/**
	 * 一回のgetで取得する最大行数
	 * @see DataList::get()
	 * @see DataList::getMaxItemsInPage()
	 * @see DataList::setMaxItemsInPage()
	 * @see PagerCtl::get()
	 */
	protected $maxItemsInPage = 10;
	
	/**
	 * ページャ配列を返す
	 */
	public function getPager() {
		return $this->pager;
	}

	/**
	 * 一回のgetで取得する最大行数（$maxItemsInPage）を返す
	 * 
	 * @return integer  一回のgetで取得する最大行数
	 */
	public function getMaxItemsInPage() {
		return $this->maxItemsInPage;
	}
	
	
	/**
	 * 一回のgetで取得する最大行数（$maxItemsInPage）を返す
	 * 
	 * @param integer $itemNum 一回のgetで取得する最大行数
	 * @return boolean 成功したらtrue、失敗したらfalse
	 */
	public function setMaxItemsInPage($itemNum) {
		if(is_int($itemNum)) {
			$this->maxItemsInPage = $itemNum;
			return true;
		} else {
			return false;
		}
	}
	
}

