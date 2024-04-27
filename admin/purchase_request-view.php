<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
$unit_head = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
    $unit_head = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
    $unit_head = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
    $unit_head = false;
}
elseif($_SESSION['auth_role']==4)
{
    $admin = false;
    $super_user = false;
    $department_editor = false;
    $unit_head = true;
}
$current_user_email = $_SESSION['auth_user']['user_email'];
$current_user_id = $_SESSION['auth_user']['user_id'];
?>



<div class="container-fluid px-4">
        <h4 class="mt-4">Purchase Requests</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Purchase Requests</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>View Purchase Requests
                        
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
                    </div>
    

                    </div>
                    <div class="card-body" style="overflow-x: auto;">
                        <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter by Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item filter-btn" data-status="all">All</a></li>
                                    <li><a class="dropdown-item filter-btn" data-status="pending">Pending</a></li>
                                    <li><a class="dropdown-item filter-btn" data-status="approved">Approved</a></li>
                                    <li><a class="dropdown-item filter-btn" data-status="rejected">Rejected</a></li>
                                    <li><a class="dropdown-item filter-btn" data-status="partially-completed">Partially Completed</a></li>
                                    <li><a class="dropdown-item filter-btn" data-status="completed">Completed</a></li>
                                    
                                    
                                    <!-- Add more items for other statuses as needed -->
                                </ul>

                                <button class="btn btn-primary dropdown-toggle" type="button" id="filterView" data-bs-toggle="dropdown" aria-expanded="false">
                                    Show (For Signers Only)
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterView">
                                    <li><a class="dropdown-item filter-view" data-status="all">All</a></li>
                                    <li><a class="dropdown-item filter-view" data-status="not_signed">Not Signed by me</a></li>
                                    <li><a class="dropdown-item filter-view" data-status="signed_by_me">Signed by me</a></li>
                                    
                                    <!-- Add more items for other statuses as needed -->
                                </ul>

                                    <a class="btn btn-danger" href="purchase_request-view.php" style="color:white;">Clear Filters</a>
                                
                        </div>

                       

                    <div class="card-body" style="overflow-x: auto;">




                    <table id="myPurchaseRequests" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Requested Date</th>
                                <th>Purchase Request Number</th>
                                <th>Requestor Name</th>
                                <th>Unit/Dept/College</th>
                                <th>Iptel#/Email</th>
                                <th>Unit Head Approval</th>
                                <th>Acknowledged by CPU</th>
                                <th>Status</th>
                                <th>Details</th>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                                
                                <!-- If Super User or admin, see Item Details Column -->
                                <?php if ($super_user || $admin) { ?><th>Item Details</th><?php } ?>
                                <th>Print</th>
                                <th>History</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //If Super User, show all purchase requests
                            if($super_user)
                            {
                                $request = $_GET['request'] ?? "SELECT * FROM purchase_requests WHERE status != 'completed' AND status != 'rejected' AND unit_head_approval = 'approved' ORDER BY id DESC";
                                //$request = "SELECT * FROM purchase_requests ORDER BY id DESC";
                            }
                            //If Admin, show only purchase requests assigned to the logged in user
                            if ($admin)
                            {
                                // Retrieve the updated query from the URL, if not available use default query
                                $request = $_GET['request'] ?? "SELECT * FROM purchase_requests WHERE assigned_user_id = '{$_SESSION['auth_user']['user_id']}' AND status != 'completed' AND status != 'rejected' ORDER BY id DESC";
                                //$request = "SELECT * FROM purchase_requests WHERE assigned_user_id = '{$_SESSION['auth_user']['user_id']}' ORDER BY id DESC";
                            }
                            //If Department Editor, show only purchase requests  that are not completed, partially-completed, or rejected or approved
                            if ($department_editor)
                            {
                                // Retrieve the updated query from the URL, if not available use default query
                                $request = $_GET['request'] ?? "SELECT * FROM purchase_requests WHERE '$current_user_email' NOT IN (COALESCE(signed_1_by, ''), COALESCE(signed_2_by, ''), COALESCE(signed_3_by, ''), COALESCE(signed_4_by, ''), COALESCE(signed_5_by, '')) AND acknowledged_by_cpu = 1 ORDER BY id DESC";
                            }
                            //If Unit Head, show only purchase requests where unit_head_approval = pending
                            if ($unit_head)
                            {
                                // Retrieve the updated query from the URL, if not available use default query
                                $request = $_GET['request'] ?? "SELECT * FROM purchase_requests WHERE unit_head_approval = 'pending' AND unit_head = '$current_user_id' ORDER BY id DESC";
                            }
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                    
                                     
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="purchase_request_details.php?request_id=<?= $row['id']; ?>">
                                                <?= $row['id']; ?> 
                                            </a>
                                        
                                        </td>

                                        <td><?= date('F j Y h:i A', strtotime($row['requested_date'])); ?></td>
                                        
                                        <td >
                                            
                                                <?php 
                                                echo $row['purchase_request_number']
                                                ?>
                                            </td>
                                        <td><?= $row['printed_name']; ?></td>
                                        <td>
                                                <?php 
                                                echo $row['unit_dept_college']
                                                
                                                ?>
                                            
                                            </td>
                                            <td>
                                                <?php 
                                                    $recipient_email = $row['iptel_email']; // Assuming $row['iptel_email'] contains the recipient email address
                                                    echo '<a href="#" onclick="window.open(\'https://mail.google.com/mail/?view=cm&to=' . $recipient_email . '\', \'_blank\'); return false;">'.$recipient_email.'</a>';
                                                ?>
                                            </td>

                                            <td><?= $row['unit_head_approval']; ?></td>
                                            <td>
                                            <?php 
                                            echo 
                                            //If acknowledged_by_cpu = 1, echo "CPU Acknowledged", else echo "Not Acknowledged"
                                            $row['acknowledged_by_cpu'] == 1 ? "CPU Acknowledged" : "Not Acknowledged";
                                            ?>
                                            </td>
                                        </td>
                                        
                                        <td><?= $row['status']; ?></td>
                                        
                                        

                                        <td>
                                            <a href="purchase_request-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Details</a>
                                        </td>

                                       

                                        <!-- If Super User, see Assigned User Column -->
                                        <?php if ($super_user && $row['acknowledged_by_cpu']==1) { ?>
                                        <td>
                                        <select class="assigned-user" data-request-id="<?= $row['id']; ?>">
                                            <option>--Select User--</option>
                                            <?php
                                            // Fetch all admin users from the database
                                            $user_query = "SELECT * FROM users WHERE role_as = 1 ORDER BY id DESC";
                                            $user_query_run = mysqli_query($con, $user_query);
                                            if (mysqli_num_rows($user_query_run) > 0 ) {
                                                foreach ($user_query_run as $user_list) {
                                                    $selected = ($user_list['id'] == $row['assigned_user_id']) ? 'selected' : ''; // Check if user is assigned
                                                    echo '<option value="' . $user_list['id'] . '" ' . $selected . '>' . $user_list['email'] . '</option>';
                                                }
                                            } else {
                                                echo '<option>No Users Found</option>';
                                            }
                                            ?>
                                        </select>
                                        </td>
                                            <?php } 
                                            else {
                                                if ($super_user){
                                                echo '<td>' . 'PLEASE ACKNOWLEDGE REQUEST FIRST' . '</td>';
                                                }
                                                elseif (!$super_user){
                                                }
                                            }
                                            ?>
                                             <!-- Java Script to update assigned user (Redirects to javascript-update_assigned_user.php) -->
                                             <script>
                                            // Add event listener to all assigned user dropdowns
                                            document.querySelectorAll('.assigned-user').forEach(function(select) {
                                                select.addEventListener('change', function() {
                                                    // Get the selected value and request id
                                                    var newUserId = this.value;
                                                    var requestId = this.getAttribute('data-request-id');

                                                    // Send AJAX request to update assigned user
                                                    var xhr = new XMLHttpRequest();
                                                    xhr.open('POST', 'javascript-update_assigned_user.php', true);
                                                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                                    xhr.onload = function() {
                                                        if (xhr.status === 200) {
                                                            var response = JSON.parse(xhr.responseText);
                                                            if (response.success) {
                                                                // Update successful, you can update UI if needed
                                                                console.log('Assigned user updated successfully');
                                                                //reload page
                                                                location.reload();
                                                            } else {
                                                                console.error('Error updating assigned user');
                                                            }
                                                        }
                                                    };
                                                    xhr.send('id=' + requestId + '&assigned_user_id=' + newUserId);
                                                });
                                            });
                                        </script>

                                            
                                        

                                        <!-- If Super User or admin, see Item Details -->
                                        <?php if ($super_user || $admin) { ?>
                                        <td >
                                            <a href="purchase_request_item_details.php?request_id=<?= $row['id']; ?>" class="btn btn-info" style="color:white;">Item Details</a>
                                        </td>
                                        <?php } ?>

                                        <td>
                                            <!-- Print Button -->
                                            <a href="print-template.php?id=<?= $row['id']; ?>" class="btn btn-success">Print</a>
                                        </td>

                                        <td>
                                            <a href="purchase_request_history.php?request_id=<?= $row['id']; ?>" class="btn btn-secondary">History</a>
                                        </td>


                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo "No Record Found";
                            } 
                            ?>
                            
                        </tbody>
                    </table>
                    </div>
                        
                    </div>
            </div>
        </div>
        </div>
</div>

<!-- Delete Confirmation Script -->
<!-- JavaScript for Delete Button Confirmation (Buttons Should have class of deleteButton) -->
<script>
    // Select all elements with the class 'deleteButton'
    var deleteButtons = document.querySelectorAll(".deleteButton");
    
    // Iterate over each delete button and attach event listener
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (confirm("Are you sure you want to delete this Request?")) {
                // Find the closest form and submit it
                this.closest(".deleteForm").submit();
            } else {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<!-- JavaScript for Hide Button Confirmation (Buttons Should have class of hideButton) -->
<script>
    // Select all elements with the class 'deleteButton'
    var hideButtons = document.querySelectorAll(".hideButton");
    
    // Iterate over each delete button and attach event listener
    hideButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (confirm("Are you sure you want to Hide this Request?")) {
                // Find the closest form and submit it
                this.closest(".hideForm").submit();
            } else {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<!-- JavaScript for Filter Buttons -->
<script>
  // Add event listener to filter buttons
document.querySelectorAll('.filter-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var status = this.getAttribute('data-status'); // Get the selected status
        // Send AJAX request to generate query dynamically
        $.ajax({
            url: 'javascript-generate_query.php',
            type: 'POST',
            data: {status: status},
            success: function(response) {
                // Remove extra quotes from the response
                response = response.replace(/^"(.*)"$/, '$1'); // Removes quotes from both ends of the string
                // Redirect to the purchase_request-view.php page with the generated query
                window.location.href = 'purchase_request-view.php?request=' + encodeURIComponent(response); // Encode the response to ensure URL safety
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});

</script>
<script>
    // Add event listener to filter buttons
document.querySelectorAll('.filter-view').forEach(function(button) {
    button.addEventListener('click', function() {
        var status = this.getAttribute('data-status'); // Get the selected status
        var current_user_email = '<?php echo $_SESSION['auth_user']['user_email']; ?>';
        // Send AJAX request to generate query dynamically
        $.ajax({
            url: 'javascript-generate_query.php',
            type: 'POST',
            data: {status: status, current_user_email: current_user_email},
            success: function(response) {
                // Remove extra quotes from the response
                response = response.replace(/^"(.*)"$/, '$1'); // Removes quotes from both ends of the string
                // Redirect to the purchase_request-view.php page with the generated query
                window.location.href = 'purchase_request-view.php?request=' + encodeURIComponent(response); // Encode the response to ensure URL safety
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});


</script>



        
<?php
include('includes/footer.php');

?>

