<?php

require_once('connect.php');

$value1 = addslashes($_POST['fullname']);
$value2 = addslashes($_POST['username']);
$value3 = addslashes($_POST['password']);
$value4 = addslashes($_POST['email']);
//$emails = array(addslashes($_POST['email']),addslashes($_POST['secondmail']),addslashes($_POST['thirdmail']),addslashes($_POST['fourthmail']));
$value5 = $_POST['grade'];

/**
if(isSet($email1)){
	array_push($emails, $email1);
}
if(isSet($email2)){
	array_push($emails, $email2);
}
if(isSet($email3)){
	array_push($emails, $email3);
}
**/

//$valueE = serialize($emails);

$sql = "INSERT INTO users (fullname, username, password, email, grade) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5')";

if (!mysqli_query($link, $sql)){
	die('Error: ' . mysql_error($link));
}

mysql_close($link);

?>