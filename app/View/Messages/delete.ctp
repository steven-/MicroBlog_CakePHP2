<h2>Delete this message ?</h2>

<div>
    <span>
        <?php echo $message['Message']['created'] ?>
    </span>
</div>
<?php echo $this->Avatar->display($message['User']); // cf View/Helper ?>
<strong>
    <?php echo $message['User']['username'] ?>
</strong>
<q>
    <?php echo $message['Message']['message'] ?>
</q>


<?php
    echo $this->Html->Link('Cancel', array('controller' => 'messages', 'action' => 'index'));
    echo $this->Form->create('Message');
    echo $this->Form->end('Delete'); // close the form and add a Submit button since we are passing a String parameter
?>