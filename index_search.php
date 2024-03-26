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


// Columns to search
$search_columns = array('id', 'unit_dept_college', 'iptel_email', 'status', 'requested_date');

// Modify SQL query to include search condition
$query_condition = "";
if (!empty($search_query)) {
    $search_conditions = array();
    foreach ($search_columns as $column) {
        if ($column == 'requested_date') {
            // Use DATE_FORMAT to format the date from the database
            $search_conditions[] = "DATE_FORMAT($column, '%M %e %Y') LIKE '%$search_query%'";
        } else {
            $search_conditions[] = "$column LIKE '%$search_query%'";
        }
    }
    $query_condition = "WHERE " . implode(" OR ", $search_conditions);
}

// Query to fetch requests with pagination and search
$query = "SELECT * FROM purchase_requests $query_condition ORDER BY id DESC LIMIT $offset, $results_per_page";
$query_run = mysqli_query($con, $query);


?>

<div class="container">
    <!-- Request Cards -->
    <div class="grid grid-cols-3 gap-10">
        <?php
        if(mysqli_num_rows($query_run) > 0) {
            foreach($query_run as $item) {
                // Check if request_received_date is older than 30 days from the current day
                $received_date = strtotime($item['requested_date']);
                $current_date = strtotime(date('Y-m-d'));
                $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                // Add a CSS class based on the condition
                $card_class = '';
                $text_color = 'black';
                if ($difference >= 30 && ($item['status'] != 'approved' && ($item['status'] != 'completed'))) {
                    $card_class = 'bg-red-500'; // Older than or equal to 30 days, set background to red
                    $text_color = 'text-white';
                } 
                elseif ($difference >= 15 && ($item['status'] != 'approved' && ($item['status'] != 'completed'))) {
                    $card_class = 'bg-yellow-500'; // Older than or equal to 15 days but less than 30, set background to yellow
                    $text_color = 'text-white';
                } 
                elseif ($item['status'] == 'rejected'){
                    $card_class = 'bg-red-500'; // Older than or equal to 30 days, set background to red
                    $text_color = 'text-white';
                }
                elseif ($item['status'] == 'approved' || $item['status'] == 'completed') {
                    $card_class = 'bg-green-500'; // Status is (Approved), set background to green
                    $text_color = 'text-white';
                }

                ?>
                <div class="max-w-sm rounded overflow-hidden shadow-lg hover:scale-105 hover:outline-dotted hover:text-blue-600 <?= $card_class?>">
                    <div class="px-6 py-4 <?=$text_color?>">
                        <div class="font-bold text-xl mb-2">ID: <?= $item['id']; ?></div>
                        <p class="text-current text-base mb-2">Unit/Dept: <?= $item['unit_dept_college'];; ?></p>
                        <p class="text-current text-base mb-2">iptel#/email: <?= $item['iptel_email']; ?></p>
                        <p class="text-current text-base mb-2">Acknowledged by CPU? <input type = "checkbox" name = "acknowledged_by_cpu" <?= $item['acknowledged_by_cpu'] =='1' ? 'checked': '' ; ?> width = "70px" height = "70px"></p>
                        <p class="text-current text-base mb-2">Requested Date: <?= date('F j Y h:i A', strtotime($item['requested_date'])); ?></p>
                        <p class="text-current text-base mb-2">
                        Status: <?= $item['status']; ?>
                        </p>
                        <!-- You can add more request details here -->
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

    $query = "SELECT COUNT(*) AS total FROM purchase_requests $query_condition";
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


