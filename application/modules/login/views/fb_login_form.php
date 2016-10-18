<div class="container-fluid">
	<h1>Missing required information!</h1>

	<div class="row">
		<div class="col-xs-4">
			<?php if (isset($fb_user['picture']) && isset($fb_user['picture']['data']) && isset($fb_user['picture']['data']['url'])): ?>
			<img src="<?php echo $fb_user['picture']['data']['url']; ?>" />
			<?php endif; ?>
		</div>

		<div class="col-xs-8">
			<div class="row">
				<div class="col-xs-4">First Name</div>
				<div class="col-xd-8"><?php echo $fb_user['first_name']; ?></div>
			</div>
			<div class="row">
				<div class="col-xs-4">Last Name</div>
				<div class="col-xd-8"><?php echo $fb_user['last_name']; ?></div>
			</div>
			<br />
			<br />
			<?php if (isset($verify_email_address)): ?>
			<p>It seems this Facebook account is already registered. Please enter your email address to verify this account (public email address on Facebook)</p>
			<?php else: ?>
			<p>Please enter a valid email address to finish the registration</p>
			<?php endif; ?>
			<br />
			<div class="row">
				<div class="col-xs-4">Email Address</div>
				<div class="col-xd-8">
					<div class="row">

						<?php $url = base_url((isset($verify_email_address) ? 'login/request_email' : 'login/missing_email')); ?>
						<form action="<?php echo $url; ?>" autocomplete="off">
							<div class="col-xs-8">
								<input type="text" class="form-control" name="email_address" id="registration_email_address" />
							</div>
							<div class="col-xs-4">
								<button type="submit" class="btn btn-primary" id="finish_fb_registration">Continue</button>
							</div>
						</form>

					</div>
				</div>
			</div>

			<br />
			<a href="<?php echo $this->facebook->logout_url(); ?>">Close Facebook Connect</a>
		</div>
	</div>
</div>