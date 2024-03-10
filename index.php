<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
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
    
    <div class="grid grid-cols-3 gap-10">
        <?php
        if(mysqli_num_rows($query_run) > 0)
        {
            foreach($query_run as $item)
            {
                //statuses
                $statuses = [
                    0 => "Received by CPU",
                    1 => "Left CPU office",
                    2 => "Received by Registrar",
                    3 => "Left Registrar office",
                    4 => "Received by VPadmin",
                    5 => "Left VPadmin office",
                    6 => "Received by President",
                    7 => "Left President office",
                    8 => "Approved",
                ];
                 // Fetch college name
                 $college_query = "SELECT name FROM college WHERE id = {$item['college_id']}";
                 $college_result = mysqli_query($con, $college_query);
                 $college_data = mysqli_fetch_assoc($college_result);
                 $college_name = $college_data['name'];
 
                 // Fetch department name
                 $department_query = "SELECT name FROM department WHERE id = {$item['department_id']}";
                 $department_result = mysqli_query($con, $department_query);
                 $department_data = mysqli_fetch_assoc($department_result);
                 $department_name = $department_data['name'];

                // Check if request_received_date is older than 30 days from the current day
                $received_date = strtotime($item['request_received_date']);
                $current_date = strtotime(date('Y-m-d'));
                $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                // Add a CSS class based on the condition
                $card_class = '';
                if ($difference >= 30 && $item['status'] != 8) {
                    $card_class = 'bg-red-500'; // Older than or equal to 30 days, set background to red
                    $text_color = 'text-white';
                } elseif ($difference >= 15 && $item['status'] != 8) {
                    $card_class = 'bg-yellow-500'; // Older than or equal to 15 days but less than 30, set background to yellow
                    $text_color = 'text-white';
                } elseif ($item['status'] == 8) {
                    $card_class = 'bg-green-500 text-white'; // Status is 8 (Approved), set background to green
                    $text_color = 'text-white';
                }

                ?>
                <div class="max-w-sm rounded overflow-hidden shadow-lg hover:scale-105 hover:outline-dotted hover:text-purple-800 <?= $card_class ?>">
                    <div class="px-6 py-4 <?= $text_color ?>">
                        <div class="font-bold text-xl mb-2 text-current">Request: <?= $item['name']; ?></div>
                        <p class="text-current text-base mb-2">ID: <?= $item['id']; ?></p>
                        <p class="text-current text-base mb-2">College: <?= $college_name; ?></p>
                        <p class="text-current text-base mb-2">Department: <?= $department_name; ?></p>
                        <p class="text-current text-base mb-2">Received Date: <?= $item['request_received_date']; ?></p>
                        <p class="text-current text-base mb-2">Expected Delivery Date: <?= $item['expected_delivery_date']; ?></p>
                        <p class="text-current text-base mb-2">
                         Status: <?= $statuses[$item['status']] ?? "Unknown Status"; ?>
                        </p>
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
            echo "<a href='index.php?page=$i' class='m-2 px-4 py-2 bg-blue-500 text-blue-50 rounded-full hover:bg-blue-400'>$i</a>";
        }
        ?>
    </div>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
