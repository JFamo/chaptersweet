<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0){

	$fileName = $_FILES['userfile']['name'];
	$tmpName = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	$fp = fopen($tmpName, 'r');
	$content = fread($fp, filesize($tmpName));
	$content = addslashes($content);
	fclose($fp);

	if(!get_magic_quotes_gpc()){

		$fileName = addslashes($fileName);

	}

	require('../php/connect.php');

	$query = "INSERT INTO minutes (name, size, type, content, date) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now())";

	$result = mysql_query($query);

	if (!$result){
		die('Error: ' . mysql_error());
	}

	mysql_close();

	$fmsg =  "File ".$fileName." Uploaded Successfully!";

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
				Minutes
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
						Here you can view all of your saved meeting minutes, and officers can upload a new minutes file.
					</p>


				<?php

				if($rank == "officer" || $rank=="admin"){

				?>

				<button class="accordion">Upload</button>
				<div class="panel" id="resultRegister">
					<!--Minutes submission form-->
					<form method="post" enctype="multipart/form-data">
						<br>
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						<input class="taskFormInput" name="userfile" type="file" id="userfile">
						<br><br>
						<input class="submitButton" name="upload" type="submit" class="box" id="upload" value="Upload">
					</form>

				</div>

				<?php

				}

				?>

				<br>
				<br>

				<button class="accordion">Browse</button>
				<div class="panel" id="resultRegister">

				<br>

				<?php

				require('../php/connect.php');

				$query="SELECT id, name, date FROM minutes";

				$result = mysql_query($query);

				if (!$result){
					die('Error: ' . mysql_error());
				}

				if(mysql_num_rows($result) == 0){
					echo "Database is empty!<br>";
				}
				else{
					while(list($id, $name, $date) = mysql_fetch_array($result)){
						?>

						<a href="../php/download.php?id=<?php echo "".$id ?>" style="float:left; padding-left: 25%;"><?php echo "".$name ?></a>
						<p style="float:right; padding-right: 25%;"><?php echo "".$date ?></p>
						<br>
						
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
				T1285
			</p></center>
		</footer>
	</div>	
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

</html>