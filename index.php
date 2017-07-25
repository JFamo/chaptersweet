<?php

session_start();

require('php/connect.php');

if(isset($_POST['username']) and isset($_POST['password'])){

	$sessionUsername = $_POST['username'];

	$sessionPassword = $_POST['password'];

	$query = "SELECT * FROM users WHERE username='$sessionUsername' and password='$sessionPassword'";

	$result = mysql_query($query);

	if (!$result){
		die('Error: ' . mysql_error());
	}

	$count = mysql_num_rows($result);

	if($count == 1){

		//fetch the rank of that user
		$query2 = "SELECT fullname, rank FROM users WHERE username='$sessionUsername' and password='$sessionPassword'";
		$result2 = mysql_query($query2);
		if (!$result2){
			die('Error: ' . mysql_error());
		}

		list($fullnameValue, $rankValue) = mysql_fetch_array($result2);

		$_SESSION['username'] = $sessionUsername;
		$_SESSION['rank'] = $rankValue;
		$_SESSION['fullname'] = $fullnameValue;

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
	<title>
		Chapter Sweet
	</title>
	<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="wrapper">
<!--Spooky bar at the top-->
		<header>
				<img src="imgs/iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
<!--Spooky stuff in the middle-->
		<div id="contentPane">

		<button class="accordion">Register</button>
			<div class="panel" id="resultRegister">

			  	<form id="registerForm" action="php/register.php"> 

			  		Enter your first and last name: <br>
			  			<input class="input1" type="text" id="fullname" required/> <br>
			  		Enter a username: <br>
			  			<input class="input1" type="text" id="username" required/> <br>
			  		Enter a password: <br>
			  			<input class="input1" type="password" id="password" required/> <br>
			  		Enter your email: <br>
			  			<input class="input1" type="email" id="email" required/> <br>
			  		Enter your grade: <br>
			  			<input class="input1" type="number" id="grade" required/> <br>
    				<input class="inputButton1" type="submit" value="Register"/>

				</form>

			</div>

		<br>
		<br>

		<button class="accordion">Login</button>
			<div class="panel" id="resultLogin">

			 	<form name="loginForm" method="POST">

			  		Enter your username: <br>
			  			<input class="input1" type="text" name="username" required/> <br>
			  		Enter your password: <br>
			  			<input class="input1" type="password" name="password" required/> <br>
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

		</div>
<!--Less spooky stuff at the bottom-->
		<footer> 
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="js/scripts.js" type="text/javascript"></script>

</html>

<?php 

}

?>