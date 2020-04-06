<?php
	// Load a cookie if it exists; use it to log in if applicable
	function loadCookie()
	{
		if (isset($_COOKIE['uname']))
		{
			$_SESSION['uname'] = $_COOKIE['uname'];
		}
	}
	
	// Destroy cookie if it exists
	function destroyCookie()
	{
		if (isset($_COOKIE['uname']))
		{
			setcookie('uname', '', time() - (86400 * 30), '/');
		}
	}
	
	// Standard for what to do on 500 Internal Server Error
	function forwardError500()
	{
		$_SESSION['error'] = '500 Internal Server Error';
		header($_SERVER['SERVER_PROTOCOL'] . ' ' . $_SESSION['error'], true, 500);
		header('Location: ' . $GLOBALS['page_error']);
		exit;
	}
	
	function forwardError404()
	{
		$_SESSION['error'] = '404 Not Found';
		header($_SERVER['SERVER_PROTOCOL'] . ' ' . $_SESSION['error'], true, 404);
		header('Location: ' . $GLOBALS['page_error']);
		exit;
	}
	
	function forwardError403()
	{
		$_SESSION['error'] = '403 Forbidden';
		header($_SERVER['SERVER_PROTOCOL'] . ' ' . $_SESSION['error'], true, 403);
		header('Location: ' . $GLOBALS['page_error']);
		exit;
	}
	
	// Start a connection with the website's database
	function connectDB()
	{
		$sql_server = 'localhost';
		$sql_uname  = 'root';
		$sql_passwd = '';
		$sql_db     = 'cnt440901secweb';
		
		// Create a connection to MySQL
		$sql_conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);
		if ($sql_conn->connect_error)
		{
			forwardError500();
		}
		
		return $sql_conn;
	}
	
	// Close the connection to the website's database
	function disconnectDB($sql_conn)
	{
		$sql_conn->close();
	}
	
	// Check if the user exists and the password that was typed is correct for that user
	function authenticatedUser($uname, $passwd)
	{
		$sql_conn = connectDB();
		
		// Grab their password from the database
		$sql_query = "SELECT passwd FROM users WHERE uname = '$uname' AND passwd = '$passwd'";
		$result = $sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
		
		// Check if the user exists
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				// Verify their password
				if ($passwd == $row['passwd'])
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	// Set up a persistent session for the user logging in (won't log the user off after exiting their browser)
	function createPersistentSession($uname)
	{
		setcookie('uname', $uname, time() + (86400 * 30), '/');
	}
	
	// Log a user into the website
	function loginUser($uname, $rememberMe)
	{
		// Log the user in
		$_SESSION['uname'] = $uname;
		
		// Remember the user if they agreed to it
		if (isset($rememberMe))
		{
			createPersistentSession($uname);
		}
	}
	
	// Check that a user exists in the database
	function existingUser($uname)
	{
		$sql_conn = connectDB();
		
		$sql_query = "SELECT uname FROM users WHERE uname = '$uname'";
		$result = $sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
		
		if ($result->num_rows > 0)
		{
			return true;
		}
		
		return false;
	}
	
	// Add a new user's credentials to the database
	function createUser($uname, $passwd)
	{
		$sql_conn = connectDB();
		
		$sql_query = "INSERT INTO $sql_table (uname, passwd) VALUES ('$uname', '$passwd')";
		$sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
	}
	
	// Update a user's password
	function changePassword($uname, $passwd)
	{
		$sql_conn = connectDB();
		
		$sql_query = "UPDATE users SET passwd = '$passwd' WHERE uname = '$uname'";
		$sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
	}
	
	// Grab a user's bio from the database
	function getBio($uname)
	{
		$sql_conn = connectDB();
		
		$sql_query = "SELECT bio FROM users WHERE uname = '$uname'";
		$result = $sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
		
		// Check if the user exists
		if ($result->num_rows > 0)
		{
			// Read the user's bio
			while ($row = $result->fetch_assoc())
			{
				if (isset($row['bio']))
				{
					return $row['bio'];
				}
			}
		}
		
		return NULL;
	}
	
	// Update a user's bio
	function changeBio($uname, $bio)
	{
		$sql_conn = connectDB();
		
		$sql_query = "UPDATE users SET bio = '$bio' WHERE uname = '$uname'";
		$sql_conn->query($sql_query);
		
		disconnectDB($sql_conn);
	}
	
	function forwardUserFailure($page_failure, $msg)
	{
		$_SESSION['error'] = $msg;
		header('Location: ' . $page_failure);
		exit;
	}
	
	function forwardUserSuccess($page_success)
	{
		header('Location: ' . $page_success);
		exit;
	}
?>
