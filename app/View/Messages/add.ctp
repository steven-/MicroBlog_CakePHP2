<?php
	echo $this->Form->create('Message', array( // Open the form
		'novalidate', // disable HTML5 validation
		'inputDefaults' => array( // specify the order in wich we want the elements to be displayed
			'format' => array('label', 'error', 'input'),
			'div' => false,
			'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error'))
		),
	));

	echo $this->Form->input('message', array('type' => 'textarea', 'label' => 'New message'));

	echo $this->Form->end('Send'); // close the form and add a Submit button since we are passing a String parameter

 ?>

