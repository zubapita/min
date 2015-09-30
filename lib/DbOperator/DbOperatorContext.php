<?php
/**
 * DbOperator Strategyを保持、
 * 制御するContextクラス
 */
class DbOperatorContext
{
    private $DbOperator; // strategy

    /**
     * コンストラクタ
     * @param $systemName string
     */
    public function __construct($systemName)
	{
		$_ = $this;
		
		$systemName = ucfirst($systemName);
		$dbOperatorClass = 'DbOperator'.$systemName;
		$_->DbOperator = new $dbOperatorClass;
    }
	
	public function setDb($DB)
	{
		$_ = $this;
		return $_->DbOperator->setDb($DB);
	}
	
	public function getTables()
	{
		$_ = $this;
		return $_->DbOperator->getTables();
	}
	
	public function getColumns($table)
	{
		$_ = $this;
		return $_->DbOperator->getColumns($table);
	}

	public function getDbClassTemplate()
	{
		$_ = $this;
		return $_->DbOperator->getDbClassTemplate();
	}

	public function getTableClassTemplate()
	{
		$_ = $this;
		return $_->DbOperator->getTableClassTemplate();
	}
	
	public function getColumnTypes()
	{
		$_ = $this;
		return $_->DbOperator->getColumnTypes();
	}
	
}
