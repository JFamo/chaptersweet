<?php

require_once('connect.php');

$value1 = $_POST['verify'];

if($value1 == "yes"){

	$sql = "DELETE FROM minutes";

	if (!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}

}

mysql_close();

?>