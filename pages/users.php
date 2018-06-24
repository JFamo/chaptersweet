<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$chapter = $_SESSION['chapter'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin" || $rank == "officer" || $rank == "adviser"){

//get permission settings
require('../php/connect.php');

//INFO POSTING
$query="SELECT value FROM settings WHERE name='officerInfoPermission' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$officerPerm = $perm;

//EVENT POINTS for OFFICERS
$query="SELECT value FROM settings WHERE name='eventpointsPermission' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$eventPointsPerm = $perm;


//function to update points by grade
if(isset($_POST['grade'])){

	//file viewability
	$grade = $_POST['grade'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE grade='$grade' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users in Grade " . $grade . " Successfully!";
		
		$activityForm = "Added " . $points . " Event Points to Grade " . $grade;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to update points by rank
if(isset($_POST['rank'])){

	//file viewability
	$rank = $_POST['rank'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE rank='$rank' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users of Rank " . $rank . " Successfully!";
		
		$activityForm = "Added " . $points . " Event Points to Rank " . $rank;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to update points by group
if(isset($_POST['group'])){

	//file viewability
	$group = $_POST['group'];
	$points = $_POST['points'];

	require('../php/connect.php');

		if($group == "all"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE chapter='$chapter'";
		}
		if($group == "upper"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE (grade='11' OR grade='12') AND chapter='$chapter'";
		}
		if($group == "lower"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE (grade='9' OR grade='10') AND chapter='$chapter'";
		}

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users of Group " . ucfirst($group) . " Successfully!";
		
		$activityForm = "Added " . $points . " Event Points to Group " . ucfirst($group);
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to remove a user from an event
if(isset($_POST['eventDelete'])){

	//file viewability
	$event = $_POST['eventDelete'];
	$user = $_POST['deleteEventUser'];
        $blank = ' ';

	require('../php/connect.php');

		$sql = "UPDATE teams SET member1 = '$blank' WHERE member1='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member2 = '$blank' WHERE member2='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member3 = '$blank' WHERE member3='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member4 = '$blank' WHERE member4='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member5 = '$blank' WHERE member5='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member6 = '$blank' WHERE member6='$user' AND event = '$event' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Removed " . $user . " from " . $event . " Successfully!";
		
		$activityForm = "Removed " . $user . " From Event " . $event;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to update points by individual
if(isset($_POST['pointsTo'])){

	//post variables
	$user = $_POST['pointsTo'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE id='$user'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to User with ID " . $user . " Successfully!";
		
		$activityForm = "Added " . $points . " Event Points to an Individual";
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to remove points by individual
if(isset($_POST['pointsFrom'])){

	//post variables
	$user = $_POST['pointsFrom'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints-'$points' WHERE id='$user'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Removed " . $points . " Event Points to User with ID " . $user . " Successfully!";
		
		$activityForm = "Removed" . $points . " Event Points from an Individual";
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}


//function to change user rank
if(isset($_POST['promoteUser'])){

	$user = $_POST['thisUser'];
	$newRank = $_POST['newRank'];

	require('../php/connect.php');

		$sql = "UPDATE users SET rank='$newRank' WHERE fullname='$user' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Changed " . $user . " to Rank " . ucfirst($newRank) . " Successfully!";
		
		$activityForm = "Changed " . $user. " to Rank " . ucfirst($newRank);
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to change user ID
if(isset($_POST['idname'])){

	$user = $_POST['idname'];
	$newNum = $_POST['idnum'];

	require('../php/connect.php');

		$sql = "UPDATE users SET idnumber='$newNum' WHERE id='$user' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Changed " . $user . " to ID " . $newNum . " Successfully!";
		
		$activityForm = "Changed " . $user. " to ID " . $newNum;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to delete user
if(isset($_POST['deleteUser'])){

	$user = $_POST['thisUser'];
	$confirm = $_POST['confirmDeleteUser'];
        $blank = ' ';

	if($confirm == "yes"){

	require('../php/connect.php');

		//first get fullname, given id
		$sql = "SELECT fullname FROM users WHERE id='$user'";

		$result = mysqli_query($link, $sql);

		if (!$result){
			die('Error: ' . mysqli_error($link));
		}

		list($userFullname) = mysqli_fetch_array($result);

		$sql2 = "UPDATE teams SET member1 = '$blank' WHERE member1='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member2 = '$blank' WHERE member2='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member3 = '$blank' WHERE member3='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member4 = '$blank' WHERE member4='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member5 = '$blank' WHERE member5='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member6 = '$blank' WHERE member6='$userFullname' AND chapter='$chapter'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}

		$sql3 = "DELETE FROM users WHERE id='$user'";

		if (!mysqli_query($link, $sql3)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Deleted User with ID " . $user . " Successfully!";
		
	mysqli_close($link);

	}
	else{
		$fmsg =  "Deletion Failed! It Was Not Confirmed!";
	}

}

//function to change user obligation
if(isset($_POST['obligationChange'])){

	$user = $_POST['thisUser'];
	$newValue = $_POST['newValue'];
	$obligation = $_POST['obligation'];
	$oblignum = $_POST['num'];
	
	$newOblig = substr($obligation, 0, ($oblignum*2)) . $newValue . substr($obligation, ($oblignum * 2) + 2);

	require('../php/connect.php');

		$sql = "UPDATE users SET obligation='$newOblig' WHERE fullname='$user' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Changed " . $user . " to " . $newValue . "Successfully!";

	mysqli_close($link);

}


//function to create a new obligation
if(isset($_POST['obligationName'])){

	$name = $_POST['obligationName'];
	$name = str_replace(' ','_',$name);
	$defaultValue = $_POST['default'];

	require('../php/connect.php');

		$sql = "INSERT INTO obligations (name, chapter) VALUES ('$name', '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$sql = "UPDATE users SET obligation = CONCAT(obligation, '$defaultValue') WHERE chapter = '$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added Obligation " . $name. " Successfully!";
		
		$activityForm = "Added Obligation " . $name;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

//function to delete an obligation
if(isset($_POST['deleteObligation'])){

	$name = $_POST['deleteObligation'];

	require('../php/connect.php');
	
		$sql = "SELECT * FROM obligations WHERE chapter = '$chapter' ORDER BY id ASC";
	
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		$q = 0;
		while($row = mysqli_fetch_array($result)) {
			if($row['name'] == $name){
				$thisNum = $q;
			}
			$q ++;
		}

		$sql = "DELETE FROM obligations WHERE name='$name'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$sql = "UPDATE users SET obligation = CONCAT(SUBSTRING(obligation, 1, '$thisNum' * 2), SUBSTRING(obligation, ('$thisNum' * 2) + 3)) WHERE chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Deleted Obligation " . $name. " Successfully!";
		
		$activityForm = "Deleted Obligation " . $name;
		$longname = $_SESSION['fullname'];
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$longname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

}

?>

<!DOCTYPE html>

<head>

	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
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
			   <li class="nav-item active"><a class="nav-link" href="users.php">My Chapter</a></li>
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
			   <?php
				if(!($blockedPages == "events" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="eventSelection.php">Event Selection</a></li>
			   <?php
				}
				?>
			   <?php
				if($rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="danger.php">Adviser Settings</a></li>
			   <?php
				}
				?>
			  </ul>
			  </div>
			</nav>
		</div>
		<div style="padding-right:0; padding-left:0; padding-top:15px; padding-bottom:15px; background-color:#efefef;" class="col-sm-10">

<!--Description-->
	<p class="display-4" style="padding-left:20px;">
		My Chapter
	</p>	
	<center>
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
				else{
				?>
	<div style="margin: 15px 15px 15px 15px;" class="alert alert-success" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
  		<p>Here advisers can review user information, check user event progress, manage user accounts, and get a summary of the chapter.
		The summary box shows a general overview of member distribution.<br>
		The user info box shows important information about users, as well as providing options to manage them.</p>
	</div>
	<?php
		}
	?>
				<!--User Table-->
				<center>
				<!--Summary-->
				<div class="adminDataSection" style="margin-bottom:15px;">
				<p class="userDashSectionHeader" style="padding-left:0px;">Summary</p>
				<div class="basicSpanDiv">

					<?php

					require("../php/connect.php");

					//get number of users
					$query="SELECT id FROM users WHERE chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Total Users : <b>" . $numUsers . "</b></p></span>";

					//get number of advisers
					$query="SELECT id FROM users WHERE rank='adviser' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Advisers : <b>" . $numUsers . "</b></p></span>";

					//get number of officers
					$query="SELECT id FROM users WHERE rank='officer' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Officers : <b>" . $numUsers . "</b></p></span>";

					//get number of members
					$query="SELECT id FROM users WHERE rank='member' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Members : <b>" . $numUsers . "</b></p></span>";

					?>

				</div>
				<div class="basicSpanDiv">

					<?php

					require("../php/connect.php");

					//get number of 9th graders
					$query="SELECT id FROM users WHERE grade='9' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Freshmen : <b>" . $numUsers . "</b></p></span>";

					//get number of 10th graders
					$query="SELECT id FROM users WHERE grade='10' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Sophomores : <b>" . $numUsers . "</b></p></span>";

					//get number of 11th graders
					$query="SELECT id FROM users WHERE grade='11' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Juniors : <b>" . $numUsers . "</b></p></span>";

					//get number of 12th graders
					$query="SELECT id FROM users WHERE grade='12' AND chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Seniors : <b>" . $numUsers . "</b></p></span>";

					mysqli_close($link);

					?>

				</div>
				<div class="basicSpanDiv">

					<?php

					require("../php/connect.php");

					//get total balance
					$query="SELECT SUM(balance) FROM users WHERE chapter='$chapter'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					list($cumBalance) = mysqli_fetch_array($result);

					echo "<span><p class='bodyTextType1'>Cumulative Balance : <b>" . $cumBalance . "</b></p></span>";

					mysqli_close($link);

					?>

				</div>
				</div>
				<div class="adminDataSection" style="margin-bottom:15px; overflow:auto;">
				<br>
				<p class="userDashSectionHeader" style="padding-left:0px;">User Info</p>
				<br>
				<input class="form-control" id="myInput" type="text" placeholder="Search..">
				<script>
				$(document).ready(function(){
				  $("#myInput").on("keyup", function() {
				    var value = $(this).val().toLowerCase();
				    $("#usersTable tr").filter(function() {
				      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				    });
				  });
				});
				</script>
				<br>
				<table class="usersTable" id="usersTable" cellspacing="0" cellpadding="0" style="overflow:auto; margin-left:10px;">
					<tr>
					
						<td style="width:250px; height:30px;"><b>Name</b></td>
						<td style="width:80px; height:30px;"><b>Grade</b></td>
						<td style="width:100px; height:30px;"><b>Rank</b></td>
						<td style="width:200px; height:30px;"><b>Email</b></td>
						<td style="width:80px; height:30px;"><b>Events</b></td>
						<td style="width:80px; height:30px;"><b>Event Points</b></td>
						<td style="width:80px; height:30px;"><b>Balance</b></td>
						<td style="width:80px; height:30px;"><b>Options</b></td>
					
						<?php
						
						require('../php/connect.php');
						
						//get the user columns
						$query="SELECT * FROM obligations WHERE chapter='$chapter'";
		
						$result = mysqli_query($link, $query);
		
						if (!$result){
							die('Error: ' . mysqli_error($link));
						}
						
						$numberOfObligations = mysqli_num_rows($result);
						
						while($row = mysqli_fetch_array($result)) {
						   	echo "<td style='width:100px; height:30px;'><b>" . ucfirst($row['name']) . "</b></td>";
						}
						
						?>

					</tr>
				<?php

				require('../php/connect.php');

				//get user details
				$query="SELECT * FROM users WHERE chapter='$chapter' ORDER BY fullname";
				$result = mysqli_query($link, $query);
				if (!$result){
					die('Error: ' . mysqli_error($link));
				}

				if(mysqli_num_rows($result) == 0){
					echo "No Users Found!<br>";
				}
				else{
					while($resultArray = mysqli_fetch_array($result)){

						$fullname = $resultArray['fullname'];
						$grade = $resultArray['grade'];
						$thisrank = $resultArray['rank'];
						$eventpoints = $resultArray['eventpoints'];
						$thisemail = $resultArray['email'];
						$thisbalance = $resultArray['balance'];
						$oblig = $resultArray['obligation'];

						if($thisrank != "admin"){

						?>

						<tr class="userRow">

						<td style="width:250px; height:30px;"><?php echo "".$fullname ?></td>
						<td style="width:60px; height:30px;"><?php if($thisrank != "adviser"){ echo "".$grade; } ?></td>
						<td style="width:100px; height:30px;"><?php echo "".$thisrank ?></td>
						<td style="width:200px; height:30px;"><?php echo "".$thisemail ?></td>
						<td style="width:80px; height:30px;"><?php if($thisrank != "adviser"){
							require('../php/connect.php');
							//get user's events
							$eventsQuery="SELECT event FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter'";
							$eventsResult = mysqli_query($link, $eventsQuery);
							if (!$eventsResult){
								die('Error: ' . mysqli_error($link));
							}
							$numEvents = mysqli_num_rows($eventsResult);
							if($numEvents < 3 || $numEvents > 6){ echo "<p style='color:red;'>"; } 
							echo $numEvents;
							if($numEvents < 3 || $numEvents > 6){ echo "</p>"; }
							mysqli_close($link);
							}
						?></td>
						<td style="width:80px; height:30px;"><?php if($thisrank != "adviser"){ echo "".$eventpoints; } ?></td>
						<td style="width:80px; height:30px;"><?php echo "".$thisbalance ?></td>
						<td style="width:80px; height:30px;">
							<form method="post" style="float:left; padding-right:5px;">
								<input type="hidden" id="viewEvents" name="viewEvents" value="<?php echo addslashes($fullname) ?>">
                                                                <input type="hidden" id="viewEventsRank" name="viewEventsRank" value="<?php echo $thisrank ?>">
								<input type="submit" name="viewUser" class="btn btn-primary" onclick="" value="User Options">
							</form>
							<!-- 
							<?php if(($thisrank != "admin" && $thisrank != "adviser") && ($rank == "admin" || $rank == "adviser")){ ?>
							<form method="post" style="float:left; padding-right:5px; padding-bottom:10px;">
								<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
								<input type="hidden" name="newRank" value="<?php 
									if($thisrank=='member'){ echo 'officer'; }
									if($thisrank=='officer'){ echo 'member'; } 
								?>" />
								<input type="submit" name="promoteUser" class="btn btn-primary" value="Make <?php 
									if($thisrank=='member'){ echo 'Officer'; }
									if($thisrank=='officer'){ echo 'Member'; } 
								?>" />
							</form>
							<br>
							<?php } ?> -->
						</td>

						<?php

						if($thisrank != "adviser"){

							for($i = 0; $i < $numberOfObligations; $i ++){ ?>
								<td style="width:100px; height:30px;">
									<form method="post" target="#hideFrame">
										<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
										<input type="hidden" name="num" value="<?php echo $i ?>" />
										<input type="hidden" name="obligation" value="<?php echo $oblig ?>" />
										<input type="hidden" name="newValue" value="<?php
											$thisval = substr($oblig, ($i * 2), 2);
											if($thisval=='ye'){ echo 'no'; }
											if($thisval=='no'){ echo 'ye'; } 
										?>" />
										<input type="submit" class="<?php
											if($thisval=='ye'){ echo 'btn btn-success'; }
											if($thisval=='no'){ echo 'btn btn-danger'; } 
										?>" name="obligationChange" value="<?php 
											if($thisval=='ye'){ echo 'Yes'; }
											if($thisval=='no'){ echo 'No'; } 
										?>" />
									</form>
								</td>
								<?php
							}

						}

						?>

						</tr>

						<?php

						}
					}
				}
				?>
				</table>

				<br>
				<br>

				</div>

<!-- User Info Modal -->
						<div class="modal fade" id="userModal" role="dialog">
					    
						</div>
				<?php if($rank == "admin" || $rank == "adviser" || ($rank == "officer" && $eventPointsPerm == "yes")){ ?>
				<!--Points-->
				<div class="adminDataSection" style="margin-bottom:15px;">
				<p class="userDashSectionHeader" style="padding-left:0px;">Assign Event Points</p><br>

						<form class="basicSpanDiv" method="post" id="pointsGradeForm" style="width:100%; height:40px; padding-top:15px;">
							<span>
							By Grade
							</span>
							<span>
							Grade : 
							<select id="grade" name="grade">
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							</span>
							<span>
							How Many Points :
							<input type="number" id="points" name="points">
							</span>
							<span>
							<input type="submit" class="btn btn-primary" value="Assign Points">
							</span>
						</form>
						<br>
						<form class="basicSpanDiv" method="post" id="pointsRankForm" style="width:100%; height:40px; padding-top:15px;">
							<span>
							By Rank
							</span>
							<span>
							Rank :
							<select id="rank" name="rank">
								<option value="member">Members</option>
								<option value="officer">Officers</option>
								<option value="adviser">Advisers</option>
							</select>
							</span>
							<span>
							How Many Points :
							<input type="number" id="points" name="points">
							</span>
							<span>
							<input type="submit" class="btn btn-primary" value="Assign Points">
							</span>
						</form>
						<br>
						<form class="basicSpanDiv" method="post" id="pointsAllForm" style="width:100%; height:40px; padding-top:15px;">
							<span>
							By Group
							</span>
							<span>
							Group :
							<select id="group" name="group">
								<option value="all">All</option>
								<option value="upper">Upperclassmen</option>
								<option value="lower">Lowerclassmen</option>
							</select>
							</span>
							<span>
							How Many Points :
							<input type="number" id="points" name="points">
							</span>
							<span>
							<input type="submit" class="btn btn-primary" value="Assign Points">
							</span>
						</form>

				<br>
				</div>
				<?php } ?>
				<!--Obligations-->
				<div class="adminDataSection" style="margin-bottom:15px;">
				<p class="userDashSectionHeader" style="padding-left:0px;">Obligations</p>
					<form class="basicSpanDiv" method="post" id="newObligationForm" style="width:100%; height:40px; padding-top:15px;">
						<span>
						<b>Create New Obligation</b>
						</span>
						<span>
						Default: 
						<select id="default" name="default">
							<option value="no">Incomplete</option>
							<option value="ye">Complete</option>
						</select>
						</span>
						<span>
						Name :
						<input type="text" id="obligationName" name="obligationName">
						</span>
						<span>
						<input type="submit" class="btn btn-primary" value="Create">
						</span>
					</form>
					<form class="basicSpanDiv" method="post" id="deleteObligationForm" style="width:100%; height:40px; padding-top:15px;">
						<span>
						<b>Delete Obligation</b>
						</span>
						<span>
						Obligation: 
						<select id="deleteObligation" name="deleteObligation">
							<?php
						
							require('../php/connect.php');
							
							//get the user columns
							$query="SELECT * FROM obligations WHERE chapter='$chapter'";
			
							$result = mysqli_query($link, $query);
			
							if (!$result){
								die('Error: ' . mysqli_error($link));
							}
							
							while($row = mysqli_fetch_array($result)) {
								$obligName = $row['name'];
									?>
									<option value="<?php echo $obligName; ?>"><?php echo $obligName; ?></option>
									<?php
							}
							
							?>
						</select>
						</span>
						<span>
						<input type="submit" class="btn btn-danger" value="Delete">
						</span>
					</form>
					<br>
				</div>
				
				</center>

			</div>
		</div>
	</div>
	</div>	

	<iframe name="hideFrame" id="hideFrame" style="display:none;"></iframe>

<!--Spooky stuff at the bottom-->
		<footer class="darknav">
			<center><p class="bodyTextType2">
				Copyright T1285 2018
			</p></center>
		<?php if(isset($_POST['viewUser'])){
			$_SESSION['eventsUser'] = $_POST['viewEvents'];
            $_SESSION['eventsUserRank'] = $_POST['viewEventsRank'];
			$_POST['viewUser'] = null; ?>
			<script type="text/javascript">
			    	$("#userModal").load("../php/userModal.php");
			    	document.getElementById('closeModalButton').onclick = function(){ $('#userModal').modal('hide'); };
			</script>
			<?php } ?>

		</footer>
</body>

<script src="../js/scripts.js" type="text/javascript"></script>

</html>

<?php
}
?>