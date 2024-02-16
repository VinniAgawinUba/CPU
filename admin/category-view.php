<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Category</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Category View</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Category View
                        <a href="category-add.php" class="btn btn-primary float-end">Add Category</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <table id="myCategory" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM categories";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {
                                    foreach($query_run as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td>
                                                <?php
                                                if($row['status'] == 1)
                                                {
                                                    echo "Hidden";
                                                }
                                                else
                                                {
                                                    echo "Visible";
                                                }
                                                ?>
                                            </td>
                                            
                                            <td><?= $row['created_at']; ?></td>
                                            <td><a href="category-edit.php?id=<?=$row['id'];?>" class="btn btn-success">Edit</a></td>
                                            <td>
                                                <form action="code.php" method="POST">
                                                <button type="submit" name="category_delete" value="<?=$row['id'];?>" id="deleteButton" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
                                   ?>
                                   <tr>
                                    <td colspan="6">No Records Found</td>
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
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>