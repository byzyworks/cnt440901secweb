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
		<?php
			echo '<div class="borderless_container">';
			echo '<span><b>' . $_SESSION['error'] . '</b></span>';
			echo '</div>';
			
			unset($_SESSION['error']);
		?>
	</body>
</html>
