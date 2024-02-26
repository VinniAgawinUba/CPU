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
        <h4 class="mt-4">Requests</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Requests</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>View Requests
                        <a href="request-add.php" class="btn btn-primary float-end">Add Request</a>
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
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="not approved">Not Approved</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Received by CPU">Received by CPU</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Left CPU office">Left CPU office</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Received by Registrar">Received by Registrar</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Left Registrar office">Left Registrar office</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Received by VPadmin">Received by VPadmin</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Left VPadmin office">Left VPadmin office</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Received by President">Received by President</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Left President office">Left President office</a></li>
                                    <li><a class="dropdown-item filter-btn" href="#" data-status="Approved">Approved</a></li>
                                    <!-- Add more items for other statuses as needed -->
                                </ul>
                                
                        </div>
                    </div>




                    <table id="myRequests" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Inventory ID</th>
                                <th>name</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Request Received Date</th>
                                <th>Expected Delivery Date</th>
                                <th>Actual Delivery Date</th>
                                <th>Semester</th>
                                <th>School Year</th>
                                <th>Edit</th>
                                <?php if ($super_user) { ?><th>Delete</th><?php } ?>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($super_user || $department_editor)
                            {
                                $request = "SELECT * FROM requests ORDER BY id DESC";
                            }
                            else
                            {
                                $request = "SELECT * FROM requests WHERE assigned_user = '{$_SESSION['auth_user']['user_id']}' ORDER BY id DESC";
                            }
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                     // Check if request_received_date is older than 30 days from the current day
                                    $received_date = strtotime($row['request_received_date']);
                                    $current_date = strtotime(date('Y-m-d'));
                                    $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                                    // Add a CSS class based on the condition
                                    $row_class = '';
                                    $Changetext_color = 'black';
                                    if ($difference >= 30 && $row['status'] != 8) {
                                        $row_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                        $Changetext_color = 'white'; // Set text color to white
                                    } elseif ($difference >= 15 && $row['status'] != 8) {
                                        $row_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                        $Changetext_color = 'black'; // Set text color to dark
                                    } elseif ($row['status'] == 8) {
                                        $row_class = 'bg-success'; // Status is 8 (Approved), set background to green
                                        $Changetext_color = 'white'; // Set text color to white
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>">
                                        <td style="color:<?= $Changetext_color ?>">
                                            <a href="request_history.php?request_id=<?= $row['id']; ?>">
                                                <?= $row['id']; ?> 
                                            </a>
                                        
                                        </td>
                                        
                                        <td style="color:<?= $Changetext_color ?>">
                                            
                                                <?php 
                                                if($row['inventory_id'] > 0)
                                                {
                                                    $inventory_query = "SELECT name FROM inventory WHERE id = ".$row['inventory_id'];
                                                    $inventory_query_run = mysqli_query($con, $inventory_query);
                                                    if(mysqli_num_rows($inventory_query_run) > 0)
                                                    {
                                                        foreach($inventory_query_run as $inventory_list)
                                                        {
                                                            echo $inventory_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Inventory Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Inventory Found";
                                                }
                                                
                                                ?>
                                            </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['name']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                if($row['college_id'] > 0)
                                                {
                                                    $college_query = "SELECT name FROM college WHERE id = ".$row['college_id'];
                                                    $college_query_run = mysqli_query($con, $college_query);
                                                    if(mysqli_num_rows($college_query_run) > 0)
                                                    {
                                                        foreach($college_query_run as $college_list)
                                                        {
                                                            echo $college_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No College Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No College Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                if($row['department_id'] > 0)
                                                {
                                                    $department_query = "SELECT name FROM department WHERE id = ".$row['department_id'];
                                                    $department_query_run = mysqli_query($con, $department_query);
                                                    if(mysqli_num_rows($department_query_run) > 0)
                                                    {
                                                        foreach($department_query_run as $department_list)
                                                        {
                                                            echo $department_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Department Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Department Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>"><?php 
                                            if ($row['status']==0) {
                                                echo "Received by CPU";
                                            } elseif ($row['status'] == 1) {
                                                echo "Left CPU office";
                                            } elseif ($row['status'] == 2) {
                                                echo "Received by Registrar";
                                            } elseif ($row['status'] == 3) {
                                                echo "Left Registrar office";
                                                
                                            }elseif ($row['status'] == 4) {
                                                echo "Received by VPadmin";
                                                
                                                
                                            }elseif ($row['status'] == 5) {
                                                echo "Left VPadmin office";
                                                
                                                
                                            }elseif ($row['status'] == 6) {
                                                echo "Received by President";
                                                
                                                
                                            }elseif ($row['status'] == 7) {
                                                echo "Left President office";
                                                
                                                
                                            }elseif ($row['status'] == 8) {
                                                echo "Approved";
                                                
                                            } else {
                                                echo "Unknown Status";
                                            }
                                            ?>
                                        </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['request_received_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['expected_delivery_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['actual_delivery_date']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['semester']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                if($row['school_year_id'] > 0)
                                                {
                                                    $sy_query = "SELECT school_year FROM school_year WHERE id = ".$row['school_year_id'];
                                                    $sy_query_run = mysqli_query($con, $sy_query);
                                                    if(mysqli_num_rows($sy_query_run) > 0)
                                                    {
                                                        foreach($sy_query_run as $sy_list)
                                                        {
                                                            echo $sy_list['school_year'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No SY Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No SY Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>
                                        
                                        <td>
                                            <a href="request-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
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
                                                if($row['assigned_user'] > 0)
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

