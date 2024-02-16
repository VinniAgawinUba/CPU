<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Partners</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Partners</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Partner
                        <a href="partner-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <?php 
                    //Checks if the id is set in the URL
                    if(isset($_GET['id']))
                    {
                        $partner_id = $_GET['id'];
                        $partners = "SELECT * FROM partners WHERE id = '$partner_id'";
                        $partners_run = mysqli_query($con, $partners);

                        if(mysqli_num_rows($partners_run) > 0)
                        {
                            foreach($partners_run as $partner)
                            {  
                            ?>

                           

                        <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= $partner['id'];?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $partner['name'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Address</label>
                                    <input type="text" name="address" class="form-control" value="<?= $partner['address'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" value="<?= $partner['contact_person'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Designation</label>
                                    <input type="text" name="designation" class="form-control" value="<?= $partner['designation'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" value="<?= $partner['email'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" value="<?= $partner['contact_number'];?>">
                                </div>
                                
                               
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "update_partner" class="btn btn-primary">Update Partner</button>
                                
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