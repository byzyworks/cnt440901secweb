<?php
	session_start();
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
			form
			{
				border: 3px solid #f1f1f1;
				width: 800px;
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
			.container
			{
				padding: 16px;
			} 
		</style>
		<script>
		</script>
	</head>
	<body>
		<form action="reg" method="post">
			<div class="container">
				<label for="uname"><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="uname" required>
				<label for="passwd"><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="passwd" required>
				<label for="passwd_conf"><b>Confirm Password</b></label>
				<input type="password" placeholder="Enter Password" name="passwd_conf" required>
				<button type="submit">Register</button>
				<label>
					<input type="checkbox" checked="checked" name="remember"> Remember me
				</label>
			</div>
		</form>
		<script>
		</script>
	</body>
</html>
