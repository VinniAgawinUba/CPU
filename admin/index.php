<?php
include('config/dbcon.php');
include('authentication.php');
include('authentication_cpu_staff_only.php');
include('includes/header.php');
include('includes/scripts.php');
?>
   <!--Tailwind Test-->
   <!-- <link rel="stylesheet" href="../../cpu/tailwind.css"> -->

   <!-- Important javascripts for Dashboard -->
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>

    <!-- CSS for XU LOGO AND INCASE TAILWIND IS DOWN -->
    <style>
    .header {
    display: flex;
    flex-direction: column; /* Align items vertically */
    align-items: left; /* Center items horizontally */
    }

    .logo-container {
        margin-bottom: 30px; /* Adjust margin as needed */
        border: 5px solid #283971; /* Adjust border color as needed */
    }

    .logo {
        height: 110px;
        width: 200px;
    }

    /* Optional: Style the header text */
    .header-text {
        text-align: left;
        font-size: 30px;
        margin-top: -40px;
    }

    /*Based on Tailwind Custom Classes*/
    .bg-xu-darkblue {
    --tw-bg-opacity: 1;
    background-color: rgb(40 57 113 / var(--tw-bg-opacity));
    }

    .bg-xu-gold {
        --tw-bg-opacity: 1;
        background-color: rgb(161 145 88 / var(--tw-bg-opacity));
    }

    </style>



<div class="container-fluid px-4">
        <!-- XU LOGO -->
        <div class="header">
            <div>
                <img src="../../cpu/assets/images/XULOGO.png" alt="XU-LOGO" class="logo">
            </div>
            <div class="header-text">
        <!-- Page Header -->
                <h1 class="mt-4">CPU Admin Panel</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- End of Page Header -->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-xu-darkblue text-white mb-4">
                        <div class="card-body">Purchase Request</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="purchase_request-view.php">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-xu-darkblue text-white mb-4">
                        <div class="card-body">Report Generation Charts</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="report-total.php">Total # of Requests</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-xu-darkblue text-white mb-4">
                        <div class="card-body">Report Generation Charts</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="report-status.php">Requests statuses</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-xu-darkblue text-white mb-4">
                        <div class="card-body">Report Generation Charts</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="report-items.php">Items Chart</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Charts Row -->
            <div class="row">
                <!-- Chart 1 -->
                <div class="col-md-6">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header bg-xu-gold text-white">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Requests by Status
                                    </div>

                                    <div class="card-body">
                                        <!-- Chart will be rendered here -->
                                        <div class="chart-container1" style=" height:40vh; width:100vw">
                                        <canvas id="pieChart1"></canvas>
                                        </div>

                                        <!-- Custom legend for percentage breakdown -->
                                        <div id="legend1">
                                            <!-- Legend for percentage breakdown -->
                                        </div>
                                    
                                    </div>

                                </div>
                            </div>
                </div>
                <!-- Chart 2 -->
                <div class="col-md-6">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header bg-xu-gold text-white">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Requests by Acknowledgement
                                    </div>

                                    <div class="card-body">
                                        <!-- Chart will be rendered here -->
                                        <div class="chart-container2" style=" height:40vh; width:100vw">
                                        <canvas id="pieChart2"></canvas>
                                        </div>

                                        <!-- Custom legend for percentage breakdown -->
                                        <div id="legend2">
                                            <!-- Legend for percentage breakdown -->
                                        </div>
                                    
                                    </div>

                                </div>
                            </div>
                </div>

                <!-- Chart 3 -->
                <div class="col-md-6">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header bg-xu-gold text-white">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Stale/OverDue Requests
                                    </div>

                                    <div class="card-body">
                                        <!-- Chart will be rendered here -->
                                        <div class="chart-container3" style=" height:40vh; width:100vw">
                                        <canvas id="barChart3"></canvas>
                                        </div>

                                        <!-- Custom legend for percentage breakdown -->
                                        <div id="legend3">
                                            <!-- Legend for percentage breakdown -->
                                        </div>
                                    
                                    </div>

                                </div>
                            </div>
                </div>
                            
            </div>
</div>
<script>
    $(document).ready(function () {
        // Fetch data from the purchase_requests table for pie chart 1
        $.ajax({
            url: 'javascript-fetch_purchase_requests.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Group data by status and count requests for each status
                var statusCounts = {};
                data.forEach(function (request) {
                    var status = request.status;
                    if (!statusCounts[status]) {
                        statusCounts[status] = 1;
                    } else {
                        statusCounts[status]++;
                    }
                });

                // Prepare data for the pie chart
                var labels = Object.keys(statusCounts);
                var counts = Object.values(statusCounts);

                // Render pie chart
                renderPieChart1(labels, counts);
            }
        });
    });

    $(document).ready(function () {
        // Fetch data from the purchase_requests table for pie chart 2
        $.ajax({
            url: 'javascript-fetch_pr_acknowledged.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Group data by acknowledged_by_cpu status and count requests for each status
                var acknowledgedCounts = {};
                data.forEach(function (request) {
                    var acknowledged_by_cpu = request.acknowledged_by_cpu;
                    if (!acknowledgedCounts[acknowledged_by_cpu]) {
                        acknowledgedCounts[acknowledged_by_cpu] = 1;
                    } else {
                        acknowledgedCounts[acknowledged_by_cpu]++;
                    }
                });

                // Prepare data for the pie chart
                var labels = Object.keys(acknowledgedCounts);
                var counts = Object.values(acknowledgedCounts);

                // Render pie chart
                renderPieChart2(labels, counts);
            }
        });
    });

    

    // Function to render the pie chart 1
    function renderPieChart1(labels, counts) {
        var ctx = document.getElementById('pieChart1').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        'rgba(90, 90, 90, 1)', // pending Grey
                        'rgba(54, 162, 235, 1)', // approved Blue
                        'rgba(251, 255, 0, 1)',//partially-completed Yellow
                        'rgba(255, 0, 0, 1)',//rejected Red
                        'rgba(11, 230, 70, 1)',//completed Green
                        // Add more colors as needed
                    ],
                    borderColor: [
                        'rgba(90, 90, 90, 1)', // pending Grey
                        'rgba(54, 162, 235, 1)', // approved Blue
                        'rgba(251, 255, 0, 1)',//partially-completed Yellow
                        'rgba(255, 0, 0, 1)',//rejected Red
                        'rgba(11, 230, 70, 1)',//completed Green
                        // Add more colors as needed
                    ],

                    textbackgroundcolor:[
                        //Black
                        'rgba(255, 255, 255, 1)',
                        'rgba(255, 255, 255, 1)',
                        'rgba(0, 0, 0, 0.5)',
                        'rgba(255, 255, 255, 1)',
                        'rgba(255, 255, 255, 1)',
                        
                    ],

                    borderWidth: 1
                }]
            },
            plugins: [{
                    afterDatasetsDraw: function(chart) {
                        var ctx = chart.ctx;
                        ctx.save();
                        ctx.fillStyle = 'black';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 12px Arial';
                        var total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        ctx.fillText('Total: ' + total, chart.width -50 , chart.height - 10);
                        ctx.restore();
                        }
                    }, 
                    ChartDataLabels
                    ],
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false, // Hide the default legend
                    },
                    datalabels: {
                    color: function(context) {
                        return context.dataset.backgroundColor;
                    },
                    //Background color of the labels (Black)
                    backgroundColor: function(context) {
                        return context.dataset.textbackgroundcolor;
                    },
                    //borderradius
                    borderRadius: 10,
                    formatter: (value, ctx) => {
                        let label = ctx.chart.data.labels[ctx.dataIndex];
                        let dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                        let total = dataset.data.reduce((acc, data) => acc + data, 0);
                        let percentage = ((value / total) * 100).toFixed(1) + '%';
                        return `${label}: ${value} (${percentage})`;
                    }

                }
                    
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.labels[tooltipItem.index] || '';
                            var value = data.datasets[0].data[tooltipItem.index];
                            var total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        });

        

        

        // Custom legend1 for percentage breakdown
        var legend = document.getElementById('legend1');
        labels.forEach(function (label, index) {
            var div = document.createElement('div');
            div.innerHTML = `<span style="background-color:${pieChart.data.datasets[0].backgroundColor[index]}">&nbsp;&nbsp;</span> ${label}`;
            legend.appendChild(div);
        });
    }


    // Function to render the pie chart 2
function renderPieChart2(labels, counts) {
     // Modify data labels to use "Acknowledged" or "Not Acknowledged"
     var labelsModified = labels.map(function(label) {
        return label === '0' ? 'Not Acknowledged by CPU' : 'Acknowledged by CPU';
    });

    var ctx = document.getElementById('pieChart2').getContext('2d');
    
    // Count acknowledged and not acknowledged items
    var acknowledgedCount = counts[labels.indexOf('1')]; // Assuming '1' represents acknowledged
    var notAcknowledgedCount = counts[labels.indexOf('0')]; // Assuming '0' represents not acknowledged

    
    // Determine background colors dynamically
    var backgroundColors = [];
    var borderColor = [];
    //if all are acknowledged, green
    if (notAcknowledgedCount === 0 || notAcknowledgedCount === undefined) {
        backgroundColors.push('#A19158');
        borderColor.push('#A19158');
    //if all are not acknowledged, xu-blue
    } else if (acknowledgedCount === 0 || acknowledgedCount === undefined) {
        backgroundColors.push('#283971');
        borderColor.push('#283971');
    //if there are both acknowledged and not acknowledged, green and xu-blue
    } else {
        backgroundColors.push('#283971', '#A19158');
        borderColor.push('#283971', '#A19158');
    }
    
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labelsModified,
            datasets: [{
                data: counts,
                backgroundColor: backgroundColors,
                borderColor: borderColor,
                borderWidth: 1,
                textbackgroundcolor: 'white',
            }]
        },
        plugins: [{
                    afterDatasetsDraw: function(chart) {
                        var ctx = chart.ctx;
                        ctx.save();
                        ctx.fillStyle = 'black';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.font = 'bold 12px Arial';
                        var total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        ctx.fillText('Total: ' + total, chart.width -50 , chart.height - 10);
                        ctx.restore();
                        }
                    }, 
                    ChartDataLabels
                    ],
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Hide the default legend
                },
                datalabels: {
                    color: function(context) {
                        return context.dataset.backgroundColor;
                    },
                    //Background color of the labels (Black)
                    backgroundColor: function(context) {
                        return context.dataset.textbackgroundcolor;
                    },
                    //borderradius
                    borderRadius: 10,
                    formatter: (value, ctx) => {
                        let label = ctx.chart.data.labels[ctx.dataIndex];
                        let dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                        let total = dataset.data.reduce((acc, data) => acc + data, 0);
                        let percentage = ((value / total) * 100).toFixed(1) + '%';
                        return `${label}: ${value} (${percentage})`;
                    }
                }
            }
        }
    });

    // Custom legend2 for percentage breakdown
    var legend = document.getElementById('legend2');
    labelsModified.forEach(function(label, index) {
        var div = document.createElement('div');
        div.innerHTML = `<span style="background-color:${pieChart.data.datasets[0].backgroundColor[index]}">&nbsp;&nbsp;</span> ${label}`;
        legend.appendChild(div);
    });

    $(document).ready(function () {
    // Fetch data from the purchase_requests table for bar chart 3
    $.ajax({
        url: 'javascript-fetch_purchase_requests_overdue.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Prepare data for the bar chart
            var labels = Object.keys(data);
            var statuses = getStatuses(data);
            var datasets = prepareDatasets(data, statuses);

            // Render Bar chart
            renderBarChart(labels, datasets);
        }
    });
});

// Function to get all unique statuses
function getStatuses(data) {
    var statuses = [];
    for (var increment in data) {
        for (var status in data[increment]) {
            if (!statuses.includes(status)) {
                statuses.push(status);
            }
        }
    }
    return statuses;
}

// Function to prepare datasets for barChart3
function prepareDatasets(data, statuses) {
    var statusColors = {
    "pending": "#A4A4A4",
    "partially-completed": "#a53a73",
    "approved": "#3a57a5",
    // Add more statuses and their colors as needed
};
    var datasets = [];
    for (var i = 0; i < statuses.length; i++) {
        var status = statuses[i];
        var dataset = {
            label: status,
            data: [],
            backgroundColor: statusColors[status]
        };
        for (var increment in data) {
            var count = data[increment][status] || 0;
            dataset.data.push(count);
        }
        datasets.push(dataset);
    }
    return datasets;
}

// Function to render the Bar chart 3
function renderBarChart(labels, datasets) {
    var ctx = document.getElementById('barChart3').getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            scales: {
                xAxes: [{
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: '3-Day Increments'
                    }
                }],
                yAxes: [{
                    stacked: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Number of Requests'
                    },
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
}
// Function to generate random color
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}




}


    
</script>

<?php
include('includes/footer.php');
?>