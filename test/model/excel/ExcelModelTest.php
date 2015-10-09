<?php
require_once __DIR__.'../../../../lib/autoload.php';
$debugConsole = new Console;
global $dataBridge;
$dataBridge->dispatch_trace = true;
class ExcelModelTest extends PHPUnit_Framework_TestCase
{

	/*
	 * Test $ExcelModel->get($condition)
	 *
	 */
	public function testGet() {
		$_ = $this;
		
		$ExcelModel = new ExcelModel();
		
		$dirPath = 'etc/template/test/etc/excel';
		$fileName = 'Apple.xlsx';
		
		$result = $ExcelModel->get("$dirPath/$fileName");
		
		var_dump($result);

		$this->assertTrue(is_array($result));
		$this->assertNotEquals(0, count($result));
    }

}
