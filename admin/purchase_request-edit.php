<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
$unit_head = null;
//Check level
if ($_SESSION['auth_role'] == 1) {
    $admin = true;
    $super_user = false;
    $department_editor = false;
    $unit_head = false;
} elseif ($_SESSION['auth_role'] == 2) {
    $admin = false;
    $super_user = true;
    $department_editor = false;
    $unit_head = false;
} elseif ($_SESSION['auth_role'] == 3) {
    $admin = false;
    $super_user = false;
    $department_editor = true;
    $unit_head = false;
} elseif ($_SESSION['auth_role'] == 4) {
    $admin = false;
    $super_user = false;
    $department_editor = false;
    $unit_head = true;
}
//Query to get request details
$request_id = $_GET['id'];
//Item Status Choices
$status_choices = array('pending', 'approved', 'for_pricing', 'for_po', 'issued_po', 'for_delivery_by_supplier', 'for_pickup_at_supplier', 'for_tagging', 'for_delivery_to_requesting_unit', 'rejected', 'completed');

//Query to get request details
$request_query = "SELECT * FROM purchase_requests WHERE id = '$request_id' LIMIT 1";
$request_query_run = mysqli_query($con, $request_query);
if (mysqli_num_rows($request_query_run) > 0) {
    $request_row = mysqli_fetch_array($request_query_run);
}
?>



<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/jquery.signature.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
</link>

<!--Signature Styles-->
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

<div class="container mx-auto p-6 bg-blue-100 shadow-black">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 justify-centeritems-center">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT<a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a></h1>
    <form action="code.php" method="post">
        <!-- Hidden request_id -->
        <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
        <!-- Hidden user_name, user_id, user_email -->
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
        <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

        <!-- Hidden input to store the request requestor email-->
        <input type="hidden" name="requestor_email" value="<?= $request_row['requestor_user_email'] ?>">

        <?php if ($super_user) { ?>
            <!-- Checkbox To Change status to acknowledged-by-cpu (ONLY ADMIN AND SUPER USER) -->
            <div class="col-md-12 mb-3 bg-white">
                <label for="">Acknowledged by CPU</label>
                <input type="checkbox" name="acknowledged_by_cpu" <?= $request_row['acknowledged_by_cpu'] == '1' ? 'checked' : ''; ?> width="70px" height="70px">
            </div>
        <?php } ?>

        <?php if ($department_editor) { ?>
            <!-- Checkbox To Change status to acknowledged-by-cpu (ONLY ADMIN AND SUPER USER) -->
            <div class="col-md-12 mb-3 bg-white">
                <label for="">Mark as Above P50,000</label>
                <input type="checkbox" name="above_50000" <?= $request_row['above_50000'] == '1' ? 'checked' : ''; ?> width="70px" height="70px">
            </div>
        <?php } ?>



        <?php if ($super_user || $admin || $department_editor) { ?>
            <fieldset class="mb-4 bg-white shadow-md rounded p-4">
                <legend class="font-bold">Purchase Request</legend>
                <div class="mb-3">
                    <label for="purchase_request_number" class="form-label">PURCHASE REQUEST#:</label>
                    <input type="text" id="purchase_request_number" name="purchase_request_number" class="form-control" value="<?= $request_row['purchase_request_number'] ?>">
                </div>
            <?php } ?>




            </fieldset>

            <!-- ITEMS -->
            <fieldset class="mb-4 bg-white shadow-md rounded p-4">
                <legend class="font-bold">Items</legend>
                <p class="mb-2">Please include complete specifications/details or attach additional information on items.</p>
                <p class="mb-2">CPU may refuse to receive request without complete specifications or details.</p>

                <table class="table table-responsive table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:60px">ITEM#</th>
                            <th style="width:60px">QTY/UNIT</th>
                            <th style="width:550px">DESCRIPTION</th>
                            <th style="width:550px">JUSTIFICATION</th>
                            <th style="width:60px">ITEM STATUS</th>
                            <th style="width:80px">REMOVE</th> <!-- Empty header for remove button -->
                        </tr>
                    </thead>
                    <tbody id="itemRows">
                        <!-- Query Existing items from items table -->
                        <?php
                        $query = "SELECT * FROM items WHERE purchase_request_id = '$request_id'";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                $item_number = $row['item_number'];
                                $item_qty = $row['item_qty'];
                                $item_description = $row['item_description'];
                                $item_justification = $row['item_justification'];
                                $item_status = $row['item_status'];
                        ?>
                                <tr class="item-row mb-2">
                                    <td style="width:60px">
                                        <!-- ITEM# -->
                                        <input type="text" name="item_number[]" class="form-control" style="width:60px" value="<?php echo $item_number; ?>">
                                    </td>
                                    <td>
                                        <!-- QTY/UNIT -->
                                        <input type="text" name="item_qty[]" class="form-control" style="width:60px" value="<?php echo $item_qty; ?>" required>
                                    </td>
                                    <td>
                                        <!-- DESCRIPTION -->
                                        <textarea name="item_description[]" class="form-control" style="width:500px" required><?php echo $item_description; ?></textarea>
                                    </td>
                                    <td>
                                        <!-- JUSTIFICATION -->
                                        <textarea type="text" name="item_justification[]" class="form-control" style="width:500px" required><?php echo $item_justification; ?></textarea>
                                    </td>
                                    <td>
                                        <!-- ITEM STATUS -->
                                        <select name="item_status[]" class="form-control" style="width:60px" required>
                                            <?php
                                            foreach ($status_choices as $status) {
                                                if ($status == $item_status) {
                                                    echo "<option value='$status' selected>$status</option>";
                                                } else {
                                                    echo "<option value='$status'>$status</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <!-- Remove Button -->
                                        <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr class="item-row mb-2">
                                <td style="width:60px">
                                    <!-- ITEM# -->
                                    <input type="text" name="item_number[]" class="form-control" style="width:60px" value="1">
                                </td>
                                <td>
                                    <!-- QTY/UNIT -->
                                    <input type="text" name="item_qty[]" class="form-control" style="width:60px" required>
                                </td>
                                <td>
                                    <!-- DESCRIPTION -->
                                    <textarea name="item_description[]" class="form-control" style="width:500px" required></textarea>
                                </td>
                                <td>
                                    <!-- JUSTIFICATION -->
                                    <textarea type="text" name="item_justification[]" class="form-control" style="width:500px" required> </textarea>
                                </td>
                                <td>
                                    <!-- ITEM STATUS -->
                                    <select name="item_status[]" class="form-control" style="width:60px" required>
                                        <?php
                                        foreach ($status_choices as $status) {
                                            echo "<option value='$status'>$status</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <!-- Remove Button -->
                                    <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <!-- Dynamically generated rows will be added here -->
                    </tbody>
                </table>

                <button type="button" class="btn btn-primary btn-add-item">Add Item</button>
            </fieldset>

            <!-- Purchase Request Attachments -->
            <?php
            // Query to get purchase request attachments based on the purchase_request_id
            $attachments_query = "SELECT * FROM purchase_requests_attachments WHERE purchase_request_id = '$request_id'";
            $attachments_query_run = mysqli_query($con, $attachments_query);

            // Check if there are attachments available for this purchase request
            if (mysqli_num_rows($attachments_query_run) > 0) {
                // Display the attachments
                echo '<fieldset class="mb-4 bg-white shadow-md rounded p-4">';
                echo '<legend class="font-bold">Attachments</legend>';
                echo '<div class="mb-3">';
                // Loop through each attachment
                while ($attachment_row = mysqli_fetch_array($attachments_query_run)) {
                    // Display attachment details (e.g., file name, download link)
                    echo '<p>Attachments: <a href="../uploads/request_documents/' . $attachment_row['file_name'] . '" download>' . $attachment_row['file_name'] . '</a></p>';
                }
                echo '</div>';
                echo '</fieldset>';
            }
            ?>



            <!-- Requestor Information -->
            <fieldset class="mb-4 bg-white shadow-md rounded p-4">
                <legend class="font-bold">Requestor Information</legend>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Cluster:</td>
                            <td>
                            <select id="cluster" name="cluster" class="form-control" required>
                                <option>--Select Cluster--</option>
                                <option value="Academic" <?=$request_row['cluster'] == 'Academic' ? 'selected' : '' ?>>Academic</option>
                                <option value="Administrative" <?=$request_row['cluster'] == 'Administrative' ? 'selected' : '' ?>>Administrative</option>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Unit/Dept:</td>
                            <td>
                                <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Unit/Dept" value="<?= $request_row['unit_dept_college'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Requested by:</td>
                            <td>
                                <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name" value="<?= $request_row['printed_name'] ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Approved by: (Unit Head)</td>
                            <td>
                                <input type="text" id="unit_head_approval_by" name="unit_head_approval_by" class="form-control" required placeholder="Unit Head Name" value="<?= isset($request_row['unit_head_approval_by']) ? $request_row['unit_head_approval_by'] : $_SESSION['auth_user']['user_name']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Unit Head Approval:</td>
                            <td>
                                <?php if ($unit_head) { ?>
                                    <!-- Dropdown To Change unit_head_approval to either pending, recommending-approval, rejected (ONLY UNIT HEAD) -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="unit_head_approval" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['unit_head_approval'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['unit_head_approval'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['unit_head_approval'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td>IPTel#/E-mail Address:</td>
                            <td>
                                <input type="text" id="iptel_email" name="iptel_email" class="form-control" required value="<?= $request_row['iptel_email'] ?>">
                            </td>
                        </tr>

                    </tbody>
                </table>
            </fieldset>



            <!--AT THIS POINT ONLY SUPER USERS AND DEPARTMENT EDITORS CAN VIEW THE SIGNATURES-->
            <?php if ($super_user || $department_editor) { ?>

                <!-- Signatures for Approvals -->
                <fieldset class="mb-4 bg-white shadow-md rounded p-4">
                    <legend class="font-bold">Approvals</legend>
                    <table class="table table-bordered">
                        <tbody>
                            <!-- Vice President -->
                            <tr>
                                <td>1-Cluster Vice President (if above P50,000):</td>
                                <td>
                                    <input type="text" id="vice_president_remarks" name="vice_president_remarks" class="form-control" placeholder="Remarks" value="<?= $request_row['vice_president_remarks'] ?>">
                                    <br>
                                    <input type="text" id="vice_president_approved" name="vice_president_approved" class="form-control" placeholder="Approved By" value="<?= $request_row['vice_president_approved'] ?>">
                                    <br>
                                    <!-- Dropdown To Change to either pending, recommending-approval, rejected -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="signed_1" class="status_dropdown" data-signed-field="signed_1" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['signed_1'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['signed_1'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['signed_1'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <!-- Vice President for Administration -->
                            <tr>
                                <td>2-VICE PRESIDENT FOR ADMINISTRATION:</td>
                                <td>
                                    <input type="text" id="vice_president_administration_remarks" name="vice_president_administration_remarks" class="form-control" placeholder="Remarks" value="<?= $request_row['vice_president_administration_remarks'] ?>">
                                    <br>
                                    <input type="text" id="vice_president_administration_approved" name="vice_president_administration_approved" class="form-control" placeholder="Approved By" value="<?= $request_row['vice_president_administration_approved'] ?>">
                                    <br>
                                    <!-- Dropdown To Change to either pending, recommending-approval, rejected -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="signed_2" class="status_dropdown" data-signed-field="signed_2" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['signed_2'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['signed_2'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['signed_2'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <!-- Budget Controller -->
                            <tr>
                                <td>3-BUDGET CONTROLLER:</td>
                                <td>
                                    <input type="text" id="budget_controller_remarks" name="budget_controller_remarks" class="form-control" placeholder="Remarks" value="<?= $request_row['budget_controller_remarks'] ?>">
                                    <br>
                                    <input type="text" id="budget_controller_approved" name="budget_controller_approved" class="form-control" placeholder="Approved By" value="<?= $request_row['budget_controller_approved'] ?>">
                                    <br>
                                    <input type="text" id="budget_controller_code" name="budget_controller_code" class="form-control" placeholder="Acct. Code" value="<?= $request_row['budget_controller_code'] ?>">
                                    <br>
                                    <!-- Dropdown To Change to either pending, recommending-approval, rejected -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="signed_3" class="status_dropdown" data-signed-field="signed_3" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['signed_3'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['signed_3'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['signed_3'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <!-- University Treasurer -->
                            <tr>
                                <td>4-UNIVERSITY TREASURER:</td>
                                <td>
                                    <input type="text" id="university_treasurer_remarks" name="university_treasurer_remarks" class="form-control" placeholder="Remarks" value="<?= $request_row['university_treasurer_remarks'] ?>">
                                    <br>
                                    <input type="text" id="university_treasurer_approved" name="university_treasurer_approved" class="form-control" placeholder="Approved By" value="<?= $request_row['university_treasurer_approved'] ?>">
                                    <br>
                                    <!-- Dropdown To Change to either pending, recommending-approval, rejected -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="signed_4" class="status_dropdown" data-signed-field="signed_4" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['signed_4'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['signed_4'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['signed_4'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <!-- OFFICE OF THE PRESIDENT -->
                            <tr>
                                <td>5-OFFICE OF THE PRESIDENT (for budget re-alignment only):</td>
                                <td>
                                    <input type="text" id="office_of_the_president_remarks" name="office_of_the_president_remarks" class="form-control" placeholder="Remarks" value="<?= $request_row['office_of_the_president_remarks'] ?>">
                                    <br>
                                    <input type="text" id="office_of_the_president_approved" name="office_of_the_president_approved" class="form-control" placeholder="Approved By" value="<?= $request_row['office_of_the_president_approved'] ?>">
                                    <br>
                                    <!-- Dropdown To Change to either pending, recommending-approval, rejected -->
                                    <div class="col-md-12 mb-3 bg-white">
                                        <select name="signed_5" class="status_dropdown" data-signed-field="signed_5" style="border: 2px solid;">
                                            <option value="pending" <?= $request_row['signed_5'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="approved" <?= $request_row['signed_5'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                            <option value="rejected" <?= $request_row['signed_5'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                    </div>

                                    <script>
                                        $(document).ready(function() {
                                            $('.status_dropdown').on('change', function() {
                                                var newStatus = $(this).val();
                                                var signedField = $(this).data('signed-field');
                                                var SignedFieldBy = signedField + '_by';
                                                var Request_id = '<?php echo $request_id; ?>';

                                                // AJAX request
                                                $.ajax({
                                                    url: 'javascript-update_approval_status.php',
                                                    method: 'POST',
                                                    data: {
                                                        current_user_email: '<?php echo $_SESSION['auth_user']['user_email']; ?>',
                                                        new_status: newStatus,
                                                        signed_field: signedField, // Pass the signed field identifier
                                                        signed_field_by: SignedFieldBy, // Pass the signed by identifier
                                                        request_id: Request_id
                                                    },
                                                    success: function(response) {
                                                        // Update UI or show success message
                                                        console.log(response);
                                                    },
                                                    error: function(xhr, status, error) {
                                                        // Handle errors
                                                        console.error(error);
                                                    }
                                                });
                                            });
                                        });
                                    </script>


                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

            <?php } ?>
            <button type="submit" name="request_update_btn_front" class="btn btn-primary">Update Request Details</button>
    </form>
</div>

<div>

    <!-- ONLY SHOW FIELDSET SUPER USER, ADMIN, OR DEPARTMENT EDITOR-->
    <?php if ($super_user || $admin || $department_editor) { ?>
        <!-- FIELDSET REJECTION, APPROVAL, COMPLETION SECTION-->
        <fieldset class="mb-4 bg-white shadow-md rounded p-4">
            <legend class="font-bold">REJECTION, APPROVAL, COMPLETION SECTION</legend>
            <select id="status-select" name="status">
                <!--If super user or admin, display rejected, approved, completed-->
                <?php if ($super_user || $admin) { ?>
                    <option>--SELECT STATUS--</option>
                    <option value="rejected" <?php if ($request_row['status'] == 'rejected') {
                                                    echo 'selected';
                                                } ?>>Rejected</option>
                    <option value="approved" <?php if ($request_row['status'] == 'approved') {
                                                    echo 'selected';
                                                } ?>>Approved</option>
                    <option value="completed" <?php if ($request_row['status'] == 'completed') {
                                                    echo 'selected';
                                                } ?>>Completed</option>
                <?php } ?>

                <!--If department_editor, display rejected-->
                <?php if ($department_editor) { ?>
                    <option>--SELECT STATUS--</option>
                    <option value="rejected" <?php if ($request_row['status'] == 'rejected') {
                                                    echo 'selected';
                                                } ?>>Rejected</option>
                <?php } ?>
            </select>
        </fieldset>
    <?php } ?>

    <!-- DYNAMIC UPDATE JS STATUS -->
    <script>
        document.getElementById('status-select').addEventListener('change', function() {
            var status = this.value;
            var requestId = <?php echo $request_row['id']; ?>; // Assuming you have the request ID available

            // Make an AJAX request to update the status
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'javscript-update_status.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Handle response if needed
                    console.log(xhr.responseText);
                }
            };
            xhr.send('status=' + encodeURIComponent(status) + '&request_id=' + encodeURIComponent(requestId));
        });
    </script>




    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const itemRows = document.getElementById('itemRows');
            const addItemButton = document.querySelector('.btn-add-item');

            <?php
            // Get Biggest item number by Quering the items table with the largest item number
            $query = "SELECT * FROM items WHERE purchase_request_id = '$request_id'";
            $query_run = mysqli_query($con, $query);
            if (mysqli_num_rows($query_run) > 0) {
                // Get the largest item number
                $item_number = mysqli_num_rows($query_run) + 1;
            } else {
                $item_number = 1;
            }
            ?>
            itemNumber = <?php echo $item_number; ?>;


            addItemButton.addEventListener('click', function() {
                const itemRow = document.createElement('tr');
                itemRow.classList.add('item-row', 'mb-2');
                itemRow.innerHTML = `
            <td style="width:60px">
                <!-- ITEM# -->
                <input type="text" name="item_number[]" class="form-control" style="width:60px" value="${itemNumber}">
            </td>
            <td>
                <!-- QTY/UNIT -->
                <input type="text" name="item_qty[]" class="form-control" style="width:60px" required>
            </td>
            <td>
                <!-- DESCRIPTION -->
                <textarea name="item_description[]" class="form-control" style="width:500px" required></textarea>
            </td>
            <td>
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" style="width:500px" required> </textarea>
            </td>
            <td>
                <!-- ITEM STATUS -->
                <select name="item_status[]" class="form-control" style="width:60px" required> 
                      <option value='pending'>pending</option>;
                </select>

            </td>
            <td>
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
            </td>
        `;
                itemRows.appendChild(itemRow);
                itemNumber++; // Increment item number
            });


            itemRows.addEventListener('click', function(event) {
                if (event.target.classList.contains('btn-remove-item')) {
                    event.target.closest('.item-row').remove();
                    itemNumber--; // Decrement item number
                }
            });



        });
    </script>



    <?php
    include('includes/footer.php');

    ?>