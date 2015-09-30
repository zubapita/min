<?php
/**
 * Data型の抽象クラス。主にDBのカラムのデータ型を表す。
 * このクラスは抽象クラスで、実際のデータ型（_Integerや_String）はこれを継承したクラスで実装する。
 * @see lib/Datatype/*.php
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
abstract class Datatype
{
	/**
	 * インスタンス（カラム）の値
	 */
	protected $value;
	
	/**
	 * インスタンス（カラム）の値を返す
	 * 
	 * @return mix インスタンス（カラム）の値
	 */
	public function get() {
		return $this->value;
	}
	
	/**
	 * インスタンス（カラム）の値をセットする
	 * 
	 * @param mix $value インスタンス（カラム）の値
	 * @return mix インスタンス（カラム）の値
	 */
	public function set($value) {
		return $this->value = $value;
	}
	
	abstract public function isValid();
}
