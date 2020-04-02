<?php
    session_start();
    
	$page_home            = 'http://' . $_SERVER['HTTP_HOST'];
	$page_account         = 'http://' . $_SERVER['HTTP_HOST'] . '/account.php';
	$page_reset           = 'http://' . $_SERVER['HTTP_HOST'] . '/reset.php';
	$page_last            = $_SERVER['HTTP_REFERER'];
    $form_passwd_old      = $_POST['passwd_old'];
	$form_passwd_new      = $_POST['passwd_new'];
	$form_passwd_new_conf = $_POST['passwd_new_conf'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}

	$sesn_usr = $_SESSION['uname'];

    // Redirect users if they opened this page through non-standard means
	if (!isset($sesn_usr))
	{
		header('Location: ' . $page_home);
		exit;
	}
	if (!isset($form_passwd_old))
	{
		header('Location: ' . $page_account);
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
	$sql_query = "SELECT passwd FROM $sql_table WHERE uname = '$sesn_usr' AND passwd = '$form_passwd_old'";
	$result = $sql_conn->query($sql_query);
	
	// Check if user exists
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			// Stop update if passwords don't match
			if ($form_passwd_new_conf != $form_passwd_new)
			{
				$sql_conn->close();
				$_SESSION['error'] = 'Passwords do not match.';
				header('Location: ' . $page_reset);
				exit;
			}
			
			// Stop update if new password is the same as the old one
			if ($form_passwd_new == $form_passwd_old)
			{
				$sql_conn->close();
				$_SESSION['error'] = 'New password is the same as the old password.';
				header('Location: ' . $page_reset);
				exit;
			}
			
			// Update the user's password
			$sql_table = 'users';
			$sql_query = "UPDATE $sql_table SET passwd = '$form_passwd_new' WHERE uname = '$sesn_usr'";
			$sql_conn->query($sql_query);
			$sql_conn->close();
			
			// Forward the user to their account page
			header('Location: ' . $page_account);
			exit;
		}
	}
	
	// If the user doesn't enter their old password correctly...
	$_SESSION['error'] = 'Old password is incorrect.';
    header('Location: ' . $page_last);
	exit;
?>

