<?php
/**
 * FacebookApiKey API
 */
class FacebookApiKey {

	public static function getParams() {

		$config = [
			"enabled" => true,
			"keys"    => [
				"id" => "PUT_YOUR_APP_ID", 
				"secret" => "PUT_YOUR_SECRET_KEY"
			],
			"scope" => "email,public_profile",
			"display" =>"popup"
		];
	
		return $config;
	}
	
}
