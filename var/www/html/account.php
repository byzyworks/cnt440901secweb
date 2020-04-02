<?php
	session_start();
	
	$page_home = 'http://' . $_SERVER['HTTP_HOST'];
	$page_curr = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$page_usr  = $_GET['user'];
	
	// Load a cookie if it exists
	$cookie_usr = $_COOKIE['uname'];
	if (isset($cookie_usr))
	{
		$_SESSION['uname'] = $cookie_usr;
	}
	
	$sesn_usr = $_SESSION['uname'];
	
	// Redirect users depending on if they're logged in or not
	if (!isset($page_usr))
	{
		if (!isset($sesn_usr))
		{
			header('Location: ' . $page_home);
			exit;
		}
		header('Location: ' . $page_curr . '?user=' . $sesn_usr);
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
	
	// Extract bio from user profile
	$sql_table = 'users';
	$sql_query = "SELECT bio FROM $sql_table WHERE uname = '$page_usr'";
	$result = $sql_conn->query($sql_query);
	$sql_conn->close();
	
	if ($result->num_rows > 0)
	{
		// Read the user's bio
		while ($row = $result->fetch_assoc())
		{
			if (isset($row['bio']))
			{
				$page_bio = $row['bio'];
			}
		}
	}
	else
	{
		// Throw 404 if user goes to user page for non-existing user
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
		die('404 Not Found');
	}
?>

<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Network Security (CNT4409.01 I&T) Secure Website Project</title>
		<style>
			*
			{
				font-family: courier;
				font-size: 125%;
			}
			input[type=text], input[type=password]
			{
				width: 100%;
				padding: 12px 20px;
				margin: 8px 0;
				display: inline-block;
				border: 1px solid #ccc;
				box-sizing: border-box;
			}
			button
			{
				background-color: #4CAF50;
				color: white;
				padding: 14px 20px;
				margin: 8px 0;
				border: none;
				cursor: pointer;
				width: 100%;
			}
			button:hover
			{
				opacity: 0.8;
			}
			.borderless_container
			{
				padding: 16px;
				font-size: 100%;
			}
			.container
			{
				border: 3px solid #f1f1f1;
				width: 800px;
				padding: 16px;
			}
            form
            {
                font-size: inherit;
            }
			textarea
			{
				resize: vertical;
				box-sizing: border-box;
				width: 100%;
			}
			h
			{
				font-weight: bold;
			}
			section
			{
				word-wrap: break-word;
				white-space: pre-line;
				width: 100%;
			}
		</style>
		<script>
			class main
			{
				constructor()
				{
					this.bio_ctnt = document.getElementById('bio_ctnt').innerHTML;
				}
				
				editBio()
				{
					var innerHTML = '';
				    
					innerHTML += '<h>Bio:</h>';
					innerHTML += '<textarea id="bio_ctnt_new" name="bio" form="bio_form">';
					innerHTML += this.bio_ctnt;
					innerHTML += '</textarea>';
                    innerHTML += '<form id="bio_form" action="/updatebio.php" method="post">';
					innerHTML += '<button type="submit">Save</button>';
                    innerHTML += '</form>';
					innerHTML += '<button onclick="resetBio()">Cancel</button>';
					
					document.getElementById('bio').innerHTML = innerHTML;
				}
				
				resetBio()
				{
					var innerHTML = '';
					
                    if (this.bio_ctnt != '')
                    {
					    innerHTML += '<h>Bio:</h>';
					    innerHTML += '<section id="bio_ctnt">';
					    innerHTML += this.bio_ctnt;
					    innerHTML += '</section>';
					    innerHTML += '<button onclick="editBio()">Edit Bio</button>';
                    }
                    else
                    {
                        innerHTML += '<section id="bio_ctnt"></section>';
					    innerHTML += '<button onclick="editBio()">Create Bio</button>';
                    }   
					
					document.getElementById('bio').innerHTML = innerHTML;
				}
			}
			
			function editBio()
			{
				myMain.editBio();
			}
			
			function resetBio()
			{
				myMain.resetBio();
			}
		</script>
	</head>
	<body>
		<div class="container">
			<button onclick="window.location.href = '/';">Home</button>
			<?php
				if (isset($sesn_usr))
				{
					if ($page_usr != $sesn_usr)
					{
						echo '<button onclick="window.location.href = \'/account.php\';">Account</button>';
					}
					else
					{
						echo '<button onclick="window.location.href = \'/reset.php\';">Change Password</button>';
					}
					echo '<button onclick="window.location.href = \'/out.php\';">Logout</button>';
				}
				else
				{
					echo '<button onclick="window.location.href = \'/signup.php\';">Sign Up</button>';
					echo '<button onclick="window.location.href = \'/signin.php\';">Sign In</button>';
				}
			?>
		</div>
		<div class="borderless_container">
			<?php
				echo '<span><b>';
				if ($page_usr == $sesn_usr)
				{
					echo 'Welcome, ';
				}
				echo $page_usr . '</b></span>';
			?>
		</div>
		<?php
			if (isset($page_bio) && !empty($page_bio) || $page_usr == $sesn_usr)
			{
				echo '<div id="bio" class="container">';
				if (isset($page_bio) && !empty($page_bio))
				{
					echo '<h>Bio:</h>';
					echo '<section id="bio_ctnt">';
					echo $page_bio;
					echo '</section>';
					if ($page_usr == $sesn_usr)
					{
						echo '<button onclick="editBio()">Edit Bio</button>';
					}
				}
				else
				{
					echo '<section id="bio_ctnt"></section>';
					if ($page_usr == $sesn_usr)
					{
						echo '<button onclick="editBio()">Create Bio</button>';
					}
				}
				echo '</div>';
			}
		?>
		<script>
			var myMain = new main();
		</script>
	</body>
</html>
