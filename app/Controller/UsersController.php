<?php

class UsersController extends AppController
{
	public $components = array('Session', 'Security');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'profile', 'login', 'register');
	}



/******************************************************************************/
/***                               ACTIONS                                *****/
/******************************************************************************/

	public function index()
	{
		$this->set('users', $this->User->find('all'));
	}



	public function profile($username)
	{
		$user = $this->User->findByUsernameWithMessages($username);
		if ( ! $user) {
			throw new NotFoundException('User not found');
		}
		$this->set('user', $user);
	}


	public function edit()
	{
		$user = $this->User->findById($this->Auth->user('id'));

		if ($this->request->is('post') || $this->request->is('put')) {
		  $this->User->id = $user['User']['id'];
		  $this->User->avatar = $user['User']['avatar'];
		  if ($this->User->save($this->request->data)) {
		    return $this->redirect(array('action' => 'profile', $user['User']['username']));
		  }
		}

		if( ! $this->request->data) {
			$this->request->data = $user;
		}

		$this->set('user', $user);
	}




	public function login()
	{
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			else {
				$this->set('signInFail', true);
			}
		}
	}


	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}





	public function register()
	{
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->request->data['User']['id'] = $this->User->id;
				$this->Auth->login($this->request->data['User']);
				return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
			}
		}
	}
}