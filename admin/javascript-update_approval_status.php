<?php
// Include database connection
include('config/dbcon.php');

$current_user_email = $_POST['current_user_email'];
$newStatus = $_POST['new_status'];
$signedField = $_POST['signed_field']; // Get the signed field identifier
$SignedFieldBy = $_POST['signed_field_by']; // Get the signed by identifier
$request_id = $_POST['request_id'];

// Update the status in the database based on the signed field
$query = "UPDATE purchase_requests SET $signedField = '$newStatus', $SignedFieldBy = '$current_user_email' WHERE id = $request_id";
$result = mysqli_query($con, $query);

// Check if query executed successfully
if ($result) {
    echo "Status updated successfully";
} else {
    echo "Error updating status: " . mysqli_error($con);
}
?>
