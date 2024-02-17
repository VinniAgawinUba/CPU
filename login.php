<?php
session_start();
if(isset($_SESSION['auth']))
{
    $_SESSION['message'] = "You are already logged in";
    header('Location: index.php');
    exit(0);
}

include('includes/header.php');
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                    
                    <?php include('message.php'); ?>
               <div class="card">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form action="logincode.php" method="POST">
                    <div class="form-group mb-3">
                        <label>Email ID</label>
                        <input required type="email" name="email" placeholder="Enter Email Address" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input required type="password" name="password" placeholder="Enter Password" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <button required type="submit" name="login_btn" class="btn btn-primary">Login Now</button>
                    </div>
                    </form>

                </div>
               </div>
            </div>
</div>

<?php
include('includes/footer.php');
?>