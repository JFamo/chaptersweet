<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$eventsUser = $_SESSION['fullname'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin" || $rank == "officer"){

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

//function to select user whose events to view
if(isset($_POST['viewEvents'])){

	//file viewability
	$user = $_POST['thisUser'];
	$eventsUser = $user;

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
	require('../php/connect.php');

		$sql = "DELETE FROM users WHERE fullname='$user'";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}
		
		$fmsg =  "Deleted " . $user . " Successfully!";

	mysqli_close($link);

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
					Chapter <?php if($_SESSION['chapter'] == 'freshman'){ echo "<i>Fresh</i>"; }else{ echo "Sweet"; } ?>
				</p>
		</header>
<!--Spooky stuff still kind of at the top-->
		<div id="subTitleBar">
			<form action="../index.php">
    			<input class="backButton" type="submit" value="Back" />
			</form>
			<center><p class="subTitleText">
				Users
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane">

			<?php
				if(isset($fmsg)){
				?>

					<p class = "bodyTextType1"><b>

					<?php
					echo $fmsg;
					?>

					</b></p><br>

				<?php
				}
				?>

				<!--Description-->
				<p class="bodyTextType1">
					Here admins can review user information, check user event progress, manage user accounts, and get a summary of the chapter.
					The summary box shows a general overview of member distribution.<br>
					The user info box shows important information about users, as well as providing options to manage them.
					The events box allows for a more detailed review of user event progress.<br>
				</p>

				<!--User Table-->
				<center>
				<?php if($rank == "admin"){ ?>
				<!--Points-->
				<div class="adminDataSection">
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
							<input type="submit" class="box" value="Assign Points">
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
								<option value="admin">Admins</option>
							</select>
							</span>
							<span>
							How Many Points :
							<input type="number" id="points" name="points">
							</span>
							<span>
							<input type="submit" class="box" value="Assign Points">
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
							<input type="submit" class="box" value="Assign Points">
							</span>
						</form>

				<br>
				</div>
				<?php } ?>
				<!--Summary-->
				<div class="adminDataSection">
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

					//get number of admins
					$query="SELECT id FROM users WHERE rank='admin'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					$numUsers = mysqli_num_rows($result);

					echo "<span><p class='bodyTextType1'>Admins : <b>" . $numUsers . "</b></p></span>";

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
				</div>
				<div class="adminDataSection" style="overflow:auto;">
				<br>
				<p class="userDashSectionHeader" style="padding-left:0px;">User Info</p>
				<br>
				<table class="usersTable" cellspacing="0" cellpadding="0" style="overflow:auto; margin-left:10px;">
					<tr>
					
						<td style="width:250px; height:30px;"><b>Name</b></td>
						<td style="width:80px; height:30px;"><b>Grade</b></td>
						<td style="width:100px; height:30px;"><b>Rank</b></td>
						<td style="width:200px; height:30px;"><b>Email</b></td>
						<td style="width:80px; height:30px;"><b>Events</b></td>
						<td style="width:80px; height:30px;"><b>Event Points</b></td>
						<td style="width:80px; height:30px;"><b>Balance</b></td>
						<td style="width:200px; height:30px;"><b>Options</b></td>
					
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

						?>

						<tr class="userRow">

						<td style="width:250px; height:30px;"><?php echo "".$fullname ?></td>
						<td style="width:60px; height:30px;"><?php echo "".$grade ?></td>
						<td style="width:100px; height:30px;"><?php echo "".$thisrank ?></td>
						<td style="width:200px; height:30px;"><?php echo "".$thisemail ?></td>
						<td style="width:80px; height:30px;"><?php
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
						?></td>
						<td style="width:80px; height:30px;"><?php echo "".$eventpoints?></td>
						<td style="width:80px; height:30px;"><?php echo "".$thisbalance?></td>
						<td style="width:300px; height:30px;">	
							<form method="post" style="float:left; padding-right:5px;">
								<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
								<input type="submit" name="viewEvents" value="View Events" />
							</form>
							<?php if($thisrank != "admin" && $rank == "admin"){ ?>
							<form method="post" style="float:left; padding-right:5px;">
								<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
								<input type="hidden" name="newRank" value="<?php 
									if($thisrank=='member'){ echo 'officer'; }
									if($thisrank=='officer'){ echo 'member'; } 
								?>" />
								<input type="submit" name="promoteUser" value="Make <?php 
									if($thisrank=='member'){ echo 'Officer'; }
									if($thisrank=='officer'){ echo 'Member'; } 
								?>" />
							</form>
							<?php } ?>
							<?php if($thisrank != "admin" && $rank == "admin"){ ?>
							<form method="post" style="float:left;">
								<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
								<input type="submit" name="deleteUser" value="Delete Account" />
							</form>
							<?php } ?>
						</td>

						<?php

						foreach($obligationsArray as $obligation){ ?>
							<td style="width:100px; height:30px;">
								<form method="post" target="#hideFrame">
									<input type="hidden" name="thisUser" value="<?php echo addslashes($fullname) ?>" />
									<input type="hidden" name="obligation" value="<?php echo $obligation ?>" />
									<input type="hidden" name="newValue" value="<?php 
										if($resultArray[$obligation]=='yes'){ echo 'no'; }
										if($resultArray[$obligation]=='no'){ echo 'yes'; } 
									?>" />
									<input type="submit" name="obligationChange" value="<?php 
										echo ucfirst($resultArray[$obligation]);
									?>" />
								</form>
							</td>
							<?php
						}

						?>

						</tr>

						<?php
					}
				}
				?>
				</table>

				<br>
				<br>

				</div>
				<div class="adminDataSection">

					<br>
					<p class="userDashSectionHeader" style="padding-left:0px;"><?php echo $eventsUser . "'s" ?> Events</p>
					<br>

					<?php
					require('../php/connect.php');

					//get user's events
					$query="SELECT event FROM teams WHERE member1='$eventsUser' OR member2='$eventsUser' OR member3='$eventsUser' OR member4='$eventsUser' OR member5='$eventsUser' OR member6='$eventsUser'";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}

					//check for users with no events
					if(mysqli_num_rows($result) == 0){
						echo "<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>User Is Not Registered For Any Events!</b></p>";
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
							<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>" . $event . "</b></p>";

						echo "<br>";

						$checkName = addslashes($eventsUser);
						$checkEvent = addslashes($event);

						//get user's tasks
						$taskQuery="SELECT id, task, done FROM tasks WHERE user='$checkName' AND event='$checkEvent'";

						$taskResult = mysqli_query($link, $taskQuery);

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
								echo "<input style='padding-left:20px;' class='noCheckBox' type='checkbox' checked>";
							}
							else{
								echo "<input style='padding-left:20px;' class='noCheckBox' type='checkbox'>";
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
				<!--Obligations-->
				<div class="adminDataSection">
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
						<input type="submit" class="box" value="Create">
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
						<input type="submit" class="box" value="Delete">
						</span>
					</form>
				</div>
				</center>

			</div>

		<iframe name="hideFrame" id="hideFrame" style="display:none;"></iframe>

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