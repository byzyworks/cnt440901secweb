<?php
	session_start();
	
	require_once('globals.php');
	require_once('functions.php');
	
	loadCookie();
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
			<?php
				if (!isset($_SESSION['uname']))
				{
					echo '<button onclick="window.location.href = \'/signup.php\';">Sign Up</button>';
					echo '<button onclick="window.location.href = \'/signin.php\';">Sign In</button>';
				}
				else
				{
					echo '<button onclick="window.location.href = \'/account.php\';">Account</button>';
					echo '<form action="script.php?script=signout.php" method="post">';
					echo '<button type="submit">Logout</button>';
					echo '</form>';
				}
			?>
		</div>
		<div class="borderless_container">
			<?php
				echo '<span><b>Welcome';
				if (isset($_SESSION['uname']))
				{
					echo ', ' . $_SESSION['uname'];
				}
				echo '</b></span>';
			?>
		</div>
		<div class="container">
			<img src="img.php?img=welcome.jpg" style="max-width:100%;"/>
		</div>
	</body>
</html>
