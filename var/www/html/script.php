<?php
	session_start();
	
	require_once('globals.php');
	require_once('functions.php');
	
	loadCookie();
	
	require($_GET['script']);
?>
