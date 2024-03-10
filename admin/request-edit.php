<?php
include('authentication.php');
include('includes/header.php');


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
        <h4 class="mt-4">Projects</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Projects</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                
            <?php include('message.php');?>

                <div class="card">
                    <div class="card-header">
                        <h4>Edit Requests
                        <a href="request-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $request_id = $_GET['id'];
                        $request_query = "SELECT * FROM requests WHERE id = '$request_id' LIMIT 1";
                        $request_query_run = mysqli_query($con, $request_query);
                        if(mysqli_num_rows($request_query_run) > 0) 
                        {
                            $request_row = mysqli_fetch_array($request_query_run);
                        ?>
                       
                    
                       <form action="code.php" method="POST" enctype="multipart/form-data">
                       <input type="hidden" name="request_id" value="<?= $request_row['id']?>">
                       <input type="hidden" name="gcalendar_eventID" value="<?= $request_row['gcalendar_eventID']?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" required class="form-control" value="<?= $request_row['name']?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Inventory</label>
                                <?php
                                $inventory_query = "SELECT * FROM inventory";
                                $inventory_query_run = mysqli_query($con, $inventory_query);
                                if(mysqli_num_rows($inventory_query_run) > 0) {
                                ?>
                                    <select name="inventory_id" required class="form-control select2">
                                        <option value="">--Select Inventory--</option>
                                        <?php
                                        foreach($inventory_query_run as $inventory_list) {
                                        ?>
                                            <option value="<?=$inventory_list['id']; ?>" <?=$inventory_list['id'] ==  $request_row['inventory_id'] ? 'selected' : '' ?>>
                                                <?= $inventory_list['name'];?>
                            
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No College Found";
                                }
                                ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                            <label for="">College</label>
                                <?php
                                $college_query = "SELECT * FROM college";
                                $college_query_run = mysqli_query($con, $college_query);
                                if(mysqli_num_rows($college_query_run) > 0) {
                                ?>
                                    <select name="college_id" required class="form-control select2">
                                        <option value="">--Select College--</option>
                                        <?php
                                        foreach($college_query_run as $college_list) {
                                        ?>
                                            <option value="<?=$college_list['id']; ?>" <?=$college_list['id'] ==  $request_row['college_id'] ? 'selected' : '' ?>>
                                                <?= $college_list['name'];?>
                            
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No College Found";
                                }
                                ?>
                            </div>

                            <div class="col-md-6 mb-3">
                            <label for="">Department</label>
                                <?php
                                $department_query = "SELECT * FROM department";
                                $department_query_run = mysqli_query($con, $department_query);
                                if(mysqli_num_rows($department_query_run) > 0) {
                                ?>
                                    <select name="department_id" required class="form-control select2">
                                        <option value="">--Select Department--</option>
                                        <?php
                                        foreach($department_query_run as $department_list) {
                                        ?>
                                            <option value="<?=$department_list['id']; ?>" <?=$department_list['id'] == $request_row['department_id'] ? 'selected' : '' ?> >
                                                <?= $department_list['name'];?>
                                        
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No Department Found";
                                }
                                ?>
                            </div>


                            <div class="col-md-6 mb-3">
                                    <label for="">Status</label>
                                    <select name="status" required class="form-control">
                                        <option value="">--Select Role--</option>
                                        <option value="0"<?= $request_row['status'] =='0' ? 'selected': '' ; ?>>Received by CPU</option>
                                        <option value="1"<?= $request_row['status'] =='1' ? 'selected': '' ; ?>>Left CPU office</option>
                                        <option value="2"<?= $request_row['status'] =='2' ? 'selected': '' ; ?>>Received by Registrar</option>
                                        <option value="3"<?= $request_row['status'] =='3' ? 'selected': '' ; ?>>Left Registrar office</option>
                                        <option value="4"<?= $request_row['status'] =='4' ? 'selected': '' ; ?>>Received by VPadmin</option>
                                        <option value="5"<?= $request_row['status'] =='5' ? 'selected': '' ; ?>>Left VPadmin office</option>
                                        <option value="6"<?= $request_row['status'] =='6' ? 'selected': '' ; ?>>Received by President</option>
                                        <option value="7"<?= $request_row['status'] =='7' ? 'selected': '' ; ?>>Left President office</option>
                                        <option value="8"<?= $request_row['status'] =='8' ? 'selected': '' ; ?>>Approved</option>
                                    </select>

                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Request Received Date:</label>
                                <input type="date" class="form-control" name="request_received_date" required value="<?= $request_row['request_received_date'] ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label  class="form-label">Expected Delivery Date:</label>
                                <input type="date" class="form-control" name="expected_delivery_date" required value="<?= $request_row['expected_delivery_date'] ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Actual Delivery Date:</label>
                                <input type="date" class="form-control" name="actual_delivery_date" required value="<?= $request_row['actual_delivery_date'] ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Semester</label>
                                <select name="semester" required class="form-control">
                                    <option value="">--Select Semester--</option>
                                    <option value="1" <?= $request_row['semester'] == '1' ? 'selected' : '' ?>>First Semester</option>
                                    <option value="2" <?= $request_row['semester'] == '2' ? 'selected' : '' ?>>Second Semester</option>
                                    <option value="3" <?= $request_row['semester'] == '3' ? 'selected' : '' ?>>Intersession Summer</option>
                                </select>
                            </div>


                            <div class="col-md-6 mb-3">
                            <label for="">School Year</label>
                                <?php
                                $school_year_query = "SELECT * FROM school_year";
                                $school_year_query_run = mysqli_query($con, $school_year_query);
                                if(mysqli_num_rows($school_year_query_run) > 0) {
                                ?>
                                    <select name="school_year_id" required class="form-control select2">
                                        <option value="">--Select School Year--</option>
                                        <?php
                                        foreach($school_year_query_run as $school_year_list) {
                                        ?>
                                            <option value="<?=$school_year_list['id']; ?>" <?=$school_year_list['id'] == $request_row['school_year_id'] ? 'selected' : ''  ?>>
                                                <?= $school_year_list['school_year'];?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No School Year Found";
                                }
                                ?>
                            </div>

                            <?php if ($super_user) { ?>
                                <div class="col-md-6 mb-3">
                            <label for="">Assign User:</label>
                                <?php
                                $user_query = "SELECT * FROM users";
                                $user_query_run = mysqli_query($con, $user_query);
                                if(mysqli_num_rows($user_query_run) > 0) {
                                ?>
                                    <select name="user_id" required class="form-control select2">
                                        <option value="">Assign User:</option>
                                        <?php
                                        foreach($user_query_run as $user_list) {
                                        ?>
                                            <option value="<?=$user_list['id']; ?>" <?=$user_list['id'] == $request_row['assigned_user'] ? 'selected' : '' ?> >
                                                <?= $user_list['fname'];?>  <?= $user_list['lname'];?>
                                        
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No Department Found";
                                }
                                ?>
                            </div>
                                            <?php }
                                            else
                                            {
                                                ?>
                                                <input type="hidden" name="user_id" value="<?= $request_row['assigned_user']?>">
                                                <?php
                                            }
                                            
                                            ?>

                            <div class="col-md-12 mb-3">
                                <button type="submit" name="request_edit_btn" class="btn btn-primary ">Save Request</button>
                            </div>

                        </div>
                    </form>

                    <?php

                    }
                    else
                    {
                        ?>
                        <h4>No Record Found</h4>
                        <?php
                    }
                        
                    ?>
                    </div>
                </div>
            </div>
        </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>