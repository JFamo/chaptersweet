<?php
session_start();
$rank = $_SESSION['rank'];
$daUsah = $_SESSION['eventsUser'];
$thisrank = $_SESSION['eventsUserRank'];
$out = "";
$out = $out .  '<div class="modal-dialog modal-lg">';
$out = $out .  '<div class="modal-content">';
$out = $out .  '<div class="modal-header">';
$out = $out .  '<h4 class="modal-title">' . $_SESSION['eventsUser'] . '</h4>';
//promote
	if(($thisrank != "admin" && $thisrank != "adviser") && ($rank == "admin" || $rank == "adviser")){
	$out = $out . '<form method="post" style="float:left; padding-right:5px; margin-left:20px; padding-bottom:10px;">';
	$out = $out . '<input type="hidden" name="thisUser" value="' . addslashes($daUsah) . '" />';
	$out = $out . '<input type="hidden" name="newRank" value="';
	if($thisrank=='member'){ $out = $out . 'officer'; }
	if($thisrank=='officer'){ $out = $out . 'member'; }
	$out = $out . '"/>';
	$out = $out . '<input type="submit" name="promoteUser" class="btn btn-primary" value="Make ';
	if($thisrank=='member'){ $out = $out . 'Officer'; }
	if($thisrank=='officer'){ $out = $out . 'Member'; } 
	$out = $out . '" />';
	$out = $out . '</form><br>';
	}
$out = $out .  '<button type="button" id="closeModalButton" class="close" data-dismiss="modal">&times;</button>';
$out = $out .  '</div>';
$out = $out .  '<div class="modal-body">';
//user's events
$out = $out .  '<div class="adminDataSection" id="userEvents" style="margin-bottom:15px;">';
	
	$out = $out .  "<br>";
	$out = $out .  '<p class="userDashSectionHeader" style="padding-left:0px;">' . $_SESSION['eventsUser'] . "'s Events</p> ";
	$out = $out .  "<br>";

	require('../php/connect.php');

	//get user's events
	$queryEve="SELECT event, team FROM teams WHERE member1='$daUsah' OR member2='$daUsah' OR member3='$daUsah' OR member4='$daUsah' OR member5='$daUsah' OR member6='$daUsah'";

	$resultEve = mysqli_query($link, $queryEve);

	if (!$resultEve){
		die('Error: ' . mysqli_error($link));
	}

	//check for users with no events
	if(mysqli_num_rows($resultEve) == 0){
		$out = $out .  "<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>User Is Not Registered For Any Events!</b></p>";
	}

	//space out events when they're displayed
	$doEventNewline = 0;

	//in a table, of course
	$out = $out .  "<table>";
	$out = $out .  "<tr style='height: 225px; vertical-align: top;'>";

	while(list($event, $team) = mysqli_fetch_array($resultEve)){

		$doEventNewline += 1;

		//rows of 3
		if($doEventNewline > 3){
			$out = $out .  "</tr>";
			$out = $out .  "<tr style='height: 225px; vertical-align: top;'>";
			$doEventNewline = 1;
		}

		$out = $out .  "<td style='width:225px; position:relative;'>
			<p style='font-family:tahoma; font-size:14px; padding-left:20px; padding-top:15px;'><b>" . $event . "</b></p>";

		$out = $out .  "<br>";

		$checkName = addslashes($_SESSION['eventsUser']);
		$checkEvent = addslashes($event);

		//get user's tasks
		$taskQuery="SELECT id, task, done FROM tasks WHERE team='$team' AND event='$checkEvent'";

		$taskResult = mysqli_query($link, $taskQuery);

		if (!$taskResult){
			die('Error: ' . mysqli_error($link));
		}

		//check for users with no events
		if(mysqli_num_rows($taskResult) == 0){
			$out = $out .  "<p style='font-family:tahoma; font-size:12px; padding-left:20px; padding-top:15px;'>No Tasks!</p>";
		}

		//for each task
		while(list($id, $task, $done) = mysqli_fetch_array($taskResult)){
			$out = $out .  "<br>";
			$out = $out .  "<form method='post'>";
			$out = $out .  "<input type='hidden' name='event' value='" . $event . "'>";
			$out = $out .  "<input type='hidden' name='task' value='" . $task . "'>";
			if($done == "yes"){
				$out = $out .  "<input style='padding-left:20px;' class='noCheckBox' type='checkbox' checked>";
			}
			else{
				$out = $out .  "<input style='padding-left:20px;' class='noCheckBox' type='checkbox'>";
			}
			$out = $out .  "<p style='padding-left:20px; display:inline-block;'>" . $task . "</p>";
			$out = $out .  "</form>";
		}


		$out = $out .  "</td>";

	}

	$out = $out .  "</tr>";
	$out = $out .  "</table>";

	$out = $out .  "<br><br>";
	$out = $out .  '<p class="userDashSectionHeader" style="padding-left:0px;">Remove From Events</p>';
	$out = $out .  '<p class="bodyTextType1">Here you can remove this user from any of their events.</p>';

	$out = $out .  '<form class="basicSpanDiv" method="post" id="removeFromEventForm" style="width:100%; height:40px; padding-top:15px;">';
	$out = $out .  "<input type='hidden' name='deleteEventUser' value='";
	$out = $out . 	$_SESSION['eventsUser'];
	$out = $out .  "' /> ";
	$out = $out .  "<span>";
	$out = $out .  "<b>Delete From Event</b>";
	$out = $out .  '</span>';
	$out = $out .  '<span>';
	$out = $out .  'Event:';
	$out = $out .  '<select id="eventDelete" name="eventDelete">';

//get events for removal
require('../php/connect.php');

//get user's events
$queryDele="SELECT event FROM teams WHERE member1='$daUsah' OR member2='$daUsah' OR member3='$daUsah' OR member4='$daUsah' OR member5='$daUsah' OR member6='$daUsah'";

$resultDele = mysqli_query($link, $queryDele);

if (!$resultDele){
	die('Error: ' . mysqli_error($link));
}

//show each event as option	
while(list($event) = mysqli_fetch_array($resultDele)){
	$out = $out .  '<option value="' . $event . '"">' . $event . '</option>';
}

//closing stuff, remove event button
$out = $out .  '</select></span><span><input type="submit" class="btn btn-danger" value="Remove"></span></form><br></div>';

$out = $out . "<script>$('#userModal').modal('show');</script>";

$out = $out .  '</div></div></div>';

echo $out;
?>
						