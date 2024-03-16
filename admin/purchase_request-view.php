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
                    <div class="card-body">
                        <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter by Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="all">All</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="pending">Pending</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="approved">Approved</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="rejected">Rejected</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="completed">Completed</a></li>
                                    
                                    <!-- Add more items for other statuses as needed -->
                                </ul>
                                
                        </div>
                    </div>




                    <table id="myPurchaseRequests" class="table table-bordered table-striped">
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
                                <th>History</th>
                                <th>Edit</th>
                                <?php if ($super_user) { ?><th>Delete</th><?php } ?>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($super_user || $department_editor)
                            {
                                $request = "SELECT * FROM purchase_requests ORDER BY id DESC";
                            }
                            else
                            {
                                $request = "SELECT * FROM purchase_requests WHERE assigned_user = '{$_SESSION['auth_user']['user_id']}' ORDER BY id DESC";
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
                                            echo $row['purchase_types']
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
                                        <form id="deleteForm" action="code.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="request_delete_btn" value="<?=$row['id']?>" class="btn btn-danger deleteButton" id="deleteButton">Delete</button>
                                        </form>

                                        </td>
                                        <?php } ?>
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

