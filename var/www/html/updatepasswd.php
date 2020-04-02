<?php
    session_start();
    
	$page_home            = 'https://' . $_SERVER['HTTP_HOST'];
	$page_account         = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
	$page_reset           = 'https://' . $_SERVER['HTTP_HOST'] . '/reset';
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
		header('Location: ' . $page_reset);
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
	
	// Query database for user
	$stmt = $sql_conn->prepare("SELECT passwd FROM $sql_table WHERE uname = ?");
	$stmt->bind_param('s', $sesn_usr);
	$stmt->execute();
	$result = $stmt->get_result();
	
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
			// Verify their old password
			if (password_verify(hash_hmac('sha256', $form_passwd_old, $pepper), $row['passwd']))
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
				
				// Stop update if new password doesn't follow policy
				$password_min_len = 12;
				if (strlen($form_passwd_new) < $password_min_len || !preg_match('/[A-Z]/', $form_passwd_new) || !preg_match('/[a-z]/', $form_passwd_new) || !preg_match('/[0-9]/', $form_passwd_new))
				{
					$sql_conn->close();
					$_SESSION['error'] = 'Your password does not follow the rules below.';
					header('Location: ' . $page_reset);
					exit;
				}
				
				// Hash the user's new password
				$cost = 12;
				$hash = hash_hmac('sha256', $form_passwd_new, $pepper);
				$hash = password_hash($hash, PASSWORD_BCRYPT, ['cost' => $cost]);
				
				// Update the user's password
				$stmt = $sql_conn->prepare("UPDATE $sql_table SET passwd = ? WHERE uname = ?");
				$stmt->bind_param('ss', $hash, $sesn_usr);
				$stmt->execute();
				
				// Close the connection to MySQL
				$sql_conn->close();
				
				// Forward the user to their account page
				header('Location: ' . $page_account);
				exit;
			}
		}
	}
	
	// If the user doesn't enter their old password correctly...
	$_SESSION['error'] = 'Old password is incorrect.';
    header('Location: ' . $page_reset);
	exit;
?>

