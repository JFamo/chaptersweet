<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$grade = $_SESSION['grade'];
$name = $_SESSION['fullname'];

?>

<!DOCTYPE html>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

<head>
	<title>
		Chapter Sweet
	</title>
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="wrapper">
<!--Spooky bar at the top-->
		<header>
				<img src="../imgs/iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
<!--Spooky stuff in the middle-->
		<div id="contentPane">
		<center>

		<!--
		<?php
			if(isset($username) && isset($rank)){
			?>

				<p class = "bodyTextType1">

				<?php
					$article = "a";
					if($rank == "officer" || $rank == "admin"){
						$article = "an";
					}
					echo "Welcome, " . $username . " who is " . $article . " " . $rank . " in grade ".$grade;
				?>

				</p>

			<?php
			}
			?>
		-->

		<!--ICON LINKS DIV-->
		<div class="iconLinks">

		<!--Events-->
			<span><a href="eventSelection.php"><img src="../imgs/icon_events.png" height="64" width="64"><p class="bodyTextType1">Event Selection</p></a></span>
		<!--Minutes-->
			<span><a href="info.php"><img src="../imgs/icon_info.png" height="64" width="64"><p class="bodyTextType1">Information</p></a></span>
		<!--Users-->
				<?php
				if($rank == "admin"){
				?>
			<span><a href="assignpoints.php"><img src="../imgs/icon_users.png" height="64" width="64"><p class="bodyTextType1">Users</p></a></span>
				<?php
				}
				?>
		<!--Logout-->
			<span><a href="../php/logout.php"><img src="../imgs/icon_logout.png" height="64" width="64"><p class="bodyTextType1">Logout</p></a></span>
		<!--Admin Settings-->
				<?php
				if($rank == "admin"){
				?>
			<span><a href="danger.php"><img src="../imgs/icon_settings.png" height="64" width="64"><p class="bodyTextType1">Admin Settings</p></a></span>
				<?php
				}
				?>

		</div>

		<!--USER DASH DIV-->
		<div class="userDash">

			<div class="userDashHeader">
				<p class="subTitleText" style="padding-top:15px">My Dashboard</p>
			</div>

			<table class="columnsTable">
			<tr class="columnsRow">
			<td class="columns">
			<div class="userDashSection">
				<p class="userDashSectionHeader">
					My Events
				</p>
			</div>
			<div class="userDashSection">
				<p class="userDashSectionHeader">
					My Account
				</p>
				<p class="bodyTextType1">
					<b>Full Name:</b> <?php echo $name ?>
					<br><br>
					<b>User Name:</b> <?php echo $username ?>
					<br><br>
					<b>Grade:</b> <?php echo $grade ?>
					<br><br>
					<b>Email:</b> <?php 
						require('../php/connect.php');

						//get user's email
						$emailquery="SELECT email FROM users WHERE username='$username' AND fullname='$name'";

						$emailresult = mysql_query($emailquery);

						if (!$emailresult){
							die('Error: ' . mysql_error());
						}

						list($email) = mysql_fetch_array($emailresult); 

						//$email = unserialize($email);
						echo $email;

						?>
					<br><br>
					<b>Rank:</b> <?php echo ucwords($rank) ?>
				</p>
			</div>
			</td>
			<td class="columns">
			<div class="userDashSection" style="height:800px">
				<p class="userDashSectionHeader">
					Announcements
				</p>
				<br>
				<?php

				require('../php/connect.php');

				$query="SELECT * FROM announcements ORDER BY id DESC";

				$result = mysql_query($query);

				if (!$result){
					die('Error: ' . mysql_error());
				}		

				if(mysql_num_rows($result) == 0){
					echo "No Articles Found!<br>";
				}
				else{
					while(list($id, $title, $body, $poster, $date) = mysql_fetch_array($result)){
						?>

						<p style="font-weight: bold; font-family:tahoma; font-size:24px; padding-left:15%; padding-top:10px;"><?php echo "".$title ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "By : ".$poster ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "".$date ?></p>
						<br><br>
						<pre>
						<p style="font-size:12px; font-family:tahoma; padding-left:20%; padding-top:10px; padding-bottom: 10px;">
<?php echo "".$body ?>
						</p>
						</pre>
						
						<?php
					}
				}
						
				mysql_close();

				?>
			</div>
			</div>
			</td>
			</tr>
			</table>

		</div>

		</center>
		</div>
<!--Less spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

</html>