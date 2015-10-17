<?php
// RFC3986 形式で URL エンコードする関数
function urlencode_rfc3986($str)
{
    return str_replace('%7E', '~', rawurlencode($str));
}

/**
 * Amazon Product Advertising API 商品検索
 */
class AmazonSearchLib
{
    /**
     * 商品を検索・取得した日付
     */
    private $date;
    
    /**
     * コンストラクタ
     * 
     */
    public function __construct()
    {
        $this->date = date('Y/m/d');
    }

    /**
     * 商品検索をリクエストするendpointのURLを生成
     * 
     * @param array $my_params 検索APIのパラメータを指定
     * @param string $secret_access_key シークレットキー
     * @return string 商品検索をリクエストするURL
     */
    public function getUrl($my_params, $secret_access_key)
    {
        // リクエストを作成
        $baseurl = 'http://webservices.amazon.co.jp/onca/xml';
        $params = array();
        $params['Service']            = 'AWSECommerceService';
        $params['Version']            = '2013-08-01';
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
     * @return array|string 成功した場合は検索結果の商品データ配列。失敗した場合はAamzonが返す生XML
     */
    public function get($params, $secret_key)
    {
        $_ = $this;
    
        $params['Operation'] = 'ItemSearch';
        $end_point_url = $_->getUrl($params, $secret_key);
        $xml = simplexml_load_file($end_point_url);

        if (!$_->isValid($xml)) {
            if ($_->dispatch_trace) {
                Console::log('AmazonSearchLib::get -Amazon API error:'.date('Y/m/d H:i:s'));
                Console::log($xml);
            }
            return $xml;
        }

        $TotalResults = (Integer) $_->totalResults($xml);
        $TotalPages = (Integer) $_->totalPages($xml);
            
        $productsSet = [];
        foreach ($xml->Items->Item as $Item) {
            $p = $_->parseItem($Item);
            $p['Item'] = $Item;
            $productsSet[(String)$Item->ASIN] = $p;
        }

        if ($params['ItemPage'] < $TotalPages) {
            sleep(1);
            $params['ItemPage']++;
            $restSet = $_->get($params, $secret_key);
            $productsSet = array_merge($productsSet, $restSet);
        }
        
        return $productsSet;
    }
    
    
    /**
     * 商品をIDで検索して結果を配列で返す
     * 
     * @param array $my_params 検索APIのパラメータを指定
     * @param string $secret_access_key シークレットキー
     * @return array|string 成功した場合は検索結果の商品データ配列。失敗した場合はAamzonが返す生XML
     */
    public function getById($params, $secret_key)
    {
        $_ = $this;
    
        $params['Operation'] = 'ItemLookup';
        $end_point_url = $_->getUrl($params, $secret_key);
        $xml = simplexml_load_file($end_point_url);

        if (!$_->isValid($xml)) {
            if ($_->dispatch_trace) {
                Console::log('AmazonSearchLib::getById -Amazon API error:'.date('Y/m/d H:i:s'));
            }
            return $xml;
        }
    
        $productsSet = [];
        $i = 1;
        foreach ($xml->Items->Item as $Item) {
            $p = $_->parseItem($Item);
            $p['Item'] = $Item;
            $productsSet[$i] = $p;
            $i++;
        }
        
        return $productsSet;
    }
    
    /**
     * BrowseNodeのTopSellersを取得
     * 
     * @param array $my_params 検索APIのパラメータを指定
     * @param string $secret_access_key シークレットキー
     * @return array|string 成功した場合は検索結果の商品データ配列。失敗した場合はAamzonが返す生XML
     */
    public function getTopSellersByNode($params, $secret_key)
    {
        $_ = $this;
    
        $params['Operation'] = 'BrowseNodeLookup';
        $params['ResponseGroup'] = 'TopSellers';
        $end_point_url = $_->getUrl($params, $secret_key);
        $xml = simplexml_load_file($end_point_url);
        
        $topSellers = $xml->BrowseNodes->BrowseNode->TopSellers;
        
        $items = array();
        if (!empty($topSellers)) {
            $topList = $topSellers->TopSeller;
            if (count($topList) > 0) {
                foreach ($topList as $Item) {
                    $items[(string)$Item->ASIN]['Title'] = (string) $Item->Title;
                    $items[(string)$Item->ASIN]['ASIN'] = (string) $Item->ASIN;
                }
            }
        }
            
        return $items;
    }
    
    /**
     * BrowseNodeIdからRootのBrowseNodeIdを取得
     * 
     * @param array $my_params 検索APIのパラメータを指定
     * @param string $secret_access_key シークレットキー
     * @return array|string 成功した場合は検索結果の商品データ配列。失敗した場合はAamzonが返す生XML
     */
    public function getRootBrowseNodeID($params, $secret_key)
    {
        $_ = $this;
    
        $params['Operation'] = 'BrowseNodeLookup';
        $end_point_url = $_->getUrl($params, $secret_key);
        
        $xml = simplexml_load_file($end_point_url);
        
        $nodeid = '';
        if (isset($xml_response->BrowseNodes->BrowseNode)) {
            $anc = $xml->BrowseNodes->BrowseNode->Ancestors;
            while (isset($anc->BrowseNode->Ancestors)) {
                $anc = $anc->BrowseNode->Ancestors;
                $nodeid = (string) $anc->BrowseNode->BrowseNodeId;
            }
        }

        return $nodeid;
    }
    
    
    /**
     * ItemSearch結果のXMLを読みやすい形に配列化
     * 
     * @param Object $Item 検索結果XMLのItemオブジェクト
     * @return array $Itemの一部を配列にして返す
     */
    public function parseItem($Item)
    {
        $p = [];
        $p['ASIN'] = (String) $Item->ASIN;
        $p['EAN'] = (String) $Item->ItemAttributes->EAN;
        $p['ISBN'] = (String) $Item->ItemAttributes->ISBN;
        $p['Title'] = (String) $Item->ItemAttributes->Title;
        $p['Publisher'] = (String) $Item->ItemAttributes->Publisher;
        $p['SalesRank'] = (String) $Item->SalesRank;
        $DetailPageURL = 'http://www.amazon.co.jp/dp/'. (String) $Item->ASIN;
        $p['DetailPageURL'] = $DetailPageURL;
        $p['SmallImage'] = (String) $Item->SmallImage->URL;
        $p['MediumImage'] = (String) $Item->MediumImage->URL;
        $p['LargeImage'] = (String) $Item->LargeImage->URL;
        $p['Author'] = (String) $Item->ItemAttributes->Author;
        $p['PublicationDate'] = (String) $Item->ItemAttributes->PublicationDate;
        $p['ReleaseDate'] = (String) $Item->ItemAttributes->ReleaseDate;
        $p['entryDatetime'] = $this->date;
        $p['updateDatetime'] = $this->date;
        
        return $p;
    }
    
    
    public function isValid($xml)
    {
        $IsValid = $xml->Items->Request->IsValid;
        return $IsValid;
    }

    public function totalResults($xml)
    {
        $TotalResults = $xml->Items->TotalResults;
        return $TotalResults;
    }

    public function totalPages($xml)
    {
        $TotalPages = $xml->Items->TotalPages;
        return $TotalPages;
    }
} // end of class

