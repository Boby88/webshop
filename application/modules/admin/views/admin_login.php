<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Admin | Login</title>
	<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/admin.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url('assets');?>/css/font-awesome.min.css" type="text/css" media="screen" />
</head>
<body>
	<div class="container" id="admin-login">
		<h1>Webshop administration</h1>
		<?php echo form_open('admin/validate_credentials'); ?>
		<div class="form-group">
			<?php echo form_input(['name' => 'email_address', 'id' => 'email_address', 'class' => 'form-control', 'placeholder' => 'Email address']); ?>
		</div>
		<div class="form-group">
			<?php echo form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']); ?>
		</div>
		<div class="form-group">
			<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Login']); ?>
		</div>
		<?php echo form_close(); ?>
	</div>

<?php $this->load->view('site/footer'); ?>