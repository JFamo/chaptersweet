<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];

if(isset($_POST['body'])){

	//variables assignment
	$articleTitle = addslashes($_POST['title']);
	$articleBody = addslashes($_POST['body']);
	$articlePoster = addslashes($_SESSION['fullname']);

	require('../php/connect.php');

	$query = "INSERT INTO announcements (title, body, poster, date) VALUES ('$articleTitle', '$articleBody', '$articlePoster', now())";

	$result = mysql_query($query);

	if (!$result){
		die('Error: ' . mysql_error());
	}

	mysql_close();

	$fmsg =  "Article '".$articleTitle."' Uploaded Successfully!";

}

?>

<!DOCTYPE html>

<head>
	<title>
		Chapter Sweet
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<div id="wrapper">
<!--Spooky stuff at the top-->
		<header>
				<img src="../imgs/iconImage.png" alt="icon" width="80" height="80" id="iconMain">
				<p class="titleText">
					Chapter Sweet
				</p>
		</header>
<!--Spooky stuff still kind of at the top-->
		<div id="subTitleBar">
			<form action="../index.php">
    			<input class="backButton" type="submit" value="Back" />
			</form>
			<center><p class="subTitleText">
				Announcements
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane">

			<?php
				if(isset($fmsg)){
				?>

					<p class = "bodyTextType1">

					<?php
					echo $fmsg;
					?>

					</p><br>

				<?php
				}
				?>

				<!--Description-->
					<p class="bodyTextType1">
						Here you can view all of your chapter's announcements. Officers and Admins can write and post new announcements.
					</p>


				<?php

				if($rank == "officer" || $rank=="admin"){

				?>

				<button class="accordion">New</button>
				<div class="panel">
					<!--Article Writing form-->
					<form method="post" id="articleWriteForm">
						<br>
						Title:
						<br>
						<input class="taskFormInput" style="width:800px; height:40px;" type="text" name="title" id="title">
						<br><br>
						Body:
						<br>
						<textarea form="articleWriteForm" cols="60" rows="15" name="body" id="body"></textarea>
						<br><br>
						<input class="submitButton" name="upload" type="submit" class="box" id="upload" value="Post">
					</form>

				</div>

				<?php

				}

				?>

				<br>
				<br>

				<div style="text-align: left;">

				<?php

				require('../php/connect.php');

				$query="SELECT * FROM announcements ORDER BY id DESC";

				$result = mysql_query($query);

				if (!$result){
					die('Error: ' . mysql_error());
				}		

				if(mysql_num_rows($result) == 0){
					echo "No Articles Found!<br>";
				}
				else{
					while(list($id, $title, $body, $poster, $date) = mysql_fetch_array($result)){
						?>

						<p style="color:white; font-family:tahoma; background-color:#B60000; font-size:24px; padding-left:15%; padding-top:10px;"><?php echo "".$title ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "By : ".$poster ?></p>
						<p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "".$date ?></p>
						<br><br>
						<pre>
						<p style="font-size:12px; font-family:tahoma; padding-left:20%; padding-top:10px; padding-bottom: 10px;">
<?php echo "".$body ?>
						</p>
						</pre>
						
						<?php
					}
				}
						
				mysql_close();

				?>

				</div>

			</div>

<!--Spooky stuff at the bottom-->
		<footer>
			<center><p class="bodyTextType2">
				© Joshua Famous 2017
			</p></center>
		</footer>
	</div>	
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

</html>