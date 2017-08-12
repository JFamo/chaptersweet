<?php

require_once('connect.php');

$value1 = addslashes($_POST['fullname']);
$value2 = addslashes($_POST['username']);
$value3 = addslashes($_POST['password']);
$email1 = addslashes($_POST['email1']);
$email2 = addslashes($_POST['email2']);
$email3 = addslashes($_POST['email3']);
$emails = array( addslashes($_POST['email']) , $email1, $email2, $email3 );
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

$valueE = serialize($emails);

$sql = "INSERT INTO users (fullname, username, password, email, grade) VALUES ('$value1', '$value2', '$value3', '$valueE', '$value5')";

if (!mysql_query($sql)){
	die('Error: ' . mysql_error());
}

mysql_close();

?>