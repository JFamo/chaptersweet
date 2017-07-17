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

?>