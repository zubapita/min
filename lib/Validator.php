<?php
/**
 * データの検証とフィルタリングのためのクラス
 *
 * 
 * バリデータ記述法
 * Language Independent Validation Rules
 * https://github.com/koorchik/LIVR
 * 
 * カスタマイズ
 * WebbyLab/php-validator-livr
 * https://github.com/WebbyLab/php-validator-livr
 *
 * @copyright	Tomoyuki Negishi and ZubaPitaTech Y.K.
 * @author		Tomoyuki Negishi <tomoyu-n@zubapita.jp>
 * @license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @package	Min - Minimam INter framework for PHP
 * @version	0.1
 */
class Validator extends AppCtl
{

	/**
	 *  php-validator-livrインスタンスを保持
	 *
	 */
	public $validator;


	/**
	 * コンストラクタ。
	 *
	 * @param array $rule 検証＆フィルタ設定
	 *
	 */
	public function __construct($rule)
	{
		parent::__construct();
		$_ = $this;
		
		Validator\LIVR::defaultAutoTrim(true);
		
		// 検証＆フィルタの設定
		$_->validator = new Validator\LIVR ($rule);

		// カスタム検証ルール／フィルタの登録
		$_->registValidator();
		
	}


	/**
	 * 行データの検証とフィルタリング
	 * 
	 * @param array $data 検証するデータ
	 * @return boolean|array 検証結果
	 */
	public function validate($data)
	{
		$_ = $this;
		
		$result = $_->validator->validate($data);
		if (!$result) {
			$_->errors = $_->validator->getErrors();
		}
		return $result;
	}

	/**
	 * カスタム検証ルール／フィルタの登録
	 */
	public function registValidator()
	{
		$_ = $this;
		
		// 検証ルール・サンプル
		$_->validator->registerRules( ['strong_password' => function() {
			return function($value) {
				if ( !isset($value) || $value === '' ) {
					return;
				}
				if ( strlen($value) < 6 ) {
					return 'WEAK_PASSWORD';
				}
			};
		}]);

		$_->validator->registerRules( ['no_check' => function() {
			return function($value) {
				return;
			};
		}]);

		/**
		 * 全角英数を半角に統一するフィルタ
		 * ・全角英数字を半角に
		 * ・全角スペースを半角に
		 * ・半角カタカナを全角に
		 * ・濁点附き文字を一文字に
		 */
		$_->validator->registerRules( ['zen2han' => function() {
			return function($value, $params, &$outputRef) {
				$outputRef = mb_convert_kana($value, "asKV");
				return;
			};
		}]);

		/**
		 * ひらがなに統一するフィルタ
		 * ・半角カタカナを全角ひらがなに
		 * ・全角カタカナを全角ひらがなに
		 */
		$_->validator->registerRules( ['2hiragana' => function() {
			return function($value, $params, &$outputRef) {
				$outputRef = mb_convert_kana($value, "Hc");
				return;
			};
		}]);

		/**
		 * 全角ハイフンやダーシを半角「-」に統一
		 */
		$_->validator->registerRules( ['hyphen' => function() {
			return function($value, $params, &$outputRef) {
				$hbars = [];
				$hbars[] =  json_decode('["\u2010"]', true)[0];
				$hbars[] =  json_decode('["\u2011"]', true)[0];
				$hbars[] =  json_decode('["\u2012"]', true)[0];
				$hbars[] =  json_decode('["\u2013"]', true)[0];
				$hbars[] =  json_decode('["\u2014"]', true)[0];
				$hbars[] =  json_decode('["\u2015"]', true)[0];
				$hbars[] =  json_decode('["\u2212"]', true)[0];
				$hbars[] =  json_decode('["\u30FC"]', true)[0];
				$hbars[] =  json_decode('["\uFF70"]', true)[0];
				$hbars[] =  json_decode('["\u4e00"]', true)[0];
				$outputRef = str_replace($hbars, '-', $value);
				return;
			};
		}]);

	}


}
