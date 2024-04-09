<?php
include('config/dbcon.php');
include('authentication.php');
include('authentication_cpu_staff_only.php');
include('includes/header.php');
include('includes/scripts.php');

// Fetch distinct statuses from the items table
$statusQuery = "SELECT DISTINCT item_status FROM items";
$statusResult = mysqli_query($con, $statusQuery);

$statusOptions = [
    'pending',
    'approved',
    'for_pricing',
    'for_pricing_officer',
    'issued_pricing_officer',
    'for_delivery_by_supplier',
    'for_pickup_at_supplier',
    'for_tagging',
    'for_delivery_to_requesting_unit',
    'completed',
    'rejected'
];


// Set default status filter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'All';

// Build the SQL query based on the selected status filter
$query = "SELECT * FROM items";
if ($statusFilter !== 'All') {
    $query .= " WHERE item_status = '$statusFilter'";
}

$result = mysqli_query($con, $query);

$deliveryRequests = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $deliveryRequests[] = $row;
    }
}
?>

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
    <!-- Page Header -->
    <div class="header">
        <div>
            <!-- <img src="../../cpu/assets/images/XULOGO.png" alt="XU-LOGO" class="logo"> -->
        </div>
        <div class="header-text">
            <h1 class="mt-4">CPU Admin Panel</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Delivery Dashboard</li>
            </ol>
        </div>
    </div>
    <!-- End of Page Header -->

    <!-- Go Back Button -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-xu-gold text-white mb-4">
            <div class="card-body">Go Back To Main Dashboard</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="index.php">Go Back</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>

    <!-- Filter Dropdown -->
    <div class="col-md-3 mb-">
        <label for="statusFilter" class="form-label">Filter by Status:</label>
        <select class="form-select" id="statusFilter" name="status" onchange="applyFilter()">
            <option value="All">All</option>
            <?php foreach ($statusOptions as $option): ?>
                <option value="<?php echo $option; ?>" <?php if ($statusFilter === $option) echo 'selected'; ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Chart: Items by Status -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-xu-gold text-white">
                <i class="fas fa-chart-area me-1"></i>
                Items by Status
            </div>
            <div class="card-body">
                <canvas id="itemsByStatusChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table: Detailed Information -->
    <div class="card mb-4">
        <div class="card-header bg-xu-gold text-white">
            <i class="fas fa-table me-1"></i>
            Detailed Information
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Purchase Request ID</th>
                            <th>Quantity</th>
                            <th>Item Description</th>
                            <th>Item Justification</th>
                            <th>Item Date Requested</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($deliveryRequests as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td><?php echo $request['purchase_request_id']; ?></td>
                                <td><?php echo $request['item_qty']; ?></td>
                                <td><?php echo $request['item_description']; ?></td>
                                <td><?php echo $request['item_justification']; ?></td>
                                <td><?php echo $request['item_date_requested']; ?></td>
                                <td><?php echo $request['item_status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>


<script>
    // Function to apply filter
    function applyFilter() {
        var selectedStatus = document.getElementById('statusFilter').value;
        window.location.href = 'delivery-dashboard.php?status=' + selectedStatus;
    }

    // Process data for Chart: Requests by Status
    var statusCounts = {};
    <?php foreach ($deliveryRequests as $request): ?>
        var status = "<?php echo $request['item_status']; ?>";
        statusCounts[status] = (statusCounts[status] || 0) + 1;
    <?php endforeach; ?>

    var statusLabels = Object.keys(statusCounts);
    var statusData = Object.values(statusCounts);

    // Draw Chart: Requests by Status
var ctx = document.getElementById('itemsByStatusChart').getContext('2d');
var itemsByStatusChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: [
                '#ffcc00',
                '#00cc00',
                '#3399ff',
                '#ff6666',
                '#9966ff',
                '#ff9900',
                '#33cccc',
                '#ff99ff',
                '#6699ff',
                '#ff6699',
                '#cccc00'
            ]
        }]
    },
    plugins: [ChartDataLabels],
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            datalabels: {
                color: 'black',
            backgroundColor: 'white',
            anchor: 'end', // Position the labels towards the end of the segment
            align: 'start', // Align the labels towards the start of the segment
            offset: 10, // Adjust the offset to create space between the labels and the segments
            clamp: true, // Prevent labels from being displayed outside the chart area
            rotation: 0, // Disable rotation to keep labels horizontal
            font: {
                size: 14 // Adjust font size for better readability
            },
            borderRadius: 4, // Add border radius to the labels
            padding: {
                top: 5, // Add padding to the top of the labels
                bottom: 5, // Add padding to the bottom of the labels
                left: 5, // Add padding to the left of the labels
                right: 5 // Add padding to the right of the labels
            },
                formatter: function(value, context) {
                    console.log(context);
                    return context.chart.data.labels[context.dataIndex] + ': ' + value;
                    
                }
            }
        }
    }
});

</script>

<!-- DataTables CSS -->
<link href="css/dataTables.dataTables.min.css" rel="stylesheet">

<!-- DataTables JavaScript -->
<script src="js/dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>


