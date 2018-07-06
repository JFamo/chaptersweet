<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

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

//post scores
if(isset($_POST['scoreValue'])){

	//variables assignment
	$testNum = $_POST['testNumber'];
	$scoreVal = $_POST['scoreValue'];
	$myName = addslashes($fullname);

	require('../php/connect.php');

	$query = "INSERT INTO scores (fullname, test, score, chapter, date) VALUES ('$myName', '$testNum', '$scoreVal', '$chapter', now())";

	$result = mysqli_query($link,$query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
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
			    <li class="nav-item active"><a class="nav-link" href="#">Parliamentarian</a></li>
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
		<div id="pageBody">
			<div class="row">
				<div class="col-10">
					<p class="display-4" style="padding-left:20px;">
						Parliamentarian
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
					        <h5 class="modal-title" id="helpModalTitle">About the Parliamentarian Page</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        The Parliamentarian page contains links to parliamentary procedure study guides and practice tests, and allows for the automatic generation of a randomized practice Chapter Team test.
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

			<!--TESTS-->
			<!--parli assist-->
			<div class="row">
	
				<div class="col-sm-10" id="content" style="padding-left:5%; padding-right:5%; padding-top:2.5%; padding-bottom: 2.5%">

					<center>
					<form id="createForm">
					  <div class="form-group">
					    <label for="numQuestions">Number of Questions</label>
					    <input style="width:200px;" type="number" class="form-control" id="numQuestions" aria-describedby="numberHelp" value="10">
					  </div>
					  <div class="form-group">
					    <label for="difficulty">Difficulty Level</label>
					    <select style="width:200px;" class="form-control" id="difficulty">
					    	<option value="beginner">Beginner</option>
					    	<option value="simple">Simple</option>
					    	<option value="average">Average</option>
					    	<option value="challenging">Challenging</option>
					    	<option value="chapter">Chapter Team (50 questions)</option>
					    	<option value="benchmark1">Beginner Benchmark</option>
					    	<option value="benchmark2">Dunbar Benchmark</option>
					    </select>
					  </div>
					</form>
					<form id="scoreForm" style="display:none;" method="post">
						<input type="number" id="scoreValue" name="scoreValue">
						<input type="number" id="testNumber" name="testNumber">
					</form>
					<button id="generateButton" class="btn btn-primary" onclick="generate()">Generate Test</button>
					</center>
	
				</div>
				
				<div class="col-sm-2">
					<center>
					<!--PARLI PRO-->
					<b><p class="bodyTextType1">Helpful Guides</p></b>
					<a href="https://docs.google.com/presentation/d/19JnTf9YjODwRgyt2N4jIxER_rYEQZaEyjZtwk_zvyRs/edit?usp=sharing">State Guide</a><br>
					<a href="http://tsaweb.org/sites/default/files/Parliamentary_Procedure_Basics.pptx">National Beginner Guide</a><br>
					<a href="http://tsaweb.org/sites/default/files/Parliamentary_Procedure_Advanced.pptx">National Advanced Guide</a><br>
	
					<b><p class="bodyTextType1">Practice Tests</p></b>
					<a href="http://www.300questions.org/" target="_blank">300 Questions</a><br>
					<a href="https://drive.google.com/file/d/0B0djtG22WOS_aEhsVWZLT0xocDg/view?usp=sharing" target="_blank">Dunbar Tests</a><br>
					</center>
				</div>
			</div>
		</div>
	</div>
</div>

<!--Spooky stuff at the bottom-->
	<footer class="darknav">
		<center><p class="bodyTextType2">
			Copyright &#x1f171;oshua &#x1f171;amous 2018
		</p></center>
	</footer>
		
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>
<script src="../js/parli.js"></script>

</html>