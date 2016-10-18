<div class="container">
	<h1>Create an Account!</h1>
	<fieldset>
	<legend>Personal Information</legend>
	<?php echo form_open('login/create_member'); ?>

	<div class="form-group">
	<?php echo form_input(['name' => 'email_address', 'class' => 'form-control', 'placeholder' => 'Email address']); ?>
	</div>
	<div class="form-group">
	<?php echo form_password(['name' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']); ?>
	</div>
	<div class="form-group">
	<?php echo form_password(['name' => 'password2', 'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>
	</div>

	<div class="form-group">
	<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Create Account']); ?>
	</div>
	</fieldset>
</div>