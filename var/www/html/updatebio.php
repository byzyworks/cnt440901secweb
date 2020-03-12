<?php
    session_start();
    
	$page_account = 'https://' . $_SERVER['HTTP_HOST'] . '/account';
    $form_bio     = $_POST['bio'];
	$sesn_usr     = $_SESSION['uname'];

    // Redirect users if they opened this page through non-standard means
	if (!isset($sesn_usr) || !isset($form_bio))
	{
		header('Location: ' . $page_account);
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
	
	// Update the user's bio
	$sql_table = 'users';
	$stmt = $sql_conn->prepare("UPDATE $sql_table SET bio = ? WHERE uname = '$sesn_usr'");
	$stmt->bind_param('s', $form_bio);
	$stmt->execute();
    $sql_conn->close();
	
	// Refresh the page the user was on
	$page_last = $_SERVER['HTTP_REFERER'];
    header('Location: ' . $page_last);
	exit;
?>

