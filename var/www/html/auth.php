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
	
	
	if (isset($_SESSION['uname']))
	{
		header("Location: https://$ip/account.php");
		exit;
	}

	// Create a session variable to be used across web pages
	$_SESSION['uname'] = $usr_uname;
	
	// Create connection
	$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}

	// Query database for user (insecure)
	//$sql = "SELECT id, uname, hash, bio FROM $usr_table WHERE uname='$usr_uname'";
	//$result = $conn->query($sql);
	
	// Query database for user
	$stmt = $conn->prepare("SELECT id, uname, hash, bio FROM $usr_table WHERE uname = ?");
	$stmt->bind_param("s", $usr_uname);
	$stmt->execute();
	$result = $stmt->get_result();
	
	// Check if user exists
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			// Verify their password
			if (password_verify($usr_passwd, $row['hash']))
			{
				// Forward the user to their account page
				$conn->close();
				header("Location: https://$ip/account.php");
				exit;
			}
		}
	}

	// In case of login failure, close the connection and give an weakly-informative error message
	$conn->close();
	header("Location: https://$ip/signin.php");
	exit;
?>
