<?php
	// Get the connecting IP address
	function getIPAddress()
	{
		// Server variable containing the IP address will change depending on whether the user is using a proxy or not
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			return $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	
	// Verifies the IP address of the connected user, to deter session hijacking
	function validIPAddress()
	{
		// Check if the user's IP address has changed from where they first arrived/logged in
		if (getIPAddress() != $_SESSION['ip_address'])
		{
			return false;
		}
		
		return true;
	}
	
	// Load the session
	function loadSession()
	{
		session_start();
		
		if (isset($_SESSION['ip_address']))
		{
			// If the IP address of the connecting user doesn't match the one set for the session ID sent with a cookie, the session is destroyed
			// Admittedly, this will log legitimate users out as well if their cookies are stolen (or they change locations), but it greatly reduces the risk of a successful attack in most cases
			if (!validIPAddress())
			{
				destroySession();
				
				session_start();
				$_SESSION['error'] = 'Your IP address has changed.';
			}
		}
		else
		{
			$_SESSION['ip_address'] = getIPAddress();
		}
	}
	
	// Destroy the session
	function destroySession()
	{
		setcookie(session_name(), session_id(), time() - 3600);
		
		session_unset();
		session_destroy();
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
		$sql_uname  = 'web-user';
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
	
	// Read the server-wide pepper to use for added password security
	function getPepper()
	{
		// Check if it can reach the file where the pepper is stored
		$pepper_file = '/etc/apache2/.phrase';
		if (!is_readable($pepper_file))
		{
			forwardError500();
		}
		
		// Try to read the pepper from the file
		$f = fopen($pepper_file, 'r');
		$pepper = fread($f, 24);
		fclose($f);
		
		return $pepper;
	}
	
	// Convert the user's typed password into a storable format
	function transformPassword($passwd)
	{
		$pepper = getPepper();
		$hash   = hash_hmac('sha256', $passwd, $pepper);
		return password_hash($hash, PASSWORD_BCRYPT, ['cost' => 12]);
	}
	
	// Check that the user's password matches up with the stored hash
	function verifiedPassword($passwd, $hash)
	{
		$pepper = getPepper();
		$passwd = hash_hmac('sha256', $passwd, $pepper);
		return password_verify($passwd, $hash);
	}
	
	// Check if the user exists and the password that was typed is correct for that user
	function authenticatedUser($uname, $passwd)
	{
		$sql_conn = connectDB();
		
		// Grab their password hash from the database
		$stmt = $sql_conn->prepare("SELECT passwd FROM users WHERE uname = ?");
		$stmt->bind_param('s', $uname);
		$stmt->execute();
		$result = $stmt->get_result();
		
		disconnectDB($sql_conn);
		
		// Check if the user exists
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				// Verify their password
				if (verifiedPassword($passwd, $row['passwd']))
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
		setcookie(session_name(), session_id(), time() + (86400 * 30), '/', '', isset($_SERVER['HTTPS']), true);
	}
	
	// Log a user into the website
	function loginUser($uname, $rememberMe)
	{
		// Due to privileges being elevated, create a new session ID for the user
		session_regenerate_id();
		
		// Log the user in
		$_SESSION['uname'] = $uname;
		
		// Remember the user if they agreed to it
		if (isset($rememberMe))
		{
			createPersistentSession($uname);
		}
	}
	
	// Check that the given password follows the website's password policy
	function validPassword($passwd)
	{
		$password_min_len = 12;
		if (strlen($passwd) < $password_min_len || !preg_match('/[A-Z]/', $passwd) || !preg_match('/[a-z]/', $passwd) || !preg_match('/[0-9]/', $passwd))
		{
			return true;
		}
		
		return false;
	}
	
	// Check that a user exists in the database
	function existingUser($uname)
	{
		$sql_conn = connectDB();
		
		$stmt = $sql_conn->prepare("SELECT uname FROM users WHERE uname = ?");
		$stmt->bind_param('s', $uname);
		$stmt->execute();
		$result = $stmt->get_result();
		
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
		
		$stmt = $sql_conn->prepare("INSERT INTO users (uname, passwd) VALUES (?, ?)");
		$stmt->bind_param('ss', $uname, transformPassword($passwd));
		$stmt->execute();
		
		disconnectDB($sql_conn);
	}
	
	// Update a user's password
	function changePassword($uname, $passwd)
	{
		$sql_conn = connectDB();
		
		$stmt = $sql_conn->prepare("UPDATE users SET passwd = ? WHERE uname = ?");
		$stmt->bind_param('ss', transformPassword($passwd), $uname);
		$stmt->execute();
		
		disconnectDB($sql_conn);
	}
	
	// Grab a user's bio from the database
	function getBio($uname)
	{
		$sql_conn = connectDB();
		
		$stmt = $sql_conn->prepare("SELECT bio FROM users WHERE uname = ?");
		$stmt->bind_param('s', $uname);
		$stmt->execute();
		$result = $stmt->get_result();
		
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
		
		$stmt = $sql_conn->prepare("UPDATE users SET bio = ? WHERE uname = '$uname'");
		$stmt->bind_param('s', htmlspecialchars($bio, ENT_QUOTES, 'UTF-8'));
		$stmt->execute();
		
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
