<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');

// Check if $_GET['id'] is set
if(isset($_GET['id'])) {
    $projectId = mysqli_real_escape_string($con, $_GET['id']);

    // Query to fetch project details
    $query1 = "SELECT * FROM projects WHERE id = $projectId";
    $query1_run = mysqli_query($con, $query1);

    // Check if the query was successful
    if($query1_run) {
        // Check if the project details are found
        if(mysqli_num_rows($query1_run) > 0) {
            include('message.php'); ?>
            <div class="container-fluid custombg-image-row ">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-md-3 fixed-left" style="width:350px">
                        <?php include('includes/sidebar.php'); ?>
                    </div>
                    <!-- Main Body -->
                    <div class="col-md-9">
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header">
                                        <h4 class="card-title text-center customHome">Project Details</h4>
                                    </div>
                                </div>
                                <?php
                                // Display project details
                                foreach($query1_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Project Name</h5>
                                                <p class="card-text"><?= $item['name']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Description</h5>
                                                <p class="card-text"><?= $item['description']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Type</h5>
                                                <p class="card-text"><?= $item['type']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Subject Hosted</h5>
                                                <p class="card-text"><?= $item['subject_hosted']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">College</h5>
                                                <p class="card-text"><?= $item['college_id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Department</h5>
                                                <p class="card-text"><?= $item['department_id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">SD Coordinator</h5>
                                                <p class="card-text"><?= $item['sd_coordinator_id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Partner</h5>
                                                <p class="card-text"><?= $item['partner_id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">School Year</h5>
                                                <p class="card-text"><?= $item['school_year_id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Semester</h5>
                                                <p class="card-text"><?= $item['semester']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Status</h5>
                                                <p class="card-text"><?= $item['status']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                // Query to fetch project documents
                                $query2 = "SELECT * FROM project_documents WHERE project_id = $projectId";
                                $query2_run = mysqli_query($con, $query2);

                                 // Check if the query was successful
                                 if($query2_run) {
                                    // Check if there are project documents
                                    if(mysqli_num_rows($query2_run) > 0) { ?>
                                        <!-- Display project documents -->
                                        <div id="project-documents" class="row">
                                            <?php
                                            // Loop through project documents
                                            foreach($query2_run as $items) {
                                                // Determine file extension
                                                $file_extension = pathinfo($items['file_name'], PATHINFO_EXTENSION);
                                                ?>
                                                <div class="col-md-4 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <h5 class="card-title">Project Document</h5>
                                                            <?php if($file_extension == 'pdf'): ?>
                                                                <!-- Display PDF preview -->
                                                                <embed src="uploads/project_documents/<?= $items['file_name']; ?>" type="application/pdf" width="100%" height="200px" />
                                                            <?php else: ?>
                                                                <!-- Display image preview using Viewer.js -->
                                                                <img src="uploads/project_documents/<?= $items['file_name']; ?>" alt="<?= $items['file_name']; ?>" style="max-width: 100%; max-height: 200px;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <!-- Initialize Viewer.js for image files -->
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.1/viewer.min.js"></script>
                                        <script>
                                            // Initialize Viewer.js for image files
                                            const documentViewer = new Viewer(document.getElementById('project-documents'), {
                                                inline: false,
                                                viewed() {
                                                    documentViewer.zoomTo(1);
                                                },
                                            });
                                        </script>
                                    <?php } else { ?>
                                        <!-- Display a card indicating no project documents -->
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">No Project Documents</h5>
                                                    <p class="card-text">There are no documents available for this project.</p>
                                                    <!-- You can customize this message as needed -->
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } else {
                                    // Error handling for the second query
                                    echo "Error: " . mysqli_error($con);
                                } ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        <?php } else {
            // If project details are not found
            echo "Project not found.";
        }
    } else {
        // Error handling for the first query
        echo "Error: " . mysqli_error($con);
    }
} else {
    // If $_GET['id'] is not set
    echo "No project ID provided.";
}

// Footer

echo '<div class="footer">';
include('includes/footer.php');
echo '</div>';
?>
