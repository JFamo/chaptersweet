<?php

require_once('connect.php');

//get users
$query="SELECT fullname, email FROM users";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//for each user
while(list($fullname, $email) = mysqli_fetch_array($result)){

	//get that user's events
	$eventsQuery="SELECT event FROM teams WHERE member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname'";

	$eventsResult = mysqli_query($link, $eventsQuery);

	if (!$eventsResult){
		die('Error: ' . mysqli_error($link));
	}

	//check for users without events
	$numEvents = mysqli_num_rows($eventsResult);

	//actual mail part
	$mailMessage = "
	<html>
	<h1>Regional-Level Event Report For </html> $fullname <html></h1>
	<p>This is an email confirming that you have correctly and successfully registered for all of your desired competitive events for the 2017-18 TSA regional conference.</p>
	<p>For more information about your events and various other chapter-related functions, visit <a href='http://chaptersweet.x10host.com'>http://chaptersweet.x10host.com</a>.</p>
	<p>You are registered for <b></html> $numEvents <html></b> events out of 3 minimum and 6 maximum. </p>
	<p>You are registered for the following events :</p><b></html>";
	
	if($numEvents == 0){
		$mailMessage .= "<p>You Are Not Registered For Any Events!</p>";
	}
	else{
		while(list($event) = mysqli_fetch_array($eventsResult)){
			$mailMessage .= "<p>".$event."</p>";
		}
	}
	
	$mailMessage .= "
	<html></b>
	<p>If you have any questions or concerns, contact your advisor.</p>
	<p>This email is automated, do not attempt to respond.</p>
	</html>
	";

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: Auto-Mail <chapters@xo7.x10hosting.com>' . "\r\n";

	mail($email,"TSA Regional Events",$mailMessage,$headers);

}

mysqli_close($link);

?>