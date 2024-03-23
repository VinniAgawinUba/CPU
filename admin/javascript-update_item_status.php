<?php
$host = "localhost:3306";
$username = "root";
$password = "";
$database = "cpu_db";

$con = mysqli_connect($host, $username, $password, $database);

if(!$con){
    header("Location: ../errors/dberror.php");
    die();
}

// Set the content type to JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['new_status'])) {
    // Sanitize and validate inputs
    $item_id = mysqli_real_escape_string($con, $_POST['id']);
    $new_status = mysqli_real_escape_string($con, $_POST['new_status']);

    // Update item status in the database
    $update_query = "UPDATE items SET item_status = '$new_status' WHERE id = '$item_id'";
    $result = mysqli_query($con, $update_query);

    if ($result) {
        // If the update was successful, send a JSON response
        echo json_encode(['success' => true]);
    } else {
        // If the update failed, send an error JSON response
        echo json_encode(['success' => false, 'error' => 'Failed to update item status']);
    }
} else {
    // If the request method is not POST or required parameters are missing
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
