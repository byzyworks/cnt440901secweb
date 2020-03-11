<?php
	session_start();

	$ip         = $_SERVER['SERVER_ADDR'];
	$sql_server = 'localhost';
	$sql_uname  = 'web-user';
	$sql_passwd = '';
	$sql_db     = 'cnt440901secweb';
	$usr_table  = 'users';
	$usr_uname  = $_POST['uname'];
	$usr_passwd = $_POST['passwd'];

	if (!isset($usr_uname))
	{
		header("Location: https://$ip/signup");
		exit;
	}
	if (isset($_SESSION['uname']))
	{
		header("Location: https://$ip/account");
		exit;
	}

	// Create connection
	$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
	
	// Hash the new user's password
	$cost     = 12;
	$usr_hash = password_hash($usr_passwd, PASSWORD_BCRYPT, ["cost" => $cost]);
	
	// Attempt to add the new user's credentials to the database
	$stmt = $conn->prepare("INSERT INTO $usr_table (uname, hash) VALUES (?, ?)");
	$stmt->bind_param("ss", $usr_uname, $usr_passwd);
	$stmt->execute();
	
	// Log the user in
	$_SESSION['uname'] = $usr_uname;

	// Close the connection and go to the user's account page
	$conn->close();
	header("Location: https://$ip/account");
	exit;
?>
