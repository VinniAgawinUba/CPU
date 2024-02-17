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
                        <h4>View Inventory
                        <a href="inventory-add.php" class="btn btn-primary float-end">Add Inventory</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <table id="myInventory" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $inventory = "SELECT * FROM inventory";
                            $inventory_run = mysqli_query($con, $inventory);
                            if (mysqli_num_rows($inventory_run) > 0) {
                                foreach ($inventory_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['name']; ?></td>
                                        <td><?= $row['price']; ?></td>
                                        <td><?= $row['quantity']; ?></td>
                                        
                                        <td>
                                            <a href="inventory-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <form action="code.php" method="POST" id="deleteForm">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <button type="submit" name="inventory_delete_btn" value="<?=$row['id']?>" class="btn btn-danger deleteButton">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
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