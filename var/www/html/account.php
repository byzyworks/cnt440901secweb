<?php
	require_once('globals.php');
	require_once('functions.php');
	
	loadSession();
	
	// Redirect users depending on if they're logged in or not and who's page they're on
	if (!isset($_GET['user']))
	{
		if (!isset($_SESSION['uname']))
		{
			header('Location: ' . $page_home);
			exit;
		}
		header('Location: ' . $page_account . '?user=' . $_SESSION['uname']);
		exit;
	}
	
	// Retrieve the bio of the account owner, or throw a 404 if they don't exist
	if (existingUser($_GET['user']))
	{
		$bio = getBio($_GET['user']);
	}
	else
	{
		forwardError404();
	}
?>

<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Network Security (CNT4409.01 I&T) Secure Website Project</title>
		<link rel="stylesheet" type="text/css" href="/style.css" />
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
                    innerHTML += '<form id="bio_form" action="/script.php?script=updatebio.php" method="post">';
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
				if (isset($_SESSION['uname']))
				{
					if ($_GET['user'] != $_SESSION['uname'])
					{
						echo '<button onclick="window.location.href = \'/account.php\';">Account</button>';
					}
					else
					{
						echo '<button onclick="window.location.href = \'/updatepasswd.php\';">Change Password</button>';
					}
					echo '<form action="script.php?script=signout.php" method="post">';
					echo '<button type="submit">Logout</button>';
					echo '</form>';
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
				if (isset($_SESSION['uname']) && $_GET['user'] == $_SESSION['uname'])
				{
					echo 'Welcome, ';
				}
				echo $_GET['user'] . '</b></span>';
			?>
		</div>
		<?php
			if (isset($bio) && !empty($bio) || (isset($_SESSION['uname']) && $_GET['user'] == $_SESSION['uname']))
			{
				echo '<div id="bio" class="container">';
				if (isset($bio) && !empty($bio))
				{
					echo '<h>Bio:</h>';
					echo '<section id="bio_ctnt">';
					echo $bio;
					echo '</section>';
					if ($_GET['user'] == $_SESSION['uname'])
					{
						echo '<button onclick="editBio()">Edit Bio</button>';
					}
				}
				else
				{
					echo '<section id="bio_ctnt"></section>';
					if ($_GET['user'] == $_SESSION['uname'])
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
