<?php
/**
 * Amazon Product Advertising API
 */
class AmazonPA() {

	public static function getParams() {

		$access_key_id = 'ACCESS_KEY';
		$secret_key = 'SECRET_ACSESS_KEY';
		$AssociateTag = 'ASSOCIATE_TAG';
	
		$params['AWSAccessKeyId']	= $access_key_id;
		$params['AssociateTag']		= $AssociateTag;
		$params['SearchIndex']    = 'Books';
		$params['ResponseGroup'] = 'ItemIds,ItemAttributes,SalesRank,Images';
		//$params['BrowseNode']    = ''; // micro contents

		$params['ItemPage']	= 1;
		$params['secret_key'] = $secret_key;
	
		return $params;
	}
	
}

