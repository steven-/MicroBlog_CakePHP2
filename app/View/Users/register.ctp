<?php
	echo $this->Form->create('User', array( // Open the form
		'novalidate', // disable HTML5 validation
		'inputDefaults' => array( // specify the order in wich we want the elements to be displayed
				'format' => array('label', 'error', 'input'),
				'div' => false,
				'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error'))
		),
	));

	echo $this->Form->inputs(array(
		'legend' => 'Sign Up',
		'username',
		'password',
		'password_confirmation' => array('label' => 'Confirm Password', 'type' => 'password')
	));

	echo $this->Form->end('Sign Up'); // close the form and add a Submit button since we are passing a String parameter

 ?>

