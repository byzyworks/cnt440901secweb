<?php
    session_start();
    
	$ip         = $_SERVER['SERVER_ADDR'];
	$sql_server = 'localhost';
	$sql_uname  = 'web-user';
	$sql_passwd = '';
	$sql_db     = 'cnt440901secweb';
	$usr_table  = 'users';
    $usr_bio    = $_POST['bio'];

    // Redirect user if not logged in
	if (!isset($_SESSION['uname']) || !isset($usr_bio))
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
	
	// Extract bio from user profile
	$stmt = $conn->prepare("UPDATE $usr_table SET bio = ? WHERE uname = ?");
	$stmt->bind_param("ss", $usr_bio, $_SESSION['uname']);
	$stmt->execute();

    $conn->close();
    header("Location: https://$ip/account");
	exit;
?>

