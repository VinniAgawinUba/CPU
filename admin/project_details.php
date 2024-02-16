<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Project Details</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Project Details</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <!-- Project Details -->
                        <h4>Project Details
                        <a href="project-view.php" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Subject Hosted</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>SD Coordinator</th>
                                <th>Partner</th>
                                <th>School Year</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$posts = "SELECT * FROM posts WHERE status != '2'";
                            $posts = "SELECT * FROM projects where id = '$_GET[id]'";
                            $posts_run = mysqli_query($con, $posts);
                            if (mysqli_num_rows($posts_run) > 0) {
                                foreach ($posts_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['name']; ?></td>
                                        <td><?= $row['description']; ?></td>
                                        <td><?= $row['type']; ?></td>
                                        <td><?= $row['subject_hosted']; ?></td>
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
                                            <td>
                                                <?php 
                                                if($row['sd_coordinator_id'] > 0)
                                                {
                                                    $coordinator_query = "SELECT fname, lname FROM faculty WHERE id = ".$row['sd_coordinator_id'];
                                                    $coordinator_query_run = mysqli_query($con, $coordinator_query);
                                                    if(mysqli_num_rows($coordinator_query_run) > 0)
                                                    {
                                                        foreach($coordinator_query_run as $coordinator_list)
                                                        {
                                                            echo $coordinator_list['fname'].' '.$coordinator_list['lname'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Coordinator Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Coordinator Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>
                                            <td>
                                                <?php 
                                                if($row['partner_id'] > 0)
                                                {
                                                    $partner_query = "SELECT name FROM partners WHERE id = ".$row['partner_id'];
                                                    $partner_query_run = mysqli_query($con, $partner_query);
                                                    if(mysqli_num_rows($partner_query_run) > 0)
                                                    {
                                                        foreach($partner_query_run as $partner_list)
                                                        {
                                                            echo $partner_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No partner Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No partner Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>
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
                                        <td><?= $row['semester']; ?></td>
                                        <td><?php 
                                            if ($row['status']==0) {
                                                echo "In Progress";
                                            } elseif ($row['status'] == 1) {
                                                echo "Finished";
                                            } elseif ($row['status'] == 2) {
                                                echo "TBD";
                                            } elseif ($row['status'] == 3) {
                                                echo "Cancelled";
                                            } else {
                                                echo "Unknown Status";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="project-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Record Found</td>
                                </tr>
                                <?php
                            }
                            ?>
                            
                        </tbody>
                    </table>
                        
                    </div>

                    <!-- Project Documents -->
                    <div class="card-header">
                        <h4>Project Documents
                        
                        </h4>
                    </div>
                    <div class="card-body">

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Project ID</th>
                                <th>File Name</th>
                                <th>File Type</th>
                                <th>File Size</th>
                                <th>Delete</th>
                                <th>Download</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$posts = "SELECT * FROM posts WHERE status != '2'";
                            $posts = "SELECT * FROM project_documents WHERE project_id = '$_GET[id]'";
                            $posts_run = mysqli_query($con, $posts);
                            if (mysqli_num_rows($posts_run) > 0) {
                                foreach ($posts_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['project_id']; ?></td>
                                        <td><?= $row['file_name']; ?></td>
                                        <td><?= $row['file_type']; ?></td>
                                        <td><?= $row['file_size']; ?></td>
                                        <td>
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="document_id" value="<?= $row['id']; ?>">
                                                <input type="hidden" name="project_id" value="<?= $row['project_id']; ?>">
                                                <button id="deleteButton" type="submit" name="delete_project_document" class="btn btn-danger" >Delete</button>
                                            </form>
                                        </td>

                                        <td>
                                        <a href="<?= $row['file_path']; ?>" download="<?= $row['file_name']; ?>" class="btn btn-primary">Download</a>
                                        </td>
                                        
                                        
                                        
                                        
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="1">No Record Found</td>
                                </tr>
                                <?php
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