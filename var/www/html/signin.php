<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
	
	// Redirect users if they opened this page through non-standard means
	if (isset($_SESSION['uname']))
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
			<button onclick="window.location.href = '/signup';">Sign Up</button>
		</div>
		<form action="/scripts/signin" method="post">
			<div class="container">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="uname" required>
				<label for="passwd"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="passwd" required>
				<button type="submit">Login</button>
				<label>
					<input type="checkbox" checked="checked" name="remember"> Remember me
				</label>
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
		?>
	</body>
</html>
