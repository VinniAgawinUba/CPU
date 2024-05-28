<?php
ob_start();
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('config/dbcon.php');
include('authentication.php');
ob_end_flush();
?>

<!-- Include the jQuery and jQuery UI libraries so that Signatures work -->
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<link type="text/css" href="assets/css/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="assets/js/bootstrap5.bundle.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css">

<!-- Signature Styles -->
<style>
    .kbw-signature {
        width: 800px;
        height: 200px;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }
</style>

<!-- Horizontal Rule Line -->
<hr class="mx-auto my-4" style="width:80%; color:#00478a; background:#429ef5; height: 3px;">

<div class="container my-4">
    <?php include('message.php'); ?>
    <form action="allcode.php" method="post" enctype="multipart/form-data">
        <!-- Hidden user_name, user_id, user_email -->
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
        <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">


        <!-- Items -->
        <fieldset class="mb-4 p-4 border rounded bg-light shadow-sm">
            <legend class="font-weight-bold">Items</legend>
            <p>Please include complete specifications/details or attach additional information on items. CPU may refuse to receive request without complete specifications or details.</p>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ITEM#</th>
                        <th>QTY/UNIT</th>
                        <th>DESCRIPTION</th>
                        <th>JUSTIFICATION</th>
                        <th>REMOVE</th>
                        <th>ATTACHMENT</th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                    <!-- Dynamically generated rows will be added here -->
                </tbody>
            </table>
            <button type="button" class="btn btn-primary btn-add-item bg-xu-blue">Add Item</button>
        </fieldset>

        <!-- Requestor Information -->
        <fieldset class="mb-4 p-4 border rounded bg-light shadow-sm">
            <legend class="font-weight-bold">Requestor Information</legend>
            <div class="form-group row py-3">
                <label for="cluster" class="col-sm-2 col-form-label">Cluster:</label>
                <div class="col-sm-10">
                    <select id="cluster" name="cluster" class="form-control" required>
                        <option>--Select Cluster--</option>
                        <option value="Administration">Administration</option>
                        <option value="Higher Education">Higher Education</option>
                        <option value="Basic Education">Basic Education</option>
                        <option value="Office of Mission & Ministry">Office of Mission & Ministry</option>
                        <option value="Social Development">Social Development</option>
                    </select>
                </div>
            </div>
            <div class="form-group row py-3">
                <label for="cluster_vp" class="col-sm-2 col-form-label">Cluster Vice President:</label>
                <div class="col-sm-10">
                    <select id="cluster_vp" name="cluster_vp" class="form-control" required>
                        <option value="">--Select Cluster Vice President--</option>
                        <?php
                        $sql = "SELECT * FROM users WHERE role_as = '7'";
                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $selected = ($request_row['cluster_vp'] == $row['id']) ? 'selected' : '';
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>



            <div class="form-group row py-3">
                <label for="unit_dept_college" class="col-sm-2 col-form-label">Unit/Dept:</label>
                <div class="col-sm-10">
                    <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Unit/Dept">
                </div>
            </div>
            <div class="form-group row py-3">
                <label for="printed_name" class="col-sm-2 col-form-label">Requested by:</label>
                <div class="col-sm-10">
                    <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
                </div>
            </div>
            <div class="form-group row py-3">
                <label for="unit_head" class="col-sm-2 col-form-label">Unit Head:</label>
                <div class="col-sm-10">
                    <select id="unit_head" name="unit_head" class="form-control" required>
                        <option value="">--Select Unit Head--</option>
                        <?php
                        $sql = "SELECT * FROM users WHERE role_as = '4'";
                        $result = mysqli_query($con, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['id'] . '">' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row py-3">
                <label for="iptel_email" class="col-sm-2 col-form-label">IPTel#/E-mail Address:</label>
                <div class="col-sm-10">
                    <input type="text" readonly id="iptel_email" name="iptel_email" class="form-control" required value="<?php echo $_SESSION['auth_user']['user_email']; ?>">
                </div>
            </div>
        </fieldset>

        <button type="submit" name="request_add_btn_front" class="btn btn-primary btn-add-item bg-xu-blue">Submit Request</button>
    </form>
</div>

<script>
    jQuery.noConflict();
    document.addEventListener("DOMContentLoaded", function() {
        const itemRows = document.getElementById('itemRows');
        const addItemButton = document.querySelector('.btn-add-item');
        let itemNumber = 1;

        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('tr');
            itemRow.innerHTML = `
                <td><input type="text" name="item_number[]" class="form-control" readonly value="${itemNumber}"></td>
                <td><input type="number" min="1" name="item_qty[]" class="form-control" required></td>
                <td><textarea name="item_description[]" class="form-control" required maxlength="500"></textarea></td>
                <td><textarea name="item_justification[]" class="form-control" required maxlength="500"></textarea></td>
                <td><button type="button" class="btn btn-danger btn-remove-item bg-red-500">Remove</button></td>
                <td><input type="file" name="request_documents[]" multiple class="form-control"></td>
            `;
            itemRows.appendChild(itemRow);
            itemNumber++;
        });

        itemRows.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove-item')) {
                event.target.closest('tr').remove();
                itemNumber--;
            }
        });

        jQuery(document).ready(function() {
            var sigRequestor = jQuery('#sigRequestor').signature({
                syncField: '#signature64_Requestor',
                syncFormat: 'PNG'
            });
            jQuery('#clearRequestor').click(function(e) {
                e.preventDefault();
                sigRequestor.signature('clear');
                jQuery("#signature64_Requestor").val('');
            });
        });

        function validateForm() {
            var itemQtyInputs = document.querySelectorAll('input[name="item_qty[]"]');
            for (var i = 0; i < itemQtyInputs.length; i++) {
                if (!itemQtyInputs[i].value.match(/^\d+$/)) {
                    alert('Quantity must be a number.');
                    return false;
                }
            }
            return true;
        }

        document.querySelector('form').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });

        const descriptionInputs = document.querySelectorAll('textarea[name="item_description[]"]');
        const justificationInputs = document.querySelectorAll('textarea[name="item_justification[]"]');

        descriptionInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.length > 500) {
                    this.value = this.value.slice(0, 500);
                    alert('Description exceeds maximum character limit (500 characters).');
                }
            });
        });

        justificationInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.length > 500) {
                    this.value = this.value.slice(0, 500);
                    alert('Justification exceeds maximum character limit (500 characters).');
                }
            });
        });

    });
</script>

<!-- Hardcoded footer to avoid multiple jQuery conflicts -->
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>About Xavier University's Central Procurement Unit</h2>
                <p>The Central Procurement Unit (CPU) at Xavier University plays a crucial role in managing the acquisition of goods and services for various departments and units within the university. As a central entity responsible for procurement activities, the CPU ensures compliance with regulations, maximizes cost-effectiveness, and maintains transparency in purchasing processes.</p>
            </div>
            <div class="col-md-3">
                <h2>Help Desk</h2>
                <p>For assistance, contact:</p>
                <p><i class="fas fa-envelope"></i> ciso@xu.edu.ph</p>
                <p><i class="fas fa-phone-alt"></i> (088) 853-9800</p>
            </div>
        </div>
        <div class="text-center mt-3">
            <p>© <?php echo date('Y'); ?> Xavier University - Ateneo de Cagayan Corrales Avenue, Cagayan de Oro City, Philippines.</p>
        </div>
    </div>
</footer>