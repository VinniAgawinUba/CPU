<?php
include('config/dbcon.php');
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

//Add Request
if(isset($_POST['request_add_btn_front'])){
  // Purchase Request Information
  $purchase_request_number = $_POST['purchase_request_number'];
  $printed_name = $_POST['printed_name'];
  $unit_dept_college = $_POST['unit_dept_college'];
  $iptel_email = $_POST['iptel_email'];
  $requestor_signature = $_POST['signed_Requestor'];

   // Signatures
   $signatures = array(
    // Add more signature fields as needed, e.g.,signed_1, signed_2, etc.
    "signed_Requestor", // Requestor's signature
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
  $item_types = $_POST['item_type'];
  $item_justifications = $_POST['item_justification'];
  $item_reasons = $_POST['item_reason'];
  $item_date_conditions = $_POST['item_date_condition'];

  // Remarks by College Dean/Principal
  $remarks_dean = $_POST['remarks_dean'];

  // Endorsed by College Dean/Principal
  $endorsed_by_dean = $_POST['endorsed_by_dean'];

  // Signatures for Approvals
  $vice_president_remarks = $_POST['vice_president_remarks'];
  $vice_president_approved = $_POST['vice_president_approved'];
  $vice_president_signature = $_POST['signed_1'];

  $vice_president_administration_remarks = $_POST['vice_president_administration'];
  $vice_president_administration_approved = $_POST['vice_president_administration_approved'];
  $vice_president_administration_signature = $_POST['signed_2'];

  $budget_controller_remarks = $_POST['budget_controller'];
  $budget_controller_approved = $_POST['budget_controller_approved'];
  $budget_controller_code = $_POST['budget_controller_code'];
  $budget_controller_signature = $_POST['signed_3'];

  $university_treasurer_remarks = $_POST['university_treasurer'];
  $university_treasurer_approved = $_POST['university_treasurer_approved'];
  $university_treasurer_signature = $_POST['signed_4'];

  $office_of_the_president_remarks = $_POST['office_of_the_president'];
  $office_of_the_president_approved = $_POST['office_of_the_president_approved'];
  $office_of_the_president_signature = $_POST['signed_5'];

  // Insert Purchase Request into the database
  $sql_purchase_request = "INSERT INTO purchase_requests (purchase_request_number, printed_name, signed_Requestor, unit_dept_college, iptel_email, purchase_types, remarks_dean, endorsed_by_dean, vice_president_remarks, vice_president_approved, signed_1, vice_president_administration_remarks, vice_president_administration_approved, signed_2, budget_controller_remarks, budget_controller_approved, budget_controller_code, signed_3, university_treasurer_remarks, university_treasurer_approved, signed_4, office_of_the_president_remarks, office_of_the_president_approved, signed_5) 
          VALUES ('$purchase_request_number', '$printed_name', '$requestor_signature, ','$unit_dept_college', '$iptel_email', '$purchase_types', '$remarks_dean', '$endorsed_by_dean', '$vice_president_remarks', '$vice_president_approved', '$vice_president_signature', '$vice_president_administration_remarks', '$vice_president_administration_approved', '$vice_president_administration_signature', '$budget_controller_remarks', '$budget_controller_approved', '$budget_controller_code', '$budget_controller_signature', '$university_treasurer_remarks', '$university_treasurer_approved', '$university_treasurer_signature', '$office_of_the_president_remarks', '$office_of_the_president_approved', '$office_of_the_president_signature')";

  // Execute Purchase Request query
  if ($con->query($sql_purchase_request) === TRUE) {
      // Get the ID of the last inserted purchase request
      $purchase_request_id = $con->insert_id;
      
// Save each signature to the server and database
foreach ($signatures as $signature_field) {
  if (isset($_POST[$signature_field])) {
      // Process each signature
      $image_parts = explode(";base64,", $_POST[$signature_field]);
      $image_type_aux = explode("image/", $image_parts[0]);
      $image_type = $image_type_aux[1];
      $image_base64 = base64_decode($image_parts[1]);
      $filename = uniqid() . ".$image_type";
      $file = $folderPath . $filename;

      // Save the signature to the server
      if (file_put_contents($file, $image_base64) !== false) {
          // Signature saved successfully
          echo "Signature saved successfully: $file<br>";

          // Insert filename and request ID into the signatures table
          $request_id = $purchase_request_id;
          $sql = "INSERT INTO signatures (request_id, filename) VALUES ('$request_id', '$filename')";
          if ($con->query($sql)) {
              echo "Signature filename and request ID inserted into database.<br>";
          } else {
              echo "Error inserting signature filename and request ID into database: " . $con->error . "<br>";
          }
      } else {
          // Error saving signature
          echo "Error saving signature.<br>";
      }
        //Update the purchase request with the signature filename
        $sql_update = "UPDATE purchase_requests SET $signature_field = '$filename' WHERE id = $purchase_request_id";
        if ($con->query($sql_update)) {
            echo "Purchase request updated with signature filename.<br>";
        } else {
            echo "Error updating purchase request with signature filename: " . $con->error . "<br>";
        }
 }
}

      // Insert Items into the database
      for ($i = 0; $i < count($item_qty); $i++) {
          $sql_item = "INSERT INTO items (purchase_request_id, item_qty, item_type, item_justification, item_reason, item_date_condition) 
                       VALUES ('$purchase_request_id', '{$item_qty[$i]}', '{$item_types[$i]}', '{$item_justifications[$i]}', '{$item_reasons[$i]}', '{$item_date_conditions[$i]}')";
          
          // Execute Item query
          if ($con->query($sql_item) !== TRUE) {
              echo "Error: " . $sql_item . "<br>" . $con->error;
          }
      }

      $_SESSION['message'] = "Successfully added a new request";
      header('location: requests1.php');
      exit(0);
  } else {
    $_SESSION['message'] = "Error: " . $sql_purchase_request . "<br>" . $con->error;
      echo "Error: " . $sql_purchase_request . "<br>" . $con->error;
  } 
}
?>