<?php

class Message extends AppModel
{
    // By setting this property, each time we will grab message into the db,
    // cake php will associate them with their user and return an array like this :
    // $message = array('Message' = > ..., 'User' =>  ....)
    public $belongsTo = 'User';


    public $validate = array(
        'message' => array(
            'Not Empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Your message cannot be empty',
                'last' => true,
                'required' => true
            ),
            'Max Length' => array(
    			 'rule' => array('maxlength', 140),
    			 'message' => 'The message may not be greater than 140 characters',
    			 'last' => true
            )
        ),
        'user_id' => array(
            'rule' => 'notEmpty',
            'required' => true
        )
    );

    // cf MessageController isAuthorized() method.
    public function isOwnedBy($messageId, $userId)
    {
        return $this->field('id', array('id' => $messageId, 'user_id' => $userId)) === $messageId;
    }
}