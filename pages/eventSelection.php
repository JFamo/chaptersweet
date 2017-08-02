<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];

//functions for event signup
if(isset($_POST['slot']) && $_SESSION['eventpoints'] > 0){

	//file viewability
	$name = $_POST['name'];
	$team = $_POST['team'];
	$slot = $_POST['slot'];
	$open = $_POST['open'];

	if($open == "yes"){

		$memberColumn = "member" . $slot;
		$alreadyInEvent = "no";

		require('../php/connect.php');

		//check if user is already in that event
		$checkEventSql = "SELECT member1, member2, member3, member4, member5, member6 FROM teams WHERE event='$name'";

		$checkEventResult = mysql_query($checkEventSql);

		if (!$checkEventResult){
			die('Error: ' . mysql_error());
		}

		//for each team of the event the user is trying to enter
		while(list($member1, $member2, $member3, $member4, $member5, $member6) = mysql_fetch_array($checkEventResult)){
			if($member1 == $fullname || $member2 == $fullname || $member3 == $fullname || $member4 == $fullname || $member5 == $fullname || $member6 == $fullname){
				$alreadyInEvent = "yes";
			}
		}

		//only enter event if not already in it
		if($alreadyInEvent == "no"){

			//add the user to that team
			$sql = "UPDATE teams SET $memberColumn='$fullname' WHERE event='$name' AND team='$team'";

			if (!mysql_query($sql)){
				die('Error: ' . mysql_error());
			}

			//get user's eventpoints
			$pointsquery="SELECT eventpoints FROM users WHERE username='$username' AND fullname='$fullname'";

			$pointsresult = mysql_query($pointsquery);

			if (!$pointsresult){
				die('Error: ' . mysql_error());
			}

			list($eventpoints) = mysql_fetch_array($pointsresult);

			//decrease event points
			$newPoints = $eventpoints - 1;

			$eventSql = "UPDATE users SET eventpoints='$newPoints' WHERE username='$username' AND fullname='$fullname'";

			if (!mysql_query($eventSql)){
				die('Error: ' . mysql_error());
			}

		}else{
			//this occurs when user tries to double-register
			$fmsg = "Already In Event!";
		}

		mysql_close();

	}
}

?>

<!DOCTYPE html>

<head>
	<title>
		Chapter Sweet
	</title>
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div id="wrapper">
<!--Spooky stuff at the top-->
		<header>
				<img src="../imgs/iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
<!--Spooky stuff still kind of at the top-->
		<div id="subTitleBar">
			<form action="../index.php">
    			<input class="backButton" type="submit" value="Back" />
			</form>
			<center><p class="subTitleText">
				Event Selection
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane">

<!--Description-->
					<p class="bodyTextType1">
						Here you can for available event slots. Event names are listed, and below each name are slots available for that event. Each row represents an available team, and each cell in that row is a spot on that team.
					</p>
					<p class = "bodyTextType1">

						<?php
						//echo "Event Points : <b>".$_SESSION['eventpoints']."</b>";
						?>
						<div id="eventPoints">Loading Event Points...</div>

					</p><br>
					<?php
						if(isset($fmsg)){
						?>

							<p class = "bodyTextType1">

							<?php
							echo $fmsg;
							?>

							</p><br>

						<?php
						}
					?>
<!--Event Selection Sheet-->
					<?php
						//require('../php/getTeams.php');
					?>
					<div id="events"><center>Loading Teams...</center></div>
			</div>

<!--Spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<iframe id="hideFrame" name="hideFrame" style="display:none;"></iframe>		
<form action="../php/getTeams.php" target="hideFrame" style="display:none;" name="teamsUpdateForm"></form>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>
<script src="../js/teamsUpdate.js" type="text/javascript"></script>

</html>