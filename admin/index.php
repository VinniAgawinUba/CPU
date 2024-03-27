<?php
include('config/dbcon.php');
include('authentication.php');
include('includes/header.php');
?>
    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>



<div class="container-fluid px-4">
            <h1 class="mt-4">CPU Admin Panel</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Purchase Request</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="purchase_request-view.php">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Report Generation Charts</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="report-total.php">Total # of Requests</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Report Generation Charts</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="report-status.php">Requests statuses</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
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
                                    <div class="card-header">
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
                                    <div class="card-header">
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
            plugins: [ChartDataLabels],
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
        return label === '0' ? 'Acknowledged by CPU' : 'Not Acknowledged by CPU';
    });

    var ctx = document.getElementById('pieChart2').getContext('2d');
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labelsModified,
            datasets: [{
                data: counts,
                backgroundColor: [
                    'rgba(11, 230, 70, 1)', // Acknowledged Green
                    'rgba(255, 0, 0, 1)', // Not Acknowledged Red
                    // Add more colors as needed
                ],
                borderColor: [
                    'rgba(11, 230, 70, 1)', // Acknowledged Green
                    'rgba(255, 0, 0, 1)', // Not Acknowledged Red
                    // Add more colors as needed
                ],
                textbackgroundcolor:[
                        //Black
                        'rgba(0, 0, 0, 1)',
                    ],

                borderWidth: 1
            }]
        },
        plugins: [ChartDataLabels],
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
}


    
</script>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>