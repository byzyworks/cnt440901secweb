<?php
	session_start();
	
	$page_account = 'http://' . $_SERVER['HTTP_HOST'] . '/account.php';
	$page_curr    = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$page_last    = $_SERVER['HTTP_REFERER'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	$sesn_usr = $_SESSION['uname'];
	
	// Redirect users if they opened this page through non-standard means
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
		<link rel="stylesheet" type="text/css" href="/style.css" />
	</head>
	<body>
		<div class="container">
			<button onclick="window.location.href = '/';">Home</button>
			<button onclick="window.location.href = '/signin.php';">Sign In</button>
		</div>
		<form action="/reg.php" method="post">
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
			if ($page_curr == $page_last)
			{
				$sesn_err = $_SESSION['error'];
				
				if (isset($sesn_err))
				{
					echo '<div class="borderless_container">';
					echo '<span><b>' . $sesn_err . '</b></span>';
					echo '</div>';
				}
			}
		?>
	</body>
</html>
