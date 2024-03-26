<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
}

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
                        <a href="purchase_request-add.php" class="btn btn-primary float-end">Add Purchase Request</a>
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
                                    <li><a class="dropdown-item filter-view" data-status="not_signed">Not Signed</a></li>
                                    <li><a class="dropdown-item filter-view" data-status="signed_by_me">Signed by me</a></li>
                                    
                                    <!-- Add more items for other statuses as needed -->
                                </ul>
                                
                        </div>

                       

                    <div class="card-body" style="overflow-x: auto;">




                    <table id="myPurchaseRequests" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Purchase Request Number</th>
                                <th>Requestor Name</th>
                                <th>Unit/Dept/College</th>
                                <th>Iptel#/Email</th>
                                <th>Acknowledged by CPU</th>
                                <th>Endorsed by</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                                <th>History</th>
                                <th>Edit</th>
                                <?php if ($super_user) { ?><th>Delete</th><?php } ?>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                                <th>Print</th>

                                <!-- If Super User or admin, see Item Details Column -->
                                <?php if ($super_user || $admin) { ?><th>Item Details</th><?php } ?>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //If Super User, show all purchase requests
                            if($super_user)
                            {
                                $request = "SELECT * FROM purchase_requests ORDER BY id DESC";
                            }
                            //If Admin, show only purchase requests assigned to the logged in user
                            if ($admin)
                            {
                                $request = "SELECT * FROM purchase_requests WHERE assigned_user_id = '{$_SESSION['auth_user']['user_id']}' ORDER BY id DESC";
                            }
                            //If Department Editor, show only purchase requests  that are not completed, partially-completed, or rejected or approved
                            if ($department_editor)
                            {
                                // Retrieve the updated query from the URL, if not available use default query
                                $request = $_GET['request'] ?? "SELECT * FROM purchase_requests WHERE status NOT IN ('completed', 'partially-completed', 'rejected', 'approved') ORDER BY id DESC";
                            }
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                     // Check if requested_date is older than 30 days from the current day
                                    $received_date = strtotime($row['requested_date']);
                                    $current_date = strtotime(date('Y-m-d'));
                                    $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                                    // Add a CSS class based on the condition
                                    $row_class = '';
                                    $Changetext_color = 'black';
                                    if ($difference >= 30 && ($row['status'] != 'approved' && ($row['status'] != 'completed'))) {
                                        $row_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                        $Changetext_color = 'white'; // Set text color to white
                                    } 
                                    elseif ($difference >= 15 && ($row['status'] != 'approved' && ($row['status'] != 'completed'))) {
                                        $row_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                        $Changetext_color = 'black'; // Set text color to dark
                                    } 
                                    elseif ($row['status'] == 'rejected'){
                                        $row_class = 'bg-danger'; // Status is Not Approved, set background to blue
                                        $Changetext_color = 'white'; // Set text color to white
                                    }
                                    elseif ($row['status'] == 'approved' || $row['status'] == 'completed') {
                                        $row_class = 'bg-success'; // Status is Approved, set background to green
                                        $Changetext_color = 'white'; // Set text color to white
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>">
                                        <td style="color:<?= $Changetext_color ?>">
                                            <a href="purchase_request_details.php?request_id=<?= $row['id']; ?>">
                                                <?= $row['id']; ?> 
                                            </a>
                                        
                                        </td>
                                        
                                        <td style="color:<?= $Changetext_color ?>">
                                            
                                                <?php 
                                                echo $row['purchase_request_number']
                                                ?>
                                            </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['printed_name']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                echo $row['unit_dept_college']
                                                
                                                ?>
                                            
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                echo $row['iptel_email']
                                                ?>
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>">
                                            <?php 
                                            echo 
                                            //If acknowledged_by_cpu = 1, echo "CPU Acknowledged", else echo "Not Acknowledged"
                                            $row['acknowledged_by_cpu'] == 1 ? "CPU Acknowledged" : "Not Acknowledged";
                                            ?>
                                            </td>
                                        </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['endorsed_by_dean']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['requested_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['status']; ?></td>
                                        
                                        <td>
                                            <a href="purchase_request_history.php?request_id=<?= $row['id']; ?>" class="btn btn-secondary">History</a>
                                        </td>

                                        <td>
                                            <a href="purchase_request-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>

                                        <!-- If Super User, see Delete Button -->
                                        <?php if ($super_user) { ?>
                                            <td>
                                                <form id="deleteForm<?= $row['id']; ?>" action="code.php" method="POST">
                                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                    <button type="submit" name="purchase_request_delete_btn" id="purchase_request_delete_btn"class="btn btn-danger deleteButton">Delete</button>
                                                </form>
                                            </td>

                                        <?php } ?>

                                        <!-- If Super User, see Assigned User Column -->
                                        <?php if ($super_user) { ?>
                                        <td>
                                        <select class="assigned-user" data-request-id="<?= $row['id']; ?>">
                                            <option>--Select User--</option>
                                            <?php
                                            // Fetch all admin users from the database
                                            $user_query = "SELECT * FROM users WHERE role_as = 1 ORDER BY id DESC";
                                            $user_query_run = mysqli_query($con, $user_query);
                                            if (mysqli_num_rows($user_query_run) > 0) {
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
                                            <?php } ?>
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

                                            
                                        <td>
                                            <!-- Print Button -->
                                            <a href="print-template.php?id=<?= $row['id']; ?>" class="btn btn-success">Print</a>
                                        </td>

                                        <!-- If Super User or admin, see Item Details -->
                                        <?php if ($super_user || $admin) { ?>
                                        <td >
                                            <a href="purchase_request_item_details.php?request_id=<?= $row['id']; ?>" class="btn btn-info" style="color:white;">Item Details</a>
                                        </td>
                                        <?php } ?>


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
        var current_user_email = '<?php echo $_SESSION['auth_user']['email']; ?>';
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

