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
        
        $currentPage = 1;

        /*
         * $condition = [];
         */
        $condition = [];
        echo "condition=";
        var_dump($condition);
        $result = ${$className}->get($condition, $currentPage);
        var_dump($result);

        $this->assertTrue( is_array($result) );
        $this->assertNotEquals(0, count($result) );


        /*
         * $condition = ['{$table}.id'=>1];
         */
        $condition = ['{$table}.id'=>1];
        echo "condition=";
        var_dump($condition);
        $result = ${$className}->get($condition, $currentPage);
        var_dump($result);

        $this->assertTrue( is_array($result) );
        $this->assertNotEquals(0, count($result) );
    }

}
