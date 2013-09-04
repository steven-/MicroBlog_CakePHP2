<?php

App::uses('AppControllerTestCase', 'Test/Parent/Controller');


class UsersControllerTest extends AppControllerTestCase
{

    public function mockController($components = array())
    {
        $this->controller = $this->generate('Users', array(
            'components' => $components
        ));
    }

    // ----------------------------------------------------
    //  INDEX
    // -----------------------------------------------------


    public function testIndex()
    {
        $this->testAction('/user', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertArrayHasKey('users', $this->vars);
        $this->assertArrayHasKey('User', $this->vars['users'][0]);
        $this->assertArrayNotHasKey('Message', $this->vars['users'][0]);
        $this->assertRegExp('/(<li>.*<\/li>\s*){4}/s', $this->view);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    // //----------------------------------------------------
    // //  PROFILE
    // //-----------------------------------------------------



    public function testProfile()
    {
        $this->testAction('/user/Abraham_Lincoln', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertArrayHasKey('user', $this->vars);
        $this->assertArrayHasKey('User', $this->vars['user']);
        $this->assertArrayHasKey('Message', $this->vars['user']);
        $this->assertEquals('Abraham_Lincoln', $this->vars['user']['User']['username']);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    public function testProfileNotFound()
    {
        try {
            $this->testAction('/user/doesnotExist', array(
                'method' => 'get'
            ));
        }
        catch (NotFoundException $expected) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }



    // //----------------------------------------------------
    // //  EDIT
    // //-----------------------------------------------------

    public function testEditGet()
    {
        $this->mockController(array('Auth'));
        $this->controller->Auth->staticExpects($this->once())->method('user')
                ->with('id')
                ->will($this->returnValue(1));

        $this->testAction(
            '/user/edit',
            array(
                'method' => 'get',
                'return' => 'contents'
            )
        );

        $this->assertArrayHasKey('user', $this->vars);
        $this->assertContains('Edit your profile', $this->view);
        $this->assertContains('<form', $this->view);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    public function testEditPostSuccess()
    {
        $this->mockController(array('Auth', 'Security'));
        $this->controller->Auth->staticExpects($this->once())
                                ->method('user')
                                ->with( 'id' )
                                ->will($this->returnValue(1));

        $this->testAction('/user/edit', array(
            'return' => 'contents',
            'data' => array('User' => array(
                'bio' => 'Abraham new bio'
            ))
        ));

        $this->assertContains('/user/Abraham_Lincoln', $this->headers['Location']);
    }


    public function testEditPostBadData()
    {
        $this->mockController(array('Auth', 'Security'));
        $this->controller->Auth->staticExpects($this->once())
                                ->method('user')
                                ->with( 'id' )
                                ->will($this->returnValue(1));

        $this->testAction('/user/edit', array(
            'return' => 'contents',
            'data' => array('User' => array())
        ));

        $this->assertContains('Edit your profile', $this->view);
        $this->assertContains('<form', $this->view);
    }


    public function testEditNotLoggedIn()
    {
        // user should be redirected to login action
        $this->mockController();
        $this->controller->Auth->logout();

        $this->testAction('/user/edit', array('method' => 'get'));

        $this->assertContains('/user/login', $this->headers['Location']);
    }

    // //----------------------------------------------------
    // //  LOGIN
    // //----------------------------------------------------


    public function testLoginGet()
    {
        $this->testAction('/user/login', array(
            'method' => 'get',
            'return' => 'contents'
        ));

        $this->assertContains('<form', $this->view);
        $this->assertContains('Sign In', $this->view);
        $this->assertNotContains('Bad credentials', $this->view);
        $this->assertArrayNotHasKey('signInFail', $this->vars);
        $this->assertRegExp($this->layoutBackboneRegExp, $this->contents);
    }


    public function testLoginPostFail()
    {
        $this->mockController(array('Security'));
        $this->controller->Auth->logout();

        $this->testAction('/user/login',
            array(
                'data' => array(
                    'username' => 'Does_not_exist',
                    'password' => 'pass'
                ),
                'return' => 'contents'
            )
        );

        $this->assertFalse($this->controller->Auth->loggedIn());
        $this->assertArrayHasKey('signInFail', $this->vars);
        $this->assertContains('<form', $this->view);
        $this->assertContains('Sign In', $this->view);
        $this->assertContains('Bad credentials', $this->view);
    }



    public function testLoginPostSuccess()
    {
        $this->mockController(array('Security'));
        $this->controller->Auth->logout();

        $this->testAction('/user/login',
            array(
                'data' => array('User' => array(
                    'username' => 'Abraham_Lincoln',
                    'password' => 'pass'
                ))
            )
        );

        $this->assertTrue($this->controller->Auth->loggedIn());
        $this->assertArrayHasKey('Location', $this->headers);
        $this->assertNotContains('/user/login', $this->headers['Location']);
    }


    //----------------------------------------------------
    //  LOGOUT
    //----------------------------------------------------
    public function testLoginThenLogout()
    {
        // login
        $this->testAction('/user/login',
            array(
                'data' => array('User' => array(
                    'username' => 'Abraham_Lincoln',
                    'password' => 'pass'
                ))
            )
        );
        $this->assertTrue($this->controller->Auth->loggedIn());

        //logout
        $this->testAction('/user/logout');

       $this->assertFalse($this->controller->Auth->loggedIn());
        $this->assertArrayHasKey('Location', $this->headers);
        $this->assertNotContains('/user/logout', $this->headers['Location']);
    }



    // //----------------------------------------------------
    // //  REGISTER
    // //----------------------------------------------------

    public function testRegisterGet()
    {
        $this->testAction('/user/register',
            array('method' => 'get', 'return' => 'contents')
        );

        $this->assertContains('<form', $this->view);
        $this->assertContains('Sign Up', $this->view);
    }



    public function testRegisterPostBadData()
    {
        $this->mockController(array('Security'));

        $this->testAction('/user/register', array('return' => 'view'));

        $this->assertContains('<form', $this->view);
        $this->assertContains('Sign Up', $this->view);
    }



    public function testRegisterPostSuccess()
    {
        $this->mockController(array('Security'));

        $this->testAction('/user/register',
            array(
                'data' => array('User' => array(
                    'username' => 'New_User',
                    'password' => 'pass',
                    'password_confirmation' => 'pass'
                ))
            )
        );

        $this->assertArrayHasKey('Location', $this->headers);
        $this->assertNotContains('/user/register', $this->headers['Location']);
        $this->assertEquals('New_User', $this->controller->Auth->user('username'));
    }
}