<?php
	if (!authenticatedUser($_SESSION['uname'], $_POST['passwd_old']))
	{
		// If the user doesn't enter their old password correctly...
		forwardUserFailure($page_reset, 'Old password is incorrect.');
	}
	
	// Stop update if passwords don't match
	if ($_POST['passwd_new_conf'] != $_POST['passwd_new'])
	{
		forwardUserFailure($page_reset, 'Passwords do not match.');
	}
	
	// Stop update if new password is the same as the old one
	if ($_POST['passwd_new'] == $_POST['passwd_old'])
	{
		forwardUserFailure($page_reset, 'New password is the same as the old password.');
	}
	
	// Stop update if new password doesn't follow policy
	if (validPassword($_POST['passwd_new']))
	{
		forwardUserFailure($page_reset, 'Your password does not follow the rules below.');
	}
	
	changePassword($_SESSION['uname'], $_POST['passwd_new']);
	forwardUserSuccess($page_account);
?>
