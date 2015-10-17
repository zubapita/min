<?php
/**
 * データベース操作クラス
 * 
 * DBAccessクラスをそのままインスタンス化して使うためのクラス
 * テーブルクラスを使わずに、create tableなどのデータベース操作を行うときに使用する
 * 
 * ex) データベース mydb のデータベースクラスファイルが作成済みとする
 * 
 * $DBSpec = new mydb;
 * $MyDB = new StdDB($DBSpec);
 * $sql = "create table mytable (id int(11) AUTO_INCREMENT, name varchar(10)";
 * $result = $MyDB->query($sql);
 * 
 */
// 
class StdDB extends DBAccess
{
}
