<?php

// cf App::build in Config/bootstrap :
App::uses('ImageResizer', 'MicroBlog/Images');

class User extends AppModel
{

    private $file;
    private $previousFile;


    public function findByUsernameWithMessages($username)
    {
        $this->bindtoMessageModel();
        return $this->findByUsername($username);
    }

    private function bindToMessageModel()
    {
        $this->bindModel(
            array('hasMany' => array(
                'Message' => array(
                    'className' => 'Message',
                    'order' => 'Message.created DESC'
                )
            ))
        );
    }


    public $validate = array(
        'username' => array(
            'Not Empty' => array(
                'rule' => 'notEmpty',
                'message' => 'The username field is required',
                'last' => true,
                'required' => 'create'
            ),
            'Alpha Num Dash' => array(
                'rule' => '/^[a-z0-9_-]*$/i',
                'message' => 'The username may only contain letters, numbers and dashes',
                'last' => false
            ),
            'Max Length' => array(
                'rule' => array('maxlength', 30),
                'message' => 'The username may not be greater than 30 characters',
                'last' => true
            ),
            'Unique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken'
            ),
            // 'required' => 'create'
        ),
        'password' => array(
            'rule' => 'notEmpty',
            'message' => 'niké de ta race',
            'required' => 'create'
        ),
        'password_confirmation' => array(
            'Same as password' => array(
                'rule' => array('sameAs','password'),
                'message' => 'The password and confirmation do not match.',
                'required' => 'create'
            )
        ),
        'bio' => array(
            'rule' => array('maxlength', 160),
            'message' => 'The bio may not be greater than 160 characters'
        ),
        'file' => array(
            'Mime Type' => array(
                'rule'    => array('mimeType', array('image/jpeg', 'image/gif', 'image/png')),
                'message' => 'Image format not allowed (only jpeg, png and gif)'
            ),
            'Size' => array(
                'rule' => array('fileSize', '<=', '2MB'),
                'message' => 'The avatar file must be less than 2MB'
            )
        )
    );





    public function beforeValidate($options = array())
    {
        parent::beforeValidate();
        if ($this->id) {
            if ( empty($this->data['User']['file']['tmp_name'])
                 || ! is_uploaded_file($this->data['User']['file']['tmp_name']))
            {
                unset($this->data['User']['file']);
            }
        }
        return true;
    }



    public function beforeSave($options = array())
    {
       if ( ! $this->id) { // create
           $this->data['User']['password'] = Security::hash($this->data['User']['password'],'blowfish');
       }
       else { // update
            if (isset($this->data['User']['file'])) {
                if ($this->avatar) {
                    $this->previousFile = $this->avatar;
                }
                $this->file = $this->data['User']['file'];
                $filename = basename($this->file['name']);
                $extension = array_pop((explode('.', $filename)));
                $this->data['User']['avatar'] = $extension;
            }
            else $this->file = null;
       }
       return true;
   }


   public function afterSave($created)
   {
        if ( ! $created) { // update
            if ($this->file) {
                if ($this->previousFile) {
                    $previousFileName = $this->getAvatarsDir() . $this->id . '.' . $this->previousFile;
                    if (is_file($previousFileName)) {
                        unlink($previousFileName);
                    }
                }

                $filename = $this->id . '.' . $this->data['User']['avatar'];
                move_uploaded_file(
                    $this->file['tmp_name'],
                    $this->getAvatarsDir() . $filename
                );
                $resizer = new ImageResizer;
                $resizer->crop($this->getAvatarsDir() . $filename, 50);
            }
        }
   }



    public function sameAs($check, $otherField)
    {
        $formValues = $this->data[$this->name];
        return $formValues[$otherField] === $formValues[key($check)];
    }


    private function getAvatarsDir()
    {
        return WWW_ROOT . 'avatars' . DS;
    }
}