<?php
	session_start();

	session_unset();
	session_destroy();

	$ip = $_SERVER['SERVER_ADDR'];
	header("Location: https://$ip/index.php");
?>