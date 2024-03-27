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
            <!--Chart Row -->
            <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Requests
                                    </div>
                                    <div class="card-body">
                                        <!-- Chart will be rendered here -->
                                        <div class="chart-container" style=" height:40vh; width:100vw">
                                        <canvas id="pieChart"></canvas>
                                        </div>

                                        <!-- Custom legend for percentage breakdown -->
                                        <div id="legend">
                                            <!-- Legend for percentage breakdown -->
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            
                        </div>
</div>
<script>
    $(document).ready(function () {
        // Fetch data from the purchase_requests table
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
                renderPieChart(labels, counts);
            }
        });
    });

    // Function to render the pie chart
    function renderPieChart(labels, counts) {
        var ctx = document.getElementById('pieChart').getContext('2d');
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

        // Custom legend for percentage breakdown
        var legend = document.getElementById('legend');
        labels.forEach(function (label, index) {
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