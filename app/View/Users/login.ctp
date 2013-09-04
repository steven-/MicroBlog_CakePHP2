<?php echo $this->Form->create('User', array('novalidate')); ?>

<fieldset>
<legend>Sign In</legend>
<?php if (isset($signInFail)): ?>
    <div class="alert-error">Bad credentials</div>
<?php endif ?>
</fieldset>

<?php
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->end('Sign In');
?>