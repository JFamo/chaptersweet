<?php
$outputVar = "";

session_start();

require('../php/connect.php');

$sessionUser = $_SESSION['username'];
$sessionName = $_SESSION['fullname'];
$conference = $_SESSION['conference'];
$rank = $_SESSION['rank'];

//actually update events
$query="SELECT event, team, member1, member2, member3, member4, member5, member6, qualifier FROM teams ORDER BY event ASC";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

if(mysqli_num_rows($result) == 0){
	$outputVar = $outputVar . "No Events Found!<br>";
}
else{

	$outputVar = $outputVar . '<table class="eventTable">';
	
	$previousEvent = '';
	$previousNum = 0;

	//for each database row
	while(list($name, $team, $m1, $m2, $m3, $m4, $m5, $m6, $isq) = mysqli_fetch_array($result)){

		if($name != $previousEvent){
		
			if($previousEvent != '' && ($rank == 'adviser' || $rank == 'admin')){
				$outputVar = $outputVar . '<tr><td></td><td><form method="post" style="width:auto;">
					<input type="hidden" id="evt" name="evt" value="' . $previousEvent. '">
					<input type="hidden" id="num" name="num" value="' . ($previousNum + 1) . '">
					<input type="submit" id="addt" name="addt" class="btn-primary" value="Add Team">
					</td></tr></form>';
			}
		
			$outputVar = $outputVar . ' <tr><td></td>';
	
			if($team == 0){
				$outputVar = $outputVar . ' <td style="border:none; colspan="3">' . $name . ' - Qualifier</td>';
			}
			else if($m2 == NULL){
				$outputVar = $outputVar . ' <td style="border:none; colspan="3">' . $name . ' - Individual</td>';
			}
			else{
				$outputVar = $outputVar . ' <td style="border:none; colspan="3">' . $name . ' - Team</td>';
			}
	
			$outputVar = $outputVar . ' </tr>';
			
			$previousEvent = $name;
		}
		
		$previousNum = $team;
		
		if($team > 0){

			$outputVar = $outputVar . '<tr><td style="min-width:100px;">Group '.$team;
		
		}

			//for each member in each event
			for($q = 1; $q <= 6; $q++){

				$cellColor = "#e5e5e5";
				if($isq == 'yes'){
					$cellColor = "#ce8ea1";
				}
				//if($q <= $membermin){
					//if($isq == 'yes'){
						//$cellColor = "#e21d58";
					//}
					//else{
						//$cellColor = "#1d69e2";
					//}
				//}
				
				//this is what shows up in each event slot
				switch($q){
					case 1:
						$memberCheck = $m1;
						break;
					case 2:
						$memberCheck = $m2;
						break;
					case 3:
						$memberCheck = $m3;
						break;
					case 4:
						$memberCheck = $m4;
						break;
					case 5:
						$memberCheck = $m5;
						break;
					case 6:
						$memberCheck = $m6;
						break;
				}
				
				if($memberCheck != null){

					$outputVar = $outputVar . ' <td style="background-color: ' . $cellColor . '; min-width: 150px; height: 30px; border: 2px solid black; padding: 10px 10px 10px 10px;" class="eventTableCell">';
					
					//this will give value to the OPEN field, stating if an event slot is available
						if($memberCheck == ' '){
							$isOpen = "yes";
						}
						else{
							$isOpen = "no";
						}
						
					$outputVar = $outputVar . ' <form method="post" target="hideFrame" style="width:auto;">
						<input type="hidden" id="name" name="name" value="' . $name . '">
						<input type="hidden" id="team" name="team" value="' . $team . '">
						<input type="hidden" id="slot" name="slot" value="' . $q . '">
						<input type="hidden" id="open" name="open" value="' . $isOpen . '">
						<input type="submit" id="signup" name="signup" class="eventSignupButton" value="' . $memberCheck . '">
					</form>
					</td>';
				
				}

			}
			
	$outputVar = $outputVar . '</tr>';
	
	}

	$outputVar = $outputVar . '</table>';
}

echo "" . $outputVar;
		
mysqli_close($link);

?>