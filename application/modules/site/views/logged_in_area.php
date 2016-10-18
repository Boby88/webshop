<!DOCTYPE html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>untitled</title>
</head>
<body>
	<h2>Welcome Back, <?php echo $this->session->userdata('email'); ?>!</h2>
     <p>This section represents the area that only logged in members can access.</p>

	<pre>
		<?php print_r($_SESSION); ?>
	</pre>

</body>
</html>