<?php
	session_start();

	$page_home = $_SERVER['HTTP_HOST'];
	$sesn_usr  = $_SESSION['uname'];
	
	if (!isset($sesn_usr))
	{
		// Redirect the user if they're not logged in anyway
		header('Location: ' . $page_home);
		exit;
	}
	else
	{
		// Log the user out
		session_unset();
		session_destroy();
	}

	// Refresh the page the user was on
	$page_last = $_SERVER['HTTP_REFERER'];
	header('Location: ' . $page_last);
	exit;
?>