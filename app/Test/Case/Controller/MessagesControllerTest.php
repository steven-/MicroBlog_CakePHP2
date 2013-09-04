<?php

App::uses('AppControllerTestCase', 'Test/Parent/Controller');

class MessagesControllerTest extends AppControllerTestCase
{

    public function mockController($components = array())
    {
        $this->controller = $this->generate('Messages', array(
            'components' => $components
        ));
    }



    //----------------------------------------------------
    //  INDEX
    //----------------------------------------------------

    public function testIndex()
    {
        $this->testAction('/', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertArrayHasKey('messages', $this->vars);
        $this->assertArrayHasKey('Message', $this->vars['messages'][0]);
        $this->assertArrayHasKey('User', $this->vars['messages'][0]);

        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }



    //----------------------------------------------------
    //  ADD
    //----------------------------------------------------

    public function testAddGet()
    {
        $this->mockController(array('Auth'));

        $this->testAction('/message/new', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertContains('<form', $this->view);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    public function testAddPost()
    {
        $this->mockController(array('Security', 'Auth', 'Session'));

        // valid data -> should set a flash message and redirect to home
        $this->controller->Auth->staticExpects($this->once())
                               ->method('user')
                               ->with('id')
                               ->will($this->returnValue(1));

        $this->controller->Session
                         ->expects($this->once())
                         ->method('setFlash');

        $this->testAction('/message/new', array(
            'data' => array('Message' => array(
                'message' => 'Hello world !'
            ))
        ));
        $this->assertArrayHasKey('Location', $this->headers);
    }


    public function testAddPostBadData()
    {
        $this->mockController(array('Security', 'Auth'));

        $this->controller->Auth->staticExpects($this->once())
                               ->method('user')
                               ->with('id')
                               ->will($this->returnValue(1));

        // bad data -> should return view
        $this->testAction('/message/new', array(
            'return' => 'contents',
            'data' => array('Message' => array())
        ));
        $this->assertContains('<form', $this->view);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    public function testAddNotLoggedIn()
    {
        // user should be redirected to login action
        $this->mockController();
        $this->controller->Auth->logout();

        $this->testAction('/message/new', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertContains('/user/login', $this->headers['Location']);
    }


    //----------------------------------------------------
    //  DELETE
    //----------------------------------------------------

    public function testDeleteGet()
    {
        $this->mockController();
        $User = ClassRegistry::init('User');
        $data = $User->read(null, 1);
        $this->controller->Auth->login($data['User']);

        // someone try to delete one of his message -> ok, confirmation form view
        $this->testAction('/message/delete/1', array(
            'method' => 'get',
            'return' => 'contents'
        ));
        $this->assertContains('<form', $this->view);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);


        // someone try to delete a message of another user -> not ok, redirect to home
        $this->testAction('/message/delete/3', array(
            'method' => 'get'
        ));
        $this->assertArrayHasKey('Location', $this->headers);
    }


    public function testDeletePost()
    {
        $this->mockController();

        // delete ok -> set flash msg then redirect
        $this->mockController(array('Auth', 'Security', 'Session'));

        $this->controller->Session
                         ->expects($this->once())
                         ->method('setFlash');

        $this->testAction('/message/delete/1');
        $this->assertArrayHasKey('Location', $this->headers);
    }


    public function testDeleteNotFound()
    {
        // Deleting a message which does not exist -> NotFoundException
        $this->mockController(array('Auth'));

        try {
            $this->testAction('/message/delete/1647687168768786787', array(
                'method' => 'get'
            ));
        }
        catch (NotFoundException $expected) {
            return;
        }
        $this->fail('An expected NotFoundException has not been raised.');
    }


    public function testDeleteNotLoggedIn()
    {
        // user should be redirected to login action
        $this->mockController();
        $this->controller->Auth->logout();

        $this->testAction('/message/delete/1', array(
            'method' => 'get'
        ));

        // $this->assertContains('/user/login', $this->headers['Location']);
    }
}