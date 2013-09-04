<?php echo $this->Form->create('User', array(
    'novalidate',
    'type' => 'file',
    'inputDefaults' => array(
        // specify the order in which we want the elements to be displayed
        'format' => array('label', 'error', 'input'),
        // disable 'div' wrapping
        'div' => false,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'error'))
    )));
?>

<fieldset>
    <legend>Edit your profile</legend>
    <?php
        echo $this->Form->label('avatar');
        echo $this->Avatar->display($user['User']); // cf View/Helper
        echo $this->Form->input('file', array('label' => false, 'type' => 'file'));

        echo $this->Form->input('bio', array('type' => 'textarea', 'label' => 'Bio'));
    ?>
</fieldset>

<?php
// close the form and add a Submit button since we are passing a String parameter
echo $this->Form->end('Save');
?>