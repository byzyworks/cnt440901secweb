<?php
	session_start();
	
	$page_home = 'https://' . $_SERVER['HTTP_HOST'];
	$page_curr = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$page_last = $_SERVER['HTTP_REFERER'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	$sesn_usr = $_SESSION['uname'];
	
	// Redirect users if they opened this page through non-standard means
	if (!isset($sesn_usr))
	{
		header('Location: ' . $page_home);
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
			<button onclick="window.location.href = '/account';">Account</button>
			<button onclick="window.location.href = '/out';">Logout</button>
		</div>
		<form action="/updatepasswd" method="post">
			<div class="container">
				<label for="passwd_old"><b>Old Password</b></label>
				<input type="password" placeholder="Enter Old Password" name="passwd_old" required>
				<label for="passwd_new"><b>New Password</b></label>
				<input type="password" placeholder="Enter New Password" name="passwd_new" required>
				<label for="passwd_new_conf"><b>Confirm New Password</b></label>
				<input type="password" placeholder="Enter New Password" name="passwd_new_conf" required>
				<button type="submit">Change Password</button>
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
		<div class="borderless_container">
			<section>
				<span>Passwords must:</span><br>
				<span>- Have at least 12 characters.</span><br>
				<span>- Have at least 1 uppercase letter A-Z.</span><br>
				<span>- Have at least 1 lowercase letter a-z.</span><br>
				<span>- Have at least 1 digit 0-9.</span>
			</section>
		</div>
	</body>
</html>
