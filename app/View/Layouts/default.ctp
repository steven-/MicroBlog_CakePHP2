<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<?php
			// echo $this->Html->meta('icon');

			// echo $this->Html->css('cake.generic');
			echo $this->Html->css('reset');
			echo $this->Html->css('style');
			echo $this->Html->css('font-icons');

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
	</head>
	<body>

		<header>
			<div class="wrapper">
				<h1>
					MicroBlogMVC
					<small> - CakePHP <?php echo Configure::version(); ?></small>
				</h1>
				<div>
					Written by
					<a href="#">Someone</a>
				</div>
			</div>
		</header>


		<?php echo $this->element('nav'); ?>


		<section>
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</section>


	</body>
</html>
