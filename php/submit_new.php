<?php

function validate($data){
  $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace('\\', '', $data);
    $data = str_replace('/', '', $data);
    $data = str_replace("'", '', $data);
    $data = str_replace(";", '', $data);
    $data = str_replace("(", '', $data);
    $data = str_replace(")", '', $data);
    return $data;
}

if($_POST['username'] && $_POST['password']){

  $user = $_POST['username'];
  $pass = $_POST['password'];
  $email = $_POST['email'];

  $pass = validate($pass);
  $pass = password_hash($pass, PASSWORD_DEFAULT);

  require_once('connect.php');

  $query="UPDATE users SET password='$pass' WHERE username='$user' AND email='$email'";

  $result = mysqli_query($link, $query);

  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

  header("Location: http://chaptersweet.x10host.com/index.php"); /* Redirect browser */
exit();

}

?>