<?php
	session_start();
	
	$ip = $_SERVER['SERVER_ADDR'];
	
	if (!isset($_SESSION['uname']))
	{
		header("Location: https://$ip");
		exit;
	}
?>

<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Network Security (CNT4409.01 I&T) Secure Website Project</title>
		<style>
			*
			{
				font-family: courier;
				font-size: 125%;
			}
			input[type=text], input[type=password]
			{
				width: 100%;
				padding: 12px 20px;
				margin: 8px 0;
				display: inline-block;
				border: 1px solid #ccc;
				box-sizing: border-box;
			}
			button
			{
				background-color: #4CAF50;
				color: white;
				padding: 14px 20px;
				margin: 8px 0;
				border: none;
				cursor: pointer;
				width: 100%;
			}
			button:hover
			{
				opacity: 0.8;
			}
			.borderless_container
			{
				width: 800px;
				padding: 16px;
			}
			.container
			{
				border: 3px solid #f1f1f1;
				width: 800px;
				padding: 16px;
			}
		</style>
		<script>
		</script>
	</head>
	<body>
		<div class="container">
			<button onclick="window.location.href = '/';">Home</button>
			<button onclick="window.location.href = 'out';">Logout</button>
		</div>
		<div class="borderless_container">
			<span><b>Welcome<?php
				if (isset($_SESSION['uname'])) {
					echo ", " . $_SESSION['uname'];
				}
			?></b></span>
		</div>
		<script>
		</script>
	</body>
</html>