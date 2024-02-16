<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
?>
<link rel="stylesheet" href="assets/css/custom.css">

<div class="container-fluid custombg-image-row">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 fixed-left" style="width:350px">
            <?php include('includes/sidebar.php'); ?>
        </div>

        <!-- Main Body -->
        <div class="col-md-9">
            <div class="">
                <div class="row ">
                    <div class="col-md-12">
                        <?php include('message.php'); ?>
                        <div class="card-header">
                            <h4 class="card-title text-center customHome">Projects</h4>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $query1 = "SELECT * FROM school_year";
                        $query1_run = mysqli_query($con, $query1);
                        $query2 = "SELECT * FROM college";
                        $query2_run = mysqli_query($con, $query2);
                       
                        
                        // Construct the base URL
                        $base_url = $_SERVER['PHP_SELF'] . "?";
                        
                        // Append existing parameters to the base URL
                        $existing_params = http_build_query(array_intersect_key($_GET, array_flip(['school_year', 'college','department'])));
                        if (!empty($existing_params)) {
                            $base_url .= $existing_params . "&";
                        }
                        
                        //If school year is already set as params render All Colleges
                        if(mysqli_num_rows($query1_run) > 0) {
                            if(isset($_GET['school_year']) && !isset($_GET['college_id']) && !isset($_GET['department_id'])) {
                                if (mysqli_num_rows($query2_run) > 0){
                                foreach($query2_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <?php
                                                $college = $item['id'];
                                                $url = $base_url . "college_id=" . urlencode($college);
                                                ?>
                                                <a href="<?= $url ?>">
                                                    <h5 class="card-title"><?= $item['name']; ?></h5>
                                                    <p class="card-text"><?= $college; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                
                            }
                        }
                            //if School Year And College is Set as params render Departments
                            
                            if(isset($_GET['school_year']) && isset($_GET['college_id']) && !isset($_GET['department_id'])) {
                                $query3 = "SELECT * FROM department where college_id = $_GET[college_id]";
                                $query3_run = mysqli_query($con, $query3);
                                if (mysqli_num_rows($query3_run) > 0){
                                foreach($query3_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <?php
                                                $department = $item['id'];
                                                $url = $base_url . "college_id=" . urlencode($_GET['college_id']) . "&department_id=" . urlencode($department);
                                                ?>
                                                <a href="<?= $url ?>">
                                                    <h5 class="card-title"><?= $item['name']; ?></h5>
                                                    <p class="card-text"><?= $department; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                            //If school year is Not set render School Years to be placed as params
                            elseif (!isset($_GET['school_year']) && !isset($_GET['college_id']) && !isset($_GET['department_id'])) {
                                foreach($query1_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <?php
                                                $school_year = $item['id'];
                                                $url = $base_url . "school_year=" . urlencode($school_year);
                                                ?>
                                                <a href="<?= $url ?>">
                                                    <h5 class="card-title"><?= $item['school_year']; ?></h5>
                                                    <p class="card-text"><?= $school_year; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            //If SY, COLLEGE, DEPARTMENT SET, SHOW PROJECTS THAT USE THE PARAMS ELSE NO RECORDS
                            if(isset($_GET['school_year']) && isset($_GET['college_id']) && isset($_GET['department_id'])){
                                $query4 = "SELECT * FROM projects where school_year_id = $_GET[school_year] AND college_id = $_GET[college_id] AND department_id = $_GET[department_id]";
                                $query4_run = mysqli_query($con, $query4);
                                if (mysqli_num_rows($query4_run) > 0){
                                foreach($query4_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <?php
                                                $project = $item['id'];
                                                ?>
                                                <a href="project-details.php?id=<?=$item['id']?>">
                                                    <h5 class="card-title"><?= $item['name']; ?></h5>
                                                    <p class="card-text"><?= $project; ?></p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                            }
                            
                           
                        } else {echo '<a style="color:white">No Records Found</a>';}
                    }
                }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
