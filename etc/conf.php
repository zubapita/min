<?php

class appConfigure
{
	static function get()
	{
		$PRODUCTION_RUN_SERVER = '';
		
		if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']==$PRODUCTION_RUN_SERVER) {
			$C['PRODUCTION_RUN'] = true;
		} else {
			$C['PRODUCTION_RUN'] = false;
		}
	
		return $C;
	}
	
}


