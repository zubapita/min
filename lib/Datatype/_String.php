<?php
/**
 * Datatype型の文字列型クラス
 * @see lib/Datatype.php
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class _String extends Datatype
{
	
	public function isValid() {
		return is_string($this->value);
	}	
}
