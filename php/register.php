<?php

require_once('config.php');

$link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);

if (!$link){
	die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db(DB_NAME,$link);

if (!$db_selected){
	die('Connot use: ' . mysql_error());
}

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