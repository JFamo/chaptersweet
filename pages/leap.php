<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

$resumeReady = false;

//get permission settings
require('../php/connect.php');

//INFO POSTING
$query="SELECT value FROM settings WHERE name='officerInfoPermission'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$officerPerm = $perm;

if(isset($_POST['qualValue'])){

	//variables assignment
	$thisvalue = addslashes($_POST['qualValue']);
	$thistype = addslashes($_POST['qualType']);
	$thisbkd = addslashes($_POST['qualBkd']);

	require('../php/connect.php');

	$query = "INSERT INTO leap (username, type, value, bkd, chapter) VALUES ('$username', '$thistype', '$thisvalue', '$thisbkd', '$chapter')";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
}

if(isset($_POST['qualid'])){
	require('../php/connect.php');

	$thisid = $_POST['qualid'];

	$query = "DELETE FROM leap WHERE id='$thisid' AND chapter='$chapter'";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
}

if(isset($_POST['leapevent'])){
	$_SESSION['leapevent'] = $_POST['leapevent'];
	header("Location:http://localhost/chaptersweet/php/resume_individual.php");
}

?>

<!DOCTYPE html>

<head>
	<!-- Bootstrap, cause it dabs -->
	<link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

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
		   	<span id="openNavButton" style="font-size:30px;cursor:pointer;color:white;padding-right:30px;" onclick="toggleNav()">&#9776;</span>
			<div class="ml-auto navbar-nav">
			    	<a class="nav-item nav-link active" href="../php/logout.php">Logout</a>
			</div>
	</div>
	</nav>
<!--Spooky stuff in the middle-->
	<div class="container-fluid">
		<div class="row">
		<div id="mySidenav" style="padding-right:0; padding-left:0;" class="sidenav darknav">
			<nav style="width:100%;" class="navbar navbar-dark darknav">
			  <div class="container" style="padding-left:0px;">
			  <ul class="nav navbar-nav align-top">
			   <a class="navbar-brand icon" href="#"><img src="../imgs/iconImage.png" alt="icon" width="60" height="60">Chapter <?php if($_SESSION['chapter'] == 2){ echo "<i>Fresh</i>"; }else{ echo "Sweet"; } ?></a>
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
			   <li class="nav-item"><a class="nav-link" href="secretary.php">Secretary</a></li>
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
			   
			   <li class="nav-item active"><a class="nav-link" href="#">LEAP Resume</a></li>
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
		<div id="pageBody">
			<div class="row">
				<div class="col-10">
					<p class="pageHeader" style="padding-left:20px;">
						LEAP Resume
					</p>
					<p class="" style="padding-left:20px;">
						Automatic Generation of <span class="text-primary">LEAP Resumes</span>
					</p>
				</div>
				<div class="col-2">
					<button type="button" class="btn btn-link openHelpModal" data-toggle="modal" data-target="#helpModal">
					  Help
					</button>

					<!-- Help modal -->
					<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="helpModalTitle">About the LEAP Resume Generator</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        The LEAP Resume Generator takes user qualifications and uses them to generate a team LEAP Resume.
					        <hr>
					        <b>Check Back Later</b><br>
					        This page's help guide is still being written.
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
<!--Spooky stuff closer to the middle-->

			<div style="margin: 15px 15px 15px 15px;" class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				</button>
		  		<p>This page is still under development. It will not work as intended.</p>
			</div>

			<div class="row">
	
				<div class="col-10" id="content" style="padding-left:5%; padding-right:5%; padding-top:2.5%; padding-bottom: 2.5%">

					<center>
					<div class="adminDataSection" style="margin-bottom:15px; padding-bottom: 10px;">
						<p class="userDashSectionHeader" style="padding-left:0px;">Update My Qualifications</p>
						<form method="post" id="addQualificationForm" style="width:100%; padding-top:15px;">
							<b>Add New Qualification</b><br>
							<select id="default" name="qualType" style="margin-top:10px; margin-bottom: 10px;">
								<option value="1">Leadership Roles</option>
								<option value="2">Community Service / Volunteer Experience</option>
								<option value="3">Leadership Development / Training</option>
								<option value="4">College / Career Planning</option>
							</select><br>
							<label class="radio-inline"><input style="min-width:30px;" value="1" type="radio" name="qualBkd">Be</label>
							<label class="radio-inline"><input style="min-width:30px;" value="2" type="radio" name="qualBkd">Know</label>
							<label class="radio-inline"><input style="min-width:30px;" value="3" type="radio" name="qualBkd">Do</label>
							<br>
							<small>Write your qualification here. Do not include Be/Know/Do in parenthesis.</small><br>
							<textarea form="addQualificationForm" cols="50" rows="3" name="qualValue"></textarea>
							<br>
							<input type="submit" class="btn btn-primary" value="Add">
						</form>
					</div>
					<div class="adminDataSection" style="margin-bottom:15px; padding-bottom: 10px;">
						<p class="userDashSectionHeader" style="padding-left:0px; margin-bottom: 5px;">Generate a Resume</p>
						<form method="post" id="generateResumeForm" style="width:100%; padding-top:15px;">
							<small>Which event is this resume for?</small><br>
							<select id="default" name="leapevent" style="margin-top:10px; margin-bottom: 10px;" required>
								<?php
								//display each event as option
								require('../php/connect.php');

								//get user's events
								$queryDele="SELECT event FROM teams WHERE (member1='$fullname' OR member2='$fullname' OR member3='$fullname' OR member4='$fullname' OR member5='$fullname' OR member6='$fullname') AND chapter='$chapter'";

								$resultDele = mysqli_query($link, $queryDele);

								if (!$resultDele){
									die('Error: ' . mysqli_error($link));
								}

								//show each event as option	
								while(list($event) = mysqli_fetch_array($resultDele)){
									echo '<option value="' . $event . '"">' . $event . '</option>';
								}
								?>
							</select><br>
							<small>Include a leadership experience specific to a competitive event here. Do not include Be/Know/Do in parenthesis.</small><br>
							<label class="radio-inline"><input style="min-width:30px;" value="1" type="radio" name="leapexp1type" required>Be</label>
							<label class="radio-inline"><input style="min-width:30px;" value="2" type="radio" name="leapexp1type" required>Know</label>
							<label class="radio-inline"><input style="min-width:30px;" value="3" type="radio" name="leapexp1type" required>Do</label>
							<br>
							<textarea form="generateResumeForm" cols="50" rows="3" name="leapexp1" required></textarea>
							<br>
							<small>Include a leadership experience specific to a competitive event here. Do not include Be/Know/Do in parenthesis.</small><br>
							<label class="radio-inline"><input style="min-width:30px;" value="1" type="radio" name="leapexp2type" required>Be</label>
							<label class="radio-inline"><input style="min-width:30px;" value="2" type="radio" name="leapexp2type" required>Know</label>
							<label class="radio-inline"><input style="min-width:30px;" value="3" type="radio" name="leapexp2type" required>Do</label>
							<br>
							<textarea form="generateResumeForm" cols="50" rows="3" name="leapexp2" required></textarea>
							<br>
							<small>Include a leadership experience specific to a competitive event here. Do not include Be/Know/Do in parenthesis.</small><br>
							<label class="radio-inline"><input style="min-width:30px;" value="1" type="radio" name="leapexp3type" required>Be</label>
							<label class="radio-inline"><input style="min-width:30px;" value="2" type="radio" name="leapexp3type" required>Know</label>
							<label class="radio-inline"><input style="min-width:30px;" value="3" type="radio" name="leapexp3type" required>Do</label>
							<br>
							<textarea form="generateResumeForm" cols="50" rows="3" name="leapexp3" required></textarea>
							<br>
							<small>Include a leadership experience specific to a competitive event here. Do not include Be/Know/Do in parenthesis.</small><br>
							<label class="radio-inline"><input style="min-width:30px;" value="1" type="radio" name="leapexp4type" required>Be</label>
							<label class="radio-inline"><input style="min-width:30px;" value="2" type="radio" name="leapexp4type" required>Know</label>
							<label class="radio-inline"><input style="min-width:30px;" value="3" type="radio" name="leapexp4type" required>Do</label>
							<br>
							<textarea form="generateResumeForm" cols="50" rows="3" name="leapexp4" required></textarea>
							<br>
							<input type="submit" class="btn btn-primary" value="Generate">
						</form>
					</div>
					<div class="adminDataSection" style="margin-bottom:15px;">
						<p class="userDashSectionHeader" style="padding-left:0px;">View My Qualifications</p>
						<?php
							require('../php/connect.php');

							$query = "SELECT id,type,bkd,value FROM leap WHERE username='$username' AND chapter='$chapter'";

							$result = mysqli_query($link,$query);

							if (!$result){
								die('Error: ' . mysqli_error($link));
							}

							while(list($id,$type,$bkd,$value) = mysqli_fetch_array($result)){
								echo "<div class='row'><div class='col-1'></div><div class='col-10'>";
								if($type == 1){
									echo "Leadership Roles";
								}
								else if($type == 2){
									echo "Community Service / Volunteer Experience";
								}
								else if($type == 3){
									echo "Leadership Development / Training";
								}
								else {
									echo "College / Career Planning";
								}
								echo "<br>";
								echo $value;
								echo " (";
								if($bkd == 1){
									echo "Be";
								}
								else if($bkd == 2){
									echo "Know";
								}
								else {
									echo "Do";
								}
								echo ")";
								echo "</div><div class='col-1'>";
								echo '<form method="post"><input type="hidden" name="qualid" value="' . $id . '";><input type="submit" class="close btn btn-link" value="&times";></form>';
								echo "</div></div>";
								echo "<br>";
							}

							mysqli_close($link);
						?>
					</div>
					</center>
	
				</div>
				
				<div class="col-2">
					<center>
					<!--PARLI PRO-->
					<b><p class="bodyTextType1">LEAP Templates</p></b>
					<a href="http://tsaweb.org/sites/default/files/leap_resume_temp_ind.pdf">Individual</a><br>
					<a href="http://www.tsaweb.org/sites/default/files/leap_resume_temp_team.pdf">Team</a><br>
	
					<b><p class="bodyTextType1">LEAP Guides</p></b>
					<a href="http://www.tsaweb.org/sites/default/files/leap_resume_ind_guide.pdf" target="_blank">Individual</a><br>
					<a href="http://www.tsaweb.org/sites/default/files/leap_resume_team_guide.pdf" target="_blank">Team</a><br>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Spooky stuff at the bottom-->
	<footer class="darknav">
		<center><p class="bodyTextType2">
			Copyright Joshua Famous 2018
		</p></center>
	</footer>
		
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>
<script src="../js/parli.js"></script>

</html>