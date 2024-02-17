<?php
include('authentication.php');
include('includes/header.php');
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
                    </div>
                    <div class="card-body">

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
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request = "SELECT * FROM requests";
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                    ?>
                                    <tr>
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
                                        <td><?= $row['request_received_date']; ?></td>
                                        <td><?= $row['expected_delivery_date']; ?></td>
                                        <td><?= $row['actual_delivery_date']; ?></td>
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
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <button type="submit" name="request_delete_btn" value="<?=$row['id']?>" class="btn btn-danger" id="deleteButton">Delete</button>
                                            </form>
                                        </td>
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
include('includes/scripts.php');
?>