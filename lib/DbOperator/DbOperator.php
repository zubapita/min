<?php
/**
 * DbOperator Strategy クラス
 * データベースに接続するための基本機能を提供する。
 * データベース毎の機能対応にConcrete Strategyクラスを使用する。
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class DbOperator
{
	public $dbmapper;

	/**
	 *  DB操作用のDBMapperを生成
	 * @param object $DB DBSpec型のクラスインスタンス
	 * @return void
	 */
	public function setDb($DB)
	{
		$this->dbmapper = new PseudoTable($DB);
	}

	/**
	 * DB内のテーブル名一覧を返す
	 * @return array
	 */
	abstract public function getTables();

	/**
	 * ターブル内のカラムの属性を返す
	 * @param $table string
	 * @return array
	 */
	abstract public function getColumns($table);

	/**
	 * DBクラスのテンプレートを返す
	 */
	abstract public function getDbClassTemplate();
	
	/**
	 * テーブルクラスのテンプレートを返す
	 * @return string
	 */
	public function getTableClassTemplate()
	{
		$template = <<<EOS
<?php
class {#table} extends DBMapper {

	public #TABLE = '{#table}';

	protected #ID_CLOUMN = '{#id_column}';
	protected #UNIQUE_KEY = '{#id_column}';

	protected #COLUMNS = array(
	{foreach #columns as #key=>#row}
		"{#row['name']}"=>"{#row['type']}",
	{/foreach}
	);	
}
EOS;

		$template = str_replace('#', '$' , $template);
		return $template;
	}

	/**
	 * DBの型とDBMapper/Datatypeの型の変換表を返す
	 * @return $columnTypes array
	 */
	abstract public function getColumnTypes();

}
