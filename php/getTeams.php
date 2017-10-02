<?php
$outputVar = "";

session_start();

require('../php/connect.php');

$sessionUser = $_SESSION['username'];
$sessionName = $_SESSION['fullname'];

//get the conference
$conferencequery="SELECT value FROM settings WHERE name='conference'";

$conferenceresult = mysqli_query($link, $conferencequery);

if (!$conferenceresult){
	die('Error: ' . mysqli_error($link));
}

list($conference) = mysqli_fetch_array($conferenceresult);

//actually update events
$query="SELECT id, name, membermin, membermax, teams FROM events WHERE conference='$conference'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

if(mysqli_num_rows($result) == 0){
	$outputVar = $outputVar . "No Events Found!<br>";
}
else{

	$outputVar = $outputVar . '<table class="eventTable">';

	//for each database row
	while(list($id, $name, $membermin, $membermax, $teams) = mysqli_fetch_array($result)){

		$outputVar = $outputVar . ' <tr><td></td>';

		if($membermax == 1){
			$outputVar = $outputVar . ' <th>' . $name . ' - Individual</th>';
		}
		else{
			$outputVar = $outputVar . ' <th>' . $name . ' - Team</th>';
		}

		$outputVar = $outputVar . ' </tr>';

		//for each team of each event
		for($i = 1; $i <= $teams; $i++){

			$outputVar = $outputVar . '<tr><td style="min-width:100px;">Group '.$i;

			//for each member in each event
			for($q = 1; $q <= $membermax; $q++){

				$cellColor = "#e5e5e5";
				if($q <= $membermin){
					$cellColor = "#B60000";
				}

				$outputVar = $outputVar . ' <td style="background-color: ' . $cellColor . '; min-width: 150px; height: 30px; border: 2px solid black; padding: 10px 10px 10px 10px;" class="eventTableCell">';

				//this is what shows up in each event slot
				$memberCheck = "member".$q;

				$eventquery="SELECT $memberCheck FROM teams WHERE event='$name' AND team='$i'";

				$eventresult = mysqli_query($link, $eventquery);

				if (!$eventresult){
					die('Error: ' . mysqli_error($link));
				}

				list($memberUse) = mysqli_fetch_array($eventresult);
				//this will give value to the OPEN field, stating if an event slot is available
					if(is_null($memberUse)){
						$isOpen = "yes";
					}
					else{
						$isOpen = "no";
					}
					
				$outputVar = $outputVar . ' <form method="post" target="hideFrame">
					<input type="hidden" id="name" name="name" value="' . $name . '">
					<input type="hidden" id="team" name="team" value="' . $i . '">
					<input type="hidden" id="slot" name="slot" value="' . $q . '">
					<input type="hidden" id="open" name="open" value="' . $isOpen . '">
					<input type="submit" id="signup" name="signup" class="eventSignupButton" value="' . $memberUse . '">
				</form>
				</td>';

			}
		}
	}

	$outputVar = $outputVar . '</table>';
}

echo "" . $outputVar;
		
mysqli_close($link);

?>