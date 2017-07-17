<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];

//encase the whole page - KEEP NON-ADMINS OUT
if($rank == "admin"){

if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0){

	//file details
	$fileName = $_FILES['userfile']['name'];
	$tmpName = $_FILES['userfile']['tmp_name'];
	$fileSize = $_FILES['userfile']['size'];
	$fileType = $_FILES['userfile']['type'];

	//file data manipulation
	$fp = fopen($tmpName, 'r');
	$content = fread($fp, filesize($tmpName));
	$content = addslashes($content);
	fclose($fp);

	if(!get_magic_quotes_gpc()){

		$fileName = addslashes($fileName);

	}

	//file viewability
	$view = $_POST['view'];

	require('../php/connect.php');

	$query = "INSERT INTO minutes (name, size, type, content, date, view) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$view')";

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
				Admin Settings
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
						These settings are for ADMINS ONLY. They are <b> DANGEROUS </b> and have a risk of <b> OVERRIDING IMPORTANT DATA! </b> Proceed with caution, and verify that any function here is used intentionally.
					</p>


				<?php

				if($rank == "officer" || $rank=="admin"){

				?>

				<button class="accordion">Clear Member Account Data</button>
				<div class="panel">
					<!--clear member account data tab-->
					<form method="post" action="../php/clearMemberData.php">
						<br>
						Are You Sure? :
						<select id="verify" name="verify">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:red;" value="Clear Data">
					</form>

				</div>

				<?php

				}

				?>

				<br>
				<br>

				<button class="accordion">Clear Minutes Data</button>
				<div class="panel">
					<!--clear member account data tab-->
					<form method="post" action="../php/clearMinutesData.php">
						<br>
						Are You Sure? :
						<select id="verify" name="verify">
							<option value="no">No</option>
							<option value="yes">Yes</option>
						</select>
						<br><br>
						<input class="submitButton" type="submit" class="box" style="background-color:red;" value="Clear Data">
					</form>

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

<?php
}
?>