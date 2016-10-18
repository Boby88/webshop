<script>
// Initiate Facebook JS SDK
window.fbAsyncInit = function() {
	FB.init({
		appId   : '<?php echo $this->config->item('facebook_app_id'); ?>', // Your app id
		cookie  : true,  // enable cookies to allow the server to access the session
		xfbml   : false,  // disable xfbml improves the page load time
		version : 'v2.8', // use version 2.4
		status  : true // Check for user login status right away
	});

		/*FB.getLoginStatus(function(response) {
			console.log('getLoginStatus', response);
			loginCheck(response);
		});*/
};

// Check login status
function statusCheck(response, self)
{
	console.log('statusCheck', response.status);
	if (response.status === 'connected')
	{
		$.post('<?php echo base_url('login/fb_login'); ?>',response, function(data) {
			console.log('login posted', data);
		});
	}
	else if (response.status === 'not_authorized')
	{
		// User logged into facebook, but not to our app.
	}
	else
	{
		// User not logged into Facebook.
	}
	stopLoader(self);
}

// Get login status
function loginCheck(self)
{
	FB.getLoginStatus(function(response) {
		console.log('loginCheck', response);
		statusCheck(response, self);
	});
}

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function getUser()
{
	FB.api('/me', function(response) {
		console.log('getUser', response);
	}, {scope: '<?php echo implode(",", $this->config->item('facebook_permissions')); ?>'});
}



(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(document).ready(function(){
	// Trigger login
	$('#fb-login-btn').on('click', function() {
		var self = this;
	  startLoader(self);
	  console.log('Login clicked');
		FB.login(function(response){
			console.log('login response', response);
			loginCheck(self);
		}, {scope: '<?php echo implode(",", $this->config->item('facebook_permissions')); ?>'});
	});
});
</script>