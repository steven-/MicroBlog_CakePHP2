<?php
 // pr($messages);
?>
<ul id="messages">
    <?php foreach ($messages as $message): ?>
    <li>
	   <div>
            <span><?php echo $message['Message']['created'] ?></span>
            <?php if (AuthComponent::user('username') === $message['User']['username']): ?>
			<a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'delete', 'id' => $message['Message']['id'])); ?>">
                Delete
                <i class="icon-remove"></i>
            </a>
            <?php endif ?>
        </div>
        <!-- <img src="/avatars/1.jpg" alt="" /> -->
        <?php echo $this->Avatar->display($message['User']); ?>
        <strong><?php echo $message['User']['username'] ?></strong>
        <q><?php echo $message['Message']['message'] ?></q>
    </li>
    <?php endforeach ?>
</ul>