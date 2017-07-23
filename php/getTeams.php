<?php
session_start();
$_SESSION['teamsOutput'] = "";

require('../php/connect.php');

$query="SELECT id, name, membermin, membermax, teams FROM events";

$result = mysql_query($query);

if (!$result){
	die('Error: ' . mysql_error());
}

if(mysql_num_rows($result) == 0){
	$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . "No Events Found!<br>";
}
else{

	//for each database row
	while(list($id, $name, $membermin, $membermax, $teams) = mysql_fetch_array($result)){

		$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' <tr>';

		if($membermax == 1){
			$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' <th><?php echo "".$name ?> - Individual</th>';
		}
		else{
			$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' <th><?php echo "".$name ?> - Team</th>';
		}

		$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' </tr>';

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

				$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' <td style="background-color:<?php echo "".$cellColor ?>; min-width: 150px; height: 30px; border: 2px solid black; padding: 10px 10px 10px 10px;" class="eventTableCell">';

					//this is what shows up in each event slot
				$memberCheck = "member".$q;

				$eventquery="SELECT $memberCheck FROM teams WHERE event='$name' AND team='$i'";

				$eventresult = mysql_query($eventquery);

				if (!$eventresult){
					die('Error: ' . mysql_error());
				}

				list($memberUse) = mysql_fetch_array($eventresult);
				<!--clear member account data tab-->
				$_SESSION['teamsOutput'] = $_SESSION['teamsOutput'] . ' <form method="post" target="hideFrame">
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
				</td>';

			}
		}
	}
}

echo "" . $_SESSION['teamsOutput'];
		
mysql_close();

?>