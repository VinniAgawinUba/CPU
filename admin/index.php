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
                            <a class="small text-white stretched-link" href="report-generator.php">Generate Charts</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Success Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">Danger Card</div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="#">View Details</a>
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
                        'rgba(131, 138, 137, 0.5)', // pending Grey
                        'rgba(54, 162, 235, 0.5)', // approved Blue
                        'rgba(251, 255, 0, 0.5)',//partially-completed Yellow
                        'rgba(255, 99, 132, 0.5)',//rejected Red
                        'rgba(11, 230, 70, 0.5)',//completed Green
                        // Add more colors as needed
                    ],
                    borderColor: [
                        'rgba(131, 138, 137, 1)', // pending
                        'rgba(54, 162, 235, 1)', // approved
                        'rgba(251, 255, 0, 1)',//partially-completed Yellow
                        'rgba(255, 99, 132, 1)',//rejected Red
                        'rgba(11, 230, 70, 1)',//completed Green
                        // Add more colors as needed
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false, // Hide the default legend
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
                    'rgba(11, 230, 70, 0.5)', // completed Green
                    'rgba(255, 99, 132, 0.5)', // rejected Red
                    
                    // Add more colors as needed
                ],
                borderColor: [
                    'rgba(11, 230, 70, 1)', // completed Green
                    'rgba(255, 99, 132, 1)', // rejected Red
                    // Add more colors as needed
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Hide the default legend
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
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