<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin"){

//functions for clearing members
if(isset($_POST['verify'])){

	//file viewability
	$verify = $_POST['verify'];
	$rankClear = "member";

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM users WHERE rank='$rankClear'";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$fmsg =  "Member Account Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Member Account Data Failed to Clear!";

	}

	mysql_close();

}

//functions for clearing minutes
if(isset($_POST['verify2'])){

	//file viewability
	$verify = $_POST['verify2'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM minutes";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$fmsg =  "Minutes Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Minutes Data Failed to Clear!";

	}

	mysql_close();

}

//functions for clearing announcements
if(isset($_POST['verify4'])){

	//file viewability
	$verify = $_POST['verify4'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM announcements";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$fmsg =  "Announcements Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Announcements Data Failed to Clear!";

	}

	mysql_close();

}

//functions for updating TEAMS table with info from EVENTS table
if(isset($_POST['verify3'])){

	//file viewability
	$verify = $_POST['verify3'];
	$conference = $_POST['conference'];

	require('../php/connect.php');

	if($verify == "yes"){

		//update the conference
		$sql = "UPDATE settings SET value='$conference' WHERE name='conference'";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}

		//clear TEAMS table
		$sql = "DELETE FROM teams";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}

		//reset TEAMS id
		$sql = "ALTER TABLE teams AUTO_INCREMENT = 1";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}

		//get EVENTS data for the specified competition level
		$query = "SELECT id, name, teams FROM events WHERE conference='$conference'";

		$result = mysql_query($query);

		if (!$result){
			die('Error: ' . mysql_error());
		}

		//for each event at the current competition level
		while(list($id, $name, $teams) = mysql_fetch_array($result)){
				
			//for each team of each event
			for($i = 1; $i <= $teams; $i++){

				//add that event to the TEAMS table
				$sql = "INSERT INTO teams (event, team) VALUES ('$name', '$i')";

				if (!mysql_query($sql)){
					die('Error: ' . mysql_error());
				}

			}

		}

		$fmsg =  "Event Selection Reset Successfully!";

	}
	else{

		$fmsg =  "Failed to Reset Event Selection! It Was Not Verified!";

	}

	mysql_close();

}

?>

<!DOCTYPE html>

<head>
	<title>
		Chapter Sweet
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
				Admin Settings
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane">

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

				<!--Description-->
					<p class="bodyTextType1">
						These settings are for ADMINS ONLY. They are <b> DANGEROUS </b> and have a risk of <b> OVERRIDING IMPORTANT DATA! </b> Proceed with caution, and verify that any function here is used intentionally.
					</p>

				<button class="accordion">Clear Member Account Data</button>
				<div class="panel" id="clearMemberDiv">
					<!--clear member account data tab-->
					<form method="post" id="clearMemberForm">
						<br>
						Are You Sure? :
						<select id="verify" name="verify">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:#B60000;" value="Clear Data">
					</form>

				</div>

				<br>
				<br>

				<button class="accordion">Clear Minutes Data</button>
				<div class="panel" id="clearMinutesDiv">
					<!--clear member account data tab-->
					<form method="post" id="clearMinutesForm">
						<br>
						Are You Sure? :
						<select id="verify2" name="verify2">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:#B60000;" value="Clear Data">
					</form>

				</div>

				<br>
				<br>

				<button class="accordion">Reset Event Selection</button>
				<div class="panel" id="updateTeamsDiv">
					<!--clear member account data tab-->
					<form method="post" id="clearMinutesForm">
						<br>
						Competition Level :
						<select id="conference" name="conference">
							<option value="regional">Regional</option>
							<option value="state">State</option>
							<option value="national">National</option>
						</select>
						<br><br>
						Are You Sure? :
						<select id="verify3" name="verify3">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:#B60000;" value="Reset">
					</form>

				</div>

				<br>
				<br>

				<button class="accordion">Clear Announcements Data</button>
				<div class="panel" id="clearAnnouncementsDiv">
					<!--clear announcements data tab-->
					<form method="post" id="clearMinutesForm">
						<br>
						Are You Sure? :
						<select id="verify4" name="verify4">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:#B60000;" value="Clear Data">
					</form>

				</div>

			</div>

<!--Spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

</html>

<?php
}
?>