<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin" || $rank == "officer" || $rank == "adviser"){

//get permission settings
require('../php/connect.php');

//INFO POSTING
$query="SELECT value FROM settings WHERE name='officerInfoPermission'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$officerPerm = $perm;

//function to update points by grade
if(isset($_POST['grade'])){

	//file viewability
	$grade = $_POST['grade'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE grade='$grade'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users in Grade " . $grade . " Successfully!";

	mysqli_close($link);

}

//function to update points by rank
if(isset($_POST['rank'])){

	//file viewability
	$rank = $_POST['rank'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE rank='$rank'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users of Rank " . $rank . " Successfully!";

	mysqli_close($link);

}

//function to update points by group
if(isset($_POST['group'])){

	//file viewability
	$group = $_POST['group'];
	$points = $_POST['points'];

	require('../php/connect.php');

		if($group == "all"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points'";
		}
		if($group == "upper"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE grade='11' OR grade='12'";
		}
		if($group == "lower"){
			$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE grade='9' OR grade='10'";
		}

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users of Group " . ucfirst($group) . " Successfully!";

	mysqli_close($link);

}

//function to remove a user from an event
if(isset($_POST['eventDelete'])){

	//file viewability
	$event = $_POST['eventDelete'];
	$user = $_POST['deleteEventUser'];

	require('../php/connect.php');

		$sql = "UPDATE teams SET member1 = NULL WHERE member1='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member2 = NULL WHERE member2='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member3 = NULL WHERE member3='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member4 = NULL WHERE member4='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member5 = NULL WHERE member5='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "UPDATE teams SET member6 = NULL WHERE member6='$user' AND event = '$event'";
		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Removed " . $user . " from " . $event . " Successfully!";

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

	mysqli_close($link);

}


//function to change user rank
if(isset($_POST['promoteUser'])){

	$user = $_POST['thisUser'];
	$newRank = $_POST['newRank'];

	require('../php/connect.php');

		$sql = "UPDATE users SET rank='$newRank' WHERE fullname='$user'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Changed " . $user . " to Rank " . ucfirst($newRank) . " Successfully!";

	mysqli_close($link);

}

//function to delete user
if(isset($_POST['deleteUser'])){

	$user = $_POST['thisUser'];
	$confirm = $_POST['confirmDeleteUser'];

	if($confirm == "yes"){

	require('../php/connect.php');

		//first get fullname, given id
		$sql = "SELECT fullname FROM users WHERE id='$user'";

		$result = mysqli_query($link, $sql);

		if (!$result){
			die('Error: ' . mysqli_error($link));
		}

		list($userFullname) = mysqli_fetch_array($result);

		$sql2 = "UPDATE teams SET member1 = NULL WHERE member1='$userFullname'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member2 = NULL WHERE member2='$userFullname'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member3 = NULL WHERE member3='$userFullname'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member4 = NULL WHERE member4='$userFullname'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member5 = NULL WHERE member5='$userFullname'";
		if (!mysqli_query($link, $sql2)){
			die('Error: ' . mysqli_error($link));
		}
		$sql2 = "UPDATE teams SET member6 = NULL WHERE member6='$userFullname'";
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

	require('../php/connect.php');

		$sql = "UPDATE users SET $obligation='$newValue' WHERE fullname='$user'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Changed " . $user . " for " . $obligation . " to " . $newValue . "Successfully!";

	mysqli_close($link);

}


//function to create a new obligation
if(isset($_POST['obligationName'])){

	$name = $_POST['obligationName'];
	$name = str_replace(' ','_',$name);
	$defaultValue = $_POST['default'];

	require('../php/connect.php');

		$sql = "ALTER TABLE users ADD COLUMN $name VARCHAR(10) NOT NULL DEFAULT '$defaultValue'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Added Obligation " . $name. " Successfully!";

	mysqli_close($link);

}

//function to delete an obligation
if(isset($_POST['deleteObligation'])){

	$name = $_POST['deleteObligation'];

	require('../php/connect.php');

		$sql = "ALTER TABLE users DROP COLUMN $name";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Deleted Obligation " . $name. " Successfully!";

	mysqli_close($link);

}

?>

<!DOCTYPE html>

<head>

	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
	<!-- ima try this jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
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
				<?php if($rank == "admin" || $rank == "adviser"){ ?>
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
				<!--Summary-->
				<div class="adminDataSection" style="margin-bottom:15px;">
				<p class="userDashSectionHeader" style="padding-left:0px;">Summary</p>
				<div class="basicSpanDiv">

					<?php

					require("../php/connect.php");

					//get number of users
					$query="SELECT id FROM users";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Total Users : <b>" . $numUsers . "</b></p></span>";

					//get number of advisers
					$query="SELECT id FROM users WHERE rank='adviser'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Advisers : <b>" . $numUsers . "</b></p></span>";

					//get number of officers
					$query="SELECT id FROM users WHERE rank='officer'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Officers : <b>" . $numUsers . "</b></p></span>";

					//get number of members
					$query="SELECT id FROM users WHERE rank='member'";

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
					$query="SELECT id FROM users WHERE grade='9'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Freshmen : <b>" . $numUsers . "</b></p></span>";

					//get number of 10th graders
					$query="SELECT id FROM users WHERE grade='10'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Sophomores : <b>" . $numUsers . "</b></p></span>";

					//get number of 11th graders
					$query="SELECT id FROM users WHERE grade='11'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Juniors : <b>" . $numUsers . "</b></p></span>";

					//get number of 12th graders
					$query="SELECT id FROM users WHERE grade='12'";

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
					$query="SELECT SUM(balance) FROM users";

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
						$query="DESCRIBE users";
		
						$result = mysqli_query($link, $query);
		
						if (!$result){
							die('Error: ' . mysqli_error($link));
						}
						
						while($row = mysqli_fetch_array($result)) {
							if(!($row['Field'] == 'id' || $row['Field'] == 'fullname' || $row['Field'] == 'username' || $row['Field'] == 'password' || $row['Field'] == 'email' || $row['Field'] == 'grade' || $row['Field'] == 'rank' || $row['Field'] == 'eventpoints' || $row['Field'] == 'balance')){
						   		echo "<td style='width:100px; height:30px;'><b>" . ucfirst($row['Field']) . "</b></td>";
						   	}
						}
						
						?>

					</tr>
				<?php

				require('../php/connect.php');

				//get user details
				$query="SELECT * FROM users ORDER BY fullname";
				$result = mysqli_query($link, $query);
				if (!$result){
					die('Error: ' . mysqli_error($link));
				}

				//figure out which fields are obligations
				$obligationsArray = array();
				$num_fields = mysqli_num_fields($result);
				$fields = mysqli_fetch_fields($result);
				//for each field
				while($thisField = mysqli_fetch_field($result)){
					$thisFieldName = $thisField->name;
					//if the field is not one of the standard user columns
					if(!($thisFieldName == 'id' || $thisFieldName == 'fullname' || $thisFieldName == 'username' || $thisFieldName == 'password' || $thisFieldName == 'email' || $thisFieldName == 'grade' || $thisFieldName == 'rank' || $thisFieldName == 'eventpoints' || $thisFieldName == 'balance')){

						array_push($obligationsArray, $thisFieldName);

					}
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
							$eventsQuery="SELECT event FROM teams WHERE member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname'";
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

							foreach($obligationsArray as $obligation){ ?>
								<td style="width:100px; height:30px;">
									<form method="post" target="#hideFrame">
										<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
										<input type="hidden" name="obligation" value="<?php echo $obligation ?>" />
										<input type="hidden" name="newValue" value="<?php
											if($resultArray[$obligation]=='yes'){ echo 'no'; }
											if($resultArray[$obligation]=='no'){ echo 'yes'; } 
										?>" />
										<input type="submit" class="<?php
											if($resultArray[$obligation]=='yes'){ echo 'btn btn-success'; }
											if($resultArray[$obligation]=='no'){ echo 'btn btn-danger'; } 
										?>" name="obligationChange" value="<?php 
											echo ucfirst($resultArray[$obligation]);
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
							<option value="yes">Complete</option>
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
							$query="DESCRIBE users";
			
							$result = mysqli_query($link, $query);
			
							if (!$result){
								die('Error: ' . mysqli_error($link));
							}
							
							while($row = mysqli_fetch_array($result)) {
								$obligName = $row['Field'];
								if(!($row['Field'] == 'id' || $row['Field'] == 'fullname' || $row['Field'] == 'username' || $row['Field'] == 'password' || $row['Field'] == 'email' || $row['Field'] == 'grade' || $row['Field'] == 'rank' || $row['Field'] == 'eventpoints' || $row['Field'] == 'balance')){
									?>
									<option value="<?php echo $obligName; ?>"><?php echo $obligName; ?></option>
									<?php
							   	}
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
				
				<?php if($rank == "admin" || $rank == "adviser"){ ?>
				<!--User Management-->
				<div class="adminDataSection">
				<p class="userDashSectionHeader" style="padding-left:0px;">User Management</p><br>
				
					<form class="basicSpanDiv" method="post" style="width:100%; height:40px; padding-top:15px;">
						<span>
						<b>Assign User Event Points</b>
						</span>
						<span>To :
						<!--Give each user as an option-->
						<select id="pointsTo" name="pointsTo">
							<?php
	
							require('../php/connect.php');
	
							$query="SELECT id, fullname, rank FROM users ORDER BY fullname ASC";
	
							$result = mysqli_query($link, $query);
	
							if (!$result){
								die('Error: ' . mysqli_error($link));
							}	
	
							while(list($id, $personname, $personrank) = mysqli_fetch_array($result)){
								if($personrank != "admin"){
								?>
	
								<option value="<?php echo $id ?>"><?php echo $personname ?></option>
								
								<?php
								}
							}
									
							mysqli_close($link);
	
							?>
						</select></span>
						<span>
						How Many Points :
						<input type="number" id="points" name="points">
						</span>
						<span>
						<input type="submit" class="btn btn-primary" value="Assign Points">
						</span>
					</form>
					<form class="basicSpanDiv" method="post" style="width:100%; height:40px; padding-top:15px;">
						<span>
						<b>Remove User Event Points</b>
						</span>
						<span>From :
						<!--Give each user as an option-->
						<select id="pointsFrom" name="pointsFrom">
							<?php
	
							require('../php/connect.php');
	
							$query="SELECT id, fullname, rank FROM users ORDER BY fullname ASC";
	
							$result = mysqli_query($link, $query);
	
							if (!$result){
								die('Error: ' . mysqli_error($link));
							}	
	
							while(list($id, $personname, $personrank) = mysqli_fetch_array($result)){
								if($personrank != "admin"){
								?>
	
								<option value="<?php echo $id ?>"><?php echo $personname ?></option>
								
								<?php
								}
							}
									
							mysqli_close($link);
	
							?>
						</select></span>
						<span>
						How Many Points :
						<input type="number" id="points" name="points">
						</span>
						<span>
						<input type="submit" class="btn btn-danger" value="Remove Points">
						</span>
					</form>
					<form class="basicSpanDiv" method="post" id="deleteUserForm" style="width:100%; height:40px; padding-top:15px;">
						<span>
						<b>Delete Account</b>
						</span>
						<span>User :
						<!--Give each user as an option-->
						<select id="thisUser" name="thisUser">
							<?php
	
							require('../php/connect.php');
	
							$query="SELECT id, fullname, rank FROM users ORDER BY fullname ASC";
	
							$result = mysqli_query($link, $query);
	
							if (!$result){
								die('Error: ' . mysqli_error($link));
							}	
	
							while(list($id, $personname, $personrank) = mysqli_fetch_array($result)){
								if($personrank != "admin" && $personrank != "adviser"){
								?>
	
								<option value="<?php echo $id ?>"><?php echo $personname ?></option>
								
								<?php
								}
							}
									
							mysqli_close($link);
	
							?>
						</select></span>
						<span>
						Are You Sure? :
						<select id="confirmDeleteUser" name="confirmDeleteUser">
								<option value="no">No</option>
								<option value="yes">Yes</option>
						</select>
						</span>
						<span>
						<input type="submit" name="deleteUser" class="btn btn-danger" value="Delete Account" />
						</span>
					</form>
					<br>
				</div>
				<?php } ?>
				</center>

			</div>
		</div>
	</div>
	</div>	

	<iframe name="hideFrame" id="hideFrame" style="display:none;"></iframe>

<!--Spooky stuff at the bottom-->
		<footer class="darknav">
			<center><p class="bodyTextType2">
				Copyright Joshua Famous 2017
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