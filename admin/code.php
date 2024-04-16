<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('authentication.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
}

//PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/vendor/autoload.php';

//Google Calendar API
use Google\Client;
use Google\Service\Calendar as Google_Service_Calendar;
use Google\Service\Calendar\Event as Google_Service_Calendar_Event;
$credentials = __DIR__ . '/vendor/credentials.json';
require_once __DIR__ . '/vendor/autoload.php';



  //Update Purchase Request
if(isset($_POST['request_update_btn_front'])){
// Purchase Request Information
$id = $_POST['request_id'];
$purchase_request_number = $_POST['purchase_request_number'];
$printed_name = $_POST['printed_name'];
$unit_dept_college = $_POST['unit_dept_college'];
$iptel_email = $_POST['iptel_email'];
$requestor_signature = $_POST['signed_Requestor'];
$acknowledged_by_cpu = $_POST['acknowledged_by_cpu'] == true ? '1' : '0'; // Set acknowledged-by-cpu to 1/true if checkbox is checked, otherwise set to 0/false
$above_50000 = $_POST['above_50000'] == true ? '1' : '0'; // Set above_50000 to 1/true if checkbox is checked, otherwise set to 0/false

//Updater User Information
$updater_user_id = $_POST['user_id'];
$updater_user_email = $_POST['user_email'];
$updater_user_name = $_POST['user_name'];

//Requestor User Information
$requestor_user_email = $_POST['requestor_email'];

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
$folderPath = "../uploads/signatures/";





// Items Information
$item_number = $_POST['item_number'];
$item_qty = $_POST['item_qty'];
$item_justifications = $_POST['item_justification'];
$item_description = $_POST['item_description'];
$item_status = $_POST['item_status'];

// unit_head_approval_by 
$unit_head_approval_by  = $_POST['unit_head_approval_by'];
$unit_head_approval = $_POST['unit_head_approval'];

// Signatures for Approvals
$vice_president_remarks = $_POST['vice_president_remarks'];
$vice_president_approved = $_POST['vice_president_approved'];
$vice_president_signature = $_POST['signed_1'];

$vice_president_administration_remarks = $_POST['vice_president_administration_remarks'];
$vice_president_administration_approved = $_POST['vice_president_administration_approved'];
$vice_president_administration_signature = $_POST['signed_2'];

$budget_controller_remarks = $_POST['budget_controller_remarks'];
$budget_controller_approved = $_POST['budget_controller_approved'];
$budget_controller_code = $_POST['budget_controller_code'];
$budget_controller_signature = $_POST['signed_3'];

$university_treasurer_remarks = $_POST['university_treasurer_remarks'];
$university_treasurer_approved = $_POST['university_treasurer_approved'];
$university_treasurer_signature = $_POST['signed_4'];

$office_of_the_president_remarks = $_POST['office_of_the_president_remarks'];
$office_of_the_president_approved = $_POST['office_of_the_president_approved'];
$office_of_the_president_signature = $_POST['signed_5'];

// Update Purchase Request into the database
$sql_purchase_request = "UPDATE purchase_requests SET purchase_request_number = '$purchase_request_number', printed_name = '$printed_name', 
signed_Requestor = '$requestor_signature', unit_dept_college = '$unit_dept_college', 
iptel_email = '$iptel_email', above_50000 = '$above_50000', 
unit_head_approval_by  = '$unit_head_approval_by ', unit_head_approval = '$unit_head_approval',
vice_president_remarks = '$vice_president_remarks', 
vice_president_approved = '$vice_president_approved', signed_1 = '$vice_president_signature', 
vice_president_administration_remarks = '$vice_president_administration_remarks', 
vice_president_administration_approved = '$vice_president_administration_approved', 
signed_2 = '$vice_president_administration_signature', budget_controller_remarks = '$budget_controller_remarks', 
budget_controller_approved = '$budget_controller_approved', budget_controller_code = '$budget_controller_code', 
signed_3 = '$budget_controller_signature', university_treasurer_remarks = '$university_treasurer_remarks', 
university_treasurer_approved = '$university_treasurer_approved', signed_4 = '$university_treasurer_signature', 
office_of_the_president_remarks = '$office_of_the_president_remarks', office_of_the_president_approved = '$office_of_the_president_approved', 
signed_5 = '$office_of_the_president_signature' WHERE id = '$id'";


// Check if acknowledged by CPU and email hasn't been sent already
if ($acknowledged_by_cpu == '1') {
    // Check if acknowledged_at field is null for this request
    $sql_check_acknowledgment = "SELECT acknowledged_at FROM purchase_requests WHERE id = '$id'";
    $result_check_acknowledgment = $con->query($sql_check_acknowledgment);

    //Update acknowledged_by_cpu to 1
    $sql_update_acknowledged_by_cpu = "UPDATE purchase_requests SET acknowledged_by_cpu = '1' WHERE id = '$id'";
    $con->query($sql_update_acknowledged_by_cpu);

    if ($result_check_acknowledgment && $result_check_acknowledgment->num_rows > 0) {
        $row = $result_check_acknowledgment->fetch_assoc();

        // Check if acknowledged_at field is null, then sends email
        if ($row['acknowledged_at'] === null) {
            // Update acknowledged_at field with current timestamp
            $current_timestamp = date("Y-m-d H:i:s");
            $sql_update_acknowledgment = "UPDATE purchase_requests SET acknowledged_at = '$current_timestamp' WHERE id = '$id'";
            if ($con->query($sql_update_acknowledgment)) {
    // Send email notification to the user_requestor_email
        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        try {
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'vinniuba1@gmail.com'; // SMTP username (your Gmail email address)TO BE REPLACED WITH WEBSITE EMAIL
            $mail->Password = 'buqn wpcc yhlx lvoz'; // SMTP password USE APP PASSWORD FOUND IN GOOGLE SETTINGS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to
    
            // Sender and recipient
            $mail->setFrom('vinniuba1@gmail.com', 'EMAIL BOT :)'); // Sender's email address and name
            // Recipient's email address (Requestor's email address)
            $mail->addAddress( ''. $requestor_user_email .'');

    
            // Define a variable to hold the status text
    $status_text = 'Your request has been acknowledged by the Central Procurement Unit.';


    // Email content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Request Status Update';

    // Constructing HTML email body
    $body = '
        <html>
        <head>
            <title>Request Status Update</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                }
                h1 {
                    color: #007bff;
                }
                p {
                    line-height: 1.6;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #ccc;
                }
                .footer p {
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Purchase Request Notification</h1>
                <h2>Purchase Request ID: ' . $purchase_request_id . '</h2>
                <p>Purchase Request Number: ' . $purchase_request_number . '</p>
                <p><strong>' . $status_text . '</strong></p>
                <div class="footer">
                    <p>This is an automated email notification. Please do not reply.</p>
                </div>
            </div>
        </body>
        </html>
    ';

    $mail->Body = $body;

    // Send email
    $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    
}
        }
    }
}


// Execute Purchase Request query
if ($con->query($sql_purchase_request) === TRUE) {

  // Get the Request ID
$purchase_request_id = $id;

// Save each signature to the server and database
foreach ($signatures as $signature_field) {
    // Check if the signature field is set
    if (isset($_POST[$signature_field]) && !empty($_POST[$signature_field])) {
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

            // Update filename and request ID into the signatures table
            $request_id = $purchase_request_id;
            $sql_insert_signature = "INSERT INTO signatures (request_id, filename) VALUES ('$request_id', '$filename')";
            if ($con->query($sql_insert_signature)) {
                // Signature filename and request ID inserted into database

                // Check if the signature field in purchase_requests table is empty
                $sql_check_empty = "SELECT $signature_field FROM purchase_requests WHERE id = $purchase_request_id";
                $result_check_empty = $con->query($sql_check_empty);
                if ($result_check_empty && $result_check_empty->num_rows > 0) {
                    $row = $result_check_empty->fetch_assoc();
                    if ($row[$signature_field] && $row[$signature_field] != '') {
                        // Update the purchase request with the signature filename
                        $sql_update_purchase_request = "UPDATE purchase_requests SET $signature_field = '$filename' WHERE id = $purchase_request_id";
                        if ($con->query($sql_update_purchase_request)) {
                            // Signature filename updated successfully

                            // Insert into _by column In the purchase_requests table
                            $sql_insert_signed_by = "UPDATE purchase_requests SET {$signature_field}_by = '$updater_user_email' WHERE id = $purchase_request_id";
                            if ($con->query($sql_insert_signed_by)) {
                                // Signed by updated successfully
                            } else {
                                $_SESSION['message'] = "Error updating signed by in purchase_requests: " . $con->error;
                                header('Location: purchase_request-view.php');
                            }
                            
                        } else {
                            $_SESSION['message'] = "Error updating purchase request with signature filename: " . $con->error;
                            header('Location: purchase_request-view.php');
                        }
                    } else {
                        // Signature field is not empty, do nothing
                    }
                } else {
                    $_SESSION['message'] = "Error checking if signature field is empty: " . $con->error;
                    header('Location: purchase_request-view.php');
                }
            } else {
                $_SESSION['message'] = "Error inserting signature filename and request ID into database: " . $con->error;
                header('Location: purchase_request-view.php');
            }
        } else {
            // Error saving signature
            $_SESSION['message'] = "Warning Some Signature fields are still empty";
            header('Location: purchase_request-view.php');
        }
    } else {
        // Signature field is empty
    }
}



// Select signature fields from the purchase_requests table (TO UPDATE sign_status)
$sql_select_sigs = "SELECT * FROM purchase_requests WHERE id = $purchase_request_id";
$result_select_sigs = $con->query($sql_select_sigs);

if ($result_select_sigs && $result_select_sigs->num_rows > 0) {
    $row = $result_select_sigs->fetch_assoc();
    
    // Check if any of the signature fields are not '' and update sign_status accordingly
    $sign_status = '0';
    //If signed 1 is not empty then it will be signed by the Vice President
    if ($row['signed_1'] && $row['signed_1'] != '') {
        $sign_status = 'Signed by Vice President ';
    }
    //If signed 1 and signed 2 is not empty then it will be signed by the Vice President for Administration
    elseif ($row['signed_2'] && $row['signed_2'] != ''){
        $sign_status = 'Signed by Vice President Administration';
    }
    //If signed 1, signed 2 and signed 3 is not empty then it will be signed by the Budget Controller
    elseif ($row['signed_1'] && $row['signed_1'] != '' && $row['signed_2'] && $row['signed_2'] != '' && $row['signed_3'] && $row['signed_3'] != '') {
        $sign_status = 'Signed by budget controller';
    }
    //If signed 1, signed 2, signed 3 and signed 4 is not empty then it will be signed by the University Treasurer
   elseif ($row['signed_1'] && $row['signed_1'] != '' && $row['signed_2'] && $row['signed_2'] != '' && $row['signed_3'] && $row['signed_3'] != '' && $row['signed_4'] && $row['signed_4'] != '') {
        $sign_status = 'Signed by university treasurer';
    }
    //If signed 1, signed 2, signed 3, signed 4 and signed 5 is not empty then it will be signed by the Office of the President
    elseif ($row['signed_1'] && $row['signed_1'] != '' && $row['signed_2'] && $row['signed_2'] != '' && $row['signed_3'] && $row['signed_3'] != '' && $row['signed_4'] && $row['signed_4'] != '' && $row['signed_5'] && $row['signed_5'] != '') {
        $sign_status = 'Signed by president';
    }
    else {
        $sign_status = 'WARNING: Sequence of signatures not followed!';
    }

    // Update the sign_status in the purchase_requests table
    $sql_update_sign_status = "UPDATE purchase_requests SET sign_status = '$sign_status' WHERE id = $purchase_request_id";
    if ($con->query($sql_update_sign_status)) {
        // sign_status updated successfully
    } else {
        $_SESSION['message'] = "Error updating sign_status in purchase_requests: " . $con->error;
        header('Location: purchase_request-view.php');
        exit; // Terminate script execution
    }
} else {
    $_SESSION['message'] = "Error selecting signature fields from purchase_requests: " . $con->error;
    header('Location: purchase_request-view.php');
    exit; // Terminate script execution
}






    //Query current Items and check if there are any new items
    $sql_current_items = "SELECT * FROM items WHERE purchase_request_id = '$purchase_request_id'";
    $result = $con->query($sql_current_items);
    $current_items = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $current_items[] = $row;
        }
    }
    //Check if there are any new items
    if (count($current_items) < count($item_qty)) {
        // Insert Items into the database
        for ($i = count($current_items); $i < count($item_qty); $i++) {
            $sql_item = "INSERT INTO items (item_number,purchase_request_id, item_qty,  item_description,item_justification) 
                         VALUES ('{$item_number[$i]}','$purchase_request_id', '{$item_qty[$i]}',  '{$item_description[$i]}','{$item_justifications[$i]}' )";
            
            // Execute Item query
            if ($con->query($sql_item) !== TRUE) {
                echo "Error: " . $sql_item . "<br>" . $con->error;
            }
        }
    } else {
        // Update Items in the database
        for ($i = 0; $i < count($item_qty); $i++) {
            $sql_item = "UPDATE items SET item_qty = '{$item_qty[$i]}', item_justification = '{$item_justifications[$i]}', item_status = '{$item_status[$i]}' WHERE id = '{$current_items[$i]['id']}'";
            
            // Execute Item query
            if ($con->query($sql_item) !== TRUE) {
                echo "Error: " . $sql_item . "<br>" . $con->error;
            }
        }
    }

   

    $_SESSION['message'] = "Successfully Updated request";
    //Insert into purchase_requests_history
    $change_made = "Request Details Updated";
    $last_modified_by = $_POST['user_name'];
    $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$purchase_request_id','$change_made', '$last_modified_by', NOW())";
    $insert_query_run = mysqli_query($con, $insert_query);
    header('location: purchase_request-view.php');
} else {
  $_SESSION['message'] = "Error: " . $sql_purchase_request . "<br>" . $con->error;
    echo "Error: " . $sql_purchase_request . "<br>" . $con->error;
}     
}

//Delete Purchase Request
if(isset($_POST['purchase_request_delete_btn'])) {
    $request_id = $_POST['id'];
    // Your SQL query to delete data from the database
    $delete_query = "DELETE FROM purchase_requests WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $delete_query);

    if($query_run) {
        $_SESSION['message'] = "Request was deleted!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Hide Purchase request
if(isset($_POST['purchase_request_mark_complete'])) {
    $request_id = $_POST['request_id'];
    $user_id = $_POST['user_id'];
    // Your SQL query to insert into user_purchase_request_completion table in the database
    $hide_query = "INSERT INTO purchase_requests (user_id, purchase_request_id, completed) VALUES ('$request_id','$user_id','1')";
    // Executing the query
    $query_run = mysqli_query($con, $hide_query);

    if($query_run) {
        $_SESSION['message'] = "Request was Hidden!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Approve Purchase Request
if(isset($_POST['request_approve_btn'])) {
    $request_id = $_POST['request_id'];
    $approval_remarks = $_POST['approval_remarks'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Approved";
    $approved = 'approved';

    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$approved', approval_remarks = '$approval_remarks' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];


    sendEmail($requestor_email, $approved, $request_id, $purchase_request_number, $approval_remarks);

    if($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id','$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was approved!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Complete Purchase Request
if(isset($_POST['request_complete_btn'])) {
    $request_id = $_POST['request_id'];
    $completion_remarks = $_POST['completion_remarks'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Completed";
    $completed = 'completed';


    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$completed', completed_remarks = '$completion_remarks' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];

    sendEmail($requestor_email, $approved, $request_id, $purchase_request_number, $completion_remarks);

    if($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id','$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was completed!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Reject Purchase Request
if(isset($_POST['request_reject_btn'])) {
    $request_id = $_POST['request_id'];
    $rejection_reason = $_POST['rejection_reason'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Rejected";
    $rejected = 'rejected';

    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$rejected', rejection_reason = '$rejection_reason' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];

    sendEmail($requestor_email, $rejected, $request_id, $purchase_request_number, $rejection_reason);

    if($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id', '$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was rejected!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}


//Add Department
if(isset($_POST['add_department']))
{
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //Insert the Department
    $query = "INSERT INTO department (name, college_id) VALUES ('$name', '$college_id')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New Department has been added";
        header('Location: department-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-add.php');
        exit(0);
    }
}

//Update Department
if(isset($_POST['update_department']))
{
    $department_id = $_POST['id'];
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //UPDATE the Department
    $query = "UPDATE department SET name = '$name', college_id = '$college_id' WHERE id = '$department_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Department has been Updated";
        header('Location: department-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-edit.php');
        exit(0);
    }
}


//Add College
if(isset($_POST['add_college']))
{
    $name = $_POST['name'];

    //Insert the College
    $query = "INSERT INTO college (name) VALUES ('$name')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New College has been added";
        header('Location: college-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-add.php');
        exit(0);
    }
}

//Update College
if(isset($_POST['update_college']))
{
    $college_id = $_POST['id'];
    $name = $_POST['name'];

    //UPDATE the College
    $query = "UPDATE college SET name = '$name' WHERE id = '$college_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "College has been Updated";
        header('Location: college-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-edit.php');
        exit(0);
    }
}



//Add Faculty
if(isset($_POST['add_faculty'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname.''.$lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' .$image_extension;


    // Insert the Post with the category_id
    $query = "INSERT INTO faculty (fname, lname, full_name, email, college_id, department_id, role, image) 
              VALUES ('$fname', '$lname', '$full_name', '$email', '$college_id', '$department_id', '$role', '$filename')";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        // Upload the image to uploads folder
        move_uploaded_file($_FILES['image']['tmp_name'],'../uploads/faculty/'.$filename);
        $_SESSION['message'] = "New Faculty has been added";
        header('Location: faculty-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-add.php');
        exit(0);
    }
}

//Update Faculty
if(isset($_POST['update_faculty'])) {
    $faculty_id = $_POST['faculty_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname.''.$lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' .$image_extension;

    $old_filename = $_POST['old_image'];

    // Update the Faculty with the new values
    $query = "UPDATE faculty SET fname = '$fname', lname = '$lname', full_name = '$full_name', email = '$email', college_id = '$college_id', department_id = '$department_id', role = '$role', image = '$filename' WHERE id = '$faculty_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run) {
        if($image != NULL) {
            if(file_exists('../uploads/faculty/' . $_POST['old_image'])) 
            {
                unlink('../uploads/faculty/' . $_POST['old_image']);
            }
            // Upload the image to uploads folder
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/faculty/' . $filename);
        }
        $_SESSION['message'] = "Faculty has been updated";
        header('Location: faculty-edit.php?id='.$faculty_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-edit.php?id='.$faculty_id);
        exit(0);
    }
}



//Delete the user
if(isset($_POST['category_delete']))
{
    $category_id = $_POST['category_delete'];

    $query = "DELETE FROM categories WHERE id = '$category_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category has been deleted";
        header('Location: category-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: category-view.php');
        exit(0);
    }
}



//Delete the user
if(isset($_POST['user_delete']))
{
    $user_id = $_POST['user_delete'];

    $query = "DELETE FROM users WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User has been deleted";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Add the user
if(isset($_POST['add_user']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status = $_POST['status'] == true ? '1' : '0';

    //Insert the user
    $query = "INSERT INTO users (fname, lname, email, password, role_as, status) VALUES ('$fname', '$lname', '$email', '$password', '$role_as', '$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New User has been added";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Update the user
if(isset($_POST['update_user']))
{
    $user_id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];

    //Update the user
    $query = "UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', password = '$password', role_as = '$role_as' WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User has been updated";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
    
}

//Add School Year
if(isset($_POST['add_school_year']))
{
    $school_year = $_POST['school_year'];

    //Insert the School Year
    $query = "INSERT INTO school_year (school_year) VALUES ('$school_year')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "New School Year has been added";
        header('Location: school_year-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-add.php');
        exit(0);
    }
}

//Update School Year
if(isset($_POST['update_school_year']))
{
    $school_year_id = $_POST['id'];
    $school_year = $_POST['school_year'];

    //UPDATE the School Year
    $query = "UPDATE school_year SET school_year = '$school_year' WHERE id = '$school_year_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "School Year has been Updated";
        header('Location: school_year-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-edit.php');
        exit(0);
    }
}

//Logout the user
if(isset($_POST['logout_btn'])){
    //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);

    if(isset($_SESSION['access_token'])){
    
    
    $accesstoken= $_SESSION['access_token'];
     
    //Reset OAuth access token
    $google_client->revokeToken($accesstoken);
    }

    session_destroy();

    $_SESSION['message'] = "Logged Out Successfully";
    header('location: ../login.php');
    exit(0);
}

//PHPMAILER Function for status email notification
function sendEmail($requestor_user_email, $status_text, $purchase_request_id, $purchase_request_number, $remarks) {
    // Send email notification to the user_requestor_email
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'vinniuba1@gmail.com'; // SMTP username (your Gmail email address)TO BE REPLACED WITH WEBSITE EMAIL
        $mail->Password = 'buqn wpcc yhlx lvoz'; // SMTP password USE APP PASSWORD FOUND IN GOOGLE SETTINGS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient
        $mail->setFrom('vinniuba1@gmail.com', 'EMAIL BOT :)'); // Sender's email address and name
        // Recipient's email address (Requestor's email address)
        $mail->addAddress( ''. $requestor_user_email .'');


        // Define a variable to hold the status text


// Email content
$mail->isHTML(true); // Set email format to HTML
$mail->Subject = 'Request Status Update';

// Constructing HTML email body
$body = '
    <html>
    <head>
        <title>Request Status Update</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
            }
            h1 {
                color: #007bff;
            }
            p {
                line-height: 1.6;
            }
            .footer {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
            }
            .footer p {
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Purchase Request Notification</h1>
            <h2>Purchase Request ID: ' . $purchase_request_id . '</h2>
            <p>Purchase Request Number: ' . $purchase_request_number . '</p>
            <p>Your request has been updated to: <strong>' . $status_text . '</strong></p>
            <p>Remarks: <strong>' . $remarks . '</strong></p>
            <div class="footer">
                <p>This is an automated email notification. Please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
';

$mail->Body = $body;

// Send email
$mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

?>