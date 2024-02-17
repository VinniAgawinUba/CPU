<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
include('authentication.php');

?>
<link rel="stylesheet" href="assets/css/custom.css">

<div class="container-fluid custombg-image-row ">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 fixed-left" style="width:350px">
            <?php include('includes/sidebar.php'); ?>
        </div>

        <!-- Main Body -->
        <div class="col-md-9">
            <div class="">
                <div class="row ">
                    <div class="col-md-12">
                        <?php include('message.php'); ?>
                        <div class="card-header">
                            <h4 class="card-title text-center customHome">Requests In Progress</h4>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $query = "SELECT * FROM requests";
                        $query_run = mysqli_query($con, $query);
                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $item)
                            {
                                // Check if request_received_date is older than 30 days from the current day
                                    $received_date = strtotime($item['request_received_date']);
                                    $current_date = strtotime(date('Y-m-d'));
                                    $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                                    // Add a CSS class based on the condition
                                    $card_class = '';
                                    if ($difference >= 30) {
                                        $card_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                    } elseif ($difference >= 15) {
                                        $card_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                    } elseif ($item['status'] == 8) {
                                        $card_class = 'bg-success'; // Status is 8 (Approved), set background to green
                                    }

                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 <?= $card_class ?>">
                                    <div class="card-body">
                                        <h5 class="card-title">Request:<?= $item['name']; ?></h5>
                                        <p class="card-text"><?= $item['id']; ?></p>
                                        <p class="card-text"><?= $item['request_received_date']; ?></p>
                                        <p class="card-text"><?= $item['expected_delivery_date']; ?></p>
                                        <p class="card-text"><?= $item['actual_delivery_date']; ?></p>
                                        <!-- You can add more project details here -->
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
