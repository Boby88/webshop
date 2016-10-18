<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		<img class="profile-img img-circle" src="<?php echo $user['photo_url']; ?>" />
		<?php echo $user['profile']; ?>
  </a>
  <ul class="dropdown-menu">
  	<li>
  		<?php echo anchor(base_url('user/profile'), 'Profile'); ?>
  	</li>
  	<li>
  		<?php echo anchor('login/logout', 'Logout'); ?>
  	</li>
  </ul>
</li>