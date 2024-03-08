<?php
session_start();
if(isset($_POST['logout_btn'])){
  //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);
    
    include('config.php');

    $accesstoken= $_SESSION['access_token'];
     
    //Reset OAuth access token
    $google_client->revokeToken($accesstoken);
    
    //Destroy entire session data.
    session_destroy();
    
     
    //redirect page to index.php
    header('Location: index.php');
$_SESSION['message'] = "Logged Out Successfully";
  header('location: login.php');
  exit(0);
}

if(isset($_POST['request_add_btn_front'])){
  header('location: login.php');
  exit(0);
}
?>