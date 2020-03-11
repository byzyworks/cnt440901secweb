<?php
	session_start();

	$ip              = $_SERVER['SERVER_ADDR'];
	$sql_server      = 'localhost';
	$sql_uname       = 'web-user';
	$sql_passwd      = '';
	$sql_db          = 'cnt440901secweb';
	$usr_table       = 'users';
	$usr_uname       = $_POST['uname'];
	$usr_passwd      = $_POST['passwd'];
	$usr_passwd_conf = $_POST['passwd_conf'];

	if (isset($_SESSION['uname']))
	{
		header("Location: https://$ip/account");
		exit;
	}
	if (!isset($usr_uname))
	{
		header("Location: https://$ip/signup");
		exit;
	}

	if ($usr_passwd_conf != $usr_passwd)
	{
		$_SESSION['error'] = "Passwords do not match.";
		header("Location: https://$ip/signup");
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
	
	// Query database to see if user already exists
	$stmt = $conn->prepare("SELECT id, uname, hash, bio FROM $usr_table WHERE uname = ?");
	$stmt->bind_param("s", $usr_uname);
	$stmt->execute();
	$result = $stmt->get_result();
	
	// Reject them if so
	if ($result->num_rows > 0)
	{
		$conn->close();
		$_SESSION['error'] = "Username already exists.";
		header("Location: https://$ip/signup");
		exit;
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
