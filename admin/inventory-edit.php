<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Inventory</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Inventory</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                
            <?php include('message.php');?>

                <div class="card">
                    <div class="card-header">
                        <h4>Edit Inventory
                        <a href="inventory-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $inventory_id = $_GET['id'];
                        $inventory_query = "SELECT * FROM inventory WHERE id = '$inventory_id' LIMIT 1";
                        $inventory_query_run = mysqli_query($con, $inventory_query);
                        if(mysqli_num_rows($inventory_query_run) > 0) 
                        {
                            $inventory_row = mysqli_fetch_array($inventory_query_run);
                        ?>
                       
                    
                       <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <input type="hidden" name="inventory_id" value="<?= $inventory_row['id']?>">
                            <div class="col-md-6 mb-3">
                                <label for="">ID</label>
                                <input type="text" name="id" required class="form-control" value="<?= $inventory_row['id']?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" required class="form-control" value="<?= $inventory_row['name']?>">
                            </div>
                            <!-- Add other project fields as needed -->

                            <div class="col-md-6 mb-3">
                                <label for="">Price</label>
                                <input type="text" name="price" class="form-control" required value="<?= $inventory_row['price']?>"></input>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Quantity</label>
                                <input type="text" name="quantity" required class="form-control" value="<?= $inventory_row['quantity']?>">
                            </div>

                            



                            <!-- Add other project fields as needed -->

                            <div class="col-md-12 mb-3">
                                <button type="submit" name="inventory_edit_btn" class="btn btn-primary">Edit Inventory</button>
                            </div>

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