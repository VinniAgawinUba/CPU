<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Category</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Category</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
            <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Category
                        <a href="category-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <?php 
                    //Checks if the id is set in the URL
                    if(isset($_GET['id']))
                    {
                        $user_id = $_GET['id'];
                        $users = "SELECT * FROM categories WHERE id = '$user_id'";
                        $users_run = mysqli_query($con, $users);

                        if(mysqli_num_rows($users_run) > 0)
                        {
                            foreach($users_run as $user)
                            {  
                            ?>

                           

                            <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= $user['id'];?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $user['name'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Slug (URL)</label>
                                    <input type="text" name="slug" class="form-control" value="<?= $user['slug'];?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Description</label>
                                    <textarea name="description" class="form-control" rows="4" > <?= $user['description'];?> </textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Meta Title</label>
                                    <input type="text" name="meta_title" max="191" class="form-control" value="<?= $user['meta_title'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Meta Description</label>
                                    <textarea name="meta_description" max="191" class="form-control" rows="4"><?= $user['meta_description'];?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Meta Keyword</label>
                                    <textarea name="meta_keyword" max="191" class="form-control" rows="4"><?= $user['meta_keyword'];?></textarea>
                                </div>
                               
                                <div class = "col-md-12 mb-3">
                                    <label for="">Navbar Status</label>
                                    <input type = "checkbox" name = "navbar_status"  width = "70px" height = "70px" value="<?= $user['navbar_status'];?>">
                                </div>

                                <div class = "col-md-12 mb-3">
                                    <label for="">Status</label>
                                    <input type = "checkbox" name = "status"  width = "70px" height = "70px" value="<?= $user['status'];?>">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "category_edit" class="btn btn-primary">Save Category</button>
                                
                            </div>

                        </form>
                        <?php
                            }
                        }
                        else
                        {
                            ?>
                            <h4>No Records Found</h4>
                            <?php

                        }
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