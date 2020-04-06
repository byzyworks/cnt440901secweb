<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
	{
		forwardError403();
	}
	
	require(basename($_GET['script'] . '.php'));
?>
