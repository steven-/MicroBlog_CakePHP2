<ul id="users">
	<?php foreach ($users as $user): ?>
        <li>
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'profile', 'username' => $user['User']['username'])); ?>">
            <?php echo $this->Avatar->display($user['User']); ?>

            <strong><?php echo $user['User']['username'] ?></strong>
            <i class="icon-chevron-right"></i>
            <?php if ( ! empty($user['User']['bio'])): ?>
                <span> <?php echo $user['User']['bio'] ?> </span>
            <?php endif ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>