<?php
// RFC3986 形式で URL エンコードする関数
function urlencode_rfc3986($str){
    return str_replace('%7E', '~', rawurlencode($str));
}

/**
 * Amazon Product Advertising API 商品検索
 */
class AmazonSearch
{
	
	/**
	 * 商品を検索・取得した日付
	 */
	private $date;
	
	/**
	 * コンストラクタ
	 * 
	 */
	function __construct() {
		$this->date = date('Y/m/d');
	}

	/**
	 * 商品検索をリクエストするendpointのURLを生成
	 * 
	 * @param array $my_params 検索APIのパラメータを指定
	 * @param string $secret_access_key シークレットキー
	 * @return string 商品検索をリクエストするURL
	 */
	function getUrl($my_params, $secret_access_key) {
		// 基本的なリクエストを作成
		$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
		$params = array();
		$params['Service']			= 'AWSECommerceService';
		$params['Version']			= '2011-08-01';
		$params['Operation']		= 'ItemSearch';
		$params['SearchIndex']		= 'All';
		$params['ResponseGroup']	= 'Small';
		$params = array_merge($params, $my_params);


		// Timestamp パラメータを追加
		// - 時間の表記は ISO8601 形式、タイムゾーンは UTC(GMT)
		$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

		// パラメータの順序を昇順に並び替え
		ksort($params);

		// canonical string を作成
		$canonical_string = '';
		foreach ($params as $k => $v) {
		    $canonical_string .= '&'.urlencode_rfc3986($k).'='.urlencode_rfc3986($v);
		}
		$canonical_string = substr($canonical_string, 1);

		// 署名を作成
		// - 規定の文字列フォーマットを作成
		// - HMAC-SHA256 を計算
		// - BASE64 エンコード
		$parsed_url = parse_url($baseurl);
		$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret_access_key, true));

		// URL を生成
		// - リクエストの末尾に署名を追加
		$url = $baseurl.'?'.$canonical_string.'&Signature='.urlencode_rfc3986($signature);

		return $url; 
	}
	
	/**
	 * 商品を検索して結果を配列で返す
	 * 
	 * @param array $my_params 検索APIのパラメータを指定
	 * @param string $secret_access_key シークレットキー
	 * @return array 検索結果の商品データ
	 */
	function get($params, $secret_key) {
		$_ = $this;
	
		$end_point_url = $_->getUrl($params, $secret_key);
		$xml = simplexml_load_file($end_point_url);

		while(!$_->isValid($xml)) {
			if ($_->dispatch_trace) {
				Console::log('AmazonSearch::gets -Amazon API error:'.date('Y/m/d H:i:s');
			}
			sleep(60);
			$xml = simplexml_load_file($end_point_url);
		}

		$TotalResults = $_->totalResults($xml);
		$TotalPages = $_->totalPages($xml);
	
		$productsSet = [];
		$i = (params['ItemPage'] * 10) - 9;
		foreach($xml->Items->Item as $Item) {
			$p['ASIN'] = $Item->ASIN;
			$p['Title'] = $Item->ItemAttributes->Title;
			$p['Publisher'] = $Item->ItemAttributes->Publisher;
			$p['SalesRank'] = $Item->SalesRank;
			$DetailPageURL = 'http://www.amazon.co.jp/dp/'.$Item->ASIN;
			$p['DetailPageURL'] = $DetailPageURL;
			$p['ImageUrl'] = $Item->MediumImage->URL;
			$p['Author'] = $Item->ItemAttributes->Author;
			$p['PublicationDate'] = $Item->ItemAttributes->PublicationDate;
			$p['ReleaseDate'] = $Item->ItemAttributes->ReleaseDate;
			$p['entryDatetime'] = $this->date;
			$p['updateDatetime'] = $this->date;
			$productsSet[$i] = $p;
			$i++;
		}

		if($params['ItemPage'] < $TotalPages) {
			sleep(60);
			$params['ItemPage']++;
			$restSet = $_->get($params, $secret_key);
			$productsSet = array($productsSet, $restSet);
		}
		
		return $productsSet;
	}
	
	
	function isValid($xml) {
		$IsValid = $xml->Items->Request->IsValid;
		return $IsValid;
	}

	function totalResults($xml) {
		$TotalResults = $xml->Items->TotalResults;
		return $TotalResults;
	}

	function totalPages($xml) {
		$TotalPages = $xml->Items->TotalPages;
		return $TotalPages;
	}

} // end of class




