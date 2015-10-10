<?php
require_once __DIR__.'../../../../../lib/autoload.php';
class {$className}CtlTest extends PHPUnit_Extensions_Selenium2TestCase
{
	/*
	 * Webブラウザ環境のセットアップ
	 */
	public function setUp() {
		$this->C = appConfigure::get();
		$targetUrl = 'http://'.$this->C['LOCAL_TEST_SERVER'];

		$this->setHost('127.0.0.1');    // SeleniumServerがインストールされているホスト名
		$this->setPort(4444);           // SeleniumServerの稼働しているポート
		$this->setBrowser('chrome');   // firefox, chrome, iexplorer, safari
		$this->setBrowserUrl($targetUrl);
	}
	
	
	/*
	 * Addページのテスト
	 *
	 */
	public function testAdd() {
		$this->url('/{$pageName}/add');
		$this->assertEquals('{$className} Add', $this->title());
	}

	/*
	 * Indexページのテスト
	 *
	 */
	public function testIndex() {
		$this->url('/{$pageName}/?id=1');
		$this->assertEquals('{$className}', $this->title());
	}

	/*
	 * Editページのテスト
	 *
	 */
	public function testEdit() {
		$this->url('/{$pageName}/edit?id=1');
		$this->assertEquals('{$className} Edit', $this->title());
	}

}

/*

操作

フォーム入力
$element = $this->byId(‘domId’); // like getElementById()
$element->value(‘value’); // set value
$value = $element->value(); // get value

リスト選択
$this->select($this->byId(‘domId’))->selectOptionByLabel(‘label’);

クリック
$this->byId(‘domId’)->click();

CSSセレクタによる指定
$this->beCssSelector(‘CSS Selector')

InnerTextの取得
$innnerText = $this->byId(‘domId’)->text();

メソッドチェーン
$this->byId(‘domId’)->value(‘value’);

*/
