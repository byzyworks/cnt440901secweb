<?php
	session_start();

	$page_account     = 'http://' . $_SERVER['HTTP_HOST'] . '/account.php';
	$page_signup      = 'http://' . $_SERVER['HTTP_HOST'] . '/signup.php';
	$page_signin      = 'http://' . $_SERVER['HTTP_HOST'] . '/signin.php';
	$form_uname       = $_POST['uname'];
	$form_passwd      = $_POST['passwd'];
	$form_passwd_conf = $_POST['passwd_conf'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}

	$sesn_usr = $_SESSION['uname'];

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
	$sql_uname  = 'root';
	$sql_passwd = '';
	$sql_db     = 'cnt440901secweb';
	$sql_table  = 'users';

	// Create a connection to MySQL
	$sql_conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);
	if ($sql_conn->connect_error)
	{
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		die('500 Internal Server Error');
	}
	
	// Query database to see if user already exists
	$sql_query = "SELECT uname FROM $sql_table WHERE uname = '$form_uname'";
	$result = $sql_conn->query($sql_query);
	
	// Stop account creation if so
	if ($result->num_rows > 0)
	{
		$_SESSION['error'] = 'Username already exists.';
		header('Location: ' . $page_signup);
		exit;
	}
	
	// Attempt to add the new user's credentials to the database
	$sql_query = "INSERT INTO $sql_table (uname, passwd) VALUES ('$form_uname', '$form_passwd')";
	$sql_conn->query($sql_query);
	
	// Close the connection to MySQL
	$sql_conn->close();

	// Forward the user to the sign-in page
	header('Location: ' . $page_signin);
	exit;
?>
