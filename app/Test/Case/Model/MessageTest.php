<?php

class MessageTest extends CakeTestCase
{
    public $fixtures = array('app.user', 'app.message');

    public function setUp()
    {
        parent::setUp();
        $this->Message = ClassRegistry::init('Message');
    }


    public function testIsOwnedBy()
    {
        $result = $this->Message->isOwnedBy('1', '1');
        $this->assertTrue($result);

        $result = $this->Message->isOwnedBy('1', '2');
        $this->assertFalse($result);
    }


    public function testBelongsTo()
    {
        $message = $this->Message->find('first');
        $this->assertArrayHasKey('User', $message);
        $this->assertEquals($message['Message']['user_id'], $message['User']['id']);
    }


    public function testValidate()
    {

        $invalidMessages = array(
            array(
            // cannot be empty
                'message' => '',
                'user_id' => 1
            ),
            array(
            // cannot be greater than 140 chars
                'message' => '012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567891', // <- 141 chars
                'user_id' => 1
            ),
            array(
                // message required
                'user_id' => 1
            ),
            array(
                // user id required
                'message' => 'hello world'
            )
        );

        // invalid messages
        while ($invalidMessage = array_pop($invalidMessages)) {
            $msg = $this->Message->Create($invalidMessage);
            $this->assertFalse($this->Message->validates());
        }


         // valid message
        $message = array(
            'message' => 'Hello world',
            'user_id' => 1
        );
        $msg = $this->Message->Create($message);
        $this->assertTrue($this->Message->validates());

    }
}