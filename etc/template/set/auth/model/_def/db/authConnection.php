<?php
class authConnection extends DBMapper {

	public $TABLE = 'authConnection';

	protected $ID_CLOUMN = 'id';
	protected $UNIQUE_KEY = ['userId', 'provider'];

	protected $COLUMNS = array(
			"id"=>"_Integer",
			"userId"=>"_Integer",
			"provider"=>"_String",
			"hybridauthSession"=>"_String",
			"updateAt"=>"_String",
		);	
}