<?php
include('authentication.php');
include('includes/header.php');

// Assume $con is your database connection

// Fetch the student details based on ID
if(isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $student_query = "SELECT * FROM students WHERE id = '$student_id'";
    $student_query_run = mysqli_query($con, $student_query);
    if(mysqli_num_rows($student_query_run) > 0) {
        $student_data = mysqli_fetch_assoc($student_query_run);
    } else {
        // Redirect or display error message if student not found
    }
} else {
    // Redirect or display error message if no ID provided
}
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Edit Student</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Students</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>Edit Student
                        <a href="student-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="student_id" value="<?php echo $student_data['id']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">First Name</label>
                                <input type="text" name="fname" value="<?php echo $student_data['fname']; ?>" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Last Name</label>
                                <input type="text" name="lname" value="<?php echo $student_data['lname']; ?>" required class="form-control">
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
                                            <option value="<?=$college_list['id']; ?>" <?=$college_list['id'] == $student_data['college_id'] ? 'selected' : '' ?>>
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
                                             <option value="<?=$department_list['id']; ?>" <?=$department_list['id'] == $student_data['department_id'] ? 'selected' : '' ?>>
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
                                <label for="">Year Level</label>
                                <input type="text" name="year_level" value="<?php echo $student_data['year_level']; ?>" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Student Number</label>
                                <input type="text" name="student_number" value="<?php echo $student_data['student_number']; ?>" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Project</label>
                                <?php
                                $project_query = "SELECT * FROM projects";
                                $project_query_run = mysqli_query($con, $project_query);
                                if(mysqli_num_rows($project_query_run) > 0) {
                                    ?>
                                    <select name="project_id" required class="form-control select2">
                                        <option value="">--Select Project--</option>
                                        <?php
                                        foreach($project_query_run as $project_list) {
                                            ?>
                                            <option value="<?= $project_list['id']; ?>" <?php if($project_list['id'] == $student_data['project_id']) echo 'selected'; ?>> <?= $project_list['name']; ?> </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php
                                } else {
                                    echo "No Project Found";
                                }
                                ?>
                            </div>


                            <div class="col-md-12 mb-3">
                                <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
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
