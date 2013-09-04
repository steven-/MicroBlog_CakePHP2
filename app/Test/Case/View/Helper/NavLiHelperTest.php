<?php

App::uses('View', 'View');
App::uses('NavLiHelperMock', 'Test/Mock/Helper');


class NavLiHelperTest extends CakeTestCase
{
    public function setUp()
    {
        parent::setUp();
        // We mock the helper so we don't depend on the router to construct our links url
        $this->NavLi = new NavLiHelperMock(new View);
    }


    public function testCreateNotActive()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages'
        );

        // basic List Item
        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li><a href="/">Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }


    public function testCreateActive()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages'
        );

        // "Active" List Item (params match the request params)
        $this->NavLi->request->params = array(
            'controller' => 'messages',
            'action'     => 'index'
        );
        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li class="active"><a href="/">Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }


    public function testNotActiveButOtherClass()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages',
            null,
            'other'
        );

        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li class="other"><a href="/">Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }


    public function testActiveWithOtherClass()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages',
            null,
            'other'
        );

        // "Active" List Item (params match the request params)
        $this->NavLi->request->params = array(
            'controller' => 'messages',
            'action'     => 'index'
        );
        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li class="active other"><a href="/">Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }


    public function testIcon()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages',
            'quote-right',
        );

        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li><a href="/"><i class="icon-quote-right"></i>Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }


    public function testAlternativeActiveConditions()
    {
        $params =  array(
            array(
                'controller' => 'messages',
                'action'     => 'index'
            ),
            'Messages',
            null,
            null,
            array(
                'controller' => 'users',
                'action' => 'edit'
            )
        );

        $this->NavLi->request->params = array(
            'controller' => 'users',
            'action'     => 'edit'
        );

        $listItem = call_user_func_array(array($this->NavLi, 'create'), $params);
        $expected = '<li class="active"><a href="/">Messages</a></li>';
        $this->assertEquals($expected, $listItem);
    }




}