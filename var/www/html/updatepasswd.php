<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
	
	// Redirect users if they opened this page through non-standard means
	if (!isset($_SESSION['uname']))
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
			<form action="/scripts/signout" method="post">
				<button type="submit">Logout</button>
			</form>
		</div>
		<form action="/scripts/updatepasswd" method="post">
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
			if (isset($_SESSION['error']))
			{
				echo '<div class="borderless_container">';
				echo '<span><b>' . $_SESSION['error'] . '</b></span>';
				echo '</div>';
				
				unset($_SESSION['error']);
			}
			
			include('passwdpolicy.php');
		?>
	</body>
</html>
