<?php

App::uses('AppHelper', 'View/Helper');

class AvatarHelper extends AppHelper {
    public function display($user)
    {
        $imgSrc = empty($user['avatar'])
                  ? 'default_avatar.png'
                  : $user['id'] . '.' . $user['avatar'];

        return '<img src="' . $this->webroot . 'avatars/' . $imgSrc . '" alt="" />';
    }
}