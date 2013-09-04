<ul id="messages">
    <?php foreach ($messages as $message): ?>
    <li>
	   <div>
            <span><?php echo $message['Message']['created'] ?></span>
            <?php if (AuthComponent::user('username') === $message['User']['username']): // message's author ? ?>
			<a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'delete', 'id' => $message['Message']['id'])); ?>">
                Delete
                <i class="icon-remove"></i>
            </a>
            <?php endif ?>
        </div>
        <?php echo $this->Avatar->display($message['User']); // cf View/Helper ?>
        <strong><?php echo $message['User']['username'] ?></strong>
        <q><?php echo $message['Message']['message'] ?></q>
    </li>
    <?php endforeach ?>
</ul>