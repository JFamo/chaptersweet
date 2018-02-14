<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin" || $rank == "adviser"){

//functions for clearing members
if(isset($_POST['verify'])){

	//file viewability
	$verify = $_POST['verify'];
	$rankClear = "member";

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM users WHERE rank='$rankClear'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Member Account Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Member Account Data Failed to Clear!";

	}

	mysqli_close($link);

}

//functions for clearing minutes
if(isset($_POST['verify2'])){

	//file viewability
	$verify = $_POST['verify2'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM minutes";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Minutes and Files Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Minutes and Files Data Failed to Clear!";

	}

	mysqli_close($link);

}

//functions for clearing announcements
if(isset($_POST['verify4'])){

	//file viewability
	$verify = $_POST['verify4'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM announcements";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Announcements Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Announcements Data Failed to Clear!";

	}

	mysqli_close($link);

}

//functions for clearing transactions
if(isset($_POST['verify5'])){

	//verification
	$verify = $_POST['verify5'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "DELETE FROM transactions";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Transactions Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Transactions Data Failed to Clear!";

	}

	mysqli_close($link);

}

//functions for clearing event points
if(isset($_POST['verify6'])){

	//verification
	$verify = $_POST['verify6'];

	require('../php/connect.php');

	if($verify == "yes"){

		$sql = "UPDATE users SET eventpoints=0";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Event Points Data Cleared Successfully!";

	}
	else{

		$fmsg =  "Event Points Data Failed to Clear!";

	}

	mysqli_close($link);

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

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

		//clear TEAMS table
		$sql = "DELETE FROM teams";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

		//clear TASKS table
		$sql = "DELETE FROM tasks";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

		//reset TEAMS id
		$sql = "ALTER TABLE teams AUTO_INCREMENT = 1";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

		//get EVENTS data for the specified competition level
		$query = "SELECT id, name, membermin, membermax, teams, qualifier FROM events WHERE conference='$conference'";

		$result = mysqli_query($link, $query);

		if (!$result){
			die('Error: ' . mysqli_error($link));
		}

		//for each event at the current competition level
		while(list($id, $name, $min, $max, $teams, $isq) = mysqli_fetch_array($result)){
		
			//handle qualifiers with no teams
			if($teams == 0){
				$tt = 0;
				$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$tt', NULL, NULL, NULL, NULL, NULL, NULL, '$isq')";
				
				if (!mysqli_query($link, $sql)){
					die('Error: ' . mysqli_error($link));
				}
			}
				
			//for each team of each event
			for($i = 1; $i <= $teams; $i++){
			
				//add that event to the TEAMS table
				$blank = ' ';
				if($max == 1){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', NULL, NULL, NULL, NULL, NULL, '$isq')";
				}
				if($max == 2){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', '$blank', NULL, NULL, NULL, NULL, '$isq')";
				}
				if($max == 3){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', '$blank', '$blank', NULL, NULL, NULL, '$isq')";
				}
				if($max == 4){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', '$blank', '$blank', '$blank', NULL, NULL, '$isq')";
				}
				if($max == 5){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', '$blank', '$blank', '$blank', '$blank', NULL, '$isq')";
				}
				if($max == 6){
					$sql = "INSERT INTO teams (event, team, member1, member2, member3, member4, member5, member6, qualifier) VALUES ('$name', '$i', '$blank', '$blank', '$blank', '$blank', '$blank', '$blank', '$isq')";
				}
				

				if (!mysqli_query($link, $sql)){
					die('Error: ' . mysqli_error($link));
				}

			}

		}

		$fmsg =  "Event Selection Reset Successfully For " . $conference . " Conference!";

	}
	else{

		$fmsg =  "Failed to Reset Event Selection! It Was Not Verified!";

	}

	mysqli_close($link);

}

//function for updating Chapter Code Setting
if(isset($_POST['chapterCode'])){

	//code
	$newCode = $_POST['chapterCode'];

	require('../php/connect.php');

	$sql = "UPDATE settings SET value='$newCode' WHERE name='code'";

	if (!mysqli_query($link, $sql)){
		die('Error: ' . mysqli_error($link));
	}
	
	$fmsg =  "Chapter Code Updated!";

	mysqli_close($link);

}

//function for updating Officer Info Permission Setting
if(isset($_POST['officerInfoPerm'])){

	//file viewability
	$level = $_POST['officerInfoPerm'];

	require('../php/connect.php');

	$sql = "UPDATE settings SET value='$level' WHERE name='officerInfoPermission'";

	if (!mysqli_query($link, $sql)){
		die('Error: ' . mysqli_error($link));
	}
	
	$fmsg =  "Officer Permissions Updated!";

	mysqli_close($link);

}

//function for updating Officer Info Permission Setting
if(isset($_POST['officerEmailPerm'])){

	//file viewability
	$level = $_POST['officerEmailPerm'];

	require('../php/connect.php');

	$sql = "UPDATE settings SET value='$level' WHERE name='officerEmailPermission'";

	if (!mysqli_query($link, $sql)){
		die('Error: ' . mysqli_error($link));
	}
	
	$fmsg =  "Officer Permissions Updated!";

	mysqli_close($link);

}

//function for blocking pages
if(isset($_POST['blockedPages'])){

	//file viewability
	$level = $_POST['blockedPages'];

	require('../php/connect.php');

	$sql = "UPDATE settings SET value='$level' WHERE name='blockPages'";

	if (!mysqli_query($link, $sql)){
		die('Error: ' . mysqli_error($link));
	}
	
	$fmsg =  "Blocked Pages Updated!";

	mysqli_close($link);

}

?>

<!DOCTYPE html>

<head>
	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
	<title>
		Chapter Sweet
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!--Spooky bar at the top-->
	<nav class="navbar navbar-dark darknav navbar-expand-sm">
	  	<div class="container-fluid">
		   	<a class="navbar-brand" href="#"><img src="../imgs/iconImage.png" alt="icon" width="60" height="60">Chapter <?php if($_SESSION['chapter'] == 'freshman'){ echo "<i>Fresh</i>"; }else{ echo "Sweet"; } ?></a>
		<div class="ml-auto navbar-nav">
		    	<a class="nav-item nav-link active" href="../php/logout.php">Logout</a>
		</div>
	</div>
	</nav>
<!--Spooky stuff in the middle-->
	<div class="container-fluid">
		<div class="row">
		<div style="padding-right:0; padding-left:0;" class="col-sm-2 darknav">
			<nav style="width:100%;" class="navbar navbar-dark darknav">
			  <div class="container">
			  <ul class="nav navbar-nav align-top">
			   <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
			   <?php
				if($rank == "admin" || $rank == "officer" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="users.php">My Chapter</a></li>
			   <?php
				}
				?>
			   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="files.php">Files</a></li>
			   <?php
				}
				?>
			   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="rules.php">Event Rules</a></li>
			   <?php
				}
				?>
	          	   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="secretary.php">Secretary</a></li>
			   <?php
				}
				?>
			   <?php
				 if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesAnnouncements" || $officerPerm == "filesAnnouncements" || $officerPerm == "announcements")) || $rank == "admin" || $rank == "adviser"){
				 ?>
			    <li class="nav-item"><a class="nav-link" href="reporter.php">Reporter</a></li>
			    <?php
				 }
				 ?>
		           <?php
				 if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ 
				 ?>
			    <li class="nav-item"><a class="nav-link" href="treasurer.php">Treasurer</a></li>
			    <?php
				 }
				 ?>
                           <?php
				 if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				 ?>
			    <li class="nav-item"><a class="nav-link" href="parli.php">Parliamentarian</a></li>
			    <?php
				 }
				 ?>
			   <li class="nav-item"><a class="nav-link" href="eventSelection.php">Event Selection</a></li>
			   <?php
				if($rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item active"><a class="nav-link" href="#">Adviser Settings</a></li>
			   <?php
				}
				?>
			  </ul>
			  </div>
			</nav>
		</div>
		<div style="padding-right:0; padding-left:0; padding-top:15px; padding-bottom:15px; overflow:hidden; background-color:#efefef;" class="col-sm-10">
		<p class="display-4" style="padding-left:20px;">
			Adviser Settings
		</p>

			<?php
			if(isset($fmsg)){
				?>
					<div style="margin: 15px 15px 15px 15px;" class="alert alert-info" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						</button>
				  		<p><?php
							echo $fmsg;
						?></p>
					</div>
				<?php
				}
			?>

				<!--Description-->
					<p class="bodyTextType1">
						These settings are for ADVISERS ONLY. They are <b> DANGEROUS </b> and have a risk of <b> OVERRIDING IMPORTANT DATA! </b> Proceed with caution, and verify that any function here is used intentionally.
					</p>

				<!--SETTINGS PANES-->
				<center>
				<!--General Settings-->
				<div class="adminDataSection">
				<p class="userDashSectionHeader" style="padding-left:0px;">Chapter Code</p><br>

					<?php
					//UPDATE THE VALUE OF THE ABOVE FORM
						//get permission settings
						require('../php/connect.php');

						$queryC="SELECT value FROM settings WHERE name='code'";

						$resultC = mysqli_query($link, $queryC);

						if (!$resultC){
							die('Error: ' . mysqli_error($link));
						}

						//save the result
						list($code) = mysqli_fetch_array($resultC);
						$chapterCode = $code;
					?>

					<!--officer info permission setting-->
					<form class="basicSpanForm" style="width:100%;" method="post">
						<span>
							<b>Chapter Code</b>
							<br>
							<p class="description">The code used to register to your chapter.</p>
						</span>
						<span>
							<input type="text" id="chapterCode" name="chapterCode" onchange="this.form.submit()" value="<?php echo $chapterCode ?>" />
						</span>
					</form>

				<br></div><br>
				<div class="adminDataSection">
				<p class="userDashSectionHeader" style="padding-left:0px;">Settings</p><br>

					<!--officer info permission setting-->
					<form class="basicSpanForm" style="width:100%;" method="post">
						<span>
							<b>Officer Permissions for Info Posting</b>
							<br>
							<p class="description">Which types of information can officers add/post?</p>
						</span>
						<span>
							Permission:
							<select id="officerInfoPerm" name="officerInfoPerm" onchange="this.form.submit()">
								<option value="all">All</option>
								<option value="minutesFiles">Minutes and Files</option>
								<option value="minutesAnnouncements">Minutes and Announcements</option>
								<option value="filesAnnouncements">Files and Announcements</option>
								<option value="minutes">Minutes Only</option>
								<option value="files">Files Only</option>
								<option value="announcements">Announcements Only</option>
							</select>
						</span>
					</form>
					<?php
					//UPDATE THE VALUE OF THE ABOVE FORM
						//get permission settings
						require('../php/connect.php');

						$query="SELECT value FROM settings WHERE name='officerInfoPermission'";

						$result = mysqli_query($link, $query);

						if (!$result){
							die('Error: ' . mysqli_error($link));
						}

						//save the result
						list($perm) = mysqli_fetch_array($result);
						$officerPerm = $perm;
					?>
					<br>

					<!--officer email permission setting-->
					<form class="basicSpanForm" style="width:100%;" method="post">
						<span>
							<b>Officer Permission to Send Emails</b>
							<br>
							<p class="description">Can officers send emails to all members?</p>
						</span>
						<span>
							Permission:
							<select id="officerEmailPerm" name="officerEmailPerm" onchange="this.form.submit()">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
					</form>
					<?php
					//UPDATE THE VALUE OF THE ABOVE FORM
						//get permission settings
						require('../php/connect.php');

						$query="SELECT value FROM settings WHERE name='officerEmailPermission'";

						$result = mysqli_query($link, $query);

						if (!$result){
							die('Error: ' . mysqli_error($link));
						}

						//save the result
						list($perm) = mysqli_fetch_array($result);
						$emailPerm = $perm;
					?>
					<br>

					<!--blocked pages setting-->
					<form class="basicSpanForm" style="width:100%;" method="post">
						<span>
							<b>Pages Blocked to Non-Admins</b>
							<br>
							<p class="description">Users Page and Adviser Settings Page are blocked by default.</p>
						</span>
						<span>
							Blocked Pages:
							<select id="blockedPages" name="blockedPages" onchange="this.form.submit()">
								<option value="none">None</option>
								<option value="events">Event Selection</option>
								<option value="info">Information</option>
								<option value="all">All</option>
							</select>
						</span>
					</form>
					<?php
					//UPDATE THE VALUE OF THE ABOVE FORM
						//get permission settings
						require('../php/connect.php');

						$query="SELECT value FROM settings WHERE name='blockPages'";

						$result = mysqli_query($link, $query);

						if (!$result){
							die('Error: ' . mysqli_error($link));
						}

						//save the result
						list($perm) = mysqli_fetch_array($result);
						$blockPages = $perm;
					?>
					<br>

				<br></div>
				<br>
				<!--Data Management-->
				<div class="adminDataSection">
				<p class="userDashSectionHeader" style="padding-left:0px;">Data Management</p><br>

					<!--clear member account data tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearMemberForm">
						<span>
							<b>Clear Member Account Data</b>
						</span>
						<span>
						</span>
						<span>
							Are You Sure? :
							<select id="verify" name="verify">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Clear Data">
						</span>
					</form>
					<br>
					<!--clear minutes and files tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearMinutesForm">
						<span>
							<b>Clear Minutes and Files</b>
						</span>
						<span>
						</span>
						<span>
							Are You Sure? :
							<select id="verify2" name="verify2">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Clear Data">
						</span>
					</form>
					<br>
					<!--reset event selection tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearTeamsForm">
						<span>
							<b>Reset Event Selection and Teams</b>
						</span>
						<span>
							Competition Level :
							<select id="conference" name="conference">
								<option value="regional">Regional</option>
								<option value="state">State</option>
								<option value="national">National</option>
							</select>
						</span>
						<span>
							Are You Sure? :
							<select id="verify3" name="verify3">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Reset Data">
						</span>
					</form>
					<br>
					<!--clear announcements tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearAnnouncementsForm">
						<span>
							<b>Clear Announcements</b>
						</span>
						<span>
						</span>
						<span>
							Are You Sure? :
							<select id="verify4" name="verify4">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Clear Data">
						</span>
					</form>
					<!--clear transactions tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearTransactionsForm">
						<span>
							<b>Clear Transactions</b>
						</span>
						<span>
						</span>
						<span>
							Are You Sure? :
							<select id="verify5" name="verify5">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Clear Data">
						</span>
					</form>
					<!--clear event points tab-->
					<form class="basicSpanForm" style="width:100%;" method="post" id="clearEventpointsForm">
						<span>
							<b>Clear Event Points</b>
						</span>
						<span>
						</span>
						<span>
							Are You Sure? :
							<select id="verify6" name="verify6">
								<option value="no">No</option>
								<option value="yes">Yes</option>
							</select>
						</span>
						<span>
							<input type="submit" class="btn btn-danger" value="Clear Data">
						</span>
					</form>

				<br></div>

			</div>
		</center>
		</div>
		</div>
		</div>

<!--Spooky stuff at the bottom-->
		<footer class="darknav">
			<center><p class="bodyTextType2">
				Copyright Joshua Famous 2017
			</p></center>
		</footer>
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>
<script type="text/javascript">
	updateSettings( <?php echo(json_encode($officerPerm).",".json_encode($emailPerm).",".json_encode($blockPages).",".json_encode($chapterCode)); ?> );
</script>

</html>

<?php
}
?>