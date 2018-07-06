<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$grade = $_SESSION['grade'];
$name = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

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

//MY CONFERENCE ID
$query="SELECT idnumber FROM users WHERE username='$username' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

list($myid) = mysqli_fetch_array($result);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

if(isset($_POST['dropEvent'])){

	//variables assignment
	$event= addslashes($_POST['thisEvent']);
	$user = addslashes($_SESSION['fullname']);
	$team = addslashes($_POST['thisTeam']);
	$verify = $_POST['verify'];
	$blank = ' ';
	
	if($verify == 'yes'){

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
	
		//post variables
		$points = 1;

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE fullname='$user' AND chapter='$chapter'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$activityForm = "Dropped Event " . $event;
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$user', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

		mysqli_close($link);
	
		$fmsg =  "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Event " . $event . " Dropped Successfully!</div>";
	
	}
	else{
	
		$fmsg =  "<div class='alert alert-danger alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Failed to Drop Event! Not Verified!</div>";
	
	}

}

if(isset($_POST['newTask'])){

	//variables assignment
	$taskName = addslashes($_POST['name']);
	$taskEvent = addslashes($_POST['thisEvent']);
	$taskUser = addslashes($_SESSION['fullname']);
	$taskTeam = addslashes($_POST['thisTeam']);

	require('../php/connect.php');

	$query = "INSERT INTO tasks (user, event, task, team, chapter) VALUES ('$taskUser', '$taskEvent', '$taskName', '$taskTeam', '$chapter')";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Task " . $taskName . " Added Successfully!</div>";

}

if(isset($_POST['dropTask'])){

	//variables assignment
	$taskName = addslashes($_POST['name']);
	$taskEvent = addslashes($_POST['thisEvent']);
	$taskTeam = addslashes($_POST['thisTeam']);

	require('../php/connect.php');

	$query = "DELETE FROM tasks WHERE chapter='$chapter' AND task='$taskName' AND team='$taskTeam' AND event='$taskEvent'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "<div class='alert alert-success alert-dismissable'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Task " . $taskName . " Deleted Successfully!</div>";

}

if(isset($_POST['task'])){

	//variables assignment
	$taskName = addslashes($_POST['task']);
	$taskEvent = addslashes($_POST['event']);
	$taskTeam = addslashes($_POST['team']);
	$isDone = addslashes($_POST['isdone']);

	//check for task status update
	if($isDone == "yes"){
		$setdone = "no";
	}
	else{
		$setdone = "yes";
	}

	require('../php/connect.php');

	$query = "UPDATE tasks SET done='$setdone' WHERE team='$taskTeam' AND event='$taskEvent' AND task='$taskName' AND chapter='$chapter'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

}

if(isset($_POST['clearlog'])){
	require('../php/connect.php');

	$query = "DELETE FROM activity WHERE chapter='$chapter'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
}

if(isset($_POST['activityid'])){
	require('../php/connect.php');

	$thisid = $_POST['activityid'];

	$query = "DELETE FROM activity WHERE id='$thisid' AND chapter='$chapter'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
}

//get permission settings
require('../php/connect.php');

//BLOCKED PAGES
$query="SELECT value FROM settings WHERE name='blockPages' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$blockedPages = $perm;

?>

<!DOCTYPE html>

<!-- ima try this jquery-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<head>
	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<title>
		Chapter Sweet
	</title>
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
			   <li class="nav-item active"><a class="nav-link" href="#">Dashboard</a></li>
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
				if(!($blockedPages == "events" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="eventSelection.php">Event Selection</a></li>
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
				<li class="nav-item"><a class="nav-link" href="leap.php">LEAP Resume</a></li>
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
		<div style="padding-right:0; padding-left:0; padding-top:15px; padding-bottom:15px; background-color:#EDF5F8;" class="col-sm-10">
		<p class="display-4" style="padding-left:20px;">
			Hello, <?php echo(substr($name, 0, strpos($name," "))); ?>
		</p>
		<center>

			<div class="container-fluid">
			<div class="row no-gutter" style="margin: 0; padding-top:15px; padding-bottom:15px;">
			<?php if($rank == 'admin' || $rank == 'adviser'){ ?>
			<div class="col-sm-12" style="padding:0; text-align:left;">
				<div style="height:auto; min-height:0px; margin-bottom:15px;" class="userDashSection">
				<form method="post" style="float:right; padding-right:10px; padding-top:10px;">
					<input type="submit" name="clearlog" class="btn btn-danger" value="Clear">
				</form>
				<p class="userDashSectionHeader">
					Activity Log
				</p>
				<div class="container-fluid">
				<div class="row">
					<div class="col-sm-3">
						<b><p class="bodyTextType1">User</p></b>
					</div>
					<div class="col-sm-5">
						<b><p class="bodyTextType1">Activity</p></b>
					</div>
					<div class="col-sm-3">
						<b><p class="bodyTextType1">Date</p></b>
					</div>
					<div class="col-sm-1">
					</div>
				</div>
				
				<?php
				require('../php/connect.php');

				//get activity log
				$query="SELECT id, user, activity, date FROM activity WHERE chapter='$chapter' ORDER BY date DESC";
				
				$result = mysqli_query($link,$query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}

				//check for users with no events
				if(mysqli_num_rows($result) == 0){
					echo "<center><p style='font-family:tahoma; font-size:14px; padding-top:10px; padding-bottom:10px;'><b>No New Activity</b></p></center>";
				}

				while(list($id, $us, $ac, $dt) = mysqli_fetch_array($result)){
					echo '<div class="row"><div class="col-sm-3">';
					echo $us;
					echo '</div><div class="col-sm-5">';
					echo $ac;
					echo '</div><div class="col-sm-3">';
					echo $dt;
					echo '</div><div class="col-sm-1">';
					echo '<form method="post"><input type="hidden" name="activityid" value="' . $id . '";><input type="submit" class="close btn btn-link" value="&times";></form>';
					echo '</div></div><br>';
				}
				?>
				</div>
				</div>
			</div>
			<?php } ?>
			<div class="col-sm-6" style="padding:0; text-align:left;">
			<div style="height:auto; min-height:0px; margin-bottom:15px;" class="userDashSection">
				<p class="userDashSectionHeader">
					My Account
				</p>
				<p class="bodyTextType1">
					<b>ID Number:</b> <?php echo $myid ?>
					<br><br>
					<b>Balance:</b> <?php 
						require('../php/connect.php');

						//get user's balance
						//I copied this from email so variable names are weird
						$emailquery="SELECT balance FROM users WHERE username='$username' AND fullname='$name' AND chapter='$chapter'";

						$emailresult = mysqli_query($link,$emailquery);

						if (!$emailresult){
							die('Error: ' . mysqli_error($link));
						}

						list($email) = mysqli_fetch_array($emailresult); 

						echo $email;

						?>
					<br><br>
					<b>Grade:</b> <?php echo $grade ?>
					<br><br>
					<b>Email:</b> <?php 
						require('../php/connect.php');

						//get user's email
						$emailquery="SELECT email FROM users WHERE username='$username' AND fullname='$name' AND chapter='$chapter'";

						$emailresult = mysqli_query($link,$emailquery);

						if (!$emailresult){
							die('Error: ' . mysqli_error($link));
						}

						list($email) = mysqli_fetch_array($emailresult); 

						//$email = unserialize($email);
						echo $email;

						?>
					<br><br>
					<b>Rank:</b> <?php echo ucwords($rank) ?>
				</p>
			</div>
			<div style="height:auto; min-height:0px;" class="userDashSection">
			<div style="padding-left: 20px;">
				<p class="userDashSectionHeader">
					My Events
				</p>
				<?php
				if(isset($fmsg)){
				?>

					<p class = "bodyTextType1">

					<?php
					echo $fmsg;
					?>

					</p>

				<?php
				}
				//script stuff to get the user's events

					require('../php/connect.php');

					//get user's events
					$query="SELECT event, team, teamid FROM teams WHERE (member1='$name' OR member2='$name' OR member3='$name' OR member4='$name' OR member5='$name' OR member6='$name')  AND chapter='$chapter'";

					$result = mysqli_query($link,$query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					//check for users with no events
					if(mysqli_num_rows($result) == 0){
						echo "<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>You Are Not Registered For Any Events!</b></p>";
					}

					//space out events when they're displayed
					$doEventNewline = 0;

					//in a table, of course
					echo "<table>";
					echo "<tr style='height: 225px; vertical-align: top;'>";

					while(list($event, $team, $teamid) = mysqli_fetch_array($result)){

						$doEventNewline += 1;

						//rows of 3
						if($doEventNewline > 3){
							echo "</tr>";
							echo "<tr style='height: 225px; vertical-align: top;'>";
							$doEventNewline = 1;
						}

						echo "<td style='width:225px; position:relative;'>
							<p style='font-family:tahoma; font-size:14px; padding-top:15px; margin-bottom:0px;'><b>" . $event . "</b></p>
							<p style='font-style:italic; font-size:10px; margin-top:0px; margin-bottom:5px;'>" . $teamid . "</p>"; ?>
							<a style="cursor:pointer;" class="text-primary" data-placement="bottom" title="Create a New Task" data-html=true data-toggle="popover" data-content='<form method="post" style="font-family:tahoma;">
									<input type="hidden" name="thisEvent" id="thisEvent" value="<?php echo $event ?>" />
									<input type="hidden" name="thisTeam" id="thisTeam" value="<?php echo $team ?>" />
									Task Name:<input type="text" id="name" name="name" style="width:125px" required />
									<br>
									<input type="submit" value="Create" name="newTask" style="font-family:tahoma;" id="newTask"/>
								</form>'>New Task</a>
							<br>
							<a style="cursor:pointer;" class="text-danger" data-placement="bottom" title="Delete Task" data-html=true data-toggle="popover" data-content='<form method="post" style="font-family:tahoma;">
									<input type="hidden" name="thisEvent" id="thisEvent" value="<?php echo $event ?>" />
									<input type="hidden" name="thisTeam" id="thisTeam" value="<?php echo $team ?>" />
									Which Task?:<select id="name" name="name" style="width:125px" required>
                                                                        	<?php 	$checkName = addslashes($_SESSION["fullname"]);
											$checkEvent = addslashes($event);

											//get users tasks
											$taskQuery="SELECT id, task, done FROM tasks WHERE team='$team' AND event='$checkEvent' AND chapter='$chapter'";

											$taskResult = mysqli_query($link,$taskQuery);

											if (!$taskResult){
												die('Error: ' . mysqli_error($link));
											}

											//for each task
											while(list($id, $task, $done) = mysqli_fetch_array($taskResult)){
												echo '<option value="' . $task . '">' . $task . '</option>';
                                                                        		}
                                                                        		?>
                                                                        </select>
									<br>
									<input type="submit" value="Drop" name="dropTask" style="font-family:tahoma;" id="dropTask"/>
								</form>'>Delete Task</a>
							<br>
							<a style="cursor:pointer;" class="text-danger" data-placement="bottom" title="Drop <?php echo $event ?>" data-html=true data-toggle="popover" data-content='<form method="post" style="font-family:tahoma;">
									<input type="hidden" name="thisEvent" id="thisEvent" value="<?php echo $event ?>" />
									<input type="hidden" name="thisTeam" id="thisTeam" value="<?php echo $team ?>" />
									Are You Sure?:<select id="verify" name="verify" style="width:125px" required>
                                                                        	<option value="no">No</option>
                                                                        	<option value="yes">Yes</option>
                                                                        </select>
									<br>
									<input type="submit" value="Drop" name="dropEvent" style="font-family:tahoma;" id="dropEvent"/>
								</form>'>Drop Event</a>
						<?php 

						//event tasks

						// /require('../php/connect.php');

						echo "<br>";

						$checkName = addslashes($_SESSION['fullname']);
						$checkEvent = addslashes($event);

						//get user's tasks
						$taskQuery="SELECT id, task, done FROM tasks WHERE team='$team' AND event='$checkEvent' AND chapter='$chapter'";

						$taskResult = mysqli_query($link,$taskQuery);

						if (!$taskResult){
							die('Error: ' . mysqli_error($link));
						}

						//check for users with no events
						if(mysqli_num_rows($taskResult) == 0){
							echo "<p style='font-family:tahoma; font-size:12px; padding-left:20px; padding-top:15px;'>No Tasks!</p>";
						}

						//for each task
						while(list($id, $task, $done) = mysqli_fetch_array($taskResult)){
							echo "<br>";
							echo "<form method='post'>";
							echo "<input type='hidden' name='event' value='" . $event . "'>";
							echo "<input type='hidden' name='team' id='team' value=" . $team . " />";
							echo "<input type='hidden' name='task' value='" . $task . "'>";
							echo "<input type='hidden' name='isdone' value='" . $done . "'>";
							if($done == "yes"){
								echo "<input style='padding-left:20px;' name='done' type='checkbox' value='yes' onchange='this.form.submit();' checked='checked'>";
							}
							if($done == "no"){
								echo "<input style='padding-left:20px;' name='done' type='checkbox' value='yes' onchange='this.form.submit();'>";
							}
							echo "<p style='padding-left:20px; display:inline-block;'>" . $task . "</p>";
							echo "</form>";
						}


						echo "</td>";

					}

					echo "</tr>";
					echo "</table>";

				?>
			</div>
			</div>
			</div>
			<div class="col-sm-6" style="padding:0; text-align:left;">
			<div style="height:auto; min-height:0px; margin-bottom:15px;" class="userDashSection">
				<p class="userDashSectionHeader">
					My Next Conference
				</p>
				<center>
				<br><br>
				<iframe src="http://free.timeanddate.com/countdown/i60zitdg/n25/cf107/cm0/cu5/ct0/cs0/ca0/cr0/ss0/cacf00/cpc000/pcfff/tc66c/fs100/szw320/szh135/tatTime%20Until%20Nationals/tac000/tptTime%20Since%20Nationals/tpc000/mac000/mpc000/iso2018-06-22T00:00:00" allowTransparency="true" frameborder="0" width="320" height="135"></iframe>


				<br><br>
				</center>
			</div>
			<div style="height:auto; min-height:0px;" class="userDashSection">
				<p class="userDashSectionHeader">
					Announcements
				</p>
				<br>
				<?php

				require('../php/connect.php');

				$query="SELECT * FROM announcements WHERE chapter='$chapter' ORDER BY id DESC";

				$result = mysqli_query($link,$query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}		

				if(mysqli_num_rows($result) == 0){
					echo "No Articles Found!<br>";
				}
				else{
					while(list($id, $title, $body, $poster, $date) = mysqli_fetch_array($result)){
						?>

						<p style="font-family:tahoma; font-size:24px; padding-left:5%; padding-top:10px;"><?php echo "".$title ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:5%; padding-top:10px;"><?php echo "By : ".$poster ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:5%; padding-top:10px;"><?php echo "".$date ?></p>
						<pre class="announcement" style="white-space: pre-wrap; word-wrap: normal;">
						<p style="font-size:12px; font-family:tahoma; padding-top:0px; padding-left:5%; padding-right:5%; padding-bottom: 10px; ">
<?php echo "".$body ?>
						</p>
						</pre>
						
						<?php
					}
				}
						
				mysqli_close($link);

				?>
			</div>
			</div>
			</div>
		</div>
		</center>
		</div>
<!--Less spooky stuff at the bottom-->
	<footer class="darknav">
		<center><p class="bodyTextType2">
			Copyright T1285 2018
		</p></center>
		<script src="../js/scripts.js" type="text/javascript"></script>
	</footer>
	</div>
</body>

</html>
