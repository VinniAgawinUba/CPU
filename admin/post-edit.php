<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Posts</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Posts</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                
            <?php include('message.php');?>

                <div class="card">
                    <div class="card-header">
                        <h4>Edit Posts
                        <a href="post-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $post_id = $_GET['id'];
                        $post_query = "SELECT * FROM posts WHERE id = '$post_id' LIMIT 1";
                        $post_query_run = mysqli_query($con, $post_query);
                        if(mysqli_num_rows($post_query_run) > 0) 
                        {
                            $post_row = mysqli_fetch_array($post_query_run);
                        ?>
                       
                    
                            <form action="code.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="post_id" value="<?= $post_row['id']?>">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        

                                    <label for="">Category List</label>
                                    <?php
                                    $category_query = "SELECT * FROM categories WHERE status = '0'";
                                    $category_query_run = mysqli_query($con, $category_query);
                                    if(mysqli_num_rows($category_query_run) > 0) {
                                    ?>
                                        <select name="category_id" required class="form-control">
                                            <option value="">--Select Category--</option>
                                            <?php
                                            foreach($category_query_run as $category_item) {
                                            ?>
                                                <option value="<?=$category_item['id'];?>" <?= $category_item['id'] == $post_row['category_id']?>>
                                                <?= $category_item['name'];?>

                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    <?php
                                    } else {
                                        echo "No Category Found";
                                    }
                                    ?>

                                        

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $post_row['name']?>" required class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Slug (URL)</label>
                                        <input type="text" name="slug" value="<?= $post_row['slug']?>" required class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Description</label>
                                        <textarea name="description" class="form-control" id="summernote" required rows="4"> <?= $post_row['description']?> </textarea>
                                    </div>
                                    
                                    <div class="col-md-12 mb-3">
                                        <label for="">Meta Title</label>
                                        <input type="text" name="meta_title" value="<?= $post_row['meta_title']?>" required max="191" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Meta Description</label>
                                        <textarea name="meta_description" max="191"  required class="form-control" rows="4"><?= $post_row['meta_description']?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Meta Keyword</label>
                                        <textarea name="meta_keyword" max="191" class="form-control" required rows="4"><?= $post_row['meta_keyword']?> </textarea>
                                    </div>
                                
                                    <div class="col-md-6 mb-3">
                                        <label for="">Image</label>
                                        <input type="hidden" name="old_image" value="<?= $post_row['image']?>">
                                        <input type="file" name="image" class="form-control">
                                    
                                        <div class="navbar navbar-light" style="background-color: #e3f2fd;">
                                        <label for="" >Current Image:</label>
                                        <img src="../uploads/posts/<?= $post_row['image']; ?>" width="60px" height="60px">
                                        </div>
                                    </div>

                                    <div class = "col-md-12 mb-3">
                                        <label for="">Status</label>
                                        <input type = "checkbox" name = "status"  <?= $post_row['status'] == '1' ? 'checked' : '' ?>width = "70px" height = "70px">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name = "post_update" class="btn btn-primary">Update Post</button>
                                    
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