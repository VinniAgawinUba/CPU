<?php
// Include database connection
include('config/dbcon.php');

// Get start date and end date from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Fetch data from the database based on the selected date range
$sql = "SELECT DATE(requested_date) AS date, COUNT(*) AS count
        FROM purchase_requests
        WHERE requested_date >= '$startDate' AND requested_date <= '$endDate'
        GROUP BY DATE(requested_date)
        ORDER BY DATE(requested_date)";

$result = mysqli_query($con, $sql);

$dataPoints = array();

// Process fetched data and structure it for CanvasJS
while ($row = mysqli_fetch_assoc($result)) {
    // Convert date to UNIX timestamp and format as milliseconds for JavaScript
    $timestamp = strtotime($row['date']) * 1000;
    // Cast the count as an integer (If its a string, chart.js will not render the data correctly)
    $count = intval($row['count']);

    // Push the formatted data to dataPoints array
    $dataPoints[] = array("x" => $timestamp, "y" => $count);
}


// Close the database connection
mysqli_close($con);

// Send JSON response back to the client-side code
echo json_encode($dataPoints);
?>
