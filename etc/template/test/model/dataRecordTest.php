<?php
require_once __DIR__.'../../../../lib/autoload.php';
$debugConsole = new Console;
global $dataBridge;
$dataBridge->dispatch_trace = true;
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

        /*
         * test each data from dataProvider
         */
        foreach ($_->dataProvider() as $data) {
            echo "data=";
            var_dump($data);
            $result = ${$className}->set($data);
            echo "result=";
            var_dump($result);
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

        // Make sure to fit test for each column type
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
        
        /*
         * $condition = [];
         */
        $condition = [];
        echo "condition=";
        var_dump($condition);
        $result = ${$className}->get($condition);
        var_dump($result);

        $this->assertTrue( is_array($result) );
        $this->assertNotEquals(0, count($result) );

        /*
         * $condition = ['{$table}.id'=>1];
         */
        $condition = ['{$table}.id'=>1];
        echo "condition=";
        var_dump($condition);
        $result = ${$className}->get($condition);
        var_dump($result);

        $this->assertTrue( is_array($result) );
        $this->assertNotEquals(0, count($result) );

    }

}
