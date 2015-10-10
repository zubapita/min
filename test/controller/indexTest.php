<?php
require_once __DIR__.'../../../lib/autoload.php';
class indexTest extends PHPUnit_Extensions_Selenium2TestCase
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
	 * Indexページのテスト
	 *
	 */
	public function testindex() {
		$this->url('/');
		$this->assertEquals('Top', $this->title());
	}
}
