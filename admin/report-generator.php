<?php 
include('config/dbcon.php');
include('authentication.php');
include('includes/header.php');
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart Example</title>
    <link href="css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/dataTables.dataTables.min.css" />
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div id="mainWrapper">
        <!-- Date range picker input field -->
        <input type="text" id="dateRangePicker" name="dateRangePicker" class="form-control bg-primary" style="margin-top:20px; height:50px; font-size: large; width: 300px; text-align:center; color:white; font-size:24px; font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;" />
        <div>
        <!-- Chart will be rendered here -->
        <canvas id="myChart"></canvas>
        </div>
        <!-- Additional information will be rendered here -->
        <div id="additionalInfo" style="border:3px solid #ccc">
            <table id="additionalTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Purchase Request Number</th>
                        <th>Unit/Dept/College</th>
                        <th>Requestor User Email</th>
                        <th>Requested Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Additional information will be rendered here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize date range picker
            $('#dateRangePicker').daterangepicker({
                opens: 'right', // Set the calendar to open on the right
                startDate: moment().subtract(7, 'days'),
                endDate: moment(),
                ranges: {
                    'Last 7 Days': [moment().subtract(7, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                // Fetch data for the selected date range
                fetchData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // Function to fetch data for the selected date range
            function fetchData(startDate, endDate) {
                $.ajax({
                    url: 'javascript-fetch_data.php', // PHP script to fetch data from database
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        var chartData = response.dataPoints; // Chart data
                        var additionalInfo = response.additionalInfo; // Additional information

                        // Update chart with fetched data
                        updateChart(chartData);

                        // Render additional information
                        renderAdditionalInfo(additionalInfo);
                    }
                });
            }

            function updateChart(data) {
    //clear the canvas and create a new chart
    $('#myChart').remove();
    $('#mainWrapper').append('<canvas id="myChart"></canvas>');

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(function(point) {
                return new Date(point.x).toLocaleDateString(); // Convert Unix timestamp to Date object and format it
            }),
            datasets: [{
                label: 'Number of Requests',
                data: data.map(function(point) {
                    return point.y;
                }),
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: [{
                    type: 'time',
                    time: {
                        unit: 'day',
                        displayFormats: {
                            day: 'MMM D YYYY' // Format for day labels
                        }
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Date Range'
                    }
                }],
                y: {
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Requests'
                    },
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        }
    });
}


            // Function to render additional information
            function renderAdditionalInfo(info) {
                var table = $('#additionalTable').DataTable();
                table.clear().draw(); // Clear the table content

                for (var i = 0; i < info.length; i++) {
                    var rowData = info[i];
                    var row = [
                        rowData.id,
                        rowData.purchase_request_number,
                        rowData.unit_dept_college,
                        rowData.requestor_user_email,
                        moment(rowData.requested_date).format('MMMM D YYYY')
                    ];
                    table.row.add(row).draw();
                }
            }

            // Initially fetch data for the default date range
            var startDate = moment().subtract(7, 'days').format('YYYY-MM-DD'); // Default start date (7 days ago)
            var endDate = moment().format('YYYY-MM-DD'); // Default end date (today)
            fetchData(startDate, endDate);
        });
    </script>
</body>
</html>


<?php
include('includes/footer.php');
?>
