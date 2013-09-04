<?php

App::uses('AppHelper', 'View/Helper');



/**
 * Help to build list items for the app menu
 */
class NavLiHelper extends AppHelper
{

    public $helpers = array('Html');

    /**
     * CREATE
     *
     * The main goal of this helper is to set the 'active' class automatically to the list item
     * when the current request params match the link params.
     * If it does not match, this helper also check if the current request params match another set
     * of params ($alternateActiveConditions) if provided.
     * It's useful in this app for the 'User' tab which has to be active even if the user is editing
     * his profile (when the link of this tab does not lead to the edit form but the user's profile).
     *
     *
     * @param array $fragments - route parameters (controller, action, extras)
     * @param string $text - text to be displayed in the anchor tag
     * @param string $icon - icon name if there is one for this list item
     * @param string $class - additional class (e.g. 'connection')
     * @param array $alternateActiveConditions - route parameters (controllers, ...)
     * @return String
     */
    public function create($fragments, $text, $icon = null, $class = null, $alternateActiveConditions = null)
    {
        $li  = '<a href="' . $this->Html->url($fragments) . '">';
        if ($icon) $li .= '<i class="icon-' . $icon . '"></i>';
        $li .= $text;
        $li .= '</a>';

        if ($this->isActive($fragments, $alternateActiveConditions)) {
            $class  = $class
                      ?  'active ' . $class
                      :  'active';
        }
        if (strlen($class)) $class = ' class="' . $class . '"';

        $li = '<li' . $class . '>' . $li . '</li>';

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