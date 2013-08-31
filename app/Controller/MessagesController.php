<?php


class MessagesController extends AppController
{

	public $components = array('Session');


	public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('index');
	}


    public function isAuthorized($user)
    {
        // The owner of a message can delete it
        if ($this->action === 'delete') {
            $messageId = $this->request->params['pass'][0];
            return $this->Message->isOwnedBy($messageId, $user['id']);
        }

        return parent::isAuthorized($user);
    }



/******************************************************************************/
/***                               ACTIONS                                *****/
/******************************************************************************/


	public function index()
	{
	   $this->set('messages', $this->Message->find('all', array('order' => array('Message.created DESC'))));
	}



	public function add()
	{
    	if ($this->request->is('post')) {
    		$this->Message->create(array(
                'user_id' => $this->Auth->user('id')
            ));
            if($this->Message->save($this->request->data)) {
                $this->Session->setFlash('Your message has been posted', 'default', array('class' => 'alert-success'));
                return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
            }
    	}
	}


	public function delete($id)
	{
        $message = $this->Message->findById($id);
        if( ! $message) {
            throw new NotFoundException('Message not found');
        }

        // $loggedUserId = $this->Auth->user('id');
        // if ( $loggedUserId !== $message['User']['id']) {
        //     throw new ForbiddenException('You are not the author of this message !');
        // }

        if ($this->request->is('post')) {
            if ($this->Message->delete($id)) {
                $this->Session->setFlash('Your message has been deleted', 'default', array('class' => 'alert-info'));
                return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
            }
            else {
                throw new InternalErrorException('An error occured');
            }
        }

        $this->set('message', $message);
	}
}