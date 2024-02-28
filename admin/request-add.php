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
                    <h4>Add Request
                        <a href="request-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">

                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" required class="form-control">
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
                                            <option value="<?=$inventory_list['id']; ?>"> <?=$inventory_list['name'];?> </option>
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
                                            <option value="<?=$college_list['id']; ?>"> <?=$college_list['name'];?> </option>
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
                                            <option value="<?=$department_list['id']; ?>"> <?=$department_list['name'];?> </option>
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
                                        <option value="">--Select Status--</option>
                                        <option value="0">Received by CPU</option>
                                        <option value="1">Left CPU office</option>
                                        <option value="2">Received by Registrar</option>
                                        <option value="3">Left Registrar office</option>
                                        <option value="4">Received by VPadmin</option>
                                        <option value="5">Left VPadmin office</option>
                                        <option value="6">Received by President</option>
                                        <option value="7">Left President office</option>
                                        <option value="8">Approved</option>
                                    </select>

                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Request Received Date:</label>
                                <input type="date" class="form-control" name="request_received_date" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label  class="form-label">Expected Delivery Date:</label>
                                <input type="date" class="form-control" name="expected_delivery_date" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Actual Delivery Date:</label>
                                <input type="date" class="form-control" name="actual_delivery_date">
                            </div>

                            <div class="col-md-6 mb-3">
                                    <label for="">Semester</label>
                                    <select name="semester" required class="form-control">
                                        <option value="">--Select Semester--</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                        <option value="3">Intersession Summer</option>
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
                                            <option value="<?=$school_year_list['id']; ?>"> <?=$school_year_list['school_year']; ?> </option>
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
                            <label for="">Assign user:</label>
                                          
                                            <?php
                                                $user_query = "SELECT * FROM users WHERE role_as = 1";
                                                $user_query_run = mysqli_query($con, $user_query);
                                                if(mysqli_num_rows($user_query_run) > 0) {
                                                ?>
                                                    <select name="user_id" required class="form-control select2">
                                                        <option value="">--Assign User--</option>
                                                        <?php
                                                        foreach($user_query_run as $user_list) {
                                                        ?>
                                                            <option value="<?=$user_list['id']; ?>"> <?=$user_list['fname'];?> <?=$user_list['lname'];?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                <?php
                                                } else {
                                                    echo "No User Found";
                                                }
                                                ?>
                            </div>
                                            <?php } ?>
                            

                            <div class="col-md-12 mb-3">
                                <button type="submit" name="request_add_btn" class="btn btn-primary ">Add Request</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>


