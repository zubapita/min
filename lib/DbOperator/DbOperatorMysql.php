<?php
//namespace Zubapita\Quicty;
/**
 * DbOperator Concrete Strategy クラス
 * MySQL版
 * データベースに接続するための基本機能を提供する。
 */
class DbOperatorMysql extends DbOperator
{
	/**
	 * DB内のテーブル名一覧を返す
	 */
	public function getTables() {
		$_ = $this;
		$tables = array();
		$sql = "show tables";
		$stmt = $_->dbmapper->query($sql);
		foreach($stmt as $row) {
			$tables[] = $row[0]; 
		}
		return $tables;
	}

	/**
	 * テーブル内のカラムの属性を返す
	 * 
	 * @param string $table
	 * @return array
	 */
	public function getColumns($table) {
		$_ = $this;
		$columns = array();
		$sql = "show columns from $table";
		$stmt = $_->dbmapper->query($sql);
		foreach($stmt as $row) {
			$column['name'] = $row['Field'];
			$column['type'] = $row['Type'];
			$column['key'] = $row['Key'];
			$column['default'] = $row['Default'];
			$column['extra'] = $row['Extra'];
			$columns[] = $column; 
		}
		return $columns;
	}

	/**
	 * DBクラスのテンプレートを返す
	 */
	public function getDbClassTemplate() {
		$template = <<<EOS
<?php
class {#dbname} extends DBSpec {
	public static #SYSTEM = '{#systemName}';
	public static #DSN = 'mysql:dbname={#dbname};host=127.0.0.1';
	public static #USER = '{#user}';
	public static #PASSWORD = '{#password}';
}
EOS;
		$template = str_replace('#', '$' , $template);
		return $template;
	}

	/**
	 * MySQLの型とDBMapper/Datatypeの型の変換表を返す
	 * @return $columnTypes array
	 */
	public function getColumnTypes() {
		$columnTypes = array(
			'int' => '_Integer',
			'bigint' => '_Integer',
			'mediumint' => '_Integer',
			'smallint' => '_Integer',
			'tinyint' => '_Integer',
			'bool' => '_Integer',
			'boolean' => '_Integer',
			'text' => '_String',
			'longtext' => '_String',
			'mediumtext' => '_String',
			'varchar' => '_String',
			'char' => '_String',
			'datetime' => '_String',
			'year' => '_String',
			'date' => '_String',
			'time' => '_String',
			'timestamp' => '_String',
			'float' => '_Float',
			'double' => '_Float',
			'real' => '_Float',
			'decimal' => '_String',
			'dec' => '_String',
			'numeric' => '_String',
		);
		return $columnTypes;
	}


}
