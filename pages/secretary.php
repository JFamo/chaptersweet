<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

//get permission settings
require('../php/connect.php');

//INFO POSTING
$query="SELECT value FROM settings WHERE name='officerInfoPermission' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$officerPerm = $perm;

if(isset($_POST['uploadMinutes']) && $_FILES['userfile']['size'] > 0){

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

	//file viewality
	$view = $_POST['view'];
	$class = "minutes";

	//get poster
	$poster = $_SESSION['fullname'];

	require('../php/connect.php');

	$query = "INSERT INTO minutes (name, size, type, content, date, view, poster, class, chapter) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$view', '$poster', '$class', '$chapter')";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}
	
	$activityForm = "Uploaded Minutes File " . $fileName;
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$fullname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

	$fmsg =  "File ".$fileName." Uploaded Successfully!";

}

?>

<!DOCTYPE html>

<head>
	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>

	<title>
		Chapter Sweet
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!--Spooky bar at the top-->
	<nav class="navbar navbar-dark darknav navbar-expand-sm">
	  	<div class="container-fluid">
		   	<a class="navbar-brand" href="#"><img src="../imgs/iconImage.png" alt="icon" width="60" height="60">Chapter <?php if($_SESSION['chapter'] == 'freshman'){ echo "<i>Fresh</i>"; }else{ echo "Sweet"; } ?></a>
		<div class="ml-auto navbar-nav">
		    	<a class="nav-item nav-link active" href="../php/logout.php">Logout</a>
		</div>
	</div>
	</nav>
<!--Spooky stuff in the middle-->
	<div class="container-fluid">
		<div class="row">
		<div style="padding-right:0; padding-left:0;" class="col-sm-2 darknav">
			<nav style="width:100%;" class="navbar navbar-dark darknav">
			  <div class="container">
			  <ul class="nav navbar-nav align-top">
			   <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
			   <?php
				if($rank == "admin" || $rank == "officer" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="users.php">My Chapter</a></li>
			   <?php
				}
				?>
			   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="files.php">Files</a></li>
			   <?php
				}
				?>
			   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="rules.php">Event Rules</a></li>
			   <?php
				}
				?>
				<li class="nav-item"><a class="nav-link" href="eventSelection.php">Event Selection</a></li>
	          	   <?php
				if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item active"><a class="nav-link" href="secretary.php">Secretary</a></li>
			   <?php
				}
				?>
			   <?php
				 if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesAnnouncements" || $officerPerm == "filesAnnouncements" || $officerPerm == "announcements")) || $rank == "admin" || $rank == "adviser"){
				 ?>
			    <li class="nav-item"><a class="nav-link" href="reporter.php">Reporter</a></li>
			    <?php
				 }
				 ?>
		           <?php
				 if($rank == "officer" || $rank == "admin" || $rank == "adviser"){ 
				 ?>
			    <li class="nav-item"><a class="nav-link" href="treasurer.php">Treasurer</a></li>
			    <?php
				 }
				 ?>
                           <?php
				 if(!($blockedPages == "info" || $blockedPages == "all") || $rank == "admin" || $rank == "adviser"){
				 ?>
			    <li class="nav-item"><a class="nav-link" href="parli.php">Parliamentarian</a></li>
			    <?php
				 }
				 ?>
			   
			   <li class="nav-item"><a class="nav-link" href="leap.php">LEAP Resume</a></li>
			   <?php
				if($rank == "admin" || $rank == "adviser"){
				?>
			   <li class="nav-item"><a class="nav-link" href="danger.php">Adviser Settings</a></li>
			   <?php
				}
				?>
			  </ul>
			  </div>
			</nav>
		</div>
		<div style="padding-right:0; padding-left:0; padding-top:15px; padding-bottom:15px; overflow:hidden; background-color:#efefef;" class="col-sm-10">
		<p class="display-4" style="padding-left:20px;">
			Minutes
		</p>
		<center>
<!--Spooky stuff closer to the middle-->

			<?php
			if(isset($fmsg)){
				?>
					<div style="margin: 15px 15px 15px 15px;" class="alert alert-info" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						    <span aria-hidden="true">&times;</span>
						</button>
				  		<p><?php
							echo $fmsg;
						?></p>
					</div>
				<?php
				}
			?>

				<?php if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesFiles" || $officerPerm == "minutesAnnouncements" || $officerPerm == "minutes")) || $rank == "admin" || $rank == "adviser"){ ?>
					<form method="post" enctype="multipart/form-data" class="fileForm darknav">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						<span><input style="font-size:16px; border:1px solid black;" name="userfile" type="file" id="userfile"></span>
						<span>Who Can View :
						<select id="view" name="view">
							<option value="all">All</option>
							<option value="officer">Officers Only</option>
						</select></span>
						<span><input class="submitButton" style="width:100px;height:30px;font-size:16px;" name="uploadMinutes" type="submit" class="box" id="uploadMinutes" value="Upload"></span>
					</form>
				<?php } ?>

				<br>
				<br>

				<table style="width:80%; height:80%;">

				<?php

				require('../php/connect.php');

				$query="SELECT id, name, date, view, poster FROM minutes WHERE class='minutes' AND chapter='$chapter'";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}

				$doMemberSkip = 0;

				if(mysqli_num_rows($result) == 0){
					echo "No Minutes Found!<br>";
				}
				else{
					//FOR MEMBERS - check if all available files are hidden
					if($rank == "member"){

						$viewLevel = "all";

						$query2="SELECT id, view FROM minutes WHERE view='$viewLevel' AND chapter='$chapter'";

						$result2 = mysqli_query($link, $query2);

						if (!$result2){
							die('Error: ' . mysqli_error($link));
						}

						if(mysqli_num_rows($result2) == 0){
							$doMemberSkip = 1;
						}

					}

					if($doMemberSkip == 1){
							echo "No Minutes Found!<br>";
					}
					else{
						while(list($id, $name, $date, $view, $poster) = mysqli_fetch_array($result)){
							if(($view == "officer" && ($rank == "officer" || $rank == "admin" || $rank == "adviser")) || ($view == "all")){
								?>
							<tr>
							<td><a class="minutesLink text-primary" href="../php/download.php?id=<?php echo "".$id ?>" style="float:left;"><?php echo "".$name ?></a></td>
							<?php
							if($view == "officer"){ ?>
									<td><p style="float:left;">Private</p></td>
								<?php } ?>
							<td><p style="float:right;"><?php echo "".$date ?></p></td>
							<td><p style="float:right;"><?php echo "".$poster ?></p></td>
							</tr>
							<?php
							}
						}
					}
				}
						
				mysqli_close($link);

				?>

				</table>
				
			</center>

		</div>
	</center>
	</div>
	</div>
	</div>

<!--Spooky stuff at the bottom-->
		<footer class="darknav">
			<center><p class="bodyTextType2">
				Copyright T1285 2018
			</p></center>
		</footer>
		
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

</html>