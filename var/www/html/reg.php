<?php
	session_start();

	$sql_server = "localhost";
	$sql_uname  = "web-user";
	$sql_passwd = "";
	$sql_db     = "cnt440901secweb";
	$usr_table  = "users";
	$usr_uname  = $_POST['uname'];
	$usr_passwd = $_POST['passwd'];
	$ip         = $_SERVER['SERVER_ADDR'];
	
	// Create connection
	$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
	
	//
	$cost       = 12;
	$usr_passwd = password_hash($usr_passwd, PASSWORD_BCRYPT, ["cost" => $cost]);
	
	// 
	$stmt = $conn->prepare("INSERT INTO $usr_table (uname, hash) VALUES (?, ?)");
	$stmt->bind_param("ss", $usr_uname, $usr_passwd);
	$stmt->execute();
	
	// Check if user exists
	/*
	if ($result->num_rows > 0) {
		$ip = $_SERVER['SERVER_ADDR'];
		while ($row = $result->fetch_assoc()) {
			// Verify their password
			if (password_verify($usr_passwd, $row["hash"])) {
				$conn->close();
				header("Location: https://$ip/account.php");
			}
		}
	}
	*/

	$conn->close();
	header("Location: https://$ip/index.php");
	//echo "Username or password is incorrect."
?>
