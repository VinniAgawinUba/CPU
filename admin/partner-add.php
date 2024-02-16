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
                
            <?php include('message.php');?>

                <div class="card">
                    <div class="card-header">
                        <h4>Add Partners
                        <a href="partner-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                    
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Name</label>
                                    <input type="text" name="name" required class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Address</label>
                                    <input type="text" name="address" required class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Contact Person</label>
                                    <input type="text" name="contact_person" required max="191" class="form-control">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">Designation</label>
                                    <input type="text" name="meta_title" required max="191" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="text" name="meta_description" max="191" required class="form-control" rows="4"> </input>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Contact Number</label>
                                    <input type="text" name="meta_keyword" max="191" class="form-control" required rows="4"> </input>
                                </div>
                               

                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "partner_add_btn" class="btn btn-primary">Save Partner</button>
                                
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