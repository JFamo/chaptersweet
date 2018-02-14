<?php

session_start();

if(isset($_POST['username']) and isset($_POST['password'])){

$value1 = addslashes($_POST['fullname']);
$value2 = addslashes($_POST['username']);
$value3 = addslashes($_POST['password']);
$value4 = addslashes($_POST['email']);
//$emails = array(addslashes($_POST['email']),addslashes($_POST['secondmail']),addslashes($_POST['thirdmail']),addslashes($_POST['fourthmail']));
$value5 = $_POST['grade'];
$valuec = $_POST['code'];
$valuechapter = $_POST['ch'];

$_SESSION['chapter'] = $valuechapter;

if($valuec == 'fr3shT5A'){
	$_SESSION['chapter'] = 'freshman';
}
if($valuec == 'b4sht5aB3ST3ST'){
	$_SESSION['chapter'] = 'freshman';
}

require_once('php/connect.php');

	$sqlp = "INSERT INTO users (fullname, username, password, email, grade) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5')";

	if (!mysqli_query($link, $sqlp)){
		die('Error: ' . mysqli_error($link));
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

}

if(isset($_POST['user']) and isset($_POST['pass'])){

	$sessionUsername = $_POST['user'];
	$sessionPassword = $_POST['pass'];
	$chapter = $_POST['chapter'];
	
	$_SESSION['chapter'] = $chapter;
	
	require('php/connect.php');
	
	$query= "SELECT * FROM users WHERE username='$sessionUsername' and password='$sessionPassword'";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	$count = mysqli_num_rows($result);

	if($count == 1){

		//fetch the rank of that user
		$query2 = "SELECT fullname, rank, grade, eventpoints FROM users WHERE username='$sessionUsername' and password='$sessionPassword'";
		$result2 = mysqli_query($link, $query2);
		if (!$result2){
			die('Error: ' . mysqli_error($link));
		}

		list($fullnameValue, $rankValue, $gradeValue, $eventPointsValue) = mysqli_fetch_array($result2);

		$_SESSION['username'] = $sessionUsername;
		$_SESSION['rank'] = $rankValue;
		$_SESSION['fullname'] = $fullnameValue;
		$_SESSION['grade'] = $gradeValue;
		$_SESSION['eventpoints'] = $eventPointsValue;
		
		//get the conference
		$conferencequery="SELECT value FROM settings WHERE name='conference'";
		
		$conferenceresult = mysqli_query($link, $conferencequery);
		
		if (!$conferenceresult){
			die('Error: ' . mysqli_error($link));
		}
		
		list($conference) = mysqli_fetch_array($conferenceresult);
		
		$_SESSION['conference'] = $conference;

	}
	else{

		$fmsg = "Invalid Login Credentials";

	}

}

if(isset($_SESSION['username'])){

	header('Location: pages/main.php');

}else{

?>

<!DOCTYPE html>

<head>
	<!-- ima try this jquery-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
	<title>
		Chapter Sweet
	</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!--Spooky bar at the top-->
	<nav class="navbar navbar-dark darknav navbar-expand-sm">
  	<div class="container-fluid">
	    <a class="navbar-brand" href="#"><img src="imgs/iconImage.png" alt="icon" width="60" height="60">Chapter Sweet</a>
	</div>
	</nav>
<!--Spooky stuff in the middle-->
	<div class="container-fluid paddy">
	<center>

	<p class="bodyTextType1">
		Chaptersweet was created by Team 2004-901, All Rights Reserved, 2017.
	</p>

	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#regisModal">Register</button>
	<div class="modal fade" id="regisModal" role="dialog">
    	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Register</h4>
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        </div>
	        <div class="modal-body">
		  	<form id="registerForm" method="POST" action="?"> 
		  		Enter your first and last name: <br>
		  			<input class="input1 form-control" type="text" id="fullname" name="fullname" required/>
		  		Enter a username: <br>
		  			<input class="input1 form-control" type="text" id="username" name="username" required/> <br>
		  		Enter a password: <br>
		  			<input class="input1 form-control" type="password" id="password" name="password" required/> <br>
		  		Enter your email: <br>
		  			<input class="input1 form-control" type="email" id="email" name="email" required/> <br>
		  		<!--Enter any additional emails: <br>
		  			<input class="input1" type="email" id="secondmail" name="secondmail" /> <br>
		  			<input class="input1" type="email" id="thirdmail" name="thirdmail" /> <br>
		  			<input class="input1" type="email" id="fourthmail" name="fourthmail" /> <br>
		  		-->
		  		Enter your grade: <br>
		  			<select class="input1 form-control" id="grade" name="grade" required>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select> <br>
				Chapter : <br>
		  			<select class="input1 form-control" name="ch" id="ch">
		  				<option value="senior">High School</option>
		  				<option value="freshman">Freshmen Academy</option>
		  			</select><br><br>
				Enter your chapter code: <br>
		  			<input class="input1 form-control" type="text" id="code" name="code" required/> <br><br>
				<input class="btn btn-primary btn-lg" type="submit" value="Register"/>
			</form>
			</div>
		</div>
	</div>
	</div>

	<br>
	<br>

	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">Login</button>
	<div class="modal fade" id="loginModal" role="dialog">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Login</h4>
	        	<button type="button" class="close" data-dismiss="modal">&times;</button>
	        </div>
	        <div class="modal-body">

		 	<form name="loginForm" method="POST" action="?">

		  		Enter your username: <br>
		  			<input class="input1 form-control" type="text" name="user" required/> <br>
		  		Enter your password: <br>
		  			<input class="input1 form-control" type="password" name="pass" required/> <br>
		  		Chapter : <br>
		  			<select class="input1 form-control" name="chapter">
		  				<option value="senior">High School</option>
		  				<option value="freshman">Freshmen Academy</option>
		  			</select><br><br>

		  		<!--CoinHive Monero Proof of Work-->
		  		<!-- disable this for now

				<script src="https://authedmine.com/lib/captcha.min.js" async></script>
				<div class="coinhive-captcha" data-hashes="512" data-key="hJEXTLgD8TbD9WIcasVdVG0VfHhgI5TQ" data-whitelabel="true" data-disable-elements="input[value=Login]">
					<em>Loading Captcha...<br>
					If it doesn't load, please disable Adblock!</em>
				</div>
				
				-->

				<input class="btn btn-primary btn-lg" type="submit" value="Login"/>

			</form>
			</div>
		</div>
	</div>
	</div>
		
	<br>

	<?php
	if(isset($fmsg)){
	?>

		<p class = "bodyTextType1">

		<?php
		echo $fmsg;
		?>

		</p>

	<?php
	}
	?>

	</center>
	</div>
<!--Less spooky stuff at the bottom-->
	<footer class="darknav"> 
		<center><p class="bodyTextType2">
			Copyright Team 2004-901 2017
		</p></center>
	</footer>
</body>

<script src="js/scripts.js" type="text/javascript"></script>

</html>

<?php 

}

?>