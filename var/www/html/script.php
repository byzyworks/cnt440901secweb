<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
	
	require($_GET['script']);
?>
