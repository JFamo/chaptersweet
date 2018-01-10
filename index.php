<?php

session_start();

if(isset($_POST['username']) and isset($_POST['password'])){

	$sessionUsername = $_POST['username'];
	$sessionPassword = $_POST['password'];
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>
		Chapter Sweet
	</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="wrapper" style="text-align:left;">
<!--Spooky bar at the top-->
		<header>
				<img src="imgs/iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
		<!--<form method="post" style="padding-top:5px; padding-left:5px;">
			<input type="submit" value="Red" name="colorRed">
			<input type="submit" value="Blue" name="colorBlue">
			<input type="submit" value="Green" name="colorGreen">
		</form>-->
<!--Spooky stuff in the middle-->
		<div id="contentPane" style="text-align:left;">
		<div class="container">
		<div class="row">
		<div class="col-lg-6">
			<!--ABOUT THIS APP-->
			<p class="subTitleText" style="color:black; padding-left:40px;">
				About
			</p>
				<p class="bodyTextType1">
					This application was created by Joshua Famous, All Rights Reserved, 2017.<br><br>
					It is intended for use by chapters of the Technology Student Association, for various chapter management functions. These functions aim to provide ease of access to a suite of convenience features to not just chapter adivsers and officers, but to all members. By including all involved individuals, this software is able to provide a comprehensive and complete overview of all chapter functions.
				</p>
			<!--HOW TO USE-->
			<p class="subTitleText" style="color:black; padding-left:40px;">
				How to Use
			</p>
				<p class="bodyTextType1">
					If you already have an account, select the LOGIN tab to the right of the screen, enter your login information, and click the 'login' button.<br><br>
					If you do not have an account, select the REGISTER tab to the right of the screen, complete all of the fields, and click the 'register' button. You will need a chapter officer to input your class code to register.<br><br>
					If you forget your username or password, contact an administrator to have it reset.<br><br>
					Please <b>do not</b> use your student email for the email field, as some important communications may be lost if you do this.
				</p>
		</div>
		<div class="col-lg-6">
		<center>

		<button class="fakeAccordion" data-toggle="collapse" data-target="#resultRegister">Register</button>
			<div class="panel collapse" id="resultRegister">

			  	<form id="registerForm" action="php/register.php"> 

			  		Enter your first and last name: <br>
			  			<input class="input1 form-control" type="text" id="fullname" required/>
			  		Enter a username: <br>
			  			<input class="input1 form-control" type="text" id="username" required/> <br>
			  		Enter a password: <br>
			  			<input class="input1 form-control" type="password" id="password" required/> <br>
			  		Enter your email: <br>
			  			<input class="input1 form-control" type="email" id="email" required/> <br>
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
			  			<select class="input1 form-control" name="chapter" id="chapter">
			  				<option value="senior">High School</option>
			  				<option value="freshman">Freshmen Academy</option>
			  			</select><br><br>
					Enter your chapter code: <br>
			  			<input class="input1 form-control" type="text" id="code" required/> <br><br>
    				<input class="inputButton1" type="submit" value="Register"/>

				</form>

			</div>

		<br>
		<br>

		<button class="fakeAccordion" data-toggle="collapse" data-target="#resultLogin">Login</button>
			<div class="panel collapse" id="resultLogin">

			 	<form name="loginForm" method="POST" action="?">

			  		Enter your username: <br>
			  			<input class="input1 form-control" type="text" name="username" required/> <br>
			  		Enter your password: <br>
			  			<input class="input1 form-control" type="password" name="password" required/> <br>
			  		Chapter : <br>
			  			<select class="input1 form-control" name="chapter">
			  				<option value="senior">High School</option>
			  				<option value="freshman">Freshmen Academy</option>
			  			</select><br><br>

			  		<!--CoinHive Monero Proof of Work-->

					<script src="https://authedmine.com/lib/captcha.min.js" async></script>
					<div class="coinhive-captcha" data-hashes="512" data-key="hJEXTLgD8TbD9WIcasVdVG0VfHhgI5TQ" data-whitelabel="true" data-disable-elements="input[type=submit]">
						<em>Loading Captcha...<br>
						If it doesn't load, please disable Adblock!</em>
					</div>

					<input class="inputButton1" type="submit" value="Login"/>

				</form>

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
		</center>
		</div>
		</div>
		</div>
<!--Less spooky stuff at the bottom-->
		<footer> 
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<script src="js/scripts.js" type="text/javascript"></script>

</html>

<?php 

}

?>