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
		<span>Welcome, <?php echo $_SESSION['uname']; ?></span>
		<script>
		</script>
	</body>
</html>
