 <nav>
	<ul class="wrapper">
		<?php

			echo $this->NavLi->create(
				array(
					'controller' => 'messages',
					'action' => 'index'
				),
				'Messages',
				'quote-right'
			);

			echo $this->NavLi->create(
				array(
					'controller' => 'users',
					'action' => 'index'
				),
				'Users',
				'group'
			);


			if (AuthComponent::user('id')) {

				echo $this->NavLi->create(
					array(
						'controller' => 'messages',
						'action' => 'add'
					),
					'New message',
					'pencil'
				);

				echo $this->NavLi->create(
					array(
						'controller' => 'users',
						'action' => 'profile',
						'username' => AuthComponent::user('username')
					),
					AuthComponent::user('username'),
					'user',
					null,
					array(
						'controller' => 'users',
						'action' => 'edit'
					)
				);

				echo $this->NavLi->create(
					array(
						'controller' => 'users',
						'action' => 'logout'
					),
					'Sign Out',
					null,
					'connection'
				);

			}
			else {

				echo $this->NavLi->create(
					array(
						'controller' => 'users',
						'action' => 'register'
					),
					'Sign Up',
					null,
					'connection'
				);

				echo $this->NavLi->create(
					array(
						'controller' => 'users',
						'action' => 'login'
					),
					'Sign In',
					null,
					'connection'
				);

			}
		?>
	</ul>
</nav>