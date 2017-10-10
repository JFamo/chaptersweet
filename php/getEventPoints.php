<?php

session_start();

require('../php/connect.php');

$sessionUser = $_SESSION['username'];
$sessionName = $_SESSION['fullname'];

//get user's eventpoints
$pointsquery="SELECT eventpoints FROM users WHERE username='$sessionUser'";

$pointsresult = mysqli_query($link, $pointsquery);

if (!$pointsresult){
	die('Error: ' . mysqli_error($link));
}

list($eventpoints) = mysqli_fetch_array($pointsresult);

echo "Event Points : <b>" . $eventpoints . "</b>";

?>