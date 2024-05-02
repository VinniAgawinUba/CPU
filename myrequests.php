<?php
ob_start();
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
ob_end_flush();
?>
<link rel="stylesheet" href="assets/css/dataTables.dataTables.min.css" />
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/dataTables.js"></script>

    <script type="text/javascript" language="javascript" class="init">
        jQuery(document).ready(function($) {
            $('#myTables').DataTable({
                "order": [[ 0, "desc" ]]
            });
});

    </script>

<!--Horizontal Rule Line-->
<hr style="width:80%; margin:auto; color:#00478a; background:#429ef5; height: 3px;">

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
                                <th>Requested Date</th>
                                <th>Purchase Request Number</th>
                                <th>Requestor Name</th>
                                <th>Unit/Dept/College</th>
                                <th>Iptel#/Email</th>
                                <th>Unit Head Approval</th>
                                <th>Acknowledged by CPU</th>
                                <th>Status</th>
                                <th>Details</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch data from the database
                            $request = "SELECT * FROM purchase_requests WHERE requestor_user_email = '".$_SESSION['auth_user']['user_email']."' ORDER BY id DESC";
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                    
                                    ?>
                                    <tr>
                                        <td style="background-color:<?= $row_class ?>;">
                                            <?= $row['id']; ?> 
                                            
                                        </td>
                                        
                                        <td><?= date('F j Y h:i A', strtotime($row['requested_date'])); ?></td>
                                        
                                        <td>
                                            
                                                <?php 
                                                echo $row['purchase_request_number']
                                                ?>
                                        </td>
                                        <td><?= $row['printed_name']; ?></td>
                                        <td><?= $row['unit_head_approval']; ?></td>
                                        <td>
                                                <?php 
                                                echo $row['unit_dept_college']
                                                
                                                ?>
                                            
                                        </td>
                                            <td>
                                                <?php 
                                                echo $row['iptel_email']
                                                ?>
                                            </td>
                                            <td>
                                            <?php 
                                            echo 
                                            //If acknowledged_by_cpu = 1, echo "CPU Acknowledged", else echo "Not Acknowledged"
                                            $row['acknowledged_by_cpu'] == 1 ? "CPU Acknowledged" : "Not Acknowledged";
                                            ?>
                                            </td>
                                        
                                        <td><?= $row['status']; ?></td>
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
