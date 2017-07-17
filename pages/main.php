<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

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

		<?php
		if(isset($username) && isset($rank)){
		?>

			<p class = "bodyTextType1">

			<?php
			echo "Welcome, " . $username . " who is a " . $rank . "";
			?>

			</p>

		<?php
		}
		?>

			<form action="eventSelection.html">
    			<input class="bigButton" type="submit" value="Event Selection" />
			</form>
			<br>
			<form action="minutes.php">
    			<input class="bigButton" type="submit" value="Minutes" />
			</form>
			<br>
			<form action="../php/logout.php">
    			<input class="bigButton" type="submit" value="Logout" />
			</form>


		</div>
<!--Less spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				T1285
			</p></center>
		</footer>
	</div>	
</body>

</html>