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

<!--Spooky stuff kind of at the top-->
		<div id="subTitleBar">
			<center><p class="subTitleText">
				Student
			</p></center>
		</div>

<!--Spooky stuff in the middle-->
		<div id="contentPane">

				<p class="bodyTextType1">
					<b><font color="#111593">This </font></b>is where student accounts can view tasks available for completion. First, review and select a task which looks interesting and achievable. Businesses will be able to approve or deny work, so students should attempt only tasks they are fully capable of. If contact information is provided, there is probably more information available from the poster on how their task should be completed. 
					<br>
					Complete the task as explained in the description, contact the poster for more information, and <b>~TODO~ ADD A WAY TO SUBMIT STUFF WHEN FINISHED</b>
				</p>

<!--Lots of poorly formatted PHP to grab tasks-->
		<?php

			//connect to database
			$link = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);

			//error handle
			if (!$link){
				die('Could not connect: ' . mysql_error());
			}

			//select the database
			$db_selected = mysql_select_db(DB_NAME,$link);

			//errpr handle
			if (!$db_selected){
				die('Connot use: ' . mysql_error());
			}

			//sql function
			$sql = "SELECT * FROM tasks";

			//perform operation
			$results = mysql_query($sql);

			//handle error
			if (!$results){
				die('Data grabbing error : ' . mysql_error());
			}

			//mysql element into usable array
			while($result = mysql_fetch_array($results)){
				//print boxes with data elements - this is HTML echo'd
				echo '<div class="taskBox">';
				echo '<p id="bodyTextType2">' . $result['taskName'] . '</p>';	
				echo '<p id="bodyTextType1">Complete Date: '. $result['completeDate'] . '</p>';	
				echo '<p id="bodyTextType1">Payout: $'. $result['payout'] . ' USD</p>';
				echo '<p id="bodyTextType1">Contact 1: '. $result['contact1'] . '</p>';	
				echo '<p id="bodyTextType1">Contact 2: '. $result['contact2'] . '</p>';	
				echo '<p id="bodyTextType1">Task Description: '. $result['taskDescription'] . '</p>';	
				echo '</div>';
			}

		?>
			
	</div>

<!--Spooky stuff at the bottom-->
	<footer>
<!--This mailbox is MINE, and this software is MINE-->
		<center><p class="bodyTextType2">
			Copyright Joshua Famous 2017
		</p></center>
	</footer>

	</div>	

</body>

</html>