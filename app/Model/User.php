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
        // We could set a 'hasMany' property to the model.
        // But we need to grab users with them messages only once in the app.
        // So we use this method to set this property dynamically when we need it
        // ( !! it's "active" for only one db query).
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
        ),
        'password' => array(
            'rule' => 'notEmpty',
            'message' => 'Please choose a password',
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
            'message' => 'The bio may not be greater than 160 characters',
            'required' => 'update'
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



    // Framework callback method
    public function beforeValidate($options = array())
    {
        parent::beforeValidate();

        // If no file choosen for the avatar, the validator will try to check
        // a file that does not exist and will fail. So we delete the file key from
        // the request data to prevent it.

        if ($this->id) {
            if ( empty($this->data['User']['file']['tmp_name'])
                 || ! is_uploaded_file($this->data['User']['file']['tmp_name']))
            {
                unset($this->data['User']['file']);
            }
        }
        return true;
    }



    // Framework callback method
    public function beforeSave($options = array())
    {
        if ( ! $this->id) { // create
            // When we create a new user we hash his password before saving it into the database.
            $this->data['User']['password'] = Security::hash($this->data['User']['password'],'blowfish');
        }
        else { // update

            if (isset($this->data['User']['file'])) {
                // if the user wants a new avatar, we have to delete the old one (if there is one).
                if ($this->avatar) {
                    $this->previousFile = $this->avatar; // keep track of the old file before updating the value
                }

                // we get the file extension, it's the only thing we need to save in the db
                $this->file = $this->data['User']['file'];
                $filename = basename($this->file['name']);
                $extension = array_pop((explode('.', $filename)));
                $this->data['User']['avatar'] = $extension;
            }
            else $this->file = null;
        }
        return true;
    }


    // Framework callback method
   public function afterSave($created)
   {
        if ( ! $created) { // update
        // Now that the user has been updated in the db
            if ($this->file) {

                // we eventually delete the old avatar
                if ($this->previousFile) {
                    $previousFileName = $this->getAvatarsDir() . $this->id . '.' . $this->previousFile;
                    if (is_file($previousFileName)) {
                        unlink($previousFileName);
                    }
                }

                // we move the new one in the /avatars directory
                $filename = $this->id . '.' . $this->data['User']['avatar'];
                move_uploaded_file(
                    $this->file['tmp_name'],
                    $this->getAvatarsDir() . $filename
                );

                // we create a thumbnail
                $resizer = new ImageResizer;
                $resizer->crop($this->getAvatarsDir() . $filename, 50);
            }
        }
   }




   /**
    * SAME AS
    *
    * Compare two form fields data and check that both are equal.
    * This method is used to validate the user (on create) since we registered
    * it as a validator (cf array validates -> password_confirmation)
    *
    * @param Array $check
    * @param String $otherField
    * @return Boolean
    */
    public function sameAs($check, $otherField)
    {
        $formValues = $this->data[$this->name];
        if ( ! isset($formValues[$otherField])) {
            return false;
        }
        return $formValues[$otherField] === $formValues[key($check)];
    }

    // used for avatar upload
    private function getAvatarsDir()
    {
        return WWW_ROOT . 'avatars' . DS;
    }
}