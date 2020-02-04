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
			//$usr_uname;
			//$usr_hash;

			// Create connection
			$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			echo "Connected successfully";

			$sql = "SELECT id, uname, passwd, bio FROM users_insecure";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			// output data from each row
				while ($row = $result->fetch_assoc()) {
					echo "ID: " . $row["id"] . ", Username: " . $row["uname"] . ", Password" . $row["passwd"] . ", Bio: " . $row["bio"] . "<br>";
				}
			} else {
				echo "No results found";
			}

			$conn->close();
		?>
	</body>
</html>
