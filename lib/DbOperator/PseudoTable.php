<?php
class PseudoTable extends DBMapper
{

	public $TABLE = 'pseudoTB';
	protected $ID_CLOUMN = 'id';
	protected $UNIQUE_KEY = 'id';
	protected $COLUMNS = array(
		'id'=>'_Integer',
	);	
}
