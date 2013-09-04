<?php

// @todo : constraints

App::uses('User', 'Model');
App::uses('Message', 'Model');
App::uses('ClassRegistry', 'Utility');

class AppSchema extends CakeSchema
{

	public function before($event = array())
	{
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}


	public function after($event = array())
	{
		if (isset($event['create'])) {
			switch ($event['create']) {
				case 'users':
					$user = ClassRegistry::init('User');
					$user->create();
					$user->saveMany(
						array('User' =>
							array(
								'username' => 'Abraham_Lincoln',
								'password' => 'pass',
								'password_confirmation' => 'pass',
								'bio' => '16th President of the United States',
								'avatar' => 'jpg'
							),
							array(
								'username' => 'Albert_Einstein',
								'password' => 'pass',
								'password_confirmation' => 'pass',
								'bio' => 'Theoretical physicist who developed the general theory of relativity',
								'avatar' => 'jpg'
							),
							array(
								'username' => 'Edgar_Allan_Poe',
								'password' => 'pass',
								'password_confirmation' => 'pass',
								'bio' => 'Author, poet, editor, and literary critic, considered part of the American Romantic Movement',
								'avatar' => 'jpg'
							),
							array(
								'username' => 'Emily_Dickinson',
								'password' => 'pass',
								'password_confirmation' => 'pass',
								'bio' => 'American poet',
								'avatar' => 'jpg'
							)
						)
					);
					break;
				case 'messages':
					$message = ClassRegistry::init('Message');
					$message->create();
					$message->saveMany(
						array('Message' =>
							array(
							 'message' => 'Give me six hours to chop down a tree and I will spend the first four sharpening the axe',
							 'created' => '2013-05-24 11:00:00',
							 'user_id' => 1
							),
							array(
							 'message' => 'The best thing about the future is that it comes one day at a time',
							 'created' => '2013-08-02 16:38:00',
							 'user_id' => 1
							),
							array(
							 'message' => "When you are courting a nice girl an hour seems like a second.When you sit on a red-hot cinder a second seems like an hour.That's relativity",
							 'created' => '2013-07-07 21:30:00',
							 'user_id' => 2
							),
							array(
							 'message' => 'A question that sometimes drives me hazy: am I or are the others crazy?',
							 'created' => '2013-01-18 15:00:00',
							 'user_id' => 2
							),
							array(
							 'message' => 'I became insane, with long intervals of horrible sanity',
							 'created' => '2013-05-28 20:00:00',
							 'user_id' => 3
							),
							array(
							 'message' => 'All that we see or seem is but a dream within a dream',
							 'created' => '2013-02-21 19:00:00',
							 'user_id' => 3
							),
							array(
							 'message' => 'Forever is composed of nows.',
							 'created' => '2013-04-23 12:00:00',
							 'user_id' => 4
							),
							array(
							 'message' => "I'm nobody, who are you?",
							 'created' => '2013-05-02 17:00:00',
							 'user_id' => 4
							)
						)
					);
					break;
			}
		}
	}




	public $messages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'message' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 140, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'salt' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 32, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'bio' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 160, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'avatar' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);




}
