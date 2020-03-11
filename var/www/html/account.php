<?php
	session_start();
	
	$ip         = $_SERVER['SERVER_ADDR'];
	$sql_server = 'localhost';
	$sql_uname  = 'web-user';
	$sql_passwd = '';
	$sql_db     = 'cnt440901secweb';
	$usr_table  = 'users';
	
	// Redirect user if not logged in
	if (!isset($_SESSION['uname']))
	{
		header("Location: https://$ip");
		exit;
	}
	
	// Create connection
	$conn = new mysqli($sql_server, $sql_uname, $sql_passwd, $sql_db);

	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
	
	// Extract bio from user profile
	$stmt = $conn->prepare("SELECT bio FROM $usr_table WHERE uname = ?");
	$stmt->bind_param("s", $_SESSION['uname']);
	$stmt->execute();
	$result = $stmt->get_result();
	
	// Read the user's bio
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if (isset($row['bio']))
			{
				$usr_bio = $row['bio'];
			}
		}
	}
	
	// Close the connection
	$conn->close();
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
				width: 800px;
				padding: 16px;
			}
			.container
			{
				border: 3px solid #f1f1f1;
				width: 800px;
				padding: 16px;
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
					this.bio_content = document.getElementById("bio_content").innerHTML;
				}
				
				editBio()
				{
					var innerHTML = "";
					
					innerHTML += "<h>Bio:</h>";
					innerHTML += "<textarea id=\"bio_content_new\">";
					innerHTML += this.bio_content;
					innerHTML += "</textarea>";
					innerHTML += "<button onclick=\"saveBio()\">Save</button>";
					innerHTML += "<button onclick=\"resetBio()\">Cancel</button>";
					
					document.getElementById("bio").innerHTML = innerHTML;
				}
				
				saveBio()
				{
					var bio_content_new = document.getElementById("bio_content_new").value;
					var innerHTML       = "";
					
					innerHTML += "<h>Bio:</h>";
					innerHTML += "<section id=\"bio_content\">";
					innerHTML += bio_content_new;
					innerHTML += "</section>";
					innerHTML += "<button onclick=\"editBio()\">Edit</button>";
					
					this.bio_content = bio_content_new;
					document.getElementById("bio").innerHTML = innerHTML;
				}
				
				resetBio()
				{
					var innerHTML = "";
					
					innerHTML += "<h>Bio:</h>";
					innerHTML += "<section id=\"bio_content\">";
					innerHTML += this.bio_content;
					innerHTML += "</section>";
					innerHTML += "<button onclick=\"editBio()\">Edit</button>";
					
					document.getElementById("bio").innerHTML = innerHTML;
				}
			}
			
			function editBio()
			{
				myMain.editBio();
			}
			
			function saveBio()
			{
				myMain.saveBio();
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
			<button onclick="window.location.href = 'out';">Logout</button>
		</div>
		<div class="borderless_container">
			<span><b>Welcome<?php
				if (isset($_SESSION['uname']))
				{
					echo ", " . $_SESSION['uname'];
				}
			?></b></span>
		</div>
		<div id="bio" class="container">
			<?php
				if (isset($usr_bio))
				{
					echo "<h>Bio:</h>";
					echo "<section id=\"bio_content\">$usr_bio</section>";
				}
			?>
			<button onclick="editBio()">Edit</button>
		</div>
		<script>
			var myMain = new main();
		</script>
	</body>
</html>