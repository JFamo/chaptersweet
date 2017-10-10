<?php
$outputVar;

session_start();

require('../php/connect.php');

//actually update events
$query="SELECT name, date FROM changes";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

if(mysqli_num_rows($result) == 0){
	$outputVar = "no";
}
else{
	$outputVar = "yes";
	while(list($name, $date) = mysqli_fetch_array($result)){
		$currentDate = new DateTime();
		$dateCompare = new DateTime($date);
		date_add($dateCompare, new DateInterval('PT2S'));
		//this record is old
		if($currentDate > $dateCompare){
			//delete it
			$queryDel="DELETE FROM changes WHERE name='$name' AND date='$date'";
			
			$resultDel = mysqli_query($link, $queryDel);
			
			if (!$resultDel){
				die('Error: ' . mysqli_error($link));
			}
		}
	}
}
		
mysqli_close($link);

echo $outputVar;

?>