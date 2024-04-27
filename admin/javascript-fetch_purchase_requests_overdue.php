<?php
// Include database connection file
include('config/dbcon.php');

// Define array to hold categorized overdue counts
$overdueCounts = array(
    '0-6 Days' => array(),
    '7-13 Days' => array(),
    '14-20 Days' => array(),
    '21+ Days' => array()
);

// Perform a query to fetch overdue purchase requests
$status_query = "SELECT * FROM purchase_requests WHERE status != 'completed' AND status != 'rejected' AND DATEDIFF(CURDATE(), requested_date) > 6"; // Change the condition for overdue to greater than 6 days
$status_result = mysqli_query($con, $status_query);

// Check if the query was successful
if ($status_result) {
    // Fetch data and categorize overdue requests
    while ($row = mysqli_fetch_assoc($status_result)) {
        $days_overdue = floor((time() - strtotime($row['requested_date'])) / (60 * 60 * 24)); // Calculate days overdue
        
        // Determine the 7-day increment category
        if ($days_overdue >= 0 && $days_overdue <= 6) {
            $increment = '0-6 Days';
        } elseif ($days_overdue >= 7 && $days_overdue <= 13) {
            $increment = '7-13 Days';
        } elseif ($days_overdue >= 14 && $days_overdue <= 20) {
            $increment = '14-20 Days';
        } else {
            $increment = '21+ Days';
        }

        // Categorize by status
        $status = $row['status'];
        if (!isset($overdueCounts[$increment][$status])) {
            $overdueCounts[$increment][$status] = 0;
        }
        $overdueCounts[$increment][$status]++;
    }

    // Close database connection
    mysqli_close($con);

    // Send the categorized counts as JSON response
    echo json_encode($overdueCounts);
} else {
    // If the query fails, return an error message
    echo json_encode(array('error' => 'Failed to fetch data from the purchase_requests table.'));
}
?>
