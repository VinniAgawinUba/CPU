<?php
// Include database connection
include('config/dbcon.php');

// Get start date and end date from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Fetch data from the database based on the selected date range
$sql = "SELECT DATE(requested_date) AS date, COUNT(*) AS count, GROUP_CONCAT(requestor_user_name) AS user_ids
        FROM purchase_requests
        WHERE requested_date >= '$startDate' AND requested_date <= '$endDate'
        GROUP BY DATE(requested_date)
        ORDER BY DATE(requested_date)";

$result = mysqli_query($con, $sql);

$dataPoints = array();
$additionalInfo = array(); // Array to store additional column information


// Process fetched data and structure it for CanvasJS and additional info
while ($row = mysqli_fetch_assoc($result)) {
    // Convert date to UNIX timestamp and format as milliseconds for JavaScript
    $timestamp = strtotime($row['date']) * 1000;
    $count = intval($row['count']);

    // Split additional information (user IDs) into an array
    $user_ids = explode(',', $row['user_ids']); // Split the user IDs into an array

    // Push the formatted data to dataPoints array
    $dataPoints[] = array("x" => $timestamp, "y" => $count);
    
    // For each count, store additional column information (requestor_user_id)
    for ($i = 0; $i < $count; $i++) {
        $additionalInfo[] = $user_ids[$i]; // Store each user ID individually
    }
}

// Close the database connection
mysqli_close($con);

// Combine chart data and additional info into a single array
$responseData = array(
    "dataPoints" => $dataPoints,
    "additionalInfo" => $additionalInfo
);

// Send JSON response back to the client-side code
header('Content-Type: application/json');
echo json_encode($responseData);
?>
