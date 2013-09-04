<?php


class UserTest extends CakeTestCase
{
    public $fixtures = array('app.user', 'app.message');


    public function setUp()
    {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }



    public function testFindByUsernameWithMessages()
    {
        $result = $this->User->findByUsernameWithMessages('Abraham_Lincoln');
        $this->assertArrayHasKey('User', $result);
        $this->assertArrayHasKey('Message', $result);
    }



    public function testValidateCreate()
    {
        $validUser = array(
           'username' => 'Steven',
           'password' => 'pass',
           'password_confirmation' => 'pass'
       );

        $invalidUsers = array(
            array(
                // username already taken
                'username' => 'Abraham_Lincoln',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // bad username format
                'username' => '#Steven',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // bad username format (space)
                'username' => '_ Steven',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // username empty
                'username' => '',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // username too long
                'username' => '0123456789012345678901234567890123456789',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // username missing
                'password' => 'pass',
                'password_confirmation' => 'pass'
            ),
            array(
                // password missing
                'username' => 'Steven',
                'password_confirmation' => 'pass',
            ),
           array(
                // password empty
                'username' => 'Steven',
                'password' => '',
                'password' => 'pass'
            ),
            array(
                // confirmation missing
                'username' => 'Steven',
                'password' => 'pass'
            ),
            array(
                // confirmation does not match password
                'username' => 'Steven',
                'password' => 'pass',
                'password_confirmation' => 'else'
            ),
            array(
                // confirmation does not match password
                'username' => 'Steven',
                'password' => 'pass',
                'password_confirmation' => ''
            ),
        );

        $this->User->Create($validUser);
        $result = $this->User->validates();
        $this->assertTrue($result);


        while ($invalidUser = array_pop($invalidUsers)) {
            $this->User->Create($invalidUser);
            $result = $this->User->validates();
            $this->assertFalse($result);
        }
    }


    public function testValidateUpdate()
    {
        $user = $this->User->find('first');
        $user['User']['bio'] = 'A great bio';

        $this->User->Create($user);
        $this->assertTrue($this->User->validates());

        $user['User']['bio'] = '';
        $this->User->Create($user);
        $this->assertTrue($this->User->validates()); // bio can be empty

        $user['User']['bio'] = '01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890'; // <-- 161 chars
        $this->User->Create($user);
        $this->assertFalse($this->User->validates()); // bio must be lower than 160 chars

        unset($user['User']['bio']);
        $this->User->Create($user);
        $this->assertFalse($this->User->validates()); // bio is required
    }


    public function testSave()
    {
        $saved = $this->User->Save(array(
            'username' => 'Steven',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ));
        $this->assertInternalType('array', $saved);
        $this->assertArrayHasKey('id', $saved['User']);
    }

}