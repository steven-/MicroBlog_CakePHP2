<?php

App::uses('View', 'View');
App::uses('AvatarHelper', 'View/Helper');

class AvatarHelperTest extends CakeTestCase
{

    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->Avatar = new AvatarHelper($View);
    }


    public function testDisplay()
    {
        $user = array('avatar' => 'jpg', 'id' => 2);
        $result = $this->Avatar->display($user);
        $this->assertContains('2.jpg', $result);

        $user = array('avatar' => null);
        $result = $this->Avatar->display($user);
        $this->assertContains('default_avatar.png', $result);

        $user = array();
        $result = $this->Avatar->display($user);
        $this->assertContains('default_avatar.png', $result);
    }
}
