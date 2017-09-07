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

	require('../php/connect.php');

	$query = "INSERT INTO tasks (user, event, task) VALUES ('$taskUser', '$taskEvent', '$taskName')";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "Task '".$taskName."' Added Successfully!";

}

if(isset($_POST['done'])){

	//variables assignment
	$taskName = addslashes($_POST['task']);
	$taskEvent = addslashes($_POST['event']);
	$taskUser = addslashes($_SESSION['fullname']);

	//check for task status update
	if(isset($_POST['done'])){
		$done = $_POST['done'];
	}
	else{
		$done = "no";
	}

	require('../php/connect.php');

	$query = "UPDATE tasks SET done='$done' WHERE user='$taskUser' AND event='$taskEvent' AND task='$taskName'";

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
				<!--ICON LINKS DIV-->
		</header>
<!--Spooky stuff in the middle-->
		<div id="contentPane">
		<center>

		<div class="iconLinksMain iconLinks">
		<!--Events-->
				<?php
				if(!($blockedPages == "events" || $blockedPages == "all") || $rank == "admin"){
				?>
			<span><a href="eventSelection.php"><img src="../imgs/icon_events.png" height="32" width="32"><p class="bodyTextType1">Event Selection</p></a></span>
				<?php
				}
				?>
		<!--Minutes-->
				<?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin"){
				?>
			<span><a href="info.php"><img src="../imgs/icon_info.png" height="32" width="32"><p class="bodyTextType1">Information</p></a></span>
				<?php
				}
				?>
		<!--Users-->
				<?php
				if($rank == "admin"){
				?>
			<span><a href="users.php"><img src="../imgs/icon_users.png" height="32" width="32"><p class="bodyTextType1">Users</p></a></span>
				<?php
				}
				?>
		<!--Logout-->
			<span><a href="../php/logout.php"><img src="../imgs/icon_logout.png" height="32" width="32"><p class="bodyTextType1">Logout</p></a></span>
		<!--Admin Settings-->
				<?php
				if($rank == "admin"){
				?>
			<span><a href="danger.php"><img src="../imgs/icon_settings.png" height="32" width="32"><p class="bodyTextType1">Admin Settings</p></a></span>
				<?php
				}
				?>
		</div>

		<!--
		<?php
			if(isset($username) && isset($rank)){
			?>

				<p class = "bodyTextType1">

				<?php
					$article = "a";
					if($rank == "officer" || $rank == "admin"){
						$article = "an";
					}
					echo "Welcome, " . $username . " who is " . $article . " " . $rank . " in grade ".$grade;
				?>

				</p>

			<?php
			}
			?>
		-->

		<!--USER DASH DIV-->
		<div class="userDash">

			<div class="userDashHeader">
				<p class="subTitleText" style="padding-top:15px">My Dashboard</p>
			</div>

			<table class="columnsTable">
			<tr class="columnsRow">
			<td class="columns">
			<div class="userDashSection" style="height:550px; overflow:auto;">
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
					$query="SELECT event FROM teams WHERE member1='$name' OR member2='$name' OR member3='$name' OR member4='$name' OR member5='$name' OR member6='$name'";

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

					while(list($event) = mysqli_fetch_array($result)){

						$doEventNewline += 1;

						//rows of 3
						if($doEventNewline > 3){
							echo "</tr>";
							echo "<tr style='height: 225px; vertical-align: top;'>";
							$doEventNewline = 1;
						}

						echo "<td style='width:225px; position:relative;'>
							<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>" . $event . "</b></p>" ?>
							<a id="newTaskLink" href='#'>New Task+</a>
							<div id="newTaskDiv">
								<form method="post" style="font-family:tahoma;">
									<b>New Task</b>
									<input type="hidden" name="thisEvent" id="thisEvent" value="<?php echo $event ?>" />
									<br><br>
									Name:<input type="text" id="name" name="name" style="width:125px" required />
									<br><br>
									<input type="submit" value="Create" name="newTask" style="font-family:tahoma;" id="newTask"/>
								</form>
							</div>
						<?php 

						//event tasks

						// /require('../php/connect.php');

						echo "<br>";

						$checkName = addslashes($_SESSION['fullname']);
						$checkEvent = addslashes($event);

						//get user's tasks
						$taskQuery="SELECT id, task, done FROM tasks WHERE user='$checkName' AND event='$checkEvent'";

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
							echo "<input type='hidden' name='task' value='" . $task . "'>";
							if($done == "yes"){
								echo "<input style='padding-left:20px;' name='done' type='checkbox' value='yes' onchange='this.form.submit()' checked>";
							}
							else{
								echo "<input style='padding-left:20px;' name='done' type='checkbox' value='yes' onchange='this.form.submit()'>";
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
			<div class="userDashSection" style="height:250px;">
				<p class="userDashSectionHeader">
					My Account
				</p>
				<p class="bodyTextType1">
					<b>Full Name:</b> <?php echo $name ?>
					<br><br>
					<b>User Name:</b> <?php echo $username ?>
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
			</td>
			<td class="columns">
			<div class="userDashSection" style="height:800px">
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
						<br>
						<pre>
						<p style="font-size:12px; font-family:tahoma; padding-left:10%; padding-top:10px; padding-bottom: 10px;">
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
			</td>
			</tr>
			</table>

		</div>

		</center>
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