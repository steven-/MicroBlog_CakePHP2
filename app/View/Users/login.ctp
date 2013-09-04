<?php echo $this->Form->create('User', array('novalidate')); ?>

<fieldset>
    <legend>Sign In</legend>
    <?php if (isset($signInFail)): ?>
        <div class="alert-error">Bad credentials</div>
    <?php endif ?>
    <?php
        echo $this->Form->input('username');
        echo $this->Form->input('password');
    ?>
</fieldset>
<?php
    echo $this->Form->end('Sign In'); // close the form and add a Submit button since we are passing a String parameter
?>