<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
include('authentication.php');

// Pagination settings
$results_per_page = 9;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Query to fetch requests with pagination
$query = "SELECT * FROM requests ORDER BY id DESC LIMIT $offset, $results_per_page";
$query_run = mysqli_query($con, $query);

?>


<div class="container">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php
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
                    $card_class = 'bg-red-500'; // Older than or equal to 30 days, set background to red
                } elseif ($difference >= 15) {
                    $card_class = 'bg-yellow-500'; // Older than or equal to 15 days but less than 30, set background to yellow
                } elseif ($item['status'] == 8) {
                    $card_class = 'bg-green-500'; // Status is 8 (Approved), set background to green
                }

                ?>
                <div class="max-w-sm rounded overflow-hidden shadow-lg hover:scale-105<?= $card_class ?>">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">Request: <?= $item['name']; ?></div>
                        <p class="text-gray-700 text-base mb-2">ID: <?= $item['id']; ?></p>
                        <p class="text-gray-700 text-base mb-2">Received Date: <?= $item['request_received_date']; ?></p>
                        <p class="text-gray-700 text-base mb-2">Expected Delivery Date: <?= $item['expected_delivery_date']; ?></p>
                        <p class="text-gray-700 text-base mb-2">Actual Delivery Date: <?= $item['actual_delivery_date']; ?></p>
                        <!-- You can add more project details here -->
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <!-- Pagination links -->
    <div class="flex justify-center mt-4">
        <?php
        $query = "SELECT COUNT(*) AS total FROM requests";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $total_pages = ceil($row['total'] / $results_per_page);

        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='index.php?page=$i' class='mx-2 px-4 py-2 bg-gray-300 rounded-lg'>$i</a>";
        }
        ?>
    </div>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
