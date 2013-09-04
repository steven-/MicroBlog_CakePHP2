<div id="profile">
	<div>
		<?php if (AuthComponent::user('username') === $user['User']['username']): ?>
            <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit')); ?>">
                Edit your profile
                <i class="icon-user"></i>
            </a>
        <?php endif ?>
        <h2>
            <?php echo $this->Avatar->display($user['User']); ?>
            <?php echo $user['User']['username'] ?>
        </h2>
        <?php if ( ! empty($user['User']['bio'])): ?>
            <p> <?php echo $user['User']['bio']; ?> </p>
        <?php endif ?>
    </div>

    <?php if (count($user['Message'])): ?>
    <ul>
        <?php foreach ($user['Message'] as $message): ?>
            <li>
                <div>
                    <span><?php echo $message['created'] ?></span>
                    <?php if (AuthComponent::user('username') === $user['User']['username']): ?>
                        <a href="<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'delete', 'id' => $message['id'])); ?>">
        					Delete
        					<i class="icon-remove"></i>
                        </a>
                    <?php endif ?>
                </div>
                <q><?php echo $message['message'] ?></q>
            </li>
        <?php endforeach ?>
        </ul>
    <?php else: ?>
        <p>
            <strong><?php echo $user['User']['username'] ?></strong> has not posted any message yet.
        </p>
    <?php endif ?>
</div>