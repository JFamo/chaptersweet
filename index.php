<!DOCTYPE html>

<head>
	<title>
		Chapter Sweet
	</title>
	<link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="wrapper">
<!--Spooky bar at the top-->
		<header>
				<img src="iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
<!--Spooky stuff in the middle-->
		<div id="contentPane">

		<button class="accordion">Register</button>
			<div class="panel" id="result">

			  	<form id="registerForm" action="register.php"> 

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
			<div class="panel">

			 	<form action="login.php" method="post" name="loginForm" target="castAway">

			  		Enter your username: <br>
			  			<input class="input1" type="text" name="username" required/> <br>
			  		Enter your password: <br>
			  			<input class="input1" type="password" name="password" required/> <br>
    				<input class="inputButton1" type="submit" value="Login" onclick="loginSubmit()"/>

				</form>

			</div>
			
		<br>

		</div>
<!--Less spooky stuff at the bottom-->
		<footer> 
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<!--HIDDEN MAGIC SPOOKY WITCHCRAFT-->
<iframe name="castAway" class="invisiframe"></iframe>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="scripts.js" type="text/javascript"></script>

</html>