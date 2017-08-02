<?php

session_start();

require('../php/connect.php');

$sessionUser = $_SESSION['username'];
$sessionName = $_SESSION['fullname'];

//get user's eventpoints
$pointsquery="SELECT eventpoints FROM users WHERE username='$sessionUser' AND fullname='$sessionName'";

$pointsresult = mysql_query($pointsquery);

if (!$pointsresult){
	die('Error: ' . mysql_error());
}

list($eventpoints) = mysql_fetch_array($pointsresult);

echo "Event Points : <b>" . $eventpoints . "</b>";

?>