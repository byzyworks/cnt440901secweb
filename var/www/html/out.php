<?php
	session_start();

	$ip = $_SERVER['SERVER_ADDR'];

	if (!isset($_SESSION['uname']))
	{
		header("Location: https://$ip/index.php");
		exit;
	}

	session_unset();
	session_destroy();

	$ip = $_SERVER['SERVER_ADDR'];
	header("Location: https://$ip/index.php");
	exit;
?>