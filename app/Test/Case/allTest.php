<?php
class AllTestsTest extends PHPUnit_Framework_TestSuite
{

    public static function suite() {

        $path = APP . 'Test' . DS . 'Case' . DS;

        $suite = new CakeTestSuite('All tests');
        $suite->addTestDirectory($path . 'Controller' . DS);
        $suite->addTestDirectory($path . 'Model' . DS);
        $suite->addTestDirectory($path . 'View/Helper' . DS);
        return $suite;

    }

}