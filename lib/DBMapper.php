<?php
/**
 * データベース操作クラス。Active Record的なメソッドを提供する。
 * 各カラムの値をインスタンスのプロパティとして読み書きできる。
 * このクラスは抽象クラスである。使用する際は各DBの各テーブルをクラス化し、このクラスを継承して使う。
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class DBMapper extends DBAccess implements IteratorAggregate {

	/**
	 * カラム名とデータ型の定義を格納
	 * データ型はDatatypeの派生クラスを使用
	 * _Integer, _Float, _String, _Datetime など
	 * 
	 * @see Datatype::get()
	 * @see Datatype::set()
	 */
	protected $COLUMNS = array('id'=>'Integer');
	
	/**
	 * 各カラムのインスタンスを格納
	 * 
	 */
	protected $INSTANCES = array();
	
	/**
	 * PDO実行結果である$PDOstatementを格納
	 * 
	 */
	protected $PDOstatement;
	
	/**
	 * プロパティ$VALUESに各カラムのインスタンスを生成して格納
	 * 
	 * @return void
	 */
	public function __construct(DBSpec $DBSpec)
	{
		parent::__construct($DBSpec);
		foreach ($this->COLUMNS as $name=>$datatype) {
			$this->INSTANCES[$name] = new $datatype;
		}
	}	
	
	/**
	 * カラムの値を返す
	 * 
	 * @param string $name 値
	 * @return void
	 */
	public function __get($name)
	{
		if (isset($this->COLUMNS[$name])) {
			return $this->INSTANCES[$name]->get();
		} elseif ($name=='TABLE') {
			return $this->TABLE;
		} else {
			return false;
		}
	}
	
	/**
	 * カラムに値を格納する
	 * 
	 * @param string $name 変数名
	 * @param mix $value 変数に代入する値
	 * @return void
	 */
	function __set($name, $value)
	{
		if(isset($this->COLUMNS[$name])) {
			return $this->INSTANCES[$name]->set($value);
		} else {
			return false;
		}
	}

	/**
	 * 各カラムの値のデータ型が正しいかチェックする
	 * 
	 * @return boolean 正しければtrue、間違っていればfalse
	 */
	public function isValid()
	{
		$result = true;
		foreach ($this->COLUMNS as $name=>$datatype) {
			if (!$this->INSTANCES[$name]->isValid()) {
				$result = false;
			}
		}	
		return $result;
	}

	
	/**
	 * 外部からPDOstatementとPDOのメソッドを呼び出しを可能にする
	 * 
	 * @param string $name メソッド名
	 * @param array $arguments メソッドに与えるパラメータ
	 * @return void
	 */
	public function __call($name, $arguments)
	{
		if (is_object($this->PDOstatement)) {
			if (method_exists($this->PDOstatement, $name)) {
				return call_user_func_array(array($this->PDOstatement, $name), $arguments);
			}
		}
		
		if(is_object($this->PDO)) {
			if(method_exists($this->PDO, $name)) {
				return call_user_func_array(array($this->PDO, $name), $arguments);
			}
		}
		
		$class_name = get_class($this);
		die("$name() is not method of class '$class_name'.\n");
		
	}
	
	/**
	 * 各カラムの値を連想配列にして返す
	 * 
	 * @return array 各カラムの値の連想配列。カラム名がキー。
	 */
	protected function makeSet()
	{
		$set = array();
		foreach ($this->COLUMNS as $name=>$datatype) {
			if (isset($this->INSTANCES[$name])) {
				
				// 主キーでかつデータがセットされていない場合は連想配列に含めない
				$value = $this->INSTANCES[$name]->get();
				if ($name==$this->ID_CLOUMN && empty($value)) {
					continue;
				}
				
				$set[$name] = $value;
			}
		}
		return $set;
	}
	
	/**
	 * 連想配列の値を各カラムに格納する
	 * 
	 * @param array $set 変数名をキー、変数の値を値とした配列
	 * @return void
	 */
	protected function setColumns($set)
	{
		foreach ($this->COLUMNS as $name=>$datatype) {
			$this->INSTANCES[$name]->set($set[$name]);
		}
	}

	/**
	 * 各カラムの値をクリアする
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function clearColumns()
	{
		foreach ($this->COLUMNS as $name=>$datatype) {
			$this->INSTANCES[$name]->set(NULL);
		}
		return $this;
	}	

	/**
	 * すべてのプロパティをクリアし、DBインスタンスの状態をリセットする
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function reset()
	{
		$this->clearColumns();
		$this->resetSelect();
		$this->resetOrderBy();
		$this->resetJoin();
		$this->resetGroupBy();
		return $this;
	}

	/**
	 * 各カラムの値をIteratorインターフェイスで取得可能にする
	 * （foreachで取得できるようにする）
	 * 
	 * @return ArrayObject 各カラムの値。カラム名がキー。
	 */
	public function getIterator()
	{
		$set = $this->makeSet();
		return new ArrayObject($set);
	}


	/**
	 * 各カラムの値を行としてINSERTする
	 * 
	 * @see DBAccess::insertSet($set)
	 * @return array INSERTした各カラムの値など
	 */
	public function insert()
	{
		$set = $this->makeSet();
		return $this->insertSet($set);
	}


	/**
	 * 各カラムの値で行をUPDATEする。
	 * その際、$UNIQUE_KEYを検索キーとする。
	 * 
	 * @see DBAccess::updateSet($set, $conditions)
	 * @param (array|string) $conditions 検索条件
	 * @return array UPDATEした各カラムの値など
	 */
	public function update($conditions)
	{
		$set = $this->makeSet();
		return $this->updateSet($set, $conditions);
	}


	/**
	 * 各カラムの値（行）をDBに保存する。
	 * 新しい行ならINSERT、既存の行ならUPDATEされる。
	 * 
	 * @see DBAccess::saveSet($set)
	 * @return array INSERT、UPDATEした各カラムの値など
	 */
	public function save()
	{
		$set = $this->makeSet();
		return $this->saveSet($set);
	}


	/**
	 *  SELECTするカラムの名称を格納する配列
	 */
	protected $SELECT_COLUMNS = array();

	/**
	 * SELECTする最大行数
	 * @see DBMapper::limit($limit)
	 */
	protected $LIMIT = NULL;

	/**
	 * SELECTを開始する行の位置
	 * @see DBMapper::offset($offset)
	 */
	protected $OFFSET = 0;
	
		
	/**
	 * SELECT関連プロパティのリセット
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function resetSelect()
	{
		$this->SELECT_COLUMNS = array();
		$this->LIMIT = NULL;
		$this->OFFSET = 0;
		return $this;
	}
	
	/**
	 * SELECTするカラムを指定
	 * 
	 * @param (array|string) $columns SELECTするカラム
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function select($columns=array())
	{
		if (is_array($columns)) {
			$this->SELECT_COLUMNS = $columns;
		} elseif (is_string($columns)) {
			$this->SELECT_COLUMNS = explode(',', $columns);
		} elseif ($columns=='*') {
			$this->SELECT_COLUMNS = array();
		}
						
		return $this;
	}
	
	/**
	 * selectやconditionsのカラムのarrayに指定したカラムが実在するかチェックする
	 * ただし、集約関数はチェックをスキップする
	 * 
	 * @param array $checkColumns 実在するかチェックするカラム
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function checkColumns($checkColumns=array())
	{
		if (!empty($checkColumns)) {
			$extTables = array();
			foreach ($checkColumns as $key=>$val) {
				if (is_array($val)) {
					$name = $key;
				} else {
					$name = $val;
				}
				
				// 集約関数はスキップ
				if (preg_match("/^.*\(.*?\)/", $name)) {
					continue;
				}
				
				// table.column の処理
				if (preg_match("/(.*?)\.(.*)/", $name, $m)) {
					if ($m[1]==$this->TABLE) {
						$tableObj = $this;
					} else {
						$tableName = $m[1];
						if (!isset($extTables[$tableName])) {
							$extTables[$tableName] = new $tableName($this->DB);
						}
						$tableObj = $extTables[$tableName];
					}
					$name = $m[2];
				} else {
					$tableObj = $this;
				}
				
				// as の処理
				if(preg_match("/(.*?) as (.*)/i", $name, $m)) {
					$name = $m[1];
				}
				
				// tableオブジェクトに登録されたカラム名と比較
				if (!isset($tableObj->COLUMNS[$name])) {
					Console::log("DBMapper::select: $name is not defined in table $tableObj->TABLE");
				} else {
					Console::log("$tableObj->TABLE.$name is exists!");
				}
			}
		}
		
		return $this;
	}

	/**
	 * SELECT時のLIMIT値を設定する
	 * 
	 * @param interger $limit SELECTする最大行数
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function limit($limit)
	{
		$this->LIMIT = $limit;
		return $this;
	}
	
	/**
	 * SELECT時のOFFSET値を設定する
	 * 
	 * @param integer $offset SELECTを開始する行の位置
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function offset($offset)
	{
		$this->OFFSET = $offset;
		return $this;
	}
	
	/**
	 * 実行したSQLを保存
	 */
	public $SQL;
	public $QUERY_VALUES;
	public $SQL_HISTORY = array();
		
	/**
	 * 検索の実行。結果データの取得はfecthやfetchAllを使う
	 * 
	 * @param (array|string) $conditions 検索条件（Where句）
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function find($conditions='')
	{
		// JOIN時のSELECTカラム名のambicios対策
		if (!empty($this->SELECT_COLUMNS)) {

			$tmpColumns = array();
			foreach ($this->SELECT_COLUMNS as $column) {
				// 指定されたカラム名が主テーブルとJOINされたテーブルにもあった場合は、主テーブル名を付加する
				if ($this->JOIN_NUM > 0) {
					foreach ($this->JOIN_TABLES_INSTANCE as $table_instance) {
						if (array_key_exists($column,$this->COLUMNS)===true &&
							array_key_exists($column,$table_instance->COLUMNS)===true) {
							$column = $this->TABLE.'.'.$column;
							break;
						}
					}
				}
				$tmpColumns[] = $column;
				
			}
			$this->SELECT_COLUMNS = $tmpColumns;
		}
		
				
		$COLS = implode(',', $this->SELECT_COLUMNS);
		if (empty($COLS)) $COLS = '*';
		
		list($CONDS, $VALUES) = $this->buildCondition($conditions);

		$sql = "SELECT $COLS FROM $this->TABLE";

		if ($this->JOIN_NUM > 0) {
			foreach($this->JOIN_TABLES as $key=>$table_name) {
				$join_on = implode(' AND ', $this->JOIN_CONDITIONS[$key]);
				$JOINS[$key] = $this->JOIN_DIRECTIONS[$key]." JOIN $table_name ON $join_on";
			}
			$sql .= " ".implode(' ',$JOINS);
		}

		if (!empty($conditions)) {
			$sql .= " WHERE $CONDS";
		}

		if ($this->GROUPBY_NUM > 0) {
			$GROUPBY = implode(',', $this->GROUPBY_COLUMNS);
			$sql .= " GROUP BY $GROUPBY";
		}

		if ($this->ORDERBY_NUM > 0) {
			$ORDERBY = implode(',', $this->ORDERBY_COLUMNS);
			$sql .= " ORDER BY $ORDERBY";
		}

		if (!empty($this->LIMIT)) {
			$sql .= " LIMIT $this->LIMIT OFFSET $this->OFFSET";
		}
		//echo $sql."\n";
		$this->SQL = $sql;
		$this->QUERY_VALUES = $VALUES;
		$this->SQL_HISTORY[date("Y/m/d h:i:s")] = $sql;

		$this->PDOstatement = $this->query($sql, $VALUES);

		return $this;
	}
	

	/**
	 * SELECTの結果の次の行を配列で返す
	 * また、各カラム値にセットする
	 * 
	 * @param integer $condition (PDO::FETCH_*)
	 * @return mix SELECT結果（array）。 取得できない場合はfalse  
	 */
	public function fetch($condition=PDO::FETCH_ASSOC)
	{
		if (is_object($this->PDOstatement)) {
			$set = $this->PDOstatement->fetch($condition);
			if (is_array($set)) {
				$this->setColumns($set);
			}
			return $set; // もし行データがなければfalseが返る
		} else {
			return false;
		}
	}


	/**
	 * SELECTの結果行をすべて返す
	 * 各カラム値は変化しない
	 * 
	 * @param integer $condition (PDO::FETCH_*)
	 * @return mix SELECT結果（array）。 取得できない場合はfalse 
	 */
	public function fetchAll($condition=PDO::FETCH_ASSOC)
	{
		if (is_object($this->PDOstatement)) {
			return $this->PDOstatement->fetchAll($condition);
		} else {
			return false;
		}
	}

	/**
	 * SELECTの結果行の中からカラムの値を返す
	 * 各カラム値は変化しない
	 * 
	 * @param integer $columnNumber 
	 * @return mix カラムの値。取得できない場合はfalse 
	 */
	public function fetchColumn($columnNumber=0)
	{
		if (is_object($this->PDOstatement)) {
			return $this->PDOstatement->fetchColumn($columnNumber);
		} else {
			return false;
		}
	}


	/**
	 * 結果セットを保持するPDOstatement オブジェクトを返す
	 * PDOstatementのメソッドを使って直に操作したいときに使う
	 * 
	 * @return mix PDOstatement オブジェクト。取得できない場合はfalse 
	 */
	public function getRowsObj()
	{
		if (is_object($this->PDOstatement)) {
			return $this->PDOstatement;
		} else {
			return false;
		}
	}


	/**
	 * ORDER BY するカラムの数。0のときはORDER BYなし
	 */
	protected $ORDERBY_NUM = 0; //
	
	/**
	 * ORDER BY するカラムを格納する配列
	 */
	protected $ORDERBY_COLUMNS = array();


	/**
	 * ORDER BY 関係の設定をリセットする
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function resetOrderBy()
	{
		$this->ORDERBY_NUM = 0;
		$this->ORDERBY_COLUMNS = array();
		return $this;
	}

	/**
	 * ORDER BY で並べ替えの基準となるカラムを指定する
	 * 
	 * @example $TABLE->orderBy('id DESC');
	 * @param (array|string) $order 並べ替えに使用するカラムを追加する
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function orderBy($order)
	{
		if (!empty($order)) {
			if (!is_array($order)) {
				$columns = explode(',', $order);
			} else {
				$columns = $order;
			}
			
			foreach ($columns as $column) {
				
				// 指定されたカラム名がJOINされたテーブルにもあった場合は、主テーブル名を付加する
				if($this->JOIN_NUM > 0) {
					foreach($this->JOIN_TABLES_INSTANCE as $table_instance) {
						if(array_key_exists($column,$table_instance->COLUMNS)===true) {
							$column = $this->TABLE.'.'.$column;
							break;
						}
					}
				}
				
				$this->ORDERBY_NUM++;
				$this->ORDERBY_COLUMNS[$this->ORDERBY_NUM] = $column;
			}
		}
		return $this;
	}



	/**
	 * JOIN するtableの数。0のときはJOINなし
	 */
	protected $JOIN_NUM = 0;

	/**
	 *  JOINするテーブルの名称を格納する配列
	 */
	protected $JOIN_TABLES = array();

	/**
	 * JOINするテーブルのインスタンスを格納する配列
	 */
	protected $JOIN_TABLES_INSTANCE = array();

	/**
	 * JOINする条件文を格納するカラム
	 */
	protected $JOIN_CONDITIONS = array();

	/**
	 * JOINする方法。 LEFT or INNER
	 */
	protected $JOIN_DIRECTIONS = array();
	
	/**
	 * JOIN関連のプロパティをリセット
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function resetJoin()
	{
		$this->JOIN_NUM = 0;
		$this->JOIN_TABLES = array();
		$this->JOIN_CONDITIONS = array();
		$this->JOIN_DIRECTIONS = array();
		return $this;
	}

	/**
	 * JOINするテーブルを指定
	 * 
	 * @param $table_instance object DBMapperオブジェクト
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function join($table_instance)
	{
		$table_name = $table_instance->TABLE;
		$this->JOIN_NUM++;
		$this->JOIN_TABLES[$this->JOIN_NUM] = $table_name;
		$this->JOIN_TABLES_INSTANCE[$this->JOIN_NUM] = $table_instance;
		$this->JOIN_DIRECTIONS[$this->JOIN_NUM] = 'LEFT';
		return $this;
	}

	/**
	 * INNER JOINするテーブルを指定
	 * 
	 * @param object $table_instance JOINするテーブルのインスタンス。DBMapper型。
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function innerJoin($table_instance)
	{
		$table_name = $table_instance->TABLE;
		$this->JOIN_NUM++;
		$this->JOIN_TABLES[$this->JOIN_NUM] = $table_name;
		$this->JOIN_TABLES_INSTANCE[$this->JOIN_NUM] = $table_instance;
		$this->JOIN_DIRECTIONS[$this->JOIN_NUM] = 'INNER';
		return $this;
	}

	
	/**
	 * JOINの条件を指定
	 * 
	 * @example $tableA->join($tableB)->on('tableA.id=tableB.id');
	 * @param string $columns JOINする際の条件
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function on($columns)
	{
		$table_1 = $this->TABLE;
		$table_2 = $this->JOIN_TABLES[$this->JOIN_NUM];
		
		// 配列で2つのカラムが指定されている場合
		if (is_array($columns)) {
			$column_1 = array_shift($columns);
			$column_2 = array_shift($columns);
			$COND = "$table_1.$column_1=$table_2.$column_2";
			
		// テキストで tableA.columnA=tableB.columnBと指定されている場合 
		} elseif (preg_match("/(.*?)=(.*)/", $columns, $matches)) {
			$column_1 = trim($matches[1]);
			if(strpos($column_1, '.'===false)) {
				$column_1 = "$table_1.$column_1";
			}
			$column_2 = trim($matches[2]);
			if(strpos($column_2, '.'===false)) {
				$column_2 = "$table_2.$column_2";
			}
			$COND = "$column_1=$column_2";
			
		// テキストでカラム名が1つだけ指定されている場合
		} else {
			$column_1 = $column_2 = $columns;
			$COND = "$table_1.$column_1=$table_2.$column_2";
		}
		
		$this->JOIN_CONDITIONS[$this->JOIN_NUM][] = $COND;
		
		return $this;
	}

	
	/**
	 * GROUP BYするcolumnの数。0のときはGROUP BYなし
	 */
	protected $GROUPBY_NUM = 0;


	/**
	 * GROUP BYするカラムの名称を格納する配列
	 */
	protected $GROUPBY_COLUMNS = array();
	
	
	/**
	 * GROUP BY関連の設定をリセット
	 * 
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function resetGroupBy()
	{
		$this->GROUPBY_NUM = 0;
		$this->GROUPBY_COLUMNS = array();
		return $this;
	}


	/**
	 * Group Byするカラムを指定
	 * 
	 * @param (array|string) $column GROUP BYするカラム名
	 * @return object 自身のクラスインスタンス（メソッドチェーン用）
	 */
	public function groupBy($columns)
	{
		if (!empty($columns)) {
			if (is_array($columns)) {
				foreach ($columns as $column) {
					$this->GROUPBY_NUM++;
					$this->GROUPBY_COLUMNS[$this->GROUPBY_NUM] = $column;
				}
			} else {
				$tArray = explode(',', $columns);
				foreach ($tArray as $column) {
					$this->GROUPBY_NUM++;
					$this->GROUPBY_COLUMNS[$this->GROUPBY_NUM] = $column;
				}
			}
		}
		return $this;
	}
		
	
}
