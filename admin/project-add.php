<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Projects</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Projects</li>
    </ol>
    <div class="row">

        <div class="col-md-12">

            <?php include('message.php'); ?>

            <div class="card">
                <div class="card-header">
                    <h4>Add Project
                        <a href="project-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">

                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" name="pname" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Type</label>
                                <input type="text" name="type" required class="form-control">
                            </div>
                            <!-- Add other project fields as needed -->

                            <div class="col-md-12 mb-3">
                                <label for="">Description</label>
                                <textarea name="description" class="form-control" required rows="4"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Subject Hosted</label>
                                <input type="text" name="subject_hosted" required class="form-control">
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
                            <label for="">SD Coordinator</label>
                                <?php
                                $faculty_query = "SELECT * FROM faculty WHERE role = '1'";
                                $faculty_query_run = mysqli_query($con, $faculty_query);
                                if(mysqli_num_rows($faculty_query_run) > 0) {
                                ?>
                                    <select name="sd_coordinator_id" required class="form-control select2">
                                        <option value="">--Select Coordinator--</option>
                                        <?php
                                        foreach($faculty_query_run as $faculty_list) {
                                        ?>
                                            <option value="<?=$faculty_list['id']?>"> <?=$faculty_list['fname'].' '.$faculty_list['lname']?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No Faculty Found";
                                }
                                ?>
                            </div>

                            <div class="col-md-6 mb-3">
                            <label for="">Partner</label>
                                <?php
                                $partner_query = "SELECT * FROM partners";
                                $partner_query_run = mysqli_query($con, $partner_query);
                                if(mysqli_num_rows($partner_query_run) > 0) {
                                ?>
                                    <select name="partner_id" required class="form-control select2">
                                        <option value="">--Select Partner--</option>
                                        <?php
                                        foreach($partner_query_run as $partner_list) {
                                        ?>
                                            <option value="<?=$partner_list['id']; ?>"> <?=$partner_list['name']; ?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No Partners Found";
                                }
                                ?>
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
                                    <label for="">Status</label>
                                    <select name="status" required class="form-control">
                                        <option value="">--Select Role--</option>
                                        <option value="0">In Progress</option>
                                        <option value="1">Finished</option>
                                        <option value="2">TBD</option>
                                        <option value="3">Cancelled</option>
                                    </select>

                            </div>
                            <!-- Upload project related files to project_documents -->
                            <div class="col-md-6 mb-3">
                                <label for="">Upload Project Files</label>
                                <input type="file" name="project_documents[]" multiple class="form-control">



                            <!-- Add other project fields as needed -->

                            <div class="col-md-12 mb-3">
                                <button type="submit" name="project_add_btn" class="btn btn-primary ">Add Project</button>
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
