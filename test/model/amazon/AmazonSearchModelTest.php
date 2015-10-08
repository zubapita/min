<?php
require_once __DIR__.'../../../../lib/autoload.php';
$debugConsole = new Console;
global $dataBridge;
$dataBridge->dispatch_trace = true;
class AmazonSearchModelTest extends PHPUnit_Framework_TestCase
{

	/*
	 * Test $AmazonSearch->get($condition)
	 *
	 */
	public function testGet() {
		$_ = $this;
		
		$AmazonSearch = new AmazonSearch();
		
		//
		// ItemSearch DV
		CmdLibs::bannerBig('ItemSearch DVD', false);

		$conditions = [
			'SearchIndex' => 'DVD',
			'Title' => 'さくら学院',
			'ResponseGroup' => 'Large',
		];

		$result = $AmazonSearch->get($conditions);
		
		foreach ($result as $item) {
			echo $item['Title']."\n";
		}
		
		$this->assertTrue(is_array($result));
		$this->assertNotEquals(0, count($result));

		//
		// ItemSearch Music
		CmdLibs::bannerBig('ItemSearch Music', false);

		$conditions = [
			'SearchIndex' => 'Music',
			'Title' => 'BABYMETAL',
			'ResponseGroup' => 'Large',
		];

		$result = $AmazonSearch->get($conditions);
		
		foreach ($result as $item) {
			echo $item['Title']."\n";
		}
		$this->assertTrue(is_array($result));
		$this->assertNotEquals(0, count($result));
	}

	/*
	 * Test $AmazonSearch->getTopSellersByNode($condition)
	 *
	 */
	public function testGetTopSellersByNode() {
		$_ = $this;
		
		$AmazonSearch = new AmazonSearch();
		
		CmdLibs::bannerBig('BrowseNode TopSeller DVD', false);

		$conditions = [
			'BrowseNodeId' => '561958',
		];

		$result = $AmazonSearch->getTopSellersByNode($conditions);
		
		foreach ($result as $ASIN=>$item) {
			echo $item['Title']."\n";
		}
		$this->assertTrue(is_array($result));
		$this->assertNotEquals(0, count($result));
	}


	/*
	 * Test $AmazonSearch->getById($condition)
	 *
	 */
	public function testGetById() {
		$_ = $this;
		
		$AmazonSearch = new AmazonSearch();
		
		CmdLibs::bannerBig('ItemLookup Books', false);

		$conditions = [
			'SearchIndex' => 'Books',
			'IdType' => 'ISBN',
			'ResponseGroup' => 'ItemIds,ItemAttributes,SalesRank,Images',
			//'ItemId' => '9784120043079', // スパイス、爆薬、医薬品
			//'ItemId' => '9784822281953', // オブジェクト指向でなぜつくるのか
			'ItemId' => '9784774174099', // ほんの1秒もムダなく片づく 情報整理術の教科書
		];

		$result = $AmazonSearch->getById($conditions);
		
		foreach ($result as $item) {
			echo $item['Title']."\n";
		}
		$this->assertTrue(is_array($result));
		$this->assertNotEquals(0, count($result));

	}
	
	/*
	[Amazon][Product Advertising API] SearchIndexとBrowseNode一覧 
	
		対象バージョンは2013-08-01
	
		SearchIndex    ブラウズノード名    ブラウズノードID
		Apparel    服＆ファッション小物    352484011
		Appliances    大型家電    2277724051
		Automotive    カー＆バイク用品    2017304051
		Baby    ベビー＆マタニティ    344845011
		Beauty    コスメ    52374051
		Books    本    465392
		Classical    クラシック    701040
		DVD    DVD    561958
		Electronics    家電＆カメラ    3210981
		ForeignBooks    洋書    52033011
		Grocery    食品＆飲料    57239051
		HealthPersonalCare    ヘルス＆ビューティー    160384011
		HomeImprovement    DIY・工具    2016929051
		Hobbies    ホビー    2277721051
		Jewelry    ジュエリー    85895051
		Kitchen    ホーム＆キッチン    3828871
		KindleStore    Kindleストア    2250738051
		Music    ミュージック    561956
		MP3Downloads    デジタルミュージック    2128134051
		MusicalInstruments    楽器    2123629051
		OfficeProducts    文房具・オフィス用品    86731051
		PCHardware    パソコン・周辺機器    2127209051
		PetSupplies    ペット用品    2127212051
		Shoes    シューズ＆バッグ    2016926051
		Software    PCソフト    637392
		SportingGoods    スポーツ＆アウトドア    14304371
		Toys    おもちゃ    13299531
		VHS    VHS    2130989051
		VideoGames    ゲーム    637394
		Watches    腕時計    324025011
	*/
	

}
