<?php
	// Stop account creation if passwords don't match
	if ($_POST['passwd_conf'] != $_POST['passwd'])
	{
		forwardUserFailure($page_signup, 'Passwords do not match.');
	}
	
	// Stop account creation if the password doesn't follow policy
	if (validPassword($_POST['passwd']))
	{
		forwardUserFailure($page_signup, 'Your password does not follow the rules below.');
	}
	
	// Stop account creation if username is already taken
	if (existingUser($_POST['uname']))
	{
		forwardUserFailure($page_signup, 'Username already exists.');
	}
	
	createUser($_POST['uname'], $_POST['passwd']);
	forwardUserSuccess($page_signin);
?>
