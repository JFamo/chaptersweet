<?php

session_start();

if(isset($_POST['email']) && isset($_POST['username'])){

	require_once('connect.php');

	$email = $_POST['email'];
	$username = $_POST['username'];

	//get fullname
	$query="SELECT fullname, password FROM users WHERE email='$email' AND username='$username'";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	list($fullname, $password) = mysqli_fetch_array($result);

		echo "Sending reset email for " . $fullname . " with username " . $username . " to " . $email;

		//actual mail part
		$mailMessage = "
		<html>
		<h1>Chaptersweet Password Reset for </html> $fullname <html></h1>
		<p>This is an email to reset the password for the account registered with this email and username </html> $username <html>.</p>
		<p>To reset your password, use this link : <a href='http://chaptersweet.x10host.com/php/reset.php?user=".$username."&reset=".$password."&email=".$email."'>Click To Reset password</a></p>
		<p>If you have any questions or concerns, contact your advisor.</p>
		<p>This email is automated, do not attempt to respond.</p>
		</html>
		";

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: Auto-Mail <chapters@xo7.x10hosting.com>' . "\r\n";

		mail($email,"Chaptersweet Password Reset",$mailMessage,$headers);

	mysqli_close($link);

}

?>