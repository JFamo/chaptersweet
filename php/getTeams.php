<?php
session_start();
$outputVar = "Empty ";

require('../php/connect.php');

$query="SELECT id, name, membermin, membermax, teams FROM events";

$result = mysql_query($query);

if (!$result){
	die('Error: ' . mysql_error());
}

if(mysql_num_rows($result) == 0){
	$outputVar = $outputVar . "No Events Found!<br>";
}
else{

	//for each database row
	while(list($id, $name, $membermin, $membermax, $teams) = mysql_fetch_array($result)){

		$outputVar = $outputVar . ' <tr>';

		if($membermax == 1){
			$outputVar = $outputVar . ' <th><?php echo "".$name ?> - Individual</th>';
		}
		else{
			$outputVar = $outputVar . ' <th><?php echo "".$name ?> - Team</th>';
		}

		$outputVar = $outputVar . ' </tr>';

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

				$outputVar = $outputVar . ' <td style="background-color:<?php echo "".$cellColor ?>; min-width: 150px; height: 30px; border: 2px solid black; padding: 10px 10px 10px 10px;" class="eventTableCell">';

					//this is what shows up in each event slot
				$memberCheck = "member".$q;

				$eventquery="SELECT $memberCheck FROM teams WHERE event='$name' AND team='$i'";

				$eventresult = mysql_query($eventquery);

				if (!$eventresult){
					die('Error: ' . mysql_error());
				}

				list($memberUse) = mysql_fetch_array($eventresult);
				$outputVar = $outputVar . ' <form method="post" target="hideFrame">
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

echo "" . $outputVar;
		
mysql_close();

?>