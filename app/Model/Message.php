<?php

class Message extends AppModel
{
    public $belongsTo = 'User';


    public $validate = array(
        'message' => array(
            'Not Empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Your message cannot be empty',
                'last' => true
            ),
            'Max Length' => array(
    			 'rule' => array('maxlength', 140),
    			 'message' => 'The message may not be greater than 140 characters',
    			 'last' => true
            )
        )
    );

    public function isOwnedBy($message, $user) {
        return $this->field('id', array('id' => $message, 'user_id' => $user)) === $message;
    }
}