<?php
require_once __DIR__.'../../../../lib/autoload.php';
class {$className}Test extends PHPUnit_Framework_TestCase
{

	/*
	 * Test ${$className}->get($condition)
	 *
	 */
	public function testGet() {
		$_ = $this;
		
		${$className} = new {$className}();
		
		$condition = [];
		$currentPage = 1;

		$this->assertTrue(is_array(${$className}->get($condition, $currentPage)));
		$this->assertNotEquals(0, count(${$className}->get($condition, $currentPage)));
    }

}
