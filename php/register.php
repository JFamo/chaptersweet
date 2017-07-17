<?php

require_once('connect.php');

$value1 = $_POST['fullname'];
$value2 = $_POST['username'];
$value3 = $_POST['password'];
$value4 = $_POST['email'];
$value5 = $_POST['grade'];

$sql = "INSERT INTO users (fullname, username, password, email, grade) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5')";

if (!mysql_query($sql)){
	die('Error: ' . mysql_error());
}

mysql_close();

?>