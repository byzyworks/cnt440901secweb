<?php
	session_start();
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	$sesn_usr = $_SESSION['uname'];
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
				padding: 16px;
				font-size: 100%;
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
			<?php
				if (!isset($sesn_usr))
				{
					echo '<button onclick="window.location.href = \'/signup.php\';">Sign Up</button>';
					echo '<button onclick="window.location.href = \'/signin.php\';">Sign In</button>';
				}
				else
				{
					echo '<button onclick="window.location.href = \'/account.php?user=' . $sesn_usr . '\';">Account</button>';
					echo '<button onclick="window.location.href = \'/out.php\';">Logout</button>';
				}
			?>
		</div>
		<div class="borderless_container">
			<?php
				echo '<span><b>Welcome';
				if (isset($sesn_usr))
				{
					echo ', ' . $sesn_usr;
				}
				echo '</b></span>';
			?>
		</div>
		<script>
		</script>
	</body>
</html>
