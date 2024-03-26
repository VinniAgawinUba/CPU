<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <style>
        #chartContainer {
            height: 370px;
            width: 100%;
        }
    </style>
</head>
<body>
    <input type="text" id="dateRangePicker" name="dateRangePicker" />

    <div id="chartContainer"></div>

    <div id="additionalInfo"></div>


    <script>
        $(document).ready(function () {
            // Initialize date range picker
            $('#dateRangePicker').daterangepicker({
                opens: 'left' // Set the calendar to open on the left
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
                // Example: Render additional information in a div with id "additionalInfo"
                var additionalInfoDiv = document.getElementById("additionalInfo");
                additionalInfoDiv.innerHTML = ""; // Clear previous content

                for (var i = 0; i < info.length; i++) {
                    var p = document.createElement("p");
                    p.textContent = info[i];
                    additionalInfoDiv.appendChild(p);
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
