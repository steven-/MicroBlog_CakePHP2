<?php

App::uses('AppController', 'Controller');

class AppControllerTestCase extends ControllerTestCase
{

    public $fixtures = array('app.user', 'app.message');

    public $layoutBackboneRegExp = '/html.*head.*\/head>\s*<body.*<header>.*<\/header>\s*<nav>.*\/nav>\s*<section>.*<\/section.*<\/body>\s*<\/html/s';


    protected static $_sessionBackup;

    public static function setupBeforeClass()
    {
        self::$_sessionBackup = Configure::read('Session');
        Configure::write('Session', array(
            'defaults' => 'php',
            'timeout' => 100,
            'cookie' => 'test' . uniqid()
        ));
    }

    public function setUp()
    {
        parent::setUp();
    }

    public static function teardownAfterClass() {
        Configure::write('Session', self::$_sessionBackup);
    }
}