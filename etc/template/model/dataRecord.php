<?php
/**
 * テーブル {$table} のレコード操作model
 * 
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class {$className} extends DataRecord
{


	/**
	 * バリデータインスタンス格納用
	 */
	private $validator;

	/**
	 * エラー格納用
	 */
	public $errors;

	/**
	 * TABLE {$db}.{$table} の初期化
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$_ = $this;

		$_->DB = new {$db};
		$_->{$table|ucfirst} = $_->getTable($_->DB, '{$table}');
	}	

	/**
	 * TABELを検索して該当する行を返す
	 * 
	 * @param (array|string) $conditions 検索条件
	 * @return array 検索結果
	 */
	public function get($conditions)
	{
		$_ = $this;
		
		// SELECT 条件の設定
		$_->{$table|ucfirst}->reset();
		$columns = [
			{foreach $columns as $column}
			'{$column['name']}',
			{/foreach}
		];
		$_->{$table|ucfirst}->select($columns);
		
		// SELECTの結果取得
		$record = $_->{$table|ucfirst}->find($conditions)->fetch();
		if ($_->dispatch_trace) {
			Console::log('{$className}::get');
			Console::log($_->{$table|ucfirst}->SQL);
		}

		return $record;
	}

	/**
	 * TABELに行を保存する
	 * 
	 * @param array $data 保存するデータ
	 * @return integer|boolean 保存に成功した場合はidを返す。失敗したらfalse
	 */
	public function set($data)
	{
		$_ = $this;
		
		// idが空ならadd。
		// idをautoinclementで入れるため一旦削除
		if (empty($data['id'])) {
			unset($data['id']);
		}
		
		// データの検証と保存
		if ($validData = $_->validate($data)) {
			$_->{$table|ucfirst}->reset();
			$result = $_->{$table|ucfirst}->saveSet($validData);
			if ($_->dispatch_trace) {
				Console::log('{$className}::set');
				Console::logSql($_->{$table|ucfirst}->SQL, $_->{$table|ucfirst}->VALUES);
			}
			return $result;
		} else {
			if ($_->dispatch_trace) {
				Console::log('{$className}::set Validate error.');
				Console::log($_->errors);
			}
			return false;
		}

	}

	/**
	 * 行データの検証とフィルタリング
	 * 
	 * バリデータ記述法
	 * Language Independent Validation Rules
	 * https://github.com/koorchik/LIVR
	 * 
	 * カスタマイズ
	 * WebbyLab/php-validator-livr
	 * https://github.com/WebbyLab/php-validator-livr
	 * 
	 * @param array $data 検証するデータ
	 * @return boolean|array 検証結果
	 */
	public function validate($data)
	{
		$_ = $this;
		
		
		// 検証＆フィルタの設定
		$rule = [
{foreach $columns as $column}
{if $column['name']!='id'}
			'{$column['name']}' => ['required'],
{/if}
{/foreach}
		];

		$validator = new Validator($rule);

		// バリデート実行
		$result = $validator->validate($data);
		if (!$result) {
			$_->errors = $validator->errors;
		}
		return $result;
	}




}

