<?php
/**
 * データベース操作クラス。データベースへの接続と基本的なテーブル単位のCRUDを提供する。
 * データベースの接続情報として、DBSpecクラスのインスタンスを使用する。
 * このクラスは抽象クラスなので、継承した別のクラスを作ってインスタンス化する必要がある。
 * 実際にはこのクラスを継承したDBMapperクラスを使うのが現実的
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class DBAccess
{
	
	/**
	 * PDOオブジェクト（≠DB接続）を保存する
	 * @see DBAccess::getPDO()
	 */
	private $PDO;

	/**
	 *  データベース接続文字列
	 * @see abstract class DBSpec
	 */
	protected $DSN;

	/**
	 * データベース接続ユーザー（文字列）
	 */
	protected $USER;

	/**
	 * データベース接続パスワード（文字列）
	 */
	protected $PASSWORD;
	
	/**
	 * テーブルの名前（文字列）
	 */
	public $TABLE;

	/**
	 * 行のid値。通常はAuto Incrementで与えられるユニークなID
	 */
	protected $ID_CLOUMN = 'id';

	/**
	 * 行をユニークであると判断するするためのカラム
	 */
	protected $UNIQUE_KEY = 'id'; // 複数の場合は配列で指定 array('id','datetime',..)
	
	/**
	 * インスタンス生成時にデータベース接続文字を設定
	 * 
	 * @param DBSpec $DBSpec データベースへの接続文字列を設定したインスタンス
	 */
	function __construct(DBSpec $DBSpec)
	{
		$this->DSN = $DBSpec::$DSN;
		$this->USER = $DBSpec::$USER;
		$this->PASSWORD = $DBSpec::$PASSWORD;
	}	
	
	/**
	 * データベースを操作するためのPDOオブジェクトを生成して返す
	 *
	 * @return PDO オブジェクト
	 */
	protected function getPDO()
	{
		if (empty($this->PDO)) {
			try {				
			    $this->PDO = new PDO($this->DSN, $this->USER, $this->PASSWORD);
			} catch (PDOException $e) {
				Console::log("Connection failed: ".$e->getMessage() );
			    die();
			}
		}
		return $this->PDO;
	}


	/**
	 * 連想配列内のデータからSQLのInsert文を生成してテーブルにインサートする。
	 *
	 * @param array $set array('カラム名'=>値, ..)
	 * @return mix 成功：integer Insertした行のID / 失敗：boolean false
	 */
	public function insertSet(array $set)
	{
		$PDO = $this->getPDO();
		foreach ($set as $key=>$value) {
			$tmp_keys[] = $key;
			$tmp_holders[] = '?';
			$VALUES[] = $value;
		}
		$COLUMNS = implode(',', $tmp_keys);
		$HOLDERS =  implode(',', $tmp_holders);
		
		$sql = "INSERT INTO $this->TABLE ($COLUMNS) VALUES($HOLDERS)";
		$stmt = $PDO->prepare($sql);

		$this->SQL = $sql;
		$this->SQL_HISTORY[date('Y-m-d H:i:s')] = $sql;
		$this->VALUES = $VALUES;
		

		try { 
	        $PDO->beginTransaction(); 
	        $status = $stmt->execute($VALUES);

			if ($status) {
				$lastInsertId = $PDO->lastInsertId(); 
			}
			$PDO->commit(); 
			if ($status) {
		         return $lastInsertId;
			}
		} catch (PDOExecption $e) { 
	        $PDO->rollback(); 
			Console::log("Error: ".$e->getMessage() );
		} 
		return false;
	}


	/**
	 * 連想配列内のデータからSQLのUpdate文を生成してテーブルを更新する。
	 *
	 * @param array $set array('カラム名'=>値, ..)
	 * @param mix $conditions array('カラム名'=>値), .. or
	 *  array('カラム名'=>array('opr'=>演算子,'val'=>値), ..) or string
	 * @return mix 成功：integer Updateした行数 / 失敗：boolean false
	 */
	public function updateSet(array $set, $conditions)
	{
		$PDO = $this->getPDO();

		foreach ($set as $key=>$value) {
			$tmp_keys[] = "$key=?";
			$VALUES[] = $value;
		}
		$HOLDERS = implode(',', $tmp_keys);
		
		list($CONDS, $COND_VALUES) = $this->buildCondition($conditions);
		if (!empty($COND_VALUES)) $VALUES = array_merge($VALUES, $COND_VALUES);

		$sql = "UPDATE $this->TABLE SET $HOLDERS WHERE $CONDS";
		$stmt = $PDO->prepare($sql);
		
		$this->SQL = $sql;
		$this->SQL_HISTORY[date('Y-m-d H:i:s')] = $sql;
		$this->VALUES = $VALUES;


		try { 
	        $PDO->beginTransaction(); 
	        $status = $stmt->execute($VALUES);
			if ($status) {
				$count = $stmt->rowCount();
			} else {
				$count = 0;
			}
	        $PDO->commit(); 
			return $count;
		} catch (PDOExecption $e) { 
	        $PDO->rollback(); 
			Console::log("Error: ".$e->getMessage() );
		} 
		return false;
	}


	/**
	 * レコードがすでにある場合はUpdate、ない場合はInsert
	 *
	 * @param array $set array('カラム名'=>値, ..)
	 * @return mix 成功：integer Insertした行のID or Updateした行数 / 失敗：boolean false
	 */
	public function saveSet($set)
	{
		$PDO = $this->getPDO();
		$CONDS = '';
		
		$VALUES = array();
		if (is_array($this->UNIQUE_KEY)) {
			foreach ($this->UNIQUE_KEY as $key) {
				$value = $set[$key];
				if (empty($value)) continue;
				$tmp_conds[] = $key.'=?';
				$VALUES[] = $value;
			}
			if (!empty($tmp_conds)) {
				$CONDS = implode(' AND ', $tmp_conds);
			}
		} elseif (!empty($this->UNIQUE_KEY)) {
			// 2014/05/17 修正
			$key = $this->UNIQUE_KEY;
			if (isset($set[$key])) {
				$value = $set[$key];
				$CONDS = $key.'=?';
				$VALUES[] = $value;
			}
		}
		
		if (!empty($CONDS)) {
			$sql = "SELECT count(*) FROM $this->TABLE WHERE $CONDS";
			$stmt = $PDO->prepare($sql);
			$status = $stmt->execute($VALUES);
			
			$this->SQL = $sql;
			$this->SQL_HISTORY[date('Y-m-d H:i:s')] = $sql;

			$count = (Integer) $stmt->fetchColumn();
			
			if ($count == 0) {
				return $this->insertSet($set);
			} else {
				if (is_array($this->UNIQUE_KEY)) {
					$CONDS = array();
					foreach ($this->UNIQUE_KEY as $key) {
						$value = $set[$key];
						if (empty($value)) continue;
						$CONDS[$key] = array('opr'=>'=','val'=>$value);
					}
				} elseif (!empty($this->UNIQUE_KEY)) {
					$key = $this->UNIQUE_KEY;
					$value = $set[$key];
					if (!empty($value)) {
						$CONDS = $key.'='.$value;
					}
				}
				return $this->updateSet($set, $CONDS);
			}
		} else {
			return $this->insertSet($set);
		}
	}

	/**
	 * 連想配列内のデータからSQLのDelete文を生成して行を削除する。
	 *
	 * @param mix conditions array('カラム名'=>値), ..) 
	 *         or array('カラム名'=>array('opr'=>演算子,'val'=>値), ..) or string
	 * @return mix 成功：integer Updateした行数 / 失敗：boolean false
	 */
	public function delete($conditions='')
	{
		$PDO = $this->getPDO();
		
		list($CONDS, $VALUES) = $this->buildCondition($conditions);

		$sql = "DELETE FROM $this->TABLE";
		if (!empty($CONDS)) {
			$sql .= " WHERE $CONDS";
		}

		$stmt = $PDO->prepare($sql);

		try { 
	        $PDO->beginTransaction(); 
			if (!empty($VALUES)) {
		        $status = $stmt->execute($VALUES);
			} else {
		        $status = $stmt->execute();
			}
			if ($status) {
				$count = $stmt->rowCount();
			} else {
				$count = 0;
			}
	        $PDO->commit(); 
			return $count;
		} catch (PDOExecption $e) { 
	        $PDO->rollback(); 
			Console::log("Error: ".$e->getMessage() );
		} 
		return false;
	}


	/**
	 * MySQLのAutoIncrement値を初期化する
	 * @param integer $num 初期化時の値。デフォルト＝1
	 * @return void
	 */
	public function resetAutoIncrement($num=1)
	{
		$PDO = $this->getPDO();
		
		$sql = "ALTER TABLE $this->TABLE AUTO_INCREMENT=$num";
		$stmt = $PDO->prepare($sql);
		try { 
		    $status = $stmt->execute();
		} catch (PDOExecption $e) { 
			Console::log("Error: ".$e->getMessage() );
		} 
	}

	/**
	 * 条件に応じてSelect文を実行して結果を返す
	 * 
	 * @param mix $conditions array('カラム名'=>値), ..) or array('カラム名'=>array('opr'=>演算子,'val'=>値), ..) or string
	 * @param mix $columns array('カラム名'=>値), ..) or string
	 * @return 成功：PDOstatementを返す / 失敗：false
	 */
	public function getRows($conditions='', $columns='*')
	{
		if (is_array($columns)) {
			$COLS = implode(',', $columns);
		} else {
			$COLS = $columns;
		}
		
		list($CONDS, $VALUES) = $this->buildCondition($conditions);
		
		$sql = "SELECT $COLS FROM $this->TABLE";
		if (!empty($conditions)) {
			$sql .= " WHERE $CONDS";
		}

		return $this->query($sql, $VALUES);
	}

	
	/**
	 * すべての行を取得
	 * 
	 * @param mix $conditions array('カラム名'=>値), ..) or array('カラム名'=>array('opr'=>演算子,'val'=>値), ..) or string
	 * @param mix $columns array('カラム名'=>値), ..) or string
	 * @return 成功：PDOstatementを返す / 失敗：false
	 */
	public function getAllRows($conditions='', $columns='*')
	{
		if (is_array($columns)) {
			$COLS = implode(',', $columns);
		} else {
			$COLS = $columns;
		}
		
		list($CONDS, $VALUES) = $this->buildCondition($conditions);
		
		$sql = "SELECT $COLS FROM $this->TABLE";
		//echo "$sql\n";
		if (!empty($conditions)) {
			$sql .= " WHERE $CONDS";
		}

		$stmt = $this->query($sql, $VALUES);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $results;
		
	}
	


	/**
	 * WHEREの条件文をプレースホルダ付きで組み立てる
	 * 
	 * @param string|array $CONDS WHEREの条件文。$conditionsがarrayの場合は、プレースホルダを使ったものになる。
	 * @return array $VALUES プレースホルダ用の値
	 */
	protected function buildCondition($conditions)
	{
		$tmp_conds = array();
		$VALUES = array();
		$CONDS = '';
		
		if (is_array($conditions) AND !empty($conditions)) {
			foreach ($conditions as $column=>$cond) {
				if (is_array($cond)) {
					$operator = $cond['opr'];
					
					// ex) $conditions = array('column'=>array(
					//	     'opr'=>'BETWEEN', 'min'=>$min, 'max'=>$max,
					//     ));
					if (strtoupper($operator)=='BETWEEN') {
						$min = $cond['min'];
						$max = $cond['max'];
						$tmp_conds[] = $column.' '.$operator.' ? AND ?';
						$VALUES[] = $min;
						$VALUES[] = $max;
					
					// ex) $conditions = array('column'=>array(
					//	     'opr'=>'=', 'val'=>$val,
					//     ));
					} else {
						$value = $cond['val'];
						$tmp_conds[] = $column.' '.$operator.' ?';
						$VALUES[] = $value;
					}
					
				// ex) $conditions = array('column'=>$val);
				} else {
					$operator = '=';
					$value = $cond;
					$tmp_conds[] = $column.' '.$operator.' ?';
					$VALUES[] = $value;
				}
			}
			$CONDS = implode(' AND ', $tmp_conds);
		
		// ex) $conditions = array(); 
		} elseif (is_array($conditions) AND empty($conditions)) {
			$CONDS = '';
			$VALUES = array();
		
		// ex) $conditions = "column = $val";
		} elseif (preg_match("/(.*?) (.*?) (.*)/", $conditions, $m)) {
			$CONDS = $m[1].' '.$m[2].' ?';
			$VALUES[] = $m[3];
		
		// ex) $conditions = "column=$val";
		} else {
			$CONDS = $conditions;
			$VALUES = array();
		}
		
		return array($CONDS, $VALUES);
	}


	/**
	 * SQLを実行して、PDOstatementを返す
	 * 
	 * @param string $sql 
	 * @param array $VALUES 
	 * @return 成功：PDOstatementを返す / 失敗：false
	 */
	public function query($sql, $VALUES=array())
	{
		$PDO = $this->getPDO();
		
		try { 
			$stmt = $PDO->prepare($sql);
			if (!empty($VALUES)) {
				$stmt->execute($VALUES);
			} else {
				$stmt->execute();
			}
			return $stmt;
		} catch (PDOExecption $e) { 
			Console::log("Error: ".$e->getMessage() );
		} 
		return false;
	}


	/**
	 * データベースを明示的にクローズする
	 */
	public function close()
	{
		$this->PDO = NULL;
	}
	
} 
