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
				$article = "a";
				if($rank == "officer" || $rank == "admin"){
					$article = "an";
				}
				echo "Welcome, " . $username . " who is " . $article . " " . $rank . "";
			?>

			</p>

		<?php
		}
		?>

			<form action="eventSelection.php">
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
			<br>
			<?php
			if($rank == "admin"){
			?>
			<form action="danger.php">
    			<input class="bigButton" style="background-color:#DF2935;" type="submit" value="! Admin Settings !" />
			</form>
			<br>
			<?php
			}
			?>


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