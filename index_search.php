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

// Retrieve search query
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Modify SQL query to include search condition
$query_condition = "";
if (!empty($search_query)) {
    $query_condition = "WHERE name LIKE '%$search_query%'";
}

// Query to fetch requests with pagination and search
$query = "SELECT * FROM requests $query_condition ORDER BY id DESC LIMIT $offset, $results_per_page";
$query_run = mysqli_query($con, $query);

?>

<div class="container">
    <!-- Request Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-auto">
        <?php
        if(mysqli_num_rows($query_run) > 0) {
            foreach($query_run as $item) {
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
                if ($difference >= 30) {
                    $card_class = 'bg-red-500'; // Older than or equal to 30 days, set background to red
                } elseif ($difference >= 15) {
                    $card_class = 'bg-yellow-500'; // Older than or equal to 15 days but less than 30, set background to yellow
                } elseif ($item['status'] == 8) {
                    $card_class = 'bg-green-500'; // Status is 8 (Approved), set background to green
                }

                ?>
                <div class="max-w-sm rounded overflow-hidden shadow-lg hover:scale-105 hover:outline-dotted hover:text-purple-800<?= $card_class ?>">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">Request: <?= $item['name']; ?></div>
                        <p class="text-gray-700 text-base mb-2">ID: <?= $item['id']; ?></p>
                        <p class="text-gray-700 text-base mb-2">College: <?= $college_name; ?></p>
                        <p class="text-gray-700 text-base mb-2">Department: <?= $department_name; ?></p>
                        <p class="text-gray-700 text-base mb-2">Received Date: <?= $item['request_received_date']; ?></p>
                        <p class="text-gray-700 text-base mb-2">Expected Delivery Date: <?= $item['expected_delivery_date']; ?></p>
                        <p class="text-gray-700 text-base mb-2">Actual Delivery Date: <?= $item['actual_delivery_date']; ?></p>
                        <!-- You can add more project details here -->
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No results found.</p>";
        }
        ?>
    </div>
    <!-- Pagination links -->
<div class="flex justify-center mt-4">
    <?php
    // Include search query parameter in pagination links
    $pagination_url = "index_search.php";
    if (!empty($search_query)) {
        $pagination_url .= "?search_query=$search_query&page=";
    } else {
        $pagination_url .= "?page=";
    }

    $query = "SELECT COUNT(*) AS total FROM requests $query_condition";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $total_pages = ceil($row['total'] / $results_per_page);

    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='$pagination_url$i' class='m-2 px-4 py-2 bg-blue-500 text-blue-50 rounded-full hover:bg-blue-400'>$i</a>";
    }
    ?>
</div>

</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>


