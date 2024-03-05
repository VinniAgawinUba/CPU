<?php
session_start();
include('config/dbcon.php');

if(isset($_SESSION['auth']))
{
    $_SESSION['message'] = "You are already logged in";
    header('Location: index.php');
    exit(0);
}

//Include Configuration File
include('config.php');
use Google\Service\Oauth2 as Google_Service_Oauth2;
$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}
//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if(!isset($_SESSION['access_token']))
{
 //Create a URL to obtain user authorization
 $login_button = '<h4 class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-cyan-50"><a href='.$google_client->createAuthUrl().'>Login with Google</a></h4>';
}


// Check if the user is already authenticated using Google
if(isset($_SESSION['access_token'])) {
    // Fetch user data from Google
    $google_client->setAccessToken($_SESSION['access_token']);
    $google_service = new Google_Service_Oauth2($google_client);
    $data = $google_service->userinfo->get();

    // Extract user details
    $email = $data['email'];
    $fname = $data['given_name'];
    $lname = $data['family_name'];

    // Check if the user already exists in the database
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($con, $query);

    foreach($result as $data) {
        $user_id = $data['id'];
        $user_name = $data['fname'].''.$data['lname'];
        $user_email = $data['email'];
        $role_as = $data['role_as'];
    }

    if(mysqli_num_rows($result) == 0) {
        // If the user does not exist, insert the user into the database
        $insert_query = "INSERT INTO users (fname, lname, email) VALUES ('$fname', '$lname', '$email')";
        mysqli_query($con, $insert_query);
    }


    // Set the session variables
    $_SESSION['auth'] = "true";
    $_SESSION['auth_user'] = [
        'user_name' => $fname . ' ' . $lname,
        'user_email' => $email
    ];
    $_SESSION['auth_role'] = $role_as;

    //Redirect to the appropriate page
    $_SESSION['message'] = "Welcome $fname $lname";
    header('Location: index.php');
    exit;
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
                        <button required type="submit" name="login_btn" class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-cyan-50">Login Now</button>
                    </div>

                     <!-- Google login button -->
                     <?php
                                         
                    ?>
                    <div class="form-group mb-3">
                        <?php
                            
                        echo $login_button;
                        if (isset($_SESSION['access_token'])) {
                            $authUrl = $google_client->createAuthUrl();
                            echo '<h4 class="btn bg-blue-500 text-cyan-50 hover:bg-blue-300 hover:text-cyan-50" onclick="openPopup(\'' . $authUrl . '\')">Login with Google</h4>';
                            }
                        
                        ?>
                        
                    </div>
                    
                    </form>

                </div>
               </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
