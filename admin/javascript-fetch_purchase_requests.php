<?php
// Include database connection file
include('config/dbcon.php');

// Perform a query to fetch data from the purchase_requests table
$query = "SELECT status FROM purchase_requests";
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Fetch data and store it in an array
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Close database connection
    mysqli_close($con);

    // Send the data as JSON response
    echo json_encode($data);
} else {
    // If the query fails, return an error message
    echo json_encode(array('error' => 'Failed to fetch data from the purchase_requests table.'));
}
?>
