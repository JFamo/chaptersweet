<!DOCTYPE html>

<script src="scripts.js" type="text/javascript">
</script>
<?php
	require_once('config.php');
?>

<head>
	<title>
		Resumix
	</title>
	<link href="main.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div id="wrapper">
<!--Spooky stuff at the top-->
		<header>
				<img src="iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Resumix
				</p>
				<ul id="navList">
					<li><a href="index.php">Home</a></li>
					<li><a href="student.php">Student</a></li>
					<li><a href="business.php">Business</a></li>
					<li><a href="login.html">Login</a></li>
				</ul>
		</header>
<!--Spooky stuff still kind of at the top-->
		<div id="subTitleBar">
			<center><p class="subTitleText">
				Business
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane">

<!--Description-->
					<p class="bodyTextType1">
						<b><font color="#111593">Here </font></b>you can submit tasks for completion. These tasks will appear to those browsing work. A task requires a brief title, a date to be completed by, a payout amount in USD, and a descriptive explanation of the requirements. Take caution to submit a task only once, so that two users do not accept the same task. Ensure that project requirements are specific, accurate, and thorough. These will be used to judge whether the project is adequately completed or not. <b>TODO - add a system to legally bind a contract using these requirements!</b>
					</p>
<!--Task submission form-->
					<form action="taskWrite.php" method="post" style="padding-left: 40px" target="castAway" name="tasksForm">
						Task Name:<br>
						<input class="taskFormInput" type="text" name="taskName" required><br>
						Due Date:<br>
						<input class="taskFormInput" type="date" name="completeDate" required><br>
						Payout (in USD):<br>
						<input class="taskFormInput" type="number" name="payout" required><br>
						Contact Field 1:<br>
						(This field is optional, but can be used for E-Mail, Phone or Fax, etc.)<br>
						<input class="taskFormInput" type="text" name="contact1"><br>
						Contact Field 2:<br>
						(This field is optional, but can be used for E-Mail, Phone or Fax, etc.)<br>
						<input class="taskFormInput" type="text" name="contact2"><br>
						Description:<br>
						<textarea class="taskFormInput" name="taskDescription" rows="10" cols="30" required></textarea><br>
						<input class="submitButton" type="button" name="submitButton1" value="Submit" onclick="taskSubmit()">
					</form>

			</div>

<!--Spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				Copyright Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<!--HIDDEN MAGIC SPOOKY WITCHCRAFT-->
<iframe name="castAway" class="invisiframe"></iframe>

</html>