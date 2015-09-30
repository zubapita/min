<?php
/**
 * Datatype型の実数型クラス
 * @see lib/Datatype.php
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class _Float extends Datatype
{
	
	public function isValid() {
		return is_numeric($this->value);
	}	
}
