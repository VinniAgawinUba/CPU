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
    <title>Report Generation Charts</title>
    <link href="css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/dataTables.dataTables.min.css" />
    
</head>
<body>
     
    <div id="mainWrapper">
        <!-- button for printing chart only -->
     <button id="printBtn" onclick="printChart()" class="btn bg-success" style="color:white; position: absolute; left:0; right:0; margin-left: auto; margin-right: auto; width: 100px; white-space: nowrap;">Print Chart</button>
        <!-- Date range picker input field -->
        <input type="text" id="dateRangePicker" name="dateRangePicker" class="form-control bg-primary" style="margin-top:20px; height:50px; font-size: large; width: 300px; text-align:center; color:white; font-size:24px; font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;" />
        
        <div class="chart-container" style="position: relative; height:40vh; width:80vw">
        <!-- Chart will be rendered here -->
        <canvas id="myChart"></canvas>
        </div>
        
        <!-- Additional information will be rendered here -->
        <div id="additionalInfo" style="border:3px solid #ccc" class="TableDiv">
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
    <script src="js/chart.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>
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

            // Function to update the chart with new data
            function updateChart(data) {
    //clear the canvas and create a new chart
    $('#myChart').remove(); // This is my <canvas> element
    $('.chart-container').append('<canvas id="myChart"><canvas>'); // Redraw chart in the container

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
                borderWidth: 1,
                textBackgroundColor: 'rgba(255, 90, 100, 1)', // Background color for datalabels
            }]
        },
        plugins: [ChartDataLabels], // Enable datalabels plugin
        options: {
            //Datalabel configurations
            plugins: {
                datalabels: {
                    backgroundColor: function(context) {
                        return context.dataset.textBackgroundColor;
                    },
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },

                    formatter: function (value, ctx) {
                        let label = ctx.chart.data.labels[ctx.dataIndex];
                        let dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                        let total = dataset.data.reduce((acc, data) => acc + data, 0);
                        return `${value}`;
                    }
                }
            },

                        
            // Customizing chart appearance
            responsive: true, //Resizes the chart canvas when its container does
            Animation: {
                duration: 2000,
                easing: 'easeInOutCubic'
            },

            scales: {
                x: {
                    scaleLabel: {
                        display: true,
                        labelString: 'Date Range'
                    }
                },
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

                // Check if DataTable is already initialized
                if ($.fn.dataTable.isDataTable('#additionalTable')) {
                    // If DataTable is already initialized, just redraw the table
                    table.clear().draw();
                } else {
                    // If DataTable is not initialized, initialize it
                    table = $('#additionalTable').DataTable({
                        "order": [[ 0, "desc" ]]
                    });
                }

                // Add rows to the table
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

         // Function to print chart and date range picker
         function printChart() {
            // Hide unwanted elements before printing
            $('.TableDiv').hide(); // Hide the table
            $('#printBtn').hide(); // Hide the print button
            window.print(); // Print the page
            $('.TableDiv').show(); // Show the table again after printing
            $('#printBtn').show(); // Show the print button again after printing
        }
    </script>
</body>
</html>


<?php
include('includes/footer.php');
?>
