<?php


class MessagesController extends AppController
{

    // we need the Session component to save Flash messages
    public $components = array('Session');


	public function beforeFilter()
    {
        parent::beforeFilter();
        // Since we are using the Auth component, CakePHP considers each action as reserved to authenticated users.
        // So we need to allow our public actions:
        $this->Auth->allow('index');
	}


    public function isAuthorized($user)
    {
        // This method allows us to have a different way of checking authorizations
        // since we set the key 'authorize' to 'Controller' in the Auth component options
        // cf (AppController)

        // So here, before the delete method is called, we check that the user who
        // asked for the deletion is the author of the message.
        if ($this->action === 'delete') {
            $messageId = $this->request->params['id'];
            return $this->Message->isOwnedBy($messageId, $user['id']);
        }

        return parent::isAuthorized($user);
    }



/******************************************************************************/
/***                               ACTIONS                                *****/
/******************************************************************************/


    /**
     * INDEX
     *
     * List all messages in reversed chronological order.
     */
	public function index()
	{
        $messages = $this->Message->find('all', array(
            'order' => array('Message.created DESC')
        ));
        $this->set('messages', $messages); // view vars
	}


    /**
     *  ADD
     *
     * If it's a get request, we don't do anything and CakePHP renders the 'add' view directly
     *
     * If it's a post request, we trigger the record of the message.
     * If it's ok, we set a flash message to the session and redirect to home;
     * Otherwise, the view will be rendered with form errors.
     *
     */
	public function add()
	{
    	if ($this->request->is('post')) {
           // set the user_id of authenticated user to the data
            $this->request->data['Message']['user_id'] = $this->Auth->user('id');

            if($this->Message->save($this->request->data)) {
                $this->Session->setFlash('Your message has been posted',
                                         'default',
                                         array('class' => 'alert-success'));

                return $this->redirect(array(
                    'controller' => 'messages',
                    'action' => 'index'
                ));
            }
    	}
	}

    /**
     * DELETE
     *
     * If it's a GET request, we don't do anything, and the 'delete' view is
     * rendered (with a confirmation form).
     *
     * If it's a POST request, we delete the message, save a flash message in
     * the session and redirect to home.
     *
     * A message can only be deleted by his author cf isAuthorized() method.
     */
	public function delete($id)
	{
        $message = $this->Message->findById($id);

        if ( ! $message) {
            throw new NotFoundException('Message not found');
        }

        if ($this->request->is('post')) {
            if ($this->Message->delete($id)) {
                $this->Session->setFlash('Your message has been deleted',
                                         'default',
                                         array('class' => 'alert-info'));

                return $this->redirect(array(
                    'controller' => 'messages',
                    'action' => 'index'
                ));
            }
        }

        $this->set('message', $message); // view vars
	}
}