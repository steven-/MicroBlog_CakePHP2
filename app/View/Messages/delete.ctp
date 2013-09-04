
    <h2>Delete this message ?</h2>


   <div>
        <span><?php echo $message['Message']['created'] ?></span>
    </div>
    <?php echo $this->Avatar->display($message['User']); ?>
    <strong><?php echo $message['User']['username'] ?></strong>
    <q><?php echo $message['Message']['message'] ?></q>


    <?php
        echo $this->Html->Link('Cancel', array('controller' => 'messages', 'action' => 'index'));
        echo $this->Form->create('Message');
        echo $this->Form->end('Delete');
    ?>