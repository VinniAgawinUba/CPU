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

            <?php include('message.php'); ?>

            <div class="card">
                <div class="card-header">
                    <h4>Add Inventory
                        <a href="inventory-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">

                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">ID</label>
                                <input type="text" name="id" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" required class="form-control">
                            </div>
                            <!-- Add other project fields as needed -->

                            <div class="col-md-6 mb-3">
                                <label for="">Price</label>
                                <input type="text" name="price" required class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Quantity</label>
                                <input type="text" name="quantity" required class="form-control">
                            </div>

                            

                            <!-- Add other project fields as needed -->

                            <div class="col-md-12 mb-3">
                                <button type="submit" name="inventory_add_btn" class="btn btn-primary ">Add Inventory</button>
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
