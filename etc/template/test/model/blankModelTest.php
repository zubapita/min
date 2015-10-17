<?php
require_once __DIR__.'../../../../lib/autoload.php';
$debugConsole = new Console;
global $dataBridge;
$dataBridge->dispatch_trace = true;
class {$className}Test extends PHPUnit_Framework_TestCase
{

    /*
     * Test ${$className}->get($condition)
     *
     */
    public function testGet()
    {
        $_ = $this;
        
        ${$className} = new {$className}();
        
        $condition = [];

        $result = ${$className}->get($condition);

        $this->assertTrue(is_array($result));
        $this->assertEquals(0, count($result));
    }

}
