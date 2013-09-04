<?php echo $this->Form->create('User', array(
    'novalidate',
    'type' => 'file',
    'inputDefaults' => array( // specify the order in wich we want the elements to be displayed
        'format' => array('label', 'error', 'input'),
        'div' => false,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error'))
    )));
?>

<fieldset>
    <legend>Edit your profile</legend>

    <?php
        echo $this->Form->label('avatar');
        echo $this->Avatar->display($user['User']);
        echo $this->Form->input('file', array('label' => false, 'type' => 'file'));

        echo $this->Form->input('bio', array('type' => 'textarea', 'label' => 'Bio'));
    ?>
</fieldset>

<?php echo $this->Form->end('Save'); ?>