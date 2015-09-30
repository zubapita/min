<?php
/**
 * Datatype型の日付・時間型クラス
 * @see lib/Datatype.php
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class _Datetime extends Datatype
{
	
	public function set($value) {
		$this->value = new DateTime($value);
	}
	
	public function isValid() {
		if(is_object($this->value)) {
			if(get_class($this->value)=='DateTime') {
				return true;
			}
		}
		return false;
	}

	public function __call($name, $arguments) {
		if(is_object($this->value)) {
			if(method_exists($this->value, $name)) {
				return call_user_func_array(array($this->value, $name), $arguments);
			}
		}
	}
	
}

