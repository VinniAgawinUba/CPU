<?php 
include('config/dbcon.php');
include('authentication.php');
include('includes/header.php');
?>
<!DOCTYPE HTML>
<html>
<head>
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <link href="css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <script src="js/daterangepicker.min.js"></script>
    <script src="js/canvasjs.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <style>
        #chartContainer {
            height: 370px;
            width: 100%;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <!-- Date range picker input field -->
    <input type="text" id="dateRangePicker" name="dateRangePicker"/>
    <br><br>
    <!-- Chart will be rendered here -->
    <div id="chartContainer"></div>
    

    <!-- Additional information will be rendered here -->
    <div id="additionalInfo">
        <table id="additionalTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Purchase Request Number</th>
                    <th>Unit/Dept/College</th>
                    <th>Requestor User Email</th>
                </tr>
            </thead>
            <tbody>
                <!-- Additional information will be rendered here -->
            </tbody>
        </table>
    </div>


    <script>
        $(document).ready(function () {
            // Initialize date range picker
            $('#dateRangePicker').daterangepicker({
                opens: 'right' // Set the calendar to open on the right
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


            // Function to update chart with fetched data
            function updateChart(data) {
                var chart = new CanvasJS.Chart("chartContainer", {
                    animationEnabled: true,
                    exportEnabled: true,
                    theme: "light1",
                    title: {
                        text: "Purchase Requests Over Time"
                    },
                    axisX: {
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
                    axisY: {
                        title: "Number of Requests",
                        includeZero: true
                    },
                    data: [{
                        type: "column",
                        dataPoints: data // Use fetched data
                    }]
                });
                chart.render();
            }

            function renderAdditionalInfo(info) {
                // Clear previous table data
                $("#additionalTable tbody").empty();

                // Render additional information in table
                for (var i = 0; i < info.length; i++) {
                    var rowData = info[i];
                    var row = "<tr>";
                    row += "<td>" + rowData.id + "</td>";
                    row += "<td>" + rowData.purchase_request_number + "</td>";
                    row += "<td>" + rowData.unit_dept_college + "</td>";
                    row += "<td>" + rowData.requestor_user_email + "</td>";
                    row += "</tr>";
                    $("#additionalTable tbody").append(row);
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
