<?php
	session_start();

	$ip = $_SERVER['SERVER_ADDR'];

	if (isset($_SESSION['uname']))
	{
		session_unset();
		session_destroy();
	}

	header("Location: https://$ip");
	exit;
?>