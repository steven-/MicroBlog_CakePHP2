<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */



Router::connect('/', array('controller' => 'messages', 'action' => 'index'));
Router::connect('/message/new', array('controller' => 'messages', 'action' => 'add'));
Router::connect('/message/delete/:id',
                array(
                    'controller' => 'messages',
                    'action' => 'delete'
                ),
                array(
                    'id' => '[0-9]+',
                    'pass' => array('id')
                ));

Router::connect('/user', array('controller' => 'users', 'action' => 'index'));
Router::connect('/user/:username',
                array(
                    'controller' => 'users',
                    'action' => 'profile',
                ),
                array(
                    'username' => '(?!login|logout|register|edit)[A-Za-z0-9_-]+',
                    'pass' => array('username') // give this parameter to the controller method
                )
);
Router::connect('/user/:action', array('controller' => 'users'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
