<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$grade = $_SESSION['grade'];
$name = $_SESSION['fullname'];

if(isset($_POST['newTask'])){

	//variables assignment
	$taskName = addslashes($_POST['name']);
	$taskEvent = addslashes($_POST['thisEvent']);
	$taskUser = addslashes($_SESSION['fullname']);
	$taskTeam = addslashes($_POST['thisTeam']);

	require('../php/connect.php');

	$query = "INSERT INTO tasks (user, event, task, team) VALUES ('$taskUser', '$taskEvent', '$taskName', '$taskTeam')";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "Task '".$taskName."' Added Successfully!";

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

	$query = "UPDATE tasks SET done='$setdone' WHERE team='$taskTeam' AND event='$taskEvent' AND task='$taskName'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

}

//get permission settings
require('../php/connect.php');

//BLOCKED PAGES
$query="SELECT value FROM settings WHERE name='blockPages'";

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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
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
			   <li class="nav-item"><a class="nav-link" href="#">Announcements</a></li>
			   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="info.php">Information</a></li>
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
				if($rank == "admin" || $rank == "officer" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="users.php">My Chapter</a></li>
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
		<div style="padding-right:0; padding-left:0;" class="col-sm-10 bg-secondary">
		<center>

			<div class="container-fluid">
			<div class="row no-gutter" style="margin: 0; padding-top:15px;">
			<div class="col-sm-6" style="padding:0; text-align:left;">
			<div class="userDashSection" style="height:auto;">
				<p class="userDashSectionHeader">
					My Account
				</p>
				<p class="bodyTextType1">
					<b>Full Name:</b> <?php echo $name ?>
					<br><br>
					<b>Balance:</b> <?php 
						require('../php/connect.php');

						//get user's balance
						//I copied this from email so variable names are weird
						$emailquery="SELECT balance FROM users WHERE username='$username' AND fullname='$name'";

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
						$emailquery="SELECT email FROM users WHERE username='$username' AND fullname='$name'";

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
			<div class="userDashSection" style="height:auto;">
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
					$query="SELECT event, team FROM teams WHERE member1='$name' OR member2='$name' OR member3='$name' OR member4='$name' OR member5='$name' OR member6='$name'";

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

					while(list($event, $team) = mysqli_fetch_array($result)){

						$doEventNewline += 1;

						//rows of 3
						if($doEventNewline > 3){
							echo "</tr>";
							echo "<tr style='height: 225px; vertical-align: top;'>";
							$doEventNewline = 1;
						}

						echo "<td style='width:225px; position:relative;'>
							<p style='font-family:tahoma; cursor:pointer; font-size:14px; padding-top:15px;'><b>" . $event . "</b></p>" ?>
							<a data-placement="bottom" title="Create a New Task" data-html=true data-toggle="popover" data-content='<form method="post" style="font-family:tahoma;">
									<input type="hidden" name="thisEvent" id="thisEvent" value="<?php echo $event ?>" />
									<input type="hidden" name="thisTeam" id="thisTeam" value="<?php echo $team ?>" />
									Task Name:<input type="text" id="name" name="name" style="width:125px" required />
									<br>
									<input type="submit" value="Create" name="newTask" style="font-family:tahoma;" id="newTask"/>
								</form>'>New Task+</a>
						<?php 

						//event tasks

						// /require('../php/connect.php');

						echo "<br>";

						$checkName = addslashes($_SESSION['fullname']);
						$checkEvent = addslashes($event);

						//get user's tasks
						$taskQuery="SELECT id, task, done FROM tasks WHERE team='$team' AND event='$checkEvent'";

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
			<div class="userDashSection" style="height:auto;">
				<p class="userDashSectionHeader">
					My Next Conference
				</p>
				<center>
				<br><br>
				<iframe src="http://free.timeanddate.com/countdown/i60zitdg/n3662/cf12/cm0/cu4/ct0/cs0/ca0/cr0/ss0/cacf00/cpc000/pct/tcfff/fs100/szw320/szh135/tatTime%20Until%20Regionals/tac000/tptTime%20Since%20Regionals/tpc000/mac000/mpc000/iso2018-02-03T08:30:00" allowTransparency="true" frameborder="0" width="184" height="69"></iframe>
				<br><br>
				</center>
			</div>
			<div class="userDashSection" style="height:auto;">
				<p class="userDashSectionHeader">
					Announcements
				</p>
				<br>
				<?php

				require('../php/connect.php');

				$query="SELECT * FROM announcements ORDER BY id DESC";

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

						<p style="font-weight: bold; font-family:tahoma; font-size:24px; padding-left:5%; padding-top:10px;"><?php echo "".$title ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:5%; padding-top:10px;"><?php echo "By : ".$poster ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:5%; padding-top:10px;"><?php echo "".$date ?></p>
						<pre class="announcement" style="white-space: pre-wrap; word-wrap: break-word;">
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
	</div>
<!--Less spooky stuff at the bottom-->
	<footer class="darknav">
		<center><p class="bodyTextType2">
			Copyright Joshua Famous 2017
		</p></center>
		<script src="../js/scripts.js" type="text/javascript"></script>
	</footer>
</body>

</html>
