<?php

class appConfigure
{
	static function get()
	{
        $C['IS_GAE'] = false;
        $C['MY_BUCKET'] = 'put_gae_bucket_name_here';
        
		$C['LOCAL_TEST_SERVER'] = 'dev.min.local';

		$C['PRODUCTION_RUN_SERVER'] = '';

		if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']==$C['PRODUCTION_RUN_SERVER']) {
			$C['PRODUCTION_RUN'] = true;
		} else {
			$C['PRODUCTION_RUN'] = false;
		}
	
		return $C;
	}
	
}

