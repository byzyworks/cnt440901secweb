<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
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
					echo '<button onclick="window.location.href = \'/signup\';">Sign Up</button>';
					echo '<button onclick="window.location.href = \'/signin\';">Sign In</button>';
				}
				else
				{
					echo '<button onclick="window.location.href = \'/account\';">Account</button>';
					echo '<form action="/scripts/signout" method="post">';
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
			<img src="/images/welcome.jpg" style="max-width:100%;"/>
		</div>
	</body>
</html>
