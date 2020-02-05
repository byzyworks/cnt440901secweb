<?php
	session_start();
?>

<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Login</title>
	</head>
	<body>
		<?php
			$sql_server = "localhost";
			$sql_uname  = "web-user";
			$sql_passwd = "";
			$sql_db     = "cnt440901secweb";
			$usr_uname  = $_POST['uname'];
			$usr_hash   = $_POST['passwd'];
			$ip         = $_SERVER['SERVER_ADDR'];

			$_SESSION['uname'] = $usr_uname;

			// Create connection
			$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT id, uname, passwd, bio FROM users_insecure WHERE uname='$usr_uname' AND passwd='$usr_hash'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			// output data from each row
				while ($row = $result->fetch_assoc()) {
					echo "ID: " . $row["id"] . ", Username: " . $row["uname"] . ", Password" . $row["passwd"] . ", Bio: " . $row["bio"] . "<br>";
				}
				header("Location: https://$ip/account.php");
			} else {
				echo "No results found";
			}

			$conn->close();
		?>
	</body>
</html>
