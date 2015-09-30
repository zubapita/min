<?php
/**
 * テーブルの指定するためのモデルクラス
 *
 * このクラスは抽象クラスなので、実際の一覧の取得のためには
 * 各テーブルごとにこのクラスを継承したモデルクラスを作って使用する
 *
 *	@copyright	Tomoyuki Negishi and ZubaPitaTech, Inc.
 *	@author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 *	@license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 *	@package	Quicty4
 *	@version	0.1
 */
abstract class DataRecord extends AppCtl
{

	public $DB;	
	public $TABLE;
	
	public $APP_ROOT;
	
	const MODEL_TYPE = 'Record';
	

	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * TABELを検索して該当する行を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	abstract public function get($conditions);

	/**
	 * TABELに行を保存する
	 * 
	 * @param array $data 保存するデータ
	 * @return array 検索結果
	 */
	abstract public function set($data);
	
}

