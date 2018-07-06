<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];
$chapter = $_SESSION['chapter'];

//get permission settings
require('../php/connect.php');

//function to get chapter balance
function getChapterBalance()
{
	$returnValue = 0;
	$chapter = $_SESSION['chapter'];
	require('../php/connect.php');
	$transQ = "SELECT personto, personfrom, description, amount, date FROM transactions WHERE chapter='$chapter'";
	$transR = mysqli_query($link, $transQ);
	if (!$transR){
		die('Error: ' . mysqli_error($link));
	}
	while($row = mysqli_fetch_array($transR)){
		if($row['personto'] == 'Chapter'){
			$returnValue += $row['amount'];
		}
		if($row['personfrom'] == 'Chapter'){
			$returnValue -= $row['amount'];
		}
	}
	return $returnValue;
}

//function to get chapter total expenses
function getChapterExpenses()
{
	$returnValue = 0;
	$chapter = $_SESSION['chapter'];
	require('../php/connect.php');
	$transQ = "SELECT personfrom, amount FROM transactions WHERE chapter='$chapter'";
	$transR = mysqli_query($link, $transQ);
	if (!$transR){
		die('Error: ' . mysqli_error($link));
	}
	while($row = mysqli_fetch_array($transR)){
		if($row['personfrom'] == 'Chapter'){
			$returnValue += $row['amount'];
		}
	}
	return $returnValue;
}

//function to get chapter total income
function getChapterIncome()
{
	$returnValue = 0;
	$chapter = $_SESSION['chapter'];
	require('../php/connect.php');
	$transQ = "SELECT personto, amount FROM transactions WHERE chapter='$chapter'";
	$transR = mysqli_query($link, $transQ);
	if (!$transR){
		die('Error: ' . mysqli_error($link));
	}
	while($row = mysqli_fetch_array($transR)){
		if($row['personto'] == 'Chapter'){
			$returnValue += $row['amount'];
		}
	}
	return $returnValue;
}

//INFO POSTING
$query="SELECT value FROM settings WHERE name='officerInfoPermission' AND chapter='$chapter'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$officerPerm = $perm;

//handling transactions
if(isset($_POST['amount'])){

	//variables assignment
	$personfrom = $_POST['personfrom'];
	$personto = $_POST['personto'];
	$amount = $_POST['amount'];
	$description = addslashes($_POST['description']);

	require('../php/connect.php');

	//get real name of person to
	if($personto != "expense" && $personto != "chapter"){

		$nameQuery = "SELECT fullname FROM users WHERE id='$personto' AND chapter='$chapter'";

		$nameResult = mysqli_query($link, $nameQuery);

		if (!$nameResult){
			die('Error: ' . mysqli_error($link));
		}

		list($realNameTo) = mysqli_fetch_array($nameResult);

	}
	else if($personto == "expense"){
		$realNameTo = "Expense";
	}
	else if($personto == "chapter"){
		$realNameTo = "Chapter";
	}

	//get real name of person from
	if($personfrom != "income" && $personfrom != "chapter"){

		$nameQuery = "SELECT fullname FROM users WHERE id='$personfrom' AND chapter='$chapter'";

		$nameResult = mysqli_query($link, $nameQuery);

		if (!$nameResult){
			die('Error: ' . mysqli_error($link));
		}

		list($realNameFrom) = mysqli_fetch_array($nameResult);

	}
	else if($personfrom == "income"){
		$realNameFrom = "Income";
	}
	else if($personfrom == "chapter"){
		$realNameFrom = "Chapter";
	}

	//make the transaction
	$query = "INSERT INTO transactions (personto, personfrom, description, amount, date, chapter) VALUES ('$realNameTo', '$realNameFrom', '$description', '$amount', now(), '$chapter')";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	//update balances
	if($personto != "expense" && $personto != "chapter"){

		$query2 = "UPDATE users SET balance=balance+'$amount' WHERE id='$personto' AND chapter='$chapter'";

		$result2 = mysqli_query($link, $query2);

		if (!$result2){
			die('Error: ' . mysqli_error($link));
		}

	}
	if($personfrom != "income" && $personfrom != "chapter"){

		$query3 = "UPDATE users SET balance=balance-'$amount' WHERE id='$personfrom' AND chapter='$chapter'";

		$result3 = mysqli_query($link, $query3);

		if (!$result3){
			die('Error: ' . mysqli_error($link));
		}

	}
	
	$activityForm = "Transacted " . $amount . " from " . $personfrom . " to " . $personto;
		$sql = "INSERT INTO activity (user, activity, date, chapter) VALUES ('$fullname', '$activityForm', now(), '$chapter')";

		if (!mysqli_query($link, $sql)){
			die('Error: ' . mysqli_error($link));
		}

	mysqli_close($link);

	$fmsg =  "Transaction of ".$amount." Completed Successfully!";

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
			    <li class="nav-item active"><a class="nav-link" href="#">Treasurer</a></li>
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
		<div id="pageBody">
			<div class="row">
				<div class="col-10">
					<p class="pageHeader" style="padding-left:20px;">
						Finances
					</p>
					<p class="" style="padding-left:20px;">
						Financial Information for the <span class="text-primary">Treasurer</span>
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
					        <h5 class="modal-title" id="helpModalTitle">About Finances</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        The Finances page allows for management of income and expenses of both the chapter and individual user accounts, tracks and displays transactions, and can automatically generate a ledger.
					        <hr>
					        <b>Check Back Later</b><br>
					        This page's help guide is still being written.
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
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

				<p style="display:none;" id='auditText'></p>
				
				<!--<script>
				var textFile = null;
				function makeTextFile(text) {
				  text = text.replace(/\n/g, '\r\n');
				  var data = new Blob([text], {type: 'text/plain'});
				
				  // If we are replacing a previously generated file we need to
				  // manually revoke the object URL to avoid memory leaks.
				  if (textFile !== null) {
				    window.URL.revokeObjectURL(textFile);
				  }
				
				  textFile = window.URL.createObjectURL(data);
				
				  return textFile;
				}
				var auditTx = document.getElementById('auditText').innerHTML;
				document.getElementById('downloadlink').href = makeTextFile(auditTx);
				</script>-->

				<div class="adminDataSection">
					<p class="userDashSectionHeader" style="padding-left:0px;">Transact</p>
					<form method="post" enctype="multipart/form-data" class="fileForm">
					  <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
					  <div class="form-row">
					    <div class="col-4">
					      <small>$ Amount</small>
					      <input name="amount" type="number" id="amount" value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : '' ?>">
					    </div>
					    <div class="col-4">
					        <small>From</small><br>
							<!--Give each user as an option-->
							<select id="personfrom" name="personfrom">
								<option value="income">Income</option>
								<option value="chapter">Chapter</option>
								<?php

								require('../php/connect.php');

								$query="SELECT id, fullname, rank FROM users WHERE chapter='$chapter' ORDER BY fullname ASC";

								$result = mysqli_query($link, $query);

								if (!$result){
									die('Error: ' . mysqli_error($link));
								}	

								while(list($id, $personname, $personrank) = mysqli_fetch_array($result)){
									if($personrank != "admin"){
									?>

									<option value="<?php echo $id ?>"><?php echo $personname ?></option>
									
									<?php
									}
								}
										
								mysqli_close($link);

								?>
							</select>
					    </div>
					    <div class="col-4">
					    	<small>To</small><br>
					    	<!--Give each user as an option-->
							<select id="personto" name="personto">
								<option value="expense">Expense</option>
								<option value="chapter">Chapter</option>
								<?php

								require('../php/connect.php');

								$query="SELECT id, fullname, rank FROM users WHERE chapter='$chapter' ORDER BY fullname ASC";

								$result = mysqli_query($link, $query);

								if (!$result){
									die('Error: ' . mysqli_error($link));
								}	

								while(list($id, $personname, $personrank) = mysqli_fetch_array($result)){
									if($personrank != "admin"){
									?>

									<option value="<?php echo $id ?>"><?php echo $personname ?></option>
									
									<?php
									}
								}
										
								mysqli_close($link);

								?>
							</select>
					    </div>
					  </div>
					  <div class="form-row">
						    <div class="col-8">
						    	<small>Description</small>
						    	<input name="description" style="width:100%;" type="text" id="description" value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>">
						    </div>
						    <div class="col-4">
						    	<input name="transact" type="submit" class="btn btn-primary" id="transact" value="Transact">
						    </div>
					    </div>
					</form>
				</div>
				
				<br>

				<div class="row" style="width:90%; padding:0; margin:0;">
					<div class="col-8"  style="padding:0; margin:0; text-align:left;">
						<div class="adminDataSection" style="padding-left:15px; width:95%; padding-bottom: 20px;">
							<p class="userDashSectionHeader" style="padding-left:0px;">User Balance Quickview</p>
								<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
								  <div class="carousel-inner">
								    <?php

										require('../php/connect.php');

										$query="SELECT id, fullname, rank, balance FROM users WHERE chapter='$chapter' ORDER BY fullname ASC";

										$result = mysqli_query($link, $query);

										if (!$result){
											die('Error: ' . mysqli_error($link));
										}

										$isfirst = true;

										while(list($id, $personname, $personrank, $personbalance) = mysqli_fetch_array($result)){
											if($personrank != "admin"){
											?>
											<div class="carousel-item <?php if($isfirst){ echo "active"; } ?>">
												<div class="innerCarouselText">
												    <p><?php echo $personname ?></p>
												    <p><?php echo "$".$personbalance ?></p>
												</div>
											</div>

											<?php
											$isfirst = false;
											}
										}
												
										mysqli_close($link);

									?>
								  </div>
								  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
								    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
								    <span class="sr-only">Previous</span>
								  </a>
								  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
								    <span class="carousel-control-next-icon" aria-hidden="true"></span>
								    <span class="sr-only">Next</span>
								  </a>
								</div>
						</div>
					</div>
					<div class="col-4"  style="padding:0; margin:0; text-align:left;">
						<div class="adminDataSection" style="padding-left:15px; float:right; width:95%;">
							<p class="userDashSectionHeader" style="padding-left:0px;">Chapter Balance</p>
							<p class="text-primary" style="font-size:30px; padding-top:10px; margin-bottom:0px;"><?php echo "$" . number_format((float)getChapterBalance(), 2, '.', ''); ?></p>
							<p style="font-size:12px; padding-top:0px; padding-bottom: 15px;">Current Balance</p>
						</div>
					</div>
				</div>

				<br>

				<div class="row" style="width:90%; padding:0; margin:0;">
					<div class="col-4"  style="padding:0; margin:0; text-align:left;">
						<div class="adminDataSection" style="padding-left:15px; float:left; width:95%;">
							<p class="userDashSectionHeader" style="padding-left:0px;">Ledger</p>
							<a style="font-size:20px; padding-bottom:0px; margin-bottom: 0px;" class="text-primary" id="downloadlink" href="../php/ledger.php">Download Ledger</a>
							<p style="font-size:12px; padding-top:0px; padding-bottom: 15px;">as Excel Spreadsheet</p>
						</div>
					</div>
					<div class="col-8"  style="padding:0; margin:0; text-align:left;">
						<div class="adminDataSection" style="padding-left:15px; width:95%; float:right; padding-bottom: 20px;">
							<p class="userDashSectionHeader" style="padding-left:0px;">Chapter Account</p>
							<div class="progress" style="width:95%">
							  <div class="progress-bar bg-success" role="progressbar" aria-valuenow="<?php echo getChapterIncome(); ?>"
							  aria-valuemin="0" aria-valuemax="<?php echo getChapterExpenses() + getChapterIncome(); ?>" style="width:<?php echo (getChapterIncome() / (getChapterExpenses() + getChapterIncome())) * 100; ?>%">
							    Income ($<?php echo number_format((float)getChapterIncome(), 2, '.', ''); ?>)
							  </div>
							</div>
							<br>
							<div class="progress" style="width:95%">
							  <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="<?php echo getChapterExpenses(); ?>"
							  aria-valuemin="0" aria-valuemax="<?php echo getChapterExpenses() + getChapterIncome(); ?>" style="width:<?php echo (getChapterExpenses() / (getChapterExpenses() + getChapterIncome())) * 100; ?>%">
							    Expenses ($<?php echo number_format((float)getChapterExpenses(), 2, '.', ''); ?>)
							  </div>
							</div>
						</div>
					</div>
				</div>

				<br>

				<div class="adminDataSection">
					<p class="userDashSectionHeader" style="padding-left:0px;">Transaction History</p>

					<table style="width:80%; height:80%;">

					<?php

					//SECOND THING - TRANSACTIONS

					require('../php/connect.php');

					$query="SELECT * FROM transactions WHERE chapter='$chapter' ORDER BY id DESC";

					$result = mysqli_query($link, $query);

					if (!$result){
						die('Error: ' . mysqli_error($link));
					}		

					if(mysqli_num_rows($result) == 0){
						echo "No Transactions Found!<br>";
					}
					else{
						while(list($id, $personto, $personfrom, $description, $amount, $date) = mysqli_fetch_array($result)){
							?>

							<tr class="announcementDiv">
								<td><p style="font-size:14px; font-family:tahoma;"><?php echo "From : ".$personfrom ?></p></td>
								<td><p style="font-size:14px; font-family:tahoma;"><?php echo "To : ".$personto ?></p></td>
								<td><p style="font-size:14px; font-family:tahoma;"><?php echo "$".$amount ?></p></td>
								<td><p style="font-size:14px; font-family:tahoma;"><?php echo "On : ".$date ?></p></td>
							<td><p style="font-size:14px; font-family:tahoma;"><?php echo $description ?></p></td>
							</tr>
							
							<?php
						}
					}
							
					mysqli_close($link);

					?>

					</table>
				</div>

			</center>

		</div>
	</center>
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

</html>