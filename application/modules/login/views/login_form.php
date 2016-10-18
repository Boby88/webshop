<li>
	<a href="<?php echo base_url('login/signup'); ?>">Registration</a>
</li>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Login</a>
  <div class="dropdown-menu">
		<div class="container-fluid" id="login_form" style="width:300px">
			<h1>Login, Fool!</h1>

			<a href="<?php echo $this->facebook->login_url(); ?>"><button class="btn btn-primary" id="fb-login-btn">Log in with Facebook</button></a>
			<br/>
		  <?php echo form_open('login/validate_credentials'); ?>
			<div class="form-group">
			<?php echo form_input(['name' => 'email_address', 'id' => 'email_address', 'class' => 'form-control', 'placeholder' => 'Email address']); ?>
			</div>
			<div class="form-group">
			<?php echo form_password(['name' => 'password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password']); ?>
			</div>
			<div class="form-group">
			<?php echo form_submit(['type' => 'submit', 'class' => 'btn btn-primary', 'value' => 'Login']); ?>
			<?php echo anchor('login/signup', 'Create Account'); ?>
			</div>
			<?php echo form_close(); ?>

		</div><!-- end login_form-->
	</div>
</li>