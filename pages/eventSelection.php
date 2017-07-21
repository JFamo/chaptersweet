<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];

//functions for event signup
if(isset($_POST['slot'])){

	//file viewability
	$name = $_POST['name'];
	$team = $_POST['team'];
	$slot = $_POST['slot'];
	$open = $_POST['open'];

	if($open == "yes"){

		$memberColumn = "member" . $slot;

		require('../php/connect.php');

		$sql = "UPDATE teams SET $memberColumn='$username' WHERE event='$name' AND team='$team'";

		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}

		mysql_close();

	}
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
						Here you can for available event slots. Event names are listed, and below each name are slots available for that event. Each row represents an available team, and each cell in that row is a spot on that team.
					</p>
<!--Event Selection Sheet-->
					<table class="eventTable">
					<?php

						require('../php/connect.php');

						$query="SELECT id, name, membermin, membermax, teams FROM events";

						$result = mysql_query($query);

						if (!$result){
							die('Error: ' . mysql_error());
						}

						if(mysql_num_rows($result) == 0){
							echo "No Events Found!<br>";
						}
						else{

							//for each database row
							while(list($id, $name, $membermin, $membermax, $teams) = mysql_fetch_array($result)){
								?>

								<tr>
								<?php
								if($membermax == 1){
								?>
									<th><?php echo "".$name ?> - Individual</th>
								<?php 
								}
								else{
								?>
									<th><?php echo "".$name ?> - Team</th>
								<?php } ?>
								</tr>

								<?php

								//for each team of each event
								for($i = 1; $i <= $teams; $i++){
									?>

									<!--Create a table row-->
									<tr>

									<?php
									//for each member in each event
									for($q = 1; $q <= $membermax; $q++){

										$cellColor = "#0066CC";
										if($q <= $membermin){
											$cellColor = "#0038A8";
										}
									?>

										<td style="background-color:<?php echo "".$cellColor ?>; min-width: 150px; height: 30px; border: 2px solid black; padding: 10px 10px 10px 10px;" class="eventTableCell">
										<?php
											//this is what shows up in each event slot
											$memberCheck = "member".$q;

											$eventquery="SELECT $memberCheck FROM teams WHERE event='$name' AND team='$i'";

											$eventresult = mysql_query($eventquery);

											if (!$eventresult){
												die('Error: ' . mysql_error());
											}

											list($memberUse) = mysql_fetch_array($eventresult);
										?>
										<!--clear member account data tab-->
										<form method="post">
											<input type="hidden" id="name" name="name" value="<?php echo "".$name ?>">
											<input type="hidden" id="team" name="team" value="<?php echo "".$i ?>">
											<input type="hidden" id="slot" name="slot" value="<?php echo "".$q ?>">
											<input type="hidden" id="open" name="open" value="<?php 
											//this will give value to the OPEN field, stating if an event slot is available
											if(is_null($memberUse)){
												echo "yes";
											}
											else{
												echo "no";
											}
											?>">
											<input type="submit" id="signup" name="signup" class="eventSignupButton" 
											value=" <?php
												echo "".$memberUse;
											?> ">
										</form>
										</td>
											
									<?php

									}
								}
							}
						}
								
						mysql_close();

					?>
					</table>

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