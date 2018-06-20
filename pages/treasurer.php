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
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
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
			   <li class="nav-item"><a class="nav-link" href="eventSelection.php">Event Selection</a></li>
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
			Treasurer
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
				<a download="ledger.txt" class="text-primary" id="downloadlink">Download Ledger</a><br><br>
				
				<p style="display:none;" id='auditText'>
				<?php
				
				require('../php/connect.php');
				
				$descriptionsIncomeC = array();
				$descriptionsExpenseC = array();
				$incomeSumC = 0.00;
				$expenseSumC = 0.00;
				
				$descriptionsIncomeM = array();
				$descriptionsExpenseM = array();
				$incomeSumM = 0.00;
				$expenseSumM = 0.00;
			
				$catsQ = "SELECT personto, personfrom, description, amount, date FROM transactions WHERE chapter='$chapter'";
				$catsR = mysqli_query($link, $catsQ);
				if (!$catsR){
					die('Error: ' . mysqli_error($link));
				}
			
				while($row = mysqli_fetch_array($catsR)){
					//chapter balance
					if($row['personto'] == "Chapter"){
						$incomeSumC += $row['amount'];
						if(!array_key_exists($row['description'], $descriptionsIncomeC)){
							$descriptionsIncomeC[$row['description']] = $row['amount'];
						}
						else{
							$descriptionsIncomeC[$row['description']] = $descriptionsIncomeC[$row['description']] + $row['amount'];
						}
					}
					else if($row['personfrom'] == "Chapter"){
						$expenseSumC += $row['amount'];
						if(!array_key_exists($row['description'], $descriptionsExpenseC)){
							$descriptionsExpenseC[$row['description']] = $row['amount'];
						}
						else{
							$descriptionsExpenseC[$row['description']] = $descriptionsExpenseC[$row['description']] + $row['amount'];
						}
					}
					//member balance
					if($row['personto'] != "Chapter" && $row['personto'] != "Expense"){
						$incomeSumM += $row['amount'];
						if(!array_key_exists($row['description'], $descriptionsIncomeM)){
							$descriptionsIncomeM[$row['description']] = $row['amount'];
						}
						else{
							$descriptionsIncomeM[$row['description']] = $descriptionsIncomeM[$row['description']] + $row['amount'];
						}
					}
					else if($row['personfrom'] != "Chapter" && $row['personfrom'] != "Income"){
						$expenseSumM += $row['amount'];
						if(!array_key_exists($row['description'], $descriptionsExpenseM)){
							$descriptionsExpenseM[$row['description']] = $row['amount'];
						}
						else{
							$descriptionsExpenseM[$row['description']] = $descriptionsExpenseM[$row['description']] + $row['amount'];
						}
					}
				}
				
				echo "Chapter Audit";
				echo "\n";
				echo "\t\t\t\t";
				echo date('Y-m-d H:i:s');
				echo "\n";
				echo "\t\t\t\t";
				echo "Generated By : " . $fullname;
				echo "\n------------------------------------------------------------------------------------\nChapter Account\n------------------------------------------------------------------------------------";
				echo "\n";
				echo "\t\tIncome";
				foreach ($descriptionsIncomeC as $key => $value) {
				    echo "\n";
				    echo "\t\t\t";
				    echo "{$key} : \${$value}";
				}
				echo "\n\n";
				echo "\t\tExpenses";
				foreach ($descriptionsExpenseC as $key => $value) {
				    echo "\n";
				    echo "\t\t\t";
				    echo "{$key} : \${$value}";
				}
				echo "\n\n";
				echo "\t\tTotals";
				echo "\n";
				echo "\t\t\t";
				echo "Income : $" . $incomeSumC;
				echo "\n";
				echo "\t\t\t";
				echo "Expense : $" . $expenseSumC;
				echo "\n";
				echo "\t\t\t";
				echo "Change : $" . ($incomeSumC - $expenseSumC);
				echo "\n------------------------------------------------------------------------------------\nMember Accounts\n------------------------------------------------------------------------------------";
				echo "\n";
				echo "\t\tIncome";
				foreach ($descriptionsIncomeM as $key => $value) {
				    echo "\n";
				    echo "\t\t\t";
				    echo "{$key} : \${$value}";
				}
				echo "\n\n";
				echo "\t\tExpenses";
				foreach ($descriptionsExpenseM as $key => $value) {
				    echo "\n";
				    echo "\t\t\t";
				    echo "{$key} : \${$value}";
				}
				echo "\n\n";
				echo "\t\tTotals";
				echo "\n";
				echo "\t\t\t";
				echo "Income : $" . $incomeSumM;
				echo "\n";
				echo "\t\t\t";
				echo "Expense : $" . $expenseSumM;
				echo "\n";
				echo "\t\t\t";
				echo "Change : $" . ($incomeSumM - $expenseSumM);
				echo "\n------------------------------------------------------------------------------------\nTotal\n------------------------------------------------------------------------------------";
				echo "\n";
				echo "\t\t\t";
				echo "Chapter Balance : $" . (getChapterBalance()) . "";
				echo "\n";
				echo "\t\t\t";
				//get total user balance
				require('../php/connect.php');

				$query="SELECT SUM(balance) FROM users WHERE chapter='$chapter'";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}
				list($cumBalance) = mysqli_fetch_array($result);
				echo "Cumulative User Balances : $" . $cumBalance;
				echo "\n";
				echo "\t\t\t";
				echo "Total Balance : $" . ($cumBalance + getChapterBalance());
				
				?>
				</p>
				
				<script>
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
				</script>

				<form method="post" class="fileForm darknav">
					<span>$Amount : <input style="font-size:16px; border:1px solid black;" name="amount" type="number" id="amount" value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : '' ?>"></span>
					<span>From :
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
					</select></span>
					<span>To :
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
					</select></span>
					<span>Description : 
					<input style="font-size:14px; border:1px solid black;" name="description" type="text" id="description" value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>"></span>
					<span><input class="submitButton" style="width:100px;height:30px;font-size:16px;" name="transact" type="submit" class="box" id="transact" value="Transact"></span>
				</form>

				<br><br>

				<b><p style="font-size:14px; font-family:tahoma; padding-top:10px;"><?php echo "Chapter Balance : $" . getChapterBalance(); ?></p></b>

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

						<tr>
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

			</center>

		</div>
	</center>
	</div>
	</div>
	</div>

<!--Spooky stuff at the bottom-->
		<footer class="darknav">
			<center><p class="bodyTextType2">
				Copyright Joshua Famous 2017
			</p></center>
		</footer>
		
</body>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../js/scripts.js" type="text/javascript"></script>

</html>