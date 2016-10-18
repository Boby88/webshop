<?php
	$user = array();
	if($userinfo != NULL) {
		foreach($userinfo as $key => $value) {
			$user[$key] = $value;
		}
	}
	if($memberinfo != NULL) {
		foreach($memberinfo as $key => $value) {
			$user[$key] = $value;
		}
	}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<img src="<?php echo base_url(isset($user['photo_url']) ? $user['photo_url'] : 'assets/img/empty_user_100x100.jpg'); ?>" />
		</div>
		<div class="col-xs-12">
			<h4>User Info <span class="fa fa-pencil"></span></h4>
			<div class="row">
				<div class="col-xs-6">Email Address:</div>
				<div class="col-xs-6">
					<?php echo isset($user['email']) ? $user['email'] : ''; ?>
					<span class="fa <?php echo isset($user['activated']) && $user['activated'] == '1' ? 'text-success fa-check-circle-o' : 'text-danger fa-times-circle-o'; ?>"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">Password:</div>
				<div class="col-xs-6">**********</div>
			</div>
		</div>
		<div class="col-xs-12">
			<h4>Contact Info <span class="fa fa-pencil"></span></h4>
			<div class="row">
				<div class="col-xs-6">First Name:</div>
				<div class="col-xs-6"><?php echo isset($user['first_name']) ? $user['first_name'] : ''; ?></div>
			</div>
			<div class="row">
				<div class="col-xs-6">Last Name:</div>
				<div class="col-xs-6"><?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?></div>
			</div>
			<div class="row">
				<div class="col-xs-6">City:</div>
				<div class="col-xs-6"><?php echo isset($user['city']) ? $user['city'] : ''; ?></div>
			</div>
			<div class="row">
				<div class="col-xs-6">Zip Code:</div>
				<div class="col-xs-6"><?php echo isset($user['zip']) ? $user['zip'] : ''; ?></div>
			</div>
			<div class="row">
				<div class="col-xs-6">Address:</div>
				<div class="col-xs-6"><?php echo isset($user['address']) ? $user['address'] : ''; ?></div>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid">
	<h3>Edit / Update User Info</h3>
	<div class="row">
		<h4>Change Email Address</h4>
		<?php echo form_open('user/change_email'); ?>
		<div class="form-group">
		<?php echo form_input(['name' => 'email_address', 'id' => 'email_address', 'class' => 'form-control', 'placeholder' => 'Email Address']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'confirm_email_address', 'id' => 'confirm_email_address', 'class' => 'form-control', 'placeholder' => 'Confirm Email Address']); ?>
		</div>
		<div class="form-group">
		<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Save']); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="row">
		<h4>Change Password</h4>
		<?php echo form_open('user/change_password'); ?>
		<div class="form-group">
		<?php echo form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']); ?>
		</div>
		<div class="form-group">
		<?php echo form_password(['name' => 'confirm_password', 'id' => 'confirm_password', 'class' => 'form-control', 'placeholder' => 'Confirm Password']); ?>
		</div>
		<div class="form-group">
		<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Save']); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
	<div class="row">
		<h4>Change Contact Info</h4>
		<?php echo form_open('user/change_userinfo'); ?>
		<div class="form-group">
		<?php echo form_input(['name' => 'first_name', 'id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'First Name']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'last_name', 'id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last Name']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'zip', 'id' => 'zip_code', 'class' => 'form-control', 'placeholder' => 'Zip Code']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'city', 'id' => 'city', 'class' => 'form-control', 'placeholder' => 'City']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'address', 'id' => 'address', 'class' => 'form-control', 'placeholder' => 'Address']); ?>
		</div>
		<div class="form-group">
		<?php echo form_input(['name' => 'phone', 'id' => 'phone', 'class' => 'form-control', 'placeholder' => 'Phone Number']); ?>
		</div>
		<div class="form-group">
		<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Save']); ?>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>