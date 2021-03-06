<?php
class authProvider extends DBMapper {

	public $TABLE = 'authProvider';

	protected $ID_CLOUMN = 'id';
	protected $UNIQUE_KEY = ['userId', 'provider',];

	protected $COLUMNS = array(
			"id"=>"_Integer",
			"userId"=>"_Integer",
			"provider"=>"_String",
			"providerId"=>"_String",
			"updateAt"=>"_String",
		);	
}