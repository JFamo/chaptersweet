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
		Chaptersweet was created by Joshua Famous, All Rights Reserved, 2017.
		<br><b>Note : If this screen looks super weird, hold SHIFT and click Refresh to load the new UI.</b>
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
				<div class="coinhive-captcha" data-hashes="512" data-key="hJEXTLgD8TbD9WIcasVdVG0VfHhgI5TQ" data-whitelabel="true" data-disable-elements="input[value=Login]">
					<em>Loading Captcha...<br>
					If it doesn't load, please disable Adblock!</em>
				</div>

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
			Copyright Joshua Famous 2017
		</p></center>
	</footer>
</body>

<script src="js/scripts.js" type="text/javascript"></script>

</html>

<?php 

}

?>