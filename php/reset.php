<?php
if($_GET['user'] && $_GET['reset']){

  $username = $_GET['user'];
  $pass = $_GET['reset'];
  $email = $_GET['email'];

  require_once('connect.php');

  $query="SELECT email, password FROM users WHERE username='$username' AND password='$pass'";

  $result = mysqli_query($link, $query);

  if (!$result){
    die('Error: ' . mysqli_error($link));
  }

  if(mysqli_num_rows($result) == 1)
  {
    ?>
    <form method="post" action="submit_new.php">
      <input type="hidden" name="username" value="<?php echo $username;?>">
      <input type="hidden" name="email" value="<?php echo $email;?>">
      <p>Enter New Password:</p>
      <input type="password" name='password'>
      <input type="submit" name="submit_password">
    </form>
    <?php
  }

}
?>