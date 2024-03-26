<?php
include('config/dbcon.php');

// Fetch data from the database
$sql = "SELECT DATE(requested_date) AS date, COUNT(*) AS count
        FROM purchase_requests
        GROUP BY DATE(requested_date)
        ORDER BY DATE(requested_date)";
$result = mysqli_query($con, $sql);

$dataPoints = array();

// Process fetched data and structure it for CanvasJS
while ($row = mysqli_fetch_assoc($result)) {
    // Convert date to UNIX timestamp and format as milliseconds for JavaScript
    $timestamp = strtotime($row['date']) * 1000;
    $count = $row['count'];

    // Push the formatted data to dataPoints array
    $dataPoints[] = array("x" => $timestamp, "y" => $count);
}

// Close the database connection
mysqli_close($con);
?>


<a href="index.php" class="btn btn-danger float-end">BACK</a>


<!DOCTYPE HTML>
<html>
<head>  
<script>
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light1",
        title:{
            text: "Purchase Requests Over Time"
        },
        axisX:{
            title: "Date",
            labelAngle: -50, // Rotate labels for better fit
            intervalType: "day", // Optional: specify interval type for labels
            valueFormatString: "YYYY-MM-DD", // Format for axis labels
            labelFormatter: function (e) {
                // Convert Unix timestamp to JavaScript Date object
                var date = new Date(e.value);
                // Get the month name
                var monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                var month = monthNames[date.getMonth()];
                // Get the day of the month
                var day = date.getDate();
                // Get the year
                var year = date.getFullYear();
                // Concatenate the parts to form the desired format
                return month + " " + day + " " + year;
            }
        },


        axisY:{
            title: "Number of Requests",
            includeZero: true
        },
        data: [{
            type: "column",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>

