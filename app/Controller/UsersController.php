<?php

class UsersController extends AppController
{

	public function beforeFilter()
	{
		parent::beforeFilter();
		// Since we are using the Auth component, CakePHP consider each action as reserved to authenticated users.
		// So we need to allow our public actions:
		$this->Auth->allow('index', 'profile', 'login', 'register');
	}



/******************************************************************************/
/***                               ACTIONS                                *****/
/******************************************************************************/

	/**
	 * INDEX
	 *
	 * List all registered users
	 */
	public function index()
	{
		$this->set('users', $this->User->find('all')); //  view vars
	}



	/**
	 * PROFILE
	 *
	 * Get one user's credentials according to his username
	 */
	public function profile($username)
	{
		$user = $this->User->findByUsernameWithMessages($username);
		if ( ! $user) {
			throw new NotFoundException('User not found');
		}
		$this->set('user', $user); // view vars
	}



	/**
	 * EDIT
	 *
	 * Get user's data and set it to the view if it's a GET request
	 *
	 * Trigger the update of the user's profile and redirect to home if it's a POST request
	 */
	public function edit()
	{
		$user = $this->User->findById($this->Auth->user('id'));

		if ($this->request->is('post') || $this->request->is('put')) {
		  // we set the id because we are not creating a user, just updating it.
		  $this->User->id = $user['User']['id'];
		  // we also set the current avatar, because if there is a new one,
		  // we have to delete the existing one before. (cf Model/User);
		  $this->User->avatar = $user['User']['avatar'];

		  if ($this->User->save($this->request->data)) {
		    return $this->redirect(array('action' => 'profile', 'username' =>  $user['User']['username']));
		  }
		}

		if ( ! $this->request->data) {
			// will fill the form in the view (for GET requests)
			$this->request->data = $user;
		}

		$this->set('user', $user); // view vars
	}



	/**
	 * LOGIN
	 *
	 * Try to log a user in.
	 * Redirect to home if successful authentication.
	 * Set a 'signInFail' var to the view if bad credentials.
	 */
	public function login()
	{
		if ($this->request->is('post')) {

			// If no parameter passed to login(), CakePHP automatically give the request params as parameters.
			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirectUrl());
			}
			else {
				$this->set('signInFail', true); // view vars
			}
		}
	}


	/**
	 * LOGOUT
	 *
	 * Log a user out and redirect (to home, cf Auth component options in AppController).
	 */
	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}


	/**
	 * REGISTER
	 *
	 * If it's a GET request, we don't do anything so CakePHP directly renders the view.
	 *
	 * If it's a POST request, we create a new User and try to save it.
	 *	  If everything is ok, we log the user in and redirect to home
	 */
	public function register()
	{
		if ($this->request->is('post')) {
			$this->User->create();

			// CakePHP automatically call $this->User->validates() for us before saving it.
			if ($this->User->save($this->request->data)) {
				// we set the id of our newly created User to the request->data params
				$this->request->data['User']['id'] = $this->User->id;
				// so we can log in directly. (We don't use $this->User because the password is now encrypted so it would not match).
				$this->Auth->login($this->request->data['User']);
				return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
			}
		}
	}
}