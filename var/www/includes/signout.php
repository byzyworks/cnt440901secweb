<?php
	destroyCookie();
	
	session_unset();
	session_destroy();
	
	// Refresh the page the user was on
	$page_last = $_SERVER['HTTP_REFERER'];
	header('Location: ' . $page_last);
	exit;
?>