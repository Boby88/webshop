<div class="container-fluid">
	<div class="row">
		<?php if($verified): ?>
		Your account successfully activated. Now you can access all functionalities on the site.
		<?php else: ?>
		We are sorry! Something went wrong. Your account not activated. Please try again or contact us on <?php echo $this->config->item('support_email'); ?>
		<?php endif; ?>
	</div>
</div>