<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$grade = $_SESSION['grade'];

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
			<span><a href="eventSelection.php"><img src="../imgs/icon_events.png" height="64" width="64"><p class="bodyTextType1">Events</p></a></span>
		<!--Minutes-->
			<span><a href="minutes.php"><img src="../imgs/icon_minutes.png" height="64" width="64"><p class="bodyTextType1">Minutes</p></a></span>
		<!--Announcements-->
			<span><a href="announcements.php"><img src="../imgs/icon_announcements.png" height="64" width="64"><p class="bodyTextType1">Announcements</p></a></span>
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
			</div>
			<div class="userDashSection">
			</div>
			</td>
			<td class="columns">
			<div class="userDashSection">
			</div>
			<div class="userDashSection">
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