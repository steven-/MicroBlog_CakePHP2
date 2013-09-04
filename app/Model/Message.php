<?php

class Message extends AppModel
{
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

    public function isOwnedBy($messageId, $userId)
    {
        return $this->field('id', array('id' => $messageId, 'user_id' => $userId)) === $messageId;
    }
}