<?php

function validate($data){
	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
}

session_start();

if(isset($_POST['username']) and isset($_POST['password'])){

	$value1 = addslashes($_POST['fullname']);
	$value2 = addslashes($_POST['username']);
	$value3 = addslashes($_POST['password']);
	$value4 = addslashes($_POST['email']);
	$value5 = $_POST['grade'];
	$valuec = $_POST['code'];
	
	$value1 = validate($value1);
	$value2 = validate($value2);
	$value3 = validate($value3);
	$value4 = validate($value4);
	$value5 = validate($value5);
	$valuec = validate($valuec);
	
	require_once('php/connect.php');
	
	$sql = "SELECT id,code,name FROM chapters";
	
	$result = mysqli_query($link, $sql);
	
	if (!$result){
		die('Error: ' . mysqli_error($link));
	}
	
	$_SESSION['chapter'] = 'nochapter';
	$mychaptername = '';
	
	while(list($thisid,$thiscode,$thisname) = mysqli_fetch_array($result)){
		if($thiscode == $valuec){
			$_SESSION['chapter'] = $thisid;
			$mychaptername = $thisname;
		}
	}
	
	if($_SESSION['chapter'] != 'nochapter'){
	
		$chapter = $_SESSION['chapter'];
		
		$query= "SELECT * FROM users WHERE username='$value2'";

		$result = mysqli_query($link, $query);
	
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
	
		$count = mysqli_num_rows($result);
	
		if($count == 0){
			$sqlp = "INSERT INTO users (fullname, username, password, email, grade, chapter) VALUES ('$value1', '$value2', '$value3', '$value4', '$value5', '$chapter')";
	
			if (!mysqli_query($link, $sqlp)){
				die('Error: ' . mysqli_error($link));
			}
		
			$mailMessage = "
			<html>
			<h1>Chaptersweet Account Registration</h1>
			<p>Your account has been successfully registered with Chaptersweet.</p>
			<p>To get started, visit <a href='http://chaptersweet.x10host.com'>http://chaptersweet.x10host.com</a>.</p>
			<p>You are registered to chapter : </html> $mychaptername <html></p>
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
		
		
			mail($valuee,"Chaptersweet Registration",$mailMessage,$headers);
			
			$fmsg = 'Successfully Registered!';
		}
		else{
			$fmsg = 'Username Already in Use!';
		}
	}
	else{
		$fmsg = 'Invalid Chapter Code!';
	}

}

if(isset($_POST['user']) and isset($_POST['pass'])){

	$sessionUsername = $_POST['user'];
	$sessionPassword = $_POST['pass'];
	$chapter = $_POST['chapter'];
	
	$sessionUsername = validate($sessionUsername);
	$sessionPassword = validate($sessionPassword);
	$chapter = validate($chapter);
	
	$_SESSION['chapter'] = $chapter;
	
	require('php/connect.php');
	
	$query= "SELECT * FROM users WHERE username='$sessionUsername' AND password='$sessionPassword' AND chapter='$chapter'";

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

if(isset($_POST['chname']) && $_POST['chpaid'] == '8675309'){

	require_once('php/connect.php');
	
	$thisname = $_POST['chname'];
	$thiscode = $_POST['chcode'];
	$value1 = addslashes($_POST['chuser']);
	$value2 = addslashes($_POST['chpass']);
	$valuee = addslashes($_POST['chemail']);
	$valuef = addslashes($_POST['chfull']);
	
	$thisname = validate($thisname);
	$thiscode = validate($thiscode);
	$value1 = validate($value1);
	$value2 = validate($value2);
	$valuee = validate($valuee);
	$valuef = validate($valuef);
	
	$query= "SELECT * FROM users WHERE username='$value1'";

	$result = mysqli_query($link, $query);
	
	if (!$result){
		die('Error: ' . mysqli_error($link));
	}
	
	$count = mysqli_num_rows($result);
	
	if($count == 0){
	
		//add to chapters database
		$sql = "INSERT INTO chapters (name, code) VALUES ('$thisname', '$thiscode')";
		
		$result = mysqli_query($link, $sql);
		
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		
		$sql = "SELECT id FROM chapters WHERE name='$thisname'";
		
		$resultdd = mysqli_query($link, $sql);
		
		if (!$resultdd){
			die('Error: ' . mysqli_error($link));
		}
		
		list($thisid) = mysqli_fetch_array($resultdd);
		
		//default settings
		$sql = "INSERT INTO settings (name, value, chapter) VALUES ('conference', 'regional', '$thisid')";
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "INSERT INTO settings (name, value, chapter) VALUES ('officerInfoPermission', 'all', '$thisid')";
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "INSERT INTO settings (name, value, chapter) VALUES ('officerEmailPermission', 'no', '$thisid')";
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "INSERT INTO settings (name, value, chapter) VALUES ('blockPages', 'none', '$thisid')";
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		$sql = "INSERT INTO settings (name, value, chapter) VALUES ('eventpointsPermission', 'yes', '$thisid')";
		$result = mysqli_query($link, $sql);
		if (!$result){
			die('Error: ' . mysqli_error($link));
		}
		
		//create admin
		$sqlp = "INSERT INTO users (fullname, username, password, email, grade, chapter) VALUES ('$valuef', '$value1', '$value2', '$valuee', '0', '$thisid')";
	
			if (!mysqli_query($link, $sqlp)){
				die('Error: ' . mysqli_error($link));
			}
		
			$mailMessage = "
			<html>
			<h1>Chaptersweet New Chapter Registration</h1>
			<p>You have successfully registered your chapter with Chaptersweet.</p>
			<p>To get started, visit <a href='http://chaptersweet.x10host.com'>http://chaptersweet.x10host.com</a>.</p>
			<p>To have students sign up, give them your chapter code, which is : </html> $chcode <html></p>
			<p>You created chapter : </html> $thisname <html></p>
			<p>Your account <b>Name</b> is : </html> $valuef <html></p>
			<p>Your account <b>Username</b> is : </html> $value1 <html></p>
			<p>This email is automated, do not attempt to respond.</p>
			</html>
			";
		
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
			// More headers
			$headers .= 'From: Auto-Mail <chapters@xo7.x10hosting.com>' . "\r\n";
		
		
			mail($value4,"Chaptersweet Registration",$mailMessage,$headers);
			
			$fmsg = 'Successfully Created Chapter!';
	
	}
	else{
		$fmsg = "Invalid Admin Username!";
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
	<nav class="navbar navbar-dark darknav navbar-expand-sm" style="height:12vh;">
  	<div class="container-fluid">
	    <a class="navbar-brand" href="#"><img src="imgs/iconImage.png" alt="icon" width="60" height="60">Chapter Sweet</a>
	</div>
	</nav>
<!--Spooky stuff in the middle-->
	<div class="container-fluid paddy" style="height:72vh;">
	<center>

	<p class="bodyTextType1">
		Chaptersweet was created by Team 2004-901, All Rights Reserved, 2018.
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
		  				<?php
		  				require_once('php/connect.php');
	
						$sql = "SELECT id,name FROM chapters";
						
						$result = mysqli_query($link, $sql);
						
						if (!$result){
							die('Error: ' . mysqli_error($link));
						}
						
						$_SESSION['chapter'] = 'nochapter';
						
						while(list($thisid,$thisname) = mysqli_fetch_array($result)){
							echo '<option value="' . $thisid . '">' . $thisname . '</option>';
						}
						
		  				?>
		  			</select><br><br>

		  		<!--CoinHive Monero Proof of Work-->
		  		<!-- disable this for now

				<script src="https://authedmine.com/lib/captcha.min.js" async></script>
				<div class="coinhive-captcha" data-hashes="512" data-key="hJEXTLgD8TbD9WIcasVdVG0VfHhgI5TQ" data-whitelabel="true" data-disable-elements="input[value=Login]">
					<em>Loading Captcha...<br>
					If it does not load, please disable Adblock!</em>
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

		<p class = "bodyTextType1"><b>

		<?php
		echo $fmsg;
		?>

		</b></p>

	<?php
	}
	?>

	</center>
	</div>
	<div style="height:8vh;">
		<center>
		<a style="cursor:pointer;" class="text-primary" data-toggle="modal" data-target="#chapterModal">Register My Chapter</a>
		<div class="modal fade" id="chapterModal" role="dialog">
	    	<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Register My Chapter</h4>
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        </div>
		        <div class="modal-body">
			  	<form method="post" style="font-family:tahoma;">
									Chapter Name:<input type="text" name="chname" style="width:125px" required /><p style="font-size:10px; font-style: italic;">The name of your chapter - this is permanent!</p>
									Chapter Code:<input type="text" name="chcode" style="width:125px" required /><p style="font-size:10px; font-style: italic;">The code users will use to join your chapter.</p>
									Pre-Paid Code:<input type="text" name="chpaid" style="width:125px" required /><p style="font-size:10px; font-style: italic;">The code you received to create a chapter.</p>
									Adviser Username:<input type="text" name="chuser" style="width:125px" required /><p style="font-size:10px; font-style: italic;">What will your username be?</p>
									Adviser Password:<input type="text" name="chpass" style="width:125px" required /><p style="font-size:10px; font-style: italic;">What will your password be?</p>
									Adviser Name:<input type="text" name="chfull" style="width:125px" required /><p style="font-size:10px; font-style: italic;">Your full name, first and last seperated with a space.</p>
									Adviser Email:<input type="text" name="chemail" style="width:125px" required /><p style="font-size:10px; font-style: italic;">Your email.</p>
									<br>
									<input id="newChapter" name="newChapter" class="btn btn-primary btn-lg" type="submit" value="Register"/>
							</form>
				</div>
			</div>
		</div>
		</div>
		</center>
	</div>
<!--Less spooky stuff at the bottom-->
	<footer class="darknav" style="height:8vh;"> 
		<center><p class="bodyTextType2">
			Copyright Team 2004-901 2018
		</p></center>
	</footer>
</body>

<script src="js/scripts.js" type="text/javascript"></script>

</html>

<?php 

}

?>