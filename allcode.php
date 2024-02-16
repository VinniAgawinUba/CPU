<?php
session_start();
if(isset($_POST['logout_btn'])){
  //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);

$_SESSION['message'] = "Logged Out Successfully";
  header('location: login.php');
  exit(0);
}
?>