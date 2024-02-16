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
                        <h4>Add Posts
                        <a href="post-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                    
                    <form action="code.php" method="POST" enctype="multipart/form-data">
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
                                        foreach($category_query_run as $category_list) {
                                        ?>
                                            <option value="<?=$category_list['id']; ?>"> <?=$category_list['name']; ?> </option>
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
                                    <input type="text" name="name" required class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Slug (URL)</label>
                                    <input type="text" name="slug" required class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="">Description</label>
                                    <textarea name="description" class="form-control" id="summernote" required rows="4"> </textarea>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="">Meta Title</label>
                                    <input type="text" name="meta_title" required max="191" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Meta Description</label>
                                    <textarea name="meta_description" max="191" required class="form-control" rows="4"> </textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Meta Keyword</label>
                                    <textarea name="meta_keyword" max="191" class="form-control" required rows="4"> </textarea>
                                </div>
                               
                                <div class="col-md-6 mb-3">
                                    <label for="">Image</label>
                                    <input type="file" name="image" required class="form-control">
                                </div>

                                <div class = "col-md-12 mb-3">
                                    <label for="">Status</label>
                                    <input type = "checkbox" name = "status"  width = "70px" height = "70px">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "post_add_btn" class="btn btn-primary">Save Post</button>
                                
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