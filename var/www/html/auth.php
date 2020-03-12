<?php
	session_start();

	$page_account = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
	$page_signin  = 'https://' . $_SERVER['HTTP_HOST'] . '/signin';
	$form_uname   = $_POST['uname'];
	$form_passwd  = $_POST['passwd'];
	$sesn_usr     = $_SESSION['uname'];
	
	// Redirect users if they opened this page through non-standard means
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
		header('HTTP/1.0 500 Internal Server Error');
		die('500 Internal Server Error');
	}

	// Query database for user (insecure)
	/*
	$sql_table = 'users';
	$sql = 'SELECT hash FROM ' . $sql_table . ' WHERE uname=\'' . $form_uname . '\'';
	$result = $sql_conn->query($sql);
	$sql_conn->close();
	*/
	
	// Query database for user
	$sql_table = 'users';
	$stmt = $sql_conn->prepare("SELECT hash FROM $sql_table WHERE uname = ?");
	$stmt->bind_param('s', $form_uname);
	$stmt->execute();
	$result = $stmt->get_result();
	$sql_conn->close();
	
	// Check if user exists
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			// Verify their password
			if (password_verify($form_passwd, $row['hash']))
			{
				// Log the user in
				$_SESSION['uname'] = $form_uname;
				
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
