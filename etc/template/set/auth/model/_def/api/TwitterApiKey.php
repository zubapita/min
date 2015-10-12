<?php
/**
 * TwitterApiKey API
 */
class TwitterApiKey {

	public static function getParams() {

		$config = [
			"enabled" => true,
			"keys"    => [
				"key" => "PUT_YOUR_API_KEY", 
				"secret" => "PUT_YOUR_SECRET_KEY"
			]
		];

	
		return $config;
	}
	
}
