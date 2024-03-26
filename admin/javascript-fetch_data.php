<?php
// Include database connection
include('config/dbcon.php');

// Get start date and end date from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Fetch data from the database based on the selected date range
$sql = "SELECT DATE(requested_date) AS date, COUNT(*) AS count, purchase_request_number
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
    $additionalColumn = $row['purchase_request_number']; // Change 'additional_column' to the actual column name

    // Push the formatted data to dataPoints array
    $dataPoints[] = array("x" => $timestamp, "y" => $count);
    // Store additional column information
    $additionalInfo[] = $additionalColumn;
}

// Close the database connection
mysqli_close($con);

// Combine chart data and additional info into a single array
$responseData = array(
    "dataPoints" => $dataPoints,
    "additionalInfo" => $additionalInfo
);

// Send JSON response back to the client-side code
echo json_encode($responseData);
?>
