<?php

App::uses('AppHelper', 'View/Helper');

class NavLiHelper extends AppHelper
{

    public $helpers = array('Html');

    /**
     * @param fragments: array of route parameters (controller, action, extras)
     * @param $text: text to be displayed in the anchor tag
     * @param $icon: icon name if there is one for this list item
     * @param $class: additional class (e.g. 'connection')
     * @param $alternateActiveConditions: array of route parameters (controllers, ...)
     * @return String
     */
    public function create($fragments, $text, $icon = null, $class = '', $alternateActiveConditions = null)
    {
        $li  = '<a href="' . $this->Html->url($fragments) . '" >';
        if ($icon) $li .= '<i class="icon-' . $icon . '"></i>';
        $li .= $text;
        $li .= '</a>';

        if ($this->isActive($fragments, $alternateActiveConditions)) $class = 'active ' . $class;
        if (strlen($class)) $class = 'class="' . $class . '"';

        $li = '<li ' . $class . '>' . $li . '</li>';

        return $li;
    }


    private function isActive($fragments, $alternateActiveConditions = null)
    {
        foreach ($fragments as $key => $value) {
            if (
                ! isset($this->request->params[$key])
                || ($this->request->params[$key] !== $value)
            ) {
                if ($alternateActiveConditions)
                    return $this->isActive($alternateActiveConditions);
                else return false;
            }
        }
        return true;
    }



}