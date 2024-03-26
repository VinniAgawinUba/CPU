<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('authentication.php');

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


// Query to fetch requests with pagination
$query = "SELECT * FROM purchase_requests ORDER BY id DESC";
$query_run = mysqli_query($con, $query);

?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

    <script type="text/javascript" language="javascript" class="init">
        jQuery(document).ready(function($) {
            $('#myTables').DataTable({
                "order": [[ 0, "desc" ]]
            });
});

    </script>

<!-- Container for Table -->
<div class="container mx-auto px-4 sm:px-8 shadow">
    <div class="py-8">
        <div>
            <h2 class="text-2xl font-semibold leading-tight">My Requests</h2>
        </div>
        <div class="my-2 flex sm:flex-row flex-col">
        <table id="myTables" class="table table-bordered table-striped">
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
                                <th>Details</th>
                                
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
                                $request = "SELECT * FROM purchase_requests WHERE status NOT IN ('completed', 'partially-completed', 'rejected', 'approved') ORDER BY id DESC";
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
                                        $row_class = 'red'; // Older than or equal to 30 days, set background to red
                                        $Changetext_color = 'red'; // Set text color to white
                                    } 
                                    elseif ($difference >= 15 && ($row['status'] != 'approved' && ($row['status'] != 'completed'))) {
                                        $row_class = 'yellow'; // Older than or equal to 15 days but less than 30, set background to yellow
                                        $Changetext_color = 'yellow'; // Set text color to dark
                                    } 
                                    elseif ($row['status'] == 'rejected'){
                                        $row_class = 'red'; // Status is Not Approved, set background to blue
                                        $Changetext_color = 'red'; // Set text color to white
                                    }
                                    elseif ($row['status'] == 'approved' || $row['status'] == 'completed') {
                                        $row_class = 'green'; // Status is Approved, set background to green
                                        $Changetext_color = 'green'; // Set text color to white
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>" style="color:bg-primary;">
                                        <td style="background-color:<?= $row_class ?>;">
                                                <?= $row['id']; ?> 
                                        
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
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['endorsed_by_dean']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['requested_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['status']; ?></td>
                                        <td>
                                        <a href="my_purchase_request_details.php?request_id=<?= $row['id']; ?>" class="btn btn-info">Details</a>
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
        <br>
        <br>
        <br>
    </div>
</div>


<!-- Footer -->
<?php include('includes/footer.php'); ?>
