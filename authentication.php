<?php
include('config/dbcon.php');
if(!isset($_SESSION['auth']))
{
    $_SESSION['message'] = "Login to Access Website";
    header('location: login.php');
    exit(0);
}
?>