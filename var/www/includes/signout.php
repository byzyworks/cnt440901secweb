<?php
	destroySession();
	
	// Refresh the page the user was on
	$page_last = $_SERVER['HTTP_REFERER'];
	header('Location: ' . $page_last);
	exit;
?>
