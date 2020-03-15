<?php
	session_start();

	$page_account  = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
	$page_signin   = 'https://' . $_SERVER['HTTP_HOST'] . '/signin';
	$form_uname    = $_POST['uname'];
	$form_passwd   = $_POST['passwd'];
	$form_remember = $_POST['remember'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	// Redirect users if they opened this page through non-standard means
	$sesn_usr = $_SESSION['uname'];
	if (isset($sesn_usr))
	{
		header('Location: ' . $page_account);
		exit;
	}
	if (!isset($form_uname))
	{
		header('Location: ' . $page_signin);
		exit;
	}

	$sql_server = 'localhost';
	$sql_uname  = 'web-user';
	$sql_passwd = '';
	$sql_db     = 'cnt440901secweb';

	// Create a connection to MySQL
	$sql_conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);
	if ($sql_conn->connect_error)
	{
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		die('500 Internal Server Error');
	}
	
	// Query database for user
	$sql_table = 'users';
	$stmt = $sql_conn->prepare("SELECT passwd FROM $sql_table WHERE uname = ?");
	$stmt->bind_param('s', $form_uname);
	$stmt->execute();
	$result = $stmt->get_result();
	$sql_conn->close();
	
	// Check if user exists
	if ($result->num_rows > 0)
	{
		// Read the server-wide pepper
		$pepper_file = '/etc/apache2/.phrase';
		if (!is_readable($pepper_file))
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			die('500 Internal Server Error');
		}
		$f = fopen($pepper_file, 'r');
		$pepper = fread($f, 24);
		fclose($f);
		
		while ($row = $result->fetch_assoc())
		{
			// Verify their password
			if (password_verify(hash_hmac('sha256', $form_passwd, $pepper), $row['passwd']))
			{
				// Log the user in
				$_SESSION['uname'] = $form_uname;
				
				// Remember the user if they agreed to it
				if (isset($form_remember))
				{
					setcookie('uname', $form_uname, time() + (86400 * 30), '/', isset($_SERVER['HTTPS']), true);
				}
				
				// Forward the user to their account page
				header('Location: ' . $page_account);
				exit;
			}
		}
	}

	// In case of login failure, tell the user they got either their username or password wrong (but never which) on the login page
	$_SESSION['error'] = 'Username or password is incorrect.';
	header('Location: ' . $page_signin);
	exit;
?>
