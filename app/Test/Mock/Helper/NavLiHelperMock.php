<?php

App::uses('NavLiHelper', 'View/Helper');


class Html
{
    public function url()
    {
        return '/';
    }
}



class NavLiHelperMock extends NavLiHelper
{
    public function __construct($view)
    {
        parent::__construct($view);
        $this->Html = new Html;
    }
}