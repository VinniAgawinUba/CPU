<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
?>
<link rel="stylesheet" href="assets/css/custom.css">

    <div class="container-fluid custombg-image-row ">
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
                                    <h4 class="card-title text-center customHome">Articles</h4>
                                </div>
                        </div>
                    <div class="row">
                        <?php
                        $query = "SELECT * FROM posts";
                        $query_run = mysqli_query($con, $query);
                        if(mysqli_num_rows($query_run) > 0)
                        {
                            foreach($query_run as $item)
                            {
                            ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $item['id']; ?></h5>
                                        <p class="card-text"><?= $item['name']; ?></p>
                                        <!-- You can add more project details here -->
                                    </div>
                                </div>
                            </div>
                            <?php
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
