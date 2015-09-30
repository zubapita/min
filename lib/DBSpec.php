<?php
/**
 *	Project:	Quicty4: Quick and Light weight framework with Smarty and PDO.
 *	File:		DBSpec.php
 *
 *	@copyright	Tomoyuki Negishi and ZubaPitaTech, Inc.
 *	@author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 *	@license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 *	@package	Quicty4
 *	@version	0.1
 */

/**
 * データベースへの接続文字列を設定
 * @package Quicty4
 * @author 著作者 <tomoyun@zubapita.jp>
 * @since PHP 5.3
 * @version $Id: DBAcess.php,v 1.58 2013/12/29 $
 */
abstract class DBSpec {
	public static $DSN;		// 'mysql:dbname=dba_name;host=127.0.0.1';
	public static $USER;		// 'db_user';
	public static $PASSWORD;	// 'db_password';
}
