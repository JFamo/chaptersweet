<?php

ob_start();
session_start();

if( isset($_SESSION['user'])!=""){
	header(Location: main.php);
}

include_once 'dbconnect.php';

$error = false;

 if ( isset($_POST['btn-signup']) ) {
  
  // clean user inputs to prevent sql injections
  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);
  
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $password = trim($_POST['password']);
  $password = strip_tags($password);
  $password = htmlspecialchars($password);

  $grade = trim($_POST['grade']);
  $grade = strip_tags($grade);
  $grade = htmlspecialchars($grade);

  $fullname = trim($_POST['fullname']);
  $fullname = strip_tags($fullname);
  $fullname = htmlspecialchars($fullname);
  
  // basic name validation
  if (empty($username)) {
   $error = true;
   $nameError = "Please enter a username.";
  } else if (strlen($name) < 3) {
   $error = true;
   $nameError = "Username must have atleat 3 characters.";
  }
  //basic email validation
  if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
   $error = true;
   $emailError = "Please enter valid email address.";
  } else {
   // check email exist or not
   $query = "SELECT email FROM users WHERE email='$email'";
   $result = mysql_query($query);
   $count = mysql_num_rows($result);
   if($count!=0){
    $error = true;
    $emailError = "Provided Email is already in use.";
   }
  }
  // password validation
  if (empty($password)){
   $error = true;
   $passError = "Please enter a password.";
  } else if(strlen($password) < 6) {
   $error = true;
   $passError = "Password must have at least 6 characters.";
  }
  
  // password encrypt using SHA256();
  $password = hash('sha256', $pass);
  
  // if there's no error, continue to signup
  if( !$error ) {
   
   $query = "INSERT INTO users(userName,userEmail,userPass) VALUES('$name','$email','$password')";
   $res = mysql_query($query);
    
   if ($res) {
    $errTyp = "success";
    $errMSG = "Successfully registered, you may login now";
    unset($name);
    unset($email);
    unset($pass);
   } else {
    $errTyp = "danger";
    $errMSG = "Something went wrong, try again later..."; 
   } 
    
  }
  
  
 }
?>