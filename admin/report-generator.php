<?php 
include('config/dbcon.php');
include('authentication.php');
include('includes/header.php');
?>
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <link href="css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <script src="js/daterangepicker.min.js"></script>
    <script src="js/canvasjs.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/dataTables.min.js"></script>
    <link rel="stylesheet" href="css/dataTables.dataTables.min.css" />

    <style>
        /* Center everything */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Main wrapper */
                #mainWrapper {
            width: 900px;
            max-width: 100%;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: auto; /* Add overflow property to make the container scrollable */
        }

        #chartContainer {
            height: 370px;
            width: 800px;
            border: 3px solid #ccc;
        }

        #additionalInfo {
            margin-top: 20px;
            width: 800px;
            border: 3px solid #ccc;
            overflow: auto; /* Add overflow property to make the container scrollable */
        }


        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            margin: 10px; /* Adjust margin as needed */
        }

        .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            margin: 10px; /* Adjust margin as needed */
        }
    </style>

<div id="mainWrapper">
    <!-- Date range picker input field -->
    <input type="text" id="dateRangePicker" name="dateRangePicker" class="form-control bg-primary" style=" margin-top:20px; height:50px; font-size: large; width: 300px; text-align:center; color:white; font-size:24px; font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; overflow: auto;" />
    <!-- Chart will be rendered here -->
    <div id="chartContainer"></div>
    
    
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
                    labelAngle: -45, // Rotate labels for better fit
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
            var table = $('#additionalTable').DataTable();
            table.clear().draw(); // Clear the table content


            // Render additional information in table
            for (var i = 0; i < info.length; i++) {
                var rowData = info[i];
                var row = [
                    rowData.id,
                    rowData.purchase_request_number,
                    rowData.unit_dept_college,
                    rowData.requestor_user_email,
                    // Format the date using Moment.js
                    moment(rowData.requested_date).format('MMMM D YYYY')
                ];
                table.row.add(row).draw(); // Add the row and redraw the table
            }
        }

        // Initially fetch data for the default date range
        var startDate = moment().subtract(7, 'days').format('YYYY-MM-DD'); // Default start date (7 days ago)
        var endDate = moment().format('YYYY-MM-DD'); // Default end date (today)
        fetchData(startDate, endDate);
    });
</script>

<?php
include('includes/footer.php');
?>
