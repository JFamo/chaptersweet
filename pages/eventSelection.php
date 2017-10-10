<?php

session_start();

//global variables
$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];

//event deletion toggle
$delevent = 0;

//EMAIL PERMISSION
require('../php/connect.php');

$query="SELECT value FROM settings WHERE name='officerEmailPermission'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$emailPerm = $perm;

//function for deleting events
if(isset($_POST['deleventOn'])){

	$randomVariableToReadPost = $_POST['deleventOn'];
	$delevent = 1;

}

//functions for event signup
if(isset($_POST['slot'])){

	require('../php/connect.php');

	//check if this is a deletion or signup
	if($delevent == 0){

		//get user's eventpoints
		$pointsquery="SELECT eventpoints FROM users WHERE username='$username' AND fullname='$fullname'";

		$pointsresult = mysqli_query($link,$pointsquery);

		if (!$pointsresult){
			die('Error: ' . mysqli_error($link));
		}

		list($eventpoints) = mysqli_fetch_array($pointsresult);

		//if the user has event points
		if($eventpoints > 0){

			//method variables, save locally
			$name = $_POST['name'];
			$team = $_POST['team'];
			$slot = $_POST['slot'];
			$open = $_POST['open'];

			//if this slot is open
			if($open == "yes"){

				//default variable values
				$memberColumn = "member" . $slot;
				$alreadyInEvent = "no";

				//check if user is already in that event
				$checkEventSql = "SELECT member1, member2, member3, member4, member5, member6 FROM teams WHERE event='$name'";

				$checkEventResult = mysqli_query($link,$checkEventSql);

				if (!$checkEventResult){
					die('Error: ' . mysqli_error($link));
				}

				//for each team of the event the user is trying to enter
				while(list($member1, $member2, $member3, $member4, $member5, $member6) = mysqli_fetch_array($checkEventResult)){
					if($member1 == $fullname || $member2 == $fullname || $member3 == $fullname || $member4 == $fullname || $member5 == $fullname || $member6 == $fullname){
						$alreadyInEvent = "yes";
					}
				}

				//only enter event if not already in it
				if($alreadyInEvent == "no"){

					//add the user to that team
					$sql = "UPDATE teams SET $memberColumn='$fullname' WHERE event='$name' AND team='$team'";

					if (!mysqli_query($link,$sql)){
						die('Error: ' . mysqli_error($link));
					}
					
					//add the team to the changes table
					$sql = "INSERT INTO changes (event, team, slot, name, date) VALUES ('$name', '$team', '$slot', '$fullname', now())";

					if (!mysqli_query($link,$sql)){
						die('Error: ' . mysqli_error($link));
					}

					//decrease event points
					$newPoints = $eventpoints - 1;

					$eventSql = "UPDATE users SET eventpoints='$newPoints' WHERE username='$username' AND fullname='$fullname'";

					if (!mysqli_query($link,$eventSql)){
						die('Error: ' . mysqli_error($link));
					}

				}else{
					//this occurs when user tries to double-register
					$fmsg = "Already In Event!";
				}
			}
		}
	}
	else{
		//this is a deletion
		//method variables, save locally
		$name = $_POST['name'];
		$team = $_POST['team'];
		$slot = $_POST['slot'];
		$open = $_POST['open'];

		//if this slot is occupied
		if($open == "no"){

			//default variable values
			$memberColumn = "member" . $slot;

			//add the user to that team
			$sql = "UPDATE teams SET $memberColumn=NULL WHERE event='$name' AND team='$team' AND $memberColumn='$fullname'";

			if (!mysqli_query($link,$sql)){
				die('Error: ' . mysqli_error($link));
			}

			//reset deletion toggle
			$delevent = 0;

		}

	}

	mysqli_close($link);
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
						Here you can for available event slots. Event names are listed, and below each name are slots available for that event. Each row represents an available team, and each cell in that row is a spot on that team. Each red cell represents the minimum required members for a team.
					</p>
					<?php
					if($rank == "admin" || $rank == "officer"){
					?>
						<button onclick="window.print()" class="utilityButton">Print Sheet</button>
					<?php
					}
					?>
					<?php
					if($rank == "admin" || ($rank == "officer" && $emailPerm == "yes")){
					?>
						<form method="post" action="../php/confirmEventsEmail.php">
							<input type="submit" id="confirmEventsButton" name="confirmEventsButton" value="Email Event Confirmation" class="utilityButton" />
						</form>
					<?php
					}
					?>
					<?php
					if($rank == "admin"){
					?>
						<form method="post" target="hideFrame">
							<input type="hidden" id="deleventOn" name="deleventOn" value="blank" />
							<input type="submit" value="Remove User From Slot" class="utilityButton" />
						</form>
					<?php
					}
					?>
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
					<div id="changed" style="display:none;"></div>
					<div id="events"><center>Loading Teams...</center></div>
					<script>
					//$("#events").load('../php/getTeams.php');
					</script>
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