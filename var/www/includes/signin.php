<?php
	if (!authenticatedUser($_POST['uname'], $_POST['passwd']))
	{
		// In case of login failure, tell the user they got either their username or password wrong (but never which) on the login page
		forwardUserFailure($page_signin, 'Username or password is incorrect.');
	}

	// Forward the user to their account page
	loginUser($_POST['uname'], $_POST['remember']);
	forwardUserSuccess($page_account);
?>
