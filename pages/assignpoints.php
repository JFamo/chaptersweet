<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin"){

//function to update points by grade
if(isset($_POST['grade'])){

	//file viewability
	$grade = $_POST['grade'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE grade='$grade'";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users in Grade " . $grade . " Successfully!";

	mysql_close();

}

//function to update points by grade
if(isset($_POST['rank'])){

	//file viewability
	$rank = $_POST['rank'];
	$points = $_POST['points'];

	require('../php/connect.php');

		$sql = "UPDATE users SET eventpoints=eventpoints+'$points' WHERE rank='$rank'";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$fmsg =  "Added " . $points . " Event Points to Users of Rank " . $rank . " Successfully!";

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
				Assign Points
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
						Here admins can assign points to members and view the amount of points each member has. Each point will allow a user to sign up for one event, in one slot.
						Points can be assigned by grade or rank.
					</p>

				<button class="accordion">View Points</button>
				<div class="panel">
					<center>
					<table>
						<tr>

							<td style="width:250px; height:30px;"><b>Name</b></td>
							<td style="width:60px; height:30px;"><b>Grade</b></td>
							<td style="width:100px; height:30px;"><b>Rank</b></td>
							<td style="width:100px; height:30px;"><b>Event Points</b></td>
							
						</tr>
					<?php

					require('../php/connect.php');

					//get points
					$query="SELECT id, fullname, grade, rank, eventpoints FROM users";

					$result = mysql_query($query);

					if (!$result){
						die('Error: ' . mysql_error());
					}

					if(mysql_num_rows($result) == 0){
						echo "No Users Found!<br>";
					}
					else{
						while(list($id, $fullname, $grade, $rank, $eventpoints) = mysql_fetch_array($result)){
							?>

							<tr>

							<td style="width:250px; height:30px;"><?php echo "".$fullname ?></td>
							<td style="width:60px; height:30px;"><?php echo "".$grade ?></td>
							<td style="width:100px; height:30px;"><?php echo "".$rank ?></td>
							<td style="width:100px; height:30px;"><?php echo "<b>".$eventpoints."</b>" ?></td>
							
							</tr>

							<?php
						}
					}
							
					mysql_close();

					?>
					</table>
					</center>
				</div>

				<br>
				<br>

				<button class="accordion">Assign Points</button>
				<div class="panel">
					<br>

					<button class="accordion" style="width:60%; font-size:20px; padding:12px, 36px; border-radius:2px;">By Grade</button>
					<div class="panel">
						<!--assign event points by grade tab-->
						<form method="post" id="pointsGradeForm">
							<br>
							Grade :
							<select id="grade" name="grade">
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<br><br>
							How Many Points :
							<input type="number" id="points" name="points">
							<br><br>
							<input class="submitButton" type="submit" class="box" value="Assign Points">
						</form>
					</div>
					<br><br>

					<button class="accordion" style="width:60%; font-size:20px; padding:12px, 36px; border-radius:2px;">By Rank</button>
					<div class="panel">
						<!--assign event points by grade tab-->
						<form method="post" id="pointsRankForm">
							<br>
							Rank :
							<select id="rank" name="rank">
								<option value="member">Members</option>
								<option value="officer">Officers</option>
								<option value="admin">Admins</option>
							</select>
							<br><br>
							How Many Points :
							<input type="number" id="points" name="points">
							<br><br>
							<input class="submitButton" type="submit" class="box" value="Assign Points">
						</form>
					</div>
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