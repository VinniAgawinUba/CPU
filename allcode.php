<?php
ob_start();
include('config/dbcon.php');
session_start();
if(isset($_POST['logout_btn'])){
  //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);
    
    include('config.php');

    //If signed in with google
    if(isset($_SESSION['access_token'])){
        unset($_SESSION['access_token']);
        $google_client->revokeToken($accesstoken);
    }
    
    //Destroy entire session data.
    session_destroy();
    
     
    //redirect page to index.php
    header('Location: index.php');
$_SESSION['message'] = "Logged Out Successfully";
  header('location: login.php');
  exit(0);
}

//Add Purchase Request
if(isset($_POST['request_add_btn_front'])){
    // Purchase Request Information
    $printed_name = $_POST['printed_name'];
    $unit_dept_college = $_POST['unit_dept_college'];
    $iptel_email = $_POST['iptel_email'];
    $unit_head = $_POST['unit_head'];
    $cluster = $_POST['cluster'];

    //Requestor User Information
    $requestor_user_id = $_POST['user_id'];
    $requestor_user_email = $_POST['user_email'];
    $requestor_user_name = $_POST['user_name'];
  
     // Signatures
     $signatures = array(
      // Add more signature fields as needed, e.g.,signed_1, signed_2, etc.
      "signed_1", // Vice President's signature
      "signed_2", // Vice President for Administration's signature
      "signed_3", // Budget Controller's signature
      "signed_4", // University Treasurer's signature
      "signed_5" // Office of the President's signature
  );
  
   // Signature Settings
   $folderPath = "uploads/signatures/";
  
   
  
    // Purchase Types
    $purchase_types = isset($_POST['purchase_type']) ? implode(',', $_POST['purchase_type']) : '';
  
    // Items Information
    $item_qty = $_POST['item_qty'];
    $item_justifications = $_POST['item_justification'];
    $item_description = $_POST['item_description'];
    $item_number = $_POST['item_number'];
  
    
  
    // Insert Purchase Request into the database
    $sql_purchase_request = "INSERT INTO purchase_requests (requestor_user_id, requestor_user_name, requestor_user_email, cluster, unit_head ,printed_name, unit_dept_college, iptel_email) 
            VALUES ('$requestor_user_id', '$requestor_user_name', '$requestor_user_email', '$cluster', '$unit_head' , '$printed_name','$unit_dept_college', '$iptel_email')";
  
    // Execute Purchase Request query
    if ($con->query($sql_purchase_request) === TRUE) {
        // Get the ID of the last inserted purchase request
        $purchase_request_id = $con->insert_id;
  
  
        // Insert Items into the database
    for ($i = 0; $i < count($item_qty); $i++) {
        $sql_item = "INSERT INTO items (item_number, purchase_request_id, item_qty, item_justification, item_description) 
                    VALUES ('{$item_number[$i]}','$purchase_request_id', '{$item_qty[$i]}', '{$item_justifications[$i]}', '{$item_description[$i]}')";
                    
        // Execute Item query using prepared statement
        $stmt = $con->prepare($sql_item);
        if ($stmt) {
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error: " . $sql_item . "<br>" . $con->error;
        }
    }

     // Upload multiple files
     if(isset($_FILES['request_documents'])) {
        $file_count = count($_FILES['request_documents']['name']);
        for($i = 0; $i < $file_count; $i++) {
            $file_name = $_FILES['request_documents']['name'][$i];
            $file_tmp = $_FILES['request_documents']['tmp_name'][$i];
            $file_type = $_FILES['request_documents']['type'][$i];
            $file_size = $_FILES['request_documents']['size'][$i];
            $file_error = $_FILES['request_documents']['error'][$i];

            if($file_error === UPLOAD_ERR_OK) {
                $file_destination = 'uploads/request_documents/' . $file_name;
                if(move_uploaded_file($file_tmp, $file_destination)) {
                    // Insert file details into purchase_requests_attatchments table
                    $query = "INSERT INTO purchase_requests_attachments (purchase_request_id, file_name, file_type, file_size, file_path) 
                              VALUES ('$purchase_request_id', '$file_name', '$file_type', '$file_size', '$file_destination')";
                    $query_run = mysqli_query($con, $query);

                    if(!$query_run) {
                        $_SESSION['message'] = "Error inserting file details into database";
                        header('Location: form.php');
                        exit(0);
                    }
                } else {
                    $_SESSION['message'] = "Error moving uploaded file to destination folder";
                    header('Location: form.php');
                    exit(0);
                }
            } else {
                $_SESSION['message'] = "Error uploading file: " . $_FILES['request_documents']['name'][$i];
                header('Location: form.php');
            }
        }
    } else {
        // Debugging statement
        echo "No request_documents uploaded";
    }

  
        $_SESSION['message'] = "Successfully added a new request";
        header('location: form.php');
        exit(0);
    } else {
      $_SESSION['message'] = "Error: " . $sql_purchase_request . "<br>" . $con->error;
        echo "Error: " . $sql_purchase_request . "<br>" . $con->error;
    } 

   
  }
  ob_end_flush();
?>