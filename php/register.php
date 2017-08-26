<?php

require_once('connect.php');

$value1 = addslashes($_POST['fullname']);
$value2 = addslashes($_POST['username']);
$value3 = addslashes($_POST['password']);
$value4 = addslashes($_POST['email']);
//$emails = array(addslashes($_POST['email']),addslashes($_POST['secondmail']),addslashes($_POST['thirdmail']),addslashes($_POST['fourthmail']));
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

//$valueE = serialize($emails);

$sql = "INSERT INTO users (fullname, username, password, email, grade) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5')";

if (!mysqli_query($link, $sql)){
	die('Error: ' . mysql_error($link));
}

$mailMessage = "
<html>
<h1>Chaptersweet Account Registration</h1>
<p>Your account has been successfully registered with Chaptersweet.</p>
<p>To get started, visit <a href='http://chaptersweet.x10host.com'>http://chaptersweet.x10host.com</a>.</p>
<p>Your account <b>Name</b> is : </html> $value1 <html></p>
<p>Your account <b>Username</b> is : </html> $value2 <html></p>
<p>Your account <b>Grade</b> is : </html> $value5 <html></p>
<p>If you have any questions or concerns, contact your advisor.</p>
<p>This email is automated, do not attempt to respond.</p>
</html>
";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: Auto-Mail <chapters@xo7.x10hosting.com>' . "\r\n";


mail($value4,"Chaptersweet Registration",$mailMessage,$headers);

mysql_close($link);

?>