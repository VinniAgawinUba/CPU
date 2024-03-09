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
        <h4 class="mt-4">Purchase Request History</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Purchase Request History</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <!-- Request Details Card -->
                    <div class="card-header">
                        <h4>Request Details
                        <a href="purchase_request-view.php" class="btn btn-danger float-end">Back</a>
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
    

                    </div>
                    <div class="card-body">
                        
                    </div>



                    <!-- Request Details Table -->
                    <table  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th>ID</th>
                                <th>Purchase Request Number</th>
                                <th>Requestor Name</th>
                                <th>Unit/Dept/College</th>
                                <th>Iptel#/Email</th>
                                <th>Purchase Type</th>
                                <th>Endorsed by</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request = "SELECT * FROM purchase_requests WHERE id = $_GET[request_id]";
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
                                    if ($difference >= 30 && $row['status'] != 'approved') {
                                        $row_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                        $Changetext_color = 'white'; // Set text color to white
                                    } elseif ($difference >= 15 && $row['status'] != 'approved') {
                                        $row_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                        $Changetext_color = 'black'; // Set text color to dark
                                    } elseif ($row['status'] == 'approved') {
                                        $row_class = 'bg-success'; // Status is 'Approved', set background to green
                                        $Changetext_color = 'white'; // Set text color to white
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>">
                                        <td style="color:<?= $Changetext_color ?>">
                                            <a href="purchase_request_history.php?request_id=<?= $row['id']; ?>">
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
                                            echo $row['purchase_types']
                                            ?>
                                            </td>
                                        </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['endorsed_by_dean']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['requested_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['status']; ?></td>
                                        
                                        
                                       
                                        <!-- If Super User, see Assigned User -->
                                        <?php if ($super_user) { ?>
                                        <td style="color:<?= $Changetext_color ?>">
                                        <?php 
                                                if($row['assigned_user_id'] > 0)
                                                {
                                                    $user_query = "SELECT * FROM users WHERE id = ".$row['assigned_user'];
                                                    $user_query_run = mysqli_query($con, $user_query);
                                                    if(mysqli_num_rows($user_query_run) > 0)
                                                    {
                                                        foreach($user_query_run as $user_list)
                                                        {
                                                            echo $user_list['fname'].' '.$user_list['lname'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Assigned User Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Assigned User Found";
                                                }
                                                
                                                ?>
                                        </td>
                                            <?php } ?>
                                    </tr>
                                    <?php
                                }
                            } 
                            if ($difference >= 30 && $row['status'] != 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-danger'>This request is older than 30 days and has not been approved</td></tr>";
                            } 
                            elseif ($difference >= 15 && $row['status'] != 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-warning'>This request is older than 15 days and has not been approved</td></tr>";
                            } 
                            elseif ($row['status'] == 'pending') {
                                echo "<tr><td colspan='100%' class='text-center'>This request is still pending</td></tr>";
                            }
                            elseif ($row['status'] == 'rejected') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-danger'>This request has been rejected</td></tr>";
                            }
                            elseif ($row['status'] == 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-success'>This request has been approved</td></tr>";
                            }
                            ?>
                            
                        </tbody>
                    </table>


                    
                    
                    </div>

                    <!-- Request History Card -->
                    <div class="card-header">
                        <h4>Status History Logs
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
    

                    </div>
                    <div class="card-body">
                        
                    </div>



                    <!-- Request History Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Request ID</th>
                                <th>Old Status</th>
                                <th>New Status</th>
                                <th>Change Date</th>
                                <th>Edited by</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request_history = "SELECT * FROM request_status_history WHERE request_id = $_GET[request_id] ORDER BY change_date DESC";
                            $request_history_run = mysqli_query($con, $request_history);
                            if (mysqli_num_rows($request_history_run) > 0) {
                                foreach ($request_history_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['request_id']; ?></td>
                                        <td>
                                            <?php 
                                            if ($row['old_status']==0) {
                                                echo "Received by CPU";
                                            } elseif ($row['old_status'] == 1) {
                                                echo "Left CPU office";
                                            } elseif ($row['old_status'] == 2) {
                                                echo "Received by Registrar";
                                            } elseif ($row['old_status'] == 3) {
                                                echo "Left Registrar office";
                                                
                                            }elseif ($row['old_status'] == 4) {
                                                echo "Received by VPadmin";
                                                
                                                
                                            }elseif ($row['old_status'] == 5) {
                                                echo "Left VPadmin office";
                                                
                                                
                                            }elseif ($row['old_status'] == 6) {
                                                echo "Received by President";
                                                
                                                
                                            }elseif ($row['old_status'] == 7) {
                                                echo "Left President office";
                                                
                                                
                                            }elseif ($row['old_status'] == 8) {
                                                echo "Approved";
                                                
                                            } else {
                                                echo "Unknown Status";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row['new_status']==0) {
                                                echo "Received by CPU";
                                            } elseif ($row['new_status'] == 1) {
                                                echo "Left CPU office";
                                            } elseif ($row['new_status'] == 2) {
                                                echo "Received by Registrar";
                                            } elseif ($row['new_status'] == 3) {
                                                echo "Left Registrar office";
                                                
                                            }elseif ($row['new_status'] == 4) {
                                                echo "Received by VPadmin";
                                                
                                                
                                            }elseif ($row['new_status'] == 5) {
                                                echo "Left VPadmin office";
                                                
                                                
                                            }elseif ($row['new_status'] == 6) {
                                                echo "Received by President";
                                                
                                                
                                            }elseif ($row['new_status'] == 7) {
                                                echo "Left President office";
                                                
                                                
                                            }elseif ($row['new_status'] == 8) {
                                                echo "Approved";
                                                
                                            } else {
                                                echo "Unknown Status";
                                            }
                                            ?>

                                        </td>
                                        <td><?= date('F j Y h:i:s A', strtotime($row['change_date'])); ?></td>

                                        <td>
                                            <?php 
                                            if($row['edited_by'] > 0)
                                            {
                                                $user_query = "SELECT * FROM users WHERE id = ".$row['edited_by'];
                                                $user_query_run = mysqli_query($con, $user_query);
                                                if(mysqli_num_rows($user_query_run) > 0)
                                                {
                                                    foreach($user_query_run as $user_list)
                                                    {
                                                        echo $user_list['fname'].' '. $user_list['lname'];
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No User Found";
                                                }
                                            }
                                            else
                                            {
                                                echo "No User Found";
                                            }
                                            
                                            ?>
                                        
                                            
                                        
                                     
                                    </tr>
                                    <?php
                                }
                            }
                            else{echo "No Request History Found";} 
                            ?>
                            
                        </tbody>
                    </table>


                    
                    
                    </div>
            </div>
        </div>
</div>
        
<?php
include('includes/footer.php');

?>

