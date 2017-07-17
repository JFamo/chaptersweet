<?php

if(isset($_GET['id'])){

	require('connect.php');

	$id = $_GET['id'];

	$query = "SELECT name, type, size, content FROM minutes WHERE id = '$id'";

	$result = mysql_query($query);

	if (!$result){
		die('Error: ' . mysql_error());
	}

	list($name,$type,$size,$content) = mysql_fetch_array($result);
	header("Content-length: $size");
	header("Content-type: $type");
	header("Content-Disposition: attachment; filename=$name");
	echo $content;

	mysql_close();

	exit;

}

?>
