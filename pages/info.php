<?php

session_start();

$username = $_SESSION['username'];
$rank = $_SESSION['rank'];
$fullname = $_SESSION['fullname'];

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

//EMAIL
$query="SELECT value FROM settings WHERE name='officerEmailPermission'";

$result = mysqli_query($link, $query);

if (!$result){
	die('Error: ' . mysqli_error($link));
}

//save the result
list($perm) = mysqli_fetch_array($result);
$emailPerm = $perm;

//file uploading
if(isset($_POST['uploadFile']) && $_FILES['userfile']['size'] > 0){

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

	//get poster
	$poster = $_SESSION['fullname'];

	require('../php/connect.php');

	$query = "INSERT INTO minutes (name, size, type, content, date, view, poster) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$view', '$poster')";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "File ".$fileName." Uploaded Successfully!";

}

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

	$query = "INSERT INTO minutes (name, size, type, content, date, view, poster, class) VALUES ('$fileName', '$fileSize', '$fileType', '$content', now(), '$view', '$poster', '$class')";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);

	$fmsg =  "File ".$fileName." Uploaded Successfully!";

}

//posting announcements
if(isset($_POST['body'])){

	//variables assignment
	$articleTitle = addslashes($_POST['title']);
	$articleBody = addslashes($_POST['body']);
	$articlePoster = addslashes($_SESSION['fullname']);
	$doMail = $_POST['mail'];

	require('../php/connect.php');

	$query = "INSERT INTO announcements (title, body, poster, date) VALUES ('$articleTitle', '$articleBody', '$articlePoster', now())";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	//emailing announcements
	if($doMail == "yes"){

		//get users
		$query="SELECT fullname, email FROM users";

		$result = mysqli_query($link, $query);

		if (!$result){
			die('Error: ' . mysqli_error($link));
		}

		//for each user
		while(list($fullname, $email) = mysqli_fetch_array($result)){

			//actual mail part
			$mailMessage = "
			<html>
			<h1></html> $articleTitle <html></h1>
<p><pre></html>
$articleBody
<html><pre></p>
			<br>
			<p>For more information about your events and various other chapter-related functions, visit <a href='http://chaptersweet.x10host.com'>http://chaptersweet.x10host.com</a>.</p>
			<p>If you have any questions or concerns, contact your advisor.</p>
			<p>This email is automated, do not attempt to respond.</p>
			</html>
			";

			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: '.$articlePoster.' <chapters@xo7.x10hosting.com>' . "\r\n";

			mail($email,"TSA Chapter Announcement",$mailMessage,$headers);

}

	}

	mysqli_close($link);

	$fmsg =  "Article '".$articleTitle."' Uploaded Successfully!";

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
	if($personto != "donation"){

		$nameQuery = "SELECT fullname FROM users WHERE id='$personto'";

		$nameResult = mysqli_query($link, $nameQuery);

		if (!$nameResult){
			die('Error: ' . mysqli_error($link));
		}

		list($realNameTo) = mysqli_fetch_array($nameResult);

	}
	else{

		$realNameTo = "Donation";

	}

	//get real name of person from
	if($personfrom != "income"){

		$nameQuery = "SELECT fullname FROM users WHERE id='$personfrom'";

		$nameResult = mysqli_query($link, $nameQuery);

		if (!$nameResult){
			die('Error: ' . mysqli_error($link));
		}

		list($realNameFrom) = mysqli_fetch_array($nameResult);

	}
	else{

		$realNameFrom = "Income";

	}

	//make the transaction
	$query = "INSERT INTO transactions (personto, personfrom, description, amount, date) VALUES ('$realNameTo', '$realNameFrom', '$description', '$amount', now())";

	$result = mysqli_query($link, $query);

	if (!$result){
		die('Error: ' . mysqli_error($link));
	}

	//update balances
	if($personto != "donation"){

		$query2 = "UPDATE users SET balance=balance+'$amount' WHERE id='$personto'";

		$result2 = mysqli_query($link, $query2);

		if (!$result2){
			die('Error: ' . mysqli_error($link));
		}

	}
	if($personfrom != "income"){

		$query3 = "UPDATE users SET balance=balance-'$amount' WHERE id='$personfrom'";

		$result3 = mysqli_query($link, $query3);

		if (!$result3){
			die('Error: ' . mysqli_error($link));
		}

	}

	mysqli_close($link);

	$fmsg =  "Transaction of ".$amount." Completed Successfully!";

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
					Chapter <?php if($_SESSION['chapter'] == 'freshman'){ echo "<i>Fresh</i>"; }else{ echo "Sweet"; } ?>
				</p>
		</header>
<!--Spooky stuff still kind of at the top-->
		<div id="subTitleBar">
			<form action="../index.php">
    			<input class="backButton" type="submit" value="Back" />
			</form>
			<center><p class="subTitleText">
				Information
			</p></center>
		</div>
<!--Spooky stuff closer to the middle-->
			<div id="contentPane" style="overflow:hidden">

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

				<!--INFO TYPES LINKS-->
				<center>
				<div class="iconLinks">

				<!--Files-->
					<span onclick="showFiles();"><a href="#"><img src="../imgs/icon_files.png" height="64" width="64"><p class="bodyTextType1">Files</p></a></span>
				<!--Minutes-->
					<span onclick="showMinutes();"><a href="#"><img src="../imgs/icon_minutes.png" height="64" width="64"><p class="bodyTextType1">Minutes</p></a></span>
				<!--Announcements-->
					<span onclick="showAnnouncements();"><a href="#"><img src="../imgs/icon_announcements.png" height="64" width="64"><p class="bodyTextType1">Announcements</p></a></span>
				<!--Announce-->
				<?php if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesAnnouncements" || $officerPerm == "filesAnnouncements" || $officerPerm == "announcements")) || $rank == "admin"){ ?>
					<span onclick="showPost();"><a href="#"><img src="../imgs/icon_announce.png" height="64" width="64"><p class="bodyTextType1">Post Announcement</p></a></span>
				<?php } ?>
				<!--Audit-->
				<?php if($rank == "officer" || $rank == "admin"){ ?>
					<span onclick="showAudit();"><a href="#"><img src="../imgs/wallet.png" height="64" width="64"><p class="bodyTextType1">Audit</p></a></span>
				<?php } ?>

				</div>

				<!--FILES-->
				<div id="filesDiv" class="infoTab">

				<div class="userDashHeader" style="width:80%;">
					<p class="subTitleText" style="padding-top:15px">Files</p>
				</div>

				<!--Description-->
				<p class="bodyTextType1">
					Here you can view all of your chapter's important files. Officers and Admins can upload new files.
				</p>

				<?php if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesFiles" || $officerPerm == "filesAnnouncements" || $officerPerm == "files")) || $rank == "admin"){ ?>
					<form method="post" enctype="multipart/form-data" class="fileForm">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						<span><input style="font-size:16px; border:1px solid #B60000;" name="userfile" type="file" id="userfile"></span>
						<span>Who Can View :
						<select id="view" name="view">
							<option value="all">All</option>
							<option value="officer">Officers Only</option>
						</select></span>
						<span><input class="submitButton" style="width:100px;height:30px;font-size:16px;" name="uploadFile" type="submit" class="box" id="uploadFile" value="Upload"></span>
					</form>
				<?php } ?>

				<br>
				<br>

				<?php

				require('../php/connect.php');

				$query="SELECT id, name, date, view, poster FROM minutes WHERE class='file'";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}

				$doMemberSkip = 0;

				if(mysqli_num_rows($result) == 0){
					echo "No Files Found!<br>";
				}
				else{
					//FOR MEMBERS - check if all available files are hidden
					if($rank == "member"){

						$viewLevel = "all";

						$query2="SELECT id, view FROM minutes WHERE view='$viewLevel'";

						$result2 = mysqli_query($link, $query2);

						if (!$result2){
							die('Error: ' . mysqli_error($link));
						}

						if(mysqli_num_rows($result2) == 0){
							$doMemberSkip = 1;
						}

					}

					if($doMemberSkip == 1){
							echo "No Files Found!<br>";
					}
					else{
						while(list($id, $name, $date, $view, $poster) = mysqli_fetch_array($result)){
							if(($view == "officer" && ($rank == "officer" || $rank == "admin")) || ($view == "all")){
								?>
							<a class="minutesLink" href="../php/download.php?id=<?php echo "".$id ?>" style="float:left; padding-left: 25%;"><?php echo "".$name ?></a>
							<?php
							if($view == "officer"){ ?>
									<p style="float:left; padding-left: 10%;">Private</p>
								<?php } ?>
							<p style="float:right; padding-right: 25%;"><?php echo "".$date ?></p>
							<p style="float:right; padding-right: 10%;"><?php echo "".$poster ?></p>
							<br>
							
							<?php
							}
						}
					}
				}
						
				mysqli_close($link);

				?>

				</div>

				<!--MINUTES-->
				<div id="minutesDiv" style="display:none;" class="infoTab">

				<div class="userDashHeader" style="width:80%;">
					<p class="subTitleText" style="padding-top:15px">Minutes</p>
				</div>

				<!--Description-->
				<p class="bodyTextType1">
					Here you can view the minutes of chapter meetings. The secretary can upload minutes here.
				</p>

				<?php if(($rank == "officer" && ($officerPerm == "all" || $officerPerm == "minutesFiles" || $officerPerm == "minutesAnnouncements" || $officerPerm == "minutes")) || $rank == "admin"){ ?>
					<form method="post" enctype="multipart/form-data" class="fileForm">
						<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						<span><input style="font-size:16px; border:1px solid #B60000;" name="userfile" type="file" id="userfile"></span>
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

				<?php

				require('../php/connect.php');

				$query="SELECT id, name, date, view, poster FROM minutes WHERE class='minutes'";

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

						$query2="SELECT id, view FROM minutes WHERE view='$viewLevel'";

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
							if(($view == "officer" && ($rank == "officer" || $rank == "admin")) || ($view == "all")){
								?>
							<a class="minutesLink" href="../php/download.php?id=<?php echo "".$id ?>" style="float:left; padding-left: 25%;"><?php echo "".$name ?></a>
							<?php
							if($view == "officer"){ ?>
									<p style="float:left; padding-left: 10%;">Private</p>
								<?php } ?>
							<p style="float:right; padding-right: 25%;"><?php echo "".$date ?></p>
							<p style="float:right; padding-right: 10%;"><?php echo "".$poster ?></p>
							<br>
							
							<?php
							}
						}
					}
				}
						
				mysqli_close($link);

				?>

				</div>

				<!--ANNOUNCEMENTS-->
				<div id="announcementsDiv" style="display:none;" class="infoTab">

				<div class="userDashHeader" style="width:80%;">
					<p class="subTitleText" style="padding-top:15px">Announcements</p>
				</div>

				<!--Description-->
				<p class="bodyTextType1">
					Here you can view all of your chapter's announcements.
				</p>

				<div style="text-align: left;">
				<?php

				require('../php/connect.php');

				$query="SELECT * FROM announcements ORDER BY id DESC";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}		

				if(mysqli_num_rows($result) == 0){
					echo "No Articles Found!<br>";
				}
				else{
					while(list($id, $title, $body, $poster, $date) = mysqli_fetch_array($result)){
						?>

						<p style="font-weight: bold; font-family:tahoma; font-size:24px; padding-left:15%; padding-top:10px;"><?php echo "".$title ?></p>
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
						
				mysqli_close($link);

				?>
				</div>

				</div>
				
				<!--ANNOUNCE-->
				<div id="postDiv" style="display:none;" class="infoTab">

				<div class="userDashHeader" style="width:80%;">
					<p class="subTitleText" style="padding-top:15px">Post Announcement</p>
				</div>

				<!--Description-->
				<p class="bodyTextType1">
					Officers and Admins can write and post announcements here.
				</p>

				<form method="post" id="articleWriteForm">
					<br>
					Title:
					<br>
					<input class="taskFormInput" style="width:800px; height:40px;" type="text" name="title" id="title">
					<br><br>
					Body:
					<br>
					<textarea form="articleWriteForm" cols="110" rows="15" name="body" id="body"></textarea>
					<br><br>
					<?php 
					if(($rank == "officer" && $emailPerm == "yes") || $rank == "admin"){ ?>
					<select id="mail" name="mail">
							<option value="no">Do Not Email</option>
							<option value="yes">Send As Email</option>
					</select>
					<br><br>
					<?php } ?>
					<input class="submitButton" name="upload" type="submit" class="box" id="upload" value="Post">
				</form>

				</div>

				<!--AUDIT-->
				<div id="auditDiv" style="display:none;" class="infoTab">

				<div class="userDashHeader" style="width:80%;">
					<p class="subTitleText" style="padding-top:15px">Audit</p>
				</div>

				<!--Description-->
				<p class="bodyTextType1">
					Officers and Admins can view the audit, and make withdrawals and deposits here.
				</p>

				<form method="post" class="fileForm">
					<span>$Amount : <input style="font-size:16px; border:1px solid #B60000;" name="amount" type="number" id="amount" value="<?php echo isset($_POST['amount']) ? $_POST['amount'] : '' ?>"></span>
					<span>From :
					<!--Give each user as an option-->
					<select id="personfrom" name="personfrom">
						<option value="income">Income</option>
						<?php

						require('../php/connect.php');

						$query="SELECT id, fullname FROM users";

						$result = mysqli_query($link, $query);

						if (!$result){
							die('Error: ' . mysqli_error($link));
						}	

						while(list($id, $personname) = mysqli_fetch_array($result)){
							?>

							<option value="<?php echo $id ?>"><?php echo $personname ?></option>
							
							<?php
						}
								
						mysqli_close($link);

						?>
					</select></span>
					<span>To :
					<!--Give each user as an option-->
					<select id="personto" name="personto">
						<option value="donation">Donation</option>
						<?php

						require('../php/connect.php');

						$query="SELECT id, fullname FROM users";

						$result = mysqli_query($link, $query);

						if (!$result){
							die('Error: ' . mysqli_error($link));
						}	

						while(list($id, $personname) = mysqli_fetch_array($result)){
							?>

							<option value="<?php echo $id ?>"><?php echo $personname ?></option>
							
							<?php
						}
								
						mysqli_close($link);

						?>
					</select></span>
					<span>Description : 
					<input style="font-size:14px; border:1px solid #B60000;" name="description" type="text" id="description" value="<?php echo isset($_POST['description']) ? $_POST['description'] : '' ?>"></span>
					<span><input class="submitButton" style="width:100px;height:30px;font-size:16px;" name="transact" type="submit" class="box" id="transact" value="Transact"></span>
				</form>

				<br><br>

				<?php

				//FIRST THING - CHAPTER BALANCES

				require('../php/connect.php');

				$query="SELECT balance FROM users WHERE id='53'";

				$result = mysqli_query($link, $query);

				if (!$result){
					die('Error: ' . mysqli_error($link));
				}		

				if(mysqli_num_rows($result) == 0){
					echo "No Chapter Balance Found!<br>";
				}
				else{
					while(list($balance) = mysqli_fetch_array($result)){
						?>
							<b><p style="font-size:14px; font-family:tahoma; padding-top:10px;"><?php echo "Chapter Balance : $".$balance ?></p></b>
						<?php
					}
				}

				//SECOND THING - TRANSACTIONS

				$query="SELECT * FROM transactions ORDER BY id DESC";

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

						<div class="basicSpanDiv" style="width:100%;">
							<span><p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "From : ".$personfrom ?></p></span>
							<span><p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "To : ".$personto ?></p></span>
							<span><p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "$".$amount ?></p></span>
							<span><p style="font-size:14px; font-family:tahoma; padding-left:15%; padding-top:10px;"><?php echo "On : ".$date ?></p></span>
						</div>
						<p style="font-size:14px; font-family:tahoma; padding-top:10px;"><?php echo $description ?></p>
						
						<?php
					}
				}
						
				mysqli_close($link);

				?>

				</div>

			</center>
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