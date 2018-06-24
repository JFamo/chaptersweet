<?php

session_start();

require('../php/connect.php');

$event = $_SESSION['leapevent'];
$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=leap_resume_" . strtolower(preg_replace('/\s+/', '', $event)) . ".doc");

echo "<html>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
echo "<body style='font-family: Calibri; font-size:11px;'>";
echo "<center><p><b style='font-size: 18px;'>TSA LEAP LEADERSHIP RESUME - INDIVIDUAL EVENT</b></p></center>";
echo "<br>";
echo "<p><b style='text-decoration:underline; font-size: 11px;'>STUDENT/TEAM IDENTIFICATION</b>";
echo "</p>";
echo "<p><b style='font-size: 11px;'>Team ID: </b>";

require('../php/connect.php');

//get user's events
$query="SELECT teamid FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter' AND event='$event'";

$result = mysqli_query($link,$query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

list($myid) = mysqli_fetch_array($result);
echo "T" . $myid;
echo "</p>";
echo "<p><b style='font-size: 11px;'>Competitive event: </b>";
echo $event;
echo "</p>";
echo "<p><b style='font-size: 11px;'>Level: </b>";
echo "High School</p>";

echo "<br>";
echo "<p><b style='text-decoration:underline; font-size: 11px;'>LEADERSHIP EXPERIENCES</b>";
echo " (specific to a competitive event)</p>";
echo "<br><br><br>";

echo "<p><b style='text-decoration:underline; font-size: 11px;'>LEADERSHIP EXPERIENCES</b>";
echo " (connected to one or more of these categories: Leadership Roles; Community Service/Volunteer Experiences; Leadership Development/Training; College/Career Planning)</p>";

echo "<p><b style='font-style:italic; font-size: 11px;'>Leadership Roles</b></p>";

//section 1
$query="SELECT member1,member2,member3,member4,member5,member6 FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter' AND event='$event'";

$result = mysqli_query($link,$query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

list($m1,$m2,$m3,$m4,$m5,$m6) = mysqli_fetch_array($result);

$query2="SELECT username FROM users WHERE (fullname='$m1' OR fullname='$m2' OR fullname='$m3' OR fullname='$m4' OR fullname='$m5' OR fullname='$m6') AND chapter='$chapter'";

$result2 = mysqli_query($link,$query2);

if (!$result2){
	die('Error: ' . mysqli_error($link));
}

while(list($memberuser) = mysqli_fetch_array($result2)){

	$query3="SELECT type, value, bkd FROM leap WHERE username='$memberuser' AND chapter='$chapter'";

	$result3 = mysqli_query($link,$query3);

	if (!$result3){
		die('Error: ' . mysqli_error($link));
	}

	while(list($type, $value, $bkd) = mysqli_fetch_array($result3)){
		$bkdsub = "Be";
		if($bkd == 2){ $bkdsub = "Know"; }
		if($bkd == 3){ $bkdsub = "Do"; }
		if($type == 1){
			echo str_replace("I ","Team members ",$value) . " (" . $bkdsub . ")<br>";
		}
	}

}

echo "<p><b style='font-style:italic; font-size: 11px;'>Community Service / Volunteer Experience</b></p>";

//section 2
$query="SELECT member1,member2,member3,member4,member5,member6 FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter' AND event='$event'";

$result = mysqli_query($link,$query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

list($m1,$m2,$m3,$m4,$m5,$m6) = mysqli_fetch_array($result);

$query2="SELECT username FROM users WHERE (fullname='$m1' OR fullname='$m2' OR fullname='$m3' OR fullname='$m4' OR fullname='$m5' OR fullname='$m6') AND chapter='$chapter'";

$result2 = mysqli_query($link,$query2);

if (!$result2){
	die('Error: ' . mysqli_error($link));
}

while(list($memberuser) = mysqli_fetch_array($result2)){

	$query3="SELECT type, value, bkd FROM leap WHERE username='$memberuser' AND chapter='$chapter'";

	$result3 = mysqli_query($link,$query3);

	if (!$result3){
		die('Error: ' . mysqli_error($link));
	}

	while(list($type, $value, $bkd) = mysqli_fetch_array($result3)){
		$bkdsub = "Be";
		if($bkd == 2){ $bkdsub = "Know"; }
		if($bkd == 3){ $bkdsub = "Do"; }
		if($type == 2){
			echo str_replace("I ","Team members ",$value) . " (" . $bkdsub . ")<br>";
		}
	}

}

echo "<p><b style='font-style:italic; font-size: 11px;'>Leadership Development / Training</b></p>";

//section 3
$query="SELECT member1,member2,member3,member4,member5,member6 FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter' AND event='$event'";

$result = mysqli_query($link,$query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

list($m1,$m2,$m3,$m4,$m5,$m6) = mysqli_fetch_array($result);

$query2="SELECT username FROM users WHERE (fullname='$m1' OR fullname='$m2' OR fullname='$m3' OR fullname='$m4' OR fullname='$m5' OR fullname='$m6') AND chapter='$chapter'";

$result2 = mysqli_query($link,$query2);

if (!$result2){
	die('Error: ' . mysqli_error($link));
}

while(list($memberuser) = mysqli_fetch_array($result2)){

	$query3="SELECT type, value, bkd FROM leap WHERE username='$memberuser' AND chapter='$chapter'";

	$result3 = mysqli_query($link,$query3);

	if (!$result3){
		die('Error: ' . mysqli_error($link));
	}

	while(list($type, $value, $bkd) = mysqli_fetch_array($result3)){
		$bkdsub = "Be";
		if($bkd == 2){ $bkdsub = "Know"; }
		if($bkd == 3){ $bkdsub = "Do"; }
		if($type == 3){
			echo str_replace("I ","Team members ",$value) . " (" . $bkdsub . ")<br>";
		}
	}

}

echo "<p><b style='font-style:italic; font-size: 11px;'>College / Career Planning</b></p>";

//section 4
$query="SELECT member1,member2,member3,member4,member5,member6 FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter' AND event='$event'";

$result = mysqli_query($link,$query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

list($m1,$m2,$m3,$m4,$m5,$m6) = mysqli_fetch_array($result);

$query2="SELECT username FROM users WHERE (fullname='$m1' OR fullname='$m2' OR fullname='$m3' OR fullname='$m4' OR fullname='$m5' OR fullname='$m6') AND chapter='$chapter'";

$result2 = mysqli_query($link,$query2);

if (!$result2){
	die('Error: ' . mysqli_error($link));
}

while(list($memberuser) = mysqli_fetch_array($result2)){

	$query3="SELECT type, value, bkd FROM leap WHERE username='$memberuser' AND chapter='$chapter'";

	$result3 = mysqli_query($link,$query3);

	if (!$result3){
		die('Error: ' . mysqli_error($link));
	}

	while(list($type, $value, $bkd) = mysqli_fetch_array($result3)){
		$bkdsub = "Be";
		if($bkd == 2){ $bkdsub = "Know"; }
		if($bkd == 3){ $bkdsub = "Do"; }
		if($type == 4){
			echo str_replace("I ","Team members ",$value) . " (" . $bkdsub . ")<br>";
		}
	}

}

echo "</body>";
echo "</html>";

include "../php/leap.php";

?>