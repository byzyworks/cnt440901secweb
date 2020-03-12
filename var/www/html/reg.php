<?php
	session_start();

	$page_home        = 'https://' . $_SERVER['HTTP_HOST'];
	$page_account     = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
	$page_signup      = 'https://' . $_SERVER['HTTP_HOST'] . '/signup';
	$form_uname       = $_POST['uname'];
	$form_passwd      = $_POST['passwd'];
	$form_passwd_conf = $_POST['passwd_conf'];
	$sesn_usr         = $_SESSION['uname'];

	// Redirect users if they opened this page through non-standard means
	if (isset($sesn_usr))
	{
		header('Location: ' . $page_account);
		exit;
	}
	if (!isset($form_uname))
	{
		header('Location: ' . $page_signup);
		exit;
	}

	// Stop account creation if passwords don't match
	if ($form_passwd_conf != $form_passwd)
	{
		$_SESSION['error'] = 'Passwords do not match.';
		header('Location: ' . $page_signup);
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
	
	// Query database to see if user already exists
	$sql_table = 'users';
	$stmt = $sql_conn->prepare("SELECT uname FROM $sql_table WHERE uname = ?");
	$stmt->bind_param('s', $form_uname);
	$stmt->execute();
	$result = $stmt->get_result();
	
	// Stop account creation if so
	if ($result->num_rows > 0)
	{
		$_SESSION['error'] = 'Username already exists.';
		header('Location: ' . $page_signup);
		exit;
	}
	
	// Hash the new user's password
	$cost = 12;
	$hash = password_hash($form_passwd, PASSWORD_BCRYPT, ['cost' => $cost]);
	
	// Attempt to add the new user's credentials to the database
	$stmt = $sql_conn->prepare("INSERT INTO $sql_table (uname, hash) VALUES (?, ?)");
	$stmt->bind_param('ss', $form_uname, $hash);
	$stmt->execute();
	
	// Close the connection to MySQL
	$sql_conn->close();

	// Forward the user to their account page
	header('Location: ' . $page_home);
	exit;
?>
