<?php

require_once('connect.php');

$value1 = $_POST['verify'];
$rankClear = "member";

if($value1 == "yes"){

	$sql = "DELETE FROM users WHERE rank='$rankClear'";

	if (!mysql_query($sql)){
		die('Error: ' . mysql_error());
	}

}

mysql_close();

?>