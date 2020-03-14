<?php
	session_start();
	
	$page_account = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	// Redirect users if they opened this page through non-standard means
	$sesn_usr = $_SESSION['uname'];
	if (isset($sesn_usr))
	{
		header('Location: ' . $page_account);
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
				padding: 16px;
				font-size: 100%;
			}
			.container
			{
				border: 3px solid #f1f1f1;
				width: 800px;
				padding: 16px;
			}
			form
            {
                font-size: inherit;
            }
		</style>
		<script>
		</script>
	</head>
	<body>
		<div class="container">
			<button onclick="window.location.href = '/';">Home</button>
			<button onclick="window.location.href = '/signin';">Sign In</button>
		</div>
		<form action="/reg" method="post">
			<div class="container">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="uname" required>
				<label for="passwd"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="passwd" required>
				<label for="passwd_conf"><b>Confirm Password</b></label>
				<input type="password" placeholder="Enter Password" name="passwd_conf" required>
				<button type="submit">Register</button>
			</div>
		</form>
		<?php
			$page_last = $_SERVER['HTTP_REFERER'];
			$page_curr = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			
			if ($page_curr == $page_last)
			{
				$sesn_err = $_SESSION['error'];
				
				echo '<div class="borderless_container">';
				echo '<span><b>' . $sesn_err . '</b></span>';
				echo '</div>';
				
				session_unset();
				session_destroy();
			}
		?>
		<script>
		</script>
	</body>
</html>
