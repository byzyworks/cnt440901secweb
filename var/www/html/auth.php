<?php
	session_start();

	$page_account  = 'http://' . $_SERVER['HTTP_HOST'] . '/account.php';
	$page_signin   = 'http://' . $_SERVER['HTTP_HOST'] . '/signin.php';
	$form_uname    = $_POST['uname'];
	$form_passwd   = $_POST['passwd'];
	$form_remember = $_POST['remember'];
	
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
		header('Location: ' . $page_signin);
		exit;
	}

	$sql_server = 'localhost';
	$sql_uname  = 'root';
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
	$sql_query = "SELECT passwd FROM $sql_table WHERE uname = '$form_uname' AND passwd = '$form_passwd'";
	$result = $sql_conn->query($sql_query);
	$sql_conn->close();
	
	// Check if user exists
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			// Log the user in
			$_SESSION['uname'] = $form_uname;
			
			// Remember the user if they agreed to it
			if (isset($form_remember))
			{
				setcookie('uname', $form_uname, time() + (86400 * 30), '/');
			}
			
			// Forward the user to their account page
			header('Location: ' . $page_account);
			exit;
		}
	}

	// In case of login failure, tell the user they got either their username or password wrong (but never which) on the login page
	$_SESSION['error'] = 'Username or password is incorrect.';
	header('Location: ' . $page_signin);
	exit;
?>
