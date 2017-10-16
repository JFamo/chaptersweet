<?php

session_start();

if(isset($_POST['username']) and isset($_POST['password'])){

	$sessionUsername = $_POST['username'];
	$sessionPassword = $_POST['password'];
	$chapter = $_POST['chapter'];
	
	$_SESSION['chapter'] = $chapter;
	
	require('php/connect.php');

	$query = "SELECT * FROM users WHERE username='$sessionUsername' and password='$sessionPassword'";

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
		<table class="columnsTable">
		<tr class="columnsRow">
		<td class="columns" style="overflow:auto;">
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
			<!--ANNOUNCEMENTS-->
			<p class="subTitleText" style="color:black; float:left; padding-left:40px;">
				Announcements
			</p>
			<br><br>
			<?php

				require('php/connect.php');

				$query="SELECT id, title, body, poster, date FROM announcements ORDER BY id DESC";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}		

				if(mysqli_num_rows($result) == 0){
					echo "No Articles Found!<br>";
				}
				else{
					while(list($id, $title, $body, $poster, $date) = mysqli_fetch_array($result)){
						?>

						<p style="font-weight: bold; font-family:tahoma; font-size:24px; padding-left:60px; padding-top:10px;"><?php echo "".$title ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:60px; padding-top:10px;"><?php echo "By : ".$poster ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:60px; padding-top:10px;"><?php echo "".$date ?></p>
						<br><br>
						<pre>
						<p style="font-size:12px; font-family:tahoma; padding-left:80px; padding-top:10px; padding-bottom: 10px;">
<?php echo "".$body ?>
						</p>
						</pre>
						
						<?php
					}
				}
						
				mysqli_close($link);

				?>
		</td>
		<td class="columns" style="vertical-align: top; overflow: auto;">
		<center>

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
			  		<!--Enter any additional emails: <br>
			  			<input class="input1" type="email" id="secondmail" name="secondmail" /> <br>
			  			<input class="input1" type="email" id="thirdmail" name="thirdmail" /> <br>
			  			<input class="input1" type="email" id="fourthmail" name="fourthmail" /> <br>
			  		-->
			  		Enter your grade: <br>
			  			<select class="input1" id="grade" name="grade" required>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select> <br>
					Enter your chapter code: <br>
			  			<input class="input1" type="text" id="code" required/> <br><br>
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
			  		Chapter : <br>
			  			<select class="input1" name="chapter">
			  				<option value="senior">High School</option>
			  				<option value="freshman">Freshmen Academy</option>
			  			</select><br><br>
			  			
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

		</td>
		</center>
		</tr>
		</table>
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