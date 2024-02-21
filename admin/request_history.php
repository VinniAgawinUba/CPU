<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Request History</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Request History</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <!-- Request Details Card -->
                    <div class="card-header">
                        <h4>Request Details
                        <a href="request-view.php" class="btn btn-danger float-end">Back</a>
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
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request = "SELECT * FROM requests WHERE id = $_GET[request_id]";
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                     // Check if request_received_date is older than 30 days from the current day
                                    $received_date = strtotime($row['request_received_date']);
                                    $current_date = strtotime(date('Y-m-d'));
                                    $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                                    // Add a CSS class based on the condition
                                    $row_class = '';
                                    if ($difference >= 30 && $row['status'] != 8) {
                                        $row_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                    } elseif ($difference >= 15 && $row['status'] != 8) {
                                        $row_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                    } elseif ($row['status'] == 8) {
                                        $row_class = 'bg-success'; // Status is 8 (Approved), set background to green
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>">
                                        <td><?= $row['id']; ?></td>
                                        <td>
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
                                        <td><?= $row['name']; ?></td>
                                        <td>
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
                                            <td>
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
                                            <td><?php 
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
                                        <td><?= date('F j, Y', strtotime($row['request_received_date'])); ?></td>
                                        <td><?= date('F j, Y', strtotime($row['expected_delivery_date'])); ?></td>
                                        <td><?= date('F j, Y', strtotime($row['actual_delivery_date'])); ?></td>
                                        <td><?= $row['semester']; ?></td>
                                        <td>
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
                                        <td>
                                        <form id="deleteForm" action="code.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <button type="submit" name="request_delete_btn" value="<?=$row['id']?>" class="btn btn-danger deleteButton" id="deleteButton">Delete</button>
                                        </form>

                                        </td>
                                    </tr>
                                    <?php
                                }
                            } 
                            if ($difference >= 30 && $row['status'] != 8) {
                                echo "<tr><td colspan='13' class='text-center text-white bg-danger'>This request is older than 30 days and has not been approved</td></tr>";
                            } elseif ($difference >= 15 && $row['status'] != 8) {
                                echo "<tr><td colspan='13' class='text-center text-white bg-warning'>This request is older than 15 days and has not been approved</td></tr>";
                            } elseif ($row['status'] == 8) {
                                echo "<tr><td colspan='13' class='text-center text-white bg-success'>This request has been approved</td></tr>";
                            }
                            else{echo "No Request Found";}
                            ?>
                            
                        </tbody>
                    </table>


                    
                    
                    </div>

                    <!-- Request History Card -->
                    <div class="card-header">
                        <h4>Request History
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

