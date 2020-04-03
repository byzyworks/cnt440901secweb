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
		<link rel="stylesheet" type="text/css" href="/style.css" />
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
		<div class="container">
			<img src="/img.php?img=welcome.jpg" style="max-width:100%;"/>
		</div>
	</body>
</html>
