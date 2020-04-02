<?php
    session_start();
    
	$page_account = 'http://' . $_SERVER['HTTP_HOST'] . '/account.php';
	$page_last    = $_SERVER['HTTP_REFERER'];
    $form_bio     = $_POST['bio'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
		$sesn_usr          = $_SESSION['uname'];
	}

	$sesn_usr = $_SESSION['uname'];

    // Redirect users if they opened this page through non-standard means
	if (!isset($sesn_usr) || !isset($form_bio))
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
	
	// Update the user's bio
	$sql_table = 'users';
	$sql_query = "UPDATE $sql_table SET bio = '$form_bio' WHERE uname = '$sesn_usr'";
	$sql_conn->query($sql_query);
    $sql_conn->close();
	
	// Refresh the page the user was on
    header('Location: ' . $page_last);
	exit;
?>

