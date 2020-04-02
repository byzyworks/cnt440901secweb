<?php
	session_start();

	$page_home = 'http://' . $_SERVER['HTTP_HOST'];
	$page_last = $_SERVER['HTTP_REFERER'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		setcookie('uname', '', time() - (86400 * 30), '/');
	}
	
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
	header('Location: ' . $page_last);
	exit;
?>