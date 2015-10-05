<?php
require_once __DIR__.'../../../../lib/autoload.php';
class {$className}Test extends PHPUnit_Framework_TestCase
{

	/*
	 * Test ${$className}->set($data)
	 *
	 */
	public function testSet() 
	{
		$_ = $this;
		${$className} = new {$className}();

		foreach ($_->dataProvider() as $data) {
			$result = ${$className}->set($data);
			$id = (integer) $result;
			$this->assertTrue(is_int($id));
	        $this->assertNotEquals(0, $id);
		}
    }
	
	
	/*
	 * Data Provider for testSet
	 *
	 */
	public function dataProvider()
	{

		// 各カラムの型にあったテストデータを設定すること！
		$data = [
			[
{foreach $columns as $column}
{if {$column['name']}!='id'}
				'{$column['name']}'=>'hoge',
{/if}
{/foreach}
			],
		];
		
		return $data;
	}

	/*
	 * Test ${$className}->get($condition)
	 *
	 */
    public function testGet() {
		$_ = $this;
		
		${$className} = new {$className}();
		
		$condition = [];

		$this->assertTrue(is_array(${$className}->get($condition)));
		$this->assertNotEquals(0, count(${$className}->get($condition)));
    }

}
