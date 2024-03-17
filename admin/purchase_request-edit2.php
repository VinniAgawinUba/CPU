<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
}
//Query to get request details
$request_id = $_GET['id'];
//Item Status Choices
$status_choices = array('pending', 'approved', 'rejected', 'completed');

//Query to get request details
$request_query = "SELECT * FROM purchase_requests WHERE id = '$request_id' LIMIT 1";
$request_query_run = mysqli_query($con, $request_query);
if(mysqli_num_rows($request_query_run) > 0) 
{
    $request_row = mysqli_fetch_array($request_query_run);

   

}
?>



  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script type="text/javascript" src="js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/jquery.signature.css"></link>

  <!--Signature Styles-->
  <style>
    .kbw-signature { width: 800px; height: 200px; }
    #sig canvas { width: 100% !important; height: auto; }
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

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Purchase Request</legend>
        <div class="mb-3">
          <label for="purchase_request_number" class="form-label">PURCHASE REQUEST#:</label>
          <input type="text" id="purchase_request_number" name="purchase_request_number" class="form-control" required value="<?=$request_row['purchase_request_number']?>">
        </div>
        
        
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
                        <textarea type="text" name="item_justification[]" class="form-control" style="width:500px" required><?php echo $item_justification; ?> </textarea>
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


      <!-- Requestor Information -->
<fieldset class="mb-4 bg-white shadow-md rounded p-4">
    <legend class="font-bold">Requestor Information</legend>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Unit/Dept:</td>
                <td>
                    <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Unit/Dept" value="<?=$request_row['unit_dept_college']?>">
                </td>
            </tr>
            <tr>
                <td>Requested by:</td>
                <td>
                    <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name" value="<?=$request_row['printed_name']?>">
                </td>
            </tr>
            <tr>
                <td>Approved by: (Unit Head)</td>
                <td>
                    <input type="text" id="endorsed_by_dean" name="endorsed_by_dean" class="form-control" required placeholder="Unit Head Name" value="<?=$request_row['endorsed_by_dean']?>">
                </td>
            </tr>
            <tr>
                <td>Unit Head Signature:</td>
                <td>
                <?php
        //if the requestor has already signed, display the signature else display the signature pad
        if($request_row['signed_Requestor'] != '')
        {
          //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
          echo '<input type="hidden" name="signed_Requestor" value="'.$request_row['signed_Requestor'].'">';
            echo '<div class="mb-3 kbw-signature">
            <label for="signed_Requestor">Signature:</label>
            <img src="../uploads/signatures/'.$request_row['signed_Requestor'].'" alt="signature" class="img-fluid">
            </div>';
            //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
            echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_Requestor">Delete Signature</button>';
        }
        else
        {
            echo '<div class="mb-3">
            <div id="sigRequestor" class="kbw-signature"></div>
            <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_Requestor" name="signed_Requestor" style="display:none"></textarea>
            </div>';
        }
        ?>
                </td>
            </tr>
            
            <tr>
                <td>IPTel#/E-mail Address:</td>
                <td>
                    <input type="text" id="iptel_email" name="iptel_email" class="form-control" required value="<?=$request_row['iptel_email']?>">
                </td>
            </tr>
            
        </tbody>
    </table>
</fieldset>



<!-- Signatures for Approvals -->
<fieldset class="mb-4 bg-white shadow-md rounded p-4">
    <legend class="font-bold">Approvals</legend>
    <table class="table table-bordered">
        <tbody>
            <!-- Vice President -->
            <tr>
                <td>1-Cluster Vice President (if above P50,000):</td>
                <td>
                    <input type="text" id="vice_president_remarks" name="vice_president_remarks" class="form-control" placeholder="Remarks" value="<?=$request_row['vice_president_remarks']?>">
                    <br>
                    <input type="text" id="vice_president_approved" name="vice_president_approved" class="form-control" placeholder="Approved By" value="<?=$request_row['vice_president_approved']?>">
                    <br>
                    <?php
          //if the vice president has already signed, display the signature else display the signature pad
            if($request_row['signed_1'] != '')
            {
               //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
                echo '<input type="hidden" name="signed_1" value="'.$request_row['signed_1'].'">';
                echo '<div class="mb-3 kbw-signature">
                <label for="signed_1">Signature:</label>
                <img src="../uploads/signatures/'.$request_row['signed_1'].'" alt="signature" class="img-fluid">
                </div>';
                //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
                echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_1">Delete Signature</button>';
            }
            else
            {
                echo '<div class="mb-3">
                <div id="sig1" class="kbw-signature"></div>
                <button id="clear1" class="btn btn-primary">Clear Signature</button>
                <textarea id="signature64_1" name="signed_1" style="display:none"></textarea>
                </div>';
            }
          ?>
                </td>
            </tr>
            <!-- Vice President for Administration -->
            <tr>
                <td>2-VICE PRESIDENT FOR ADMINISTRATION:</td>
                <td>
                    <input type="text" id="vice_president_administration_remarks" name="vice_president_administration_remarks" class="form-control" placeholder="Remarks" value="<?=$request_row['vice_president_administration_remarks']?>">
                    <br>
                    <input type="text" id="vice_president_administration_approved" name="vice_president_administration_approved" class="form-control" placeholder="Approved By" value="<?=$request_row['vice_president_administration_approved']?>">
                    <br>
                    <?php
            //if the vice president for administration has already signed, display the signature else display the signature pad
            if($request_row['signed_2'] != '')
            {
               //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
                echo '<input type="hidden" name="signed_2" value="'.$request_row['signed_2'].'">';
                echo '<div class="mb-3 kbw-signature">
                <label for="signed_2">Signature:</label>
                <img src="../uploads/signatures/'.$request_row['signed_2'].'" alt="signature" class="img-fluid">
                </div>';
                //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
                echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_2">Delete Signature</button>';
            }
            else
            {
                echo '<div class="mb-3"> 
                <div id="sig2" class="kbw-signature"></div>
                <button id="clear2" class="btn btn-primary">Clear Signature</button>
                <textarea id="signature64_2" name="signed_2" style="display:none"></textarea>
                </div>';
            }
            ?>
                </td>
            </tr>
            <!-- Budget Controller -->
            <tr>
                <td>3-BUDGET CONTROLLER:</td>
                <td>
                    <input type="text" id="budget_controller_remarks" name="budget_controller_remarks" class="form-control" placeholder="Remarks" value="<?=$request_row['budget_controller_remarks']?>">
                    <br>
                    <input type="text" id="budget_controller_approved" name="budget_controller_approved" class="form-control" placeholder="Approved By" value="<?=$request_row['budget_controller_approved']?>">
                    <br>
                    <input type="text" id="budget_controller_code" name="budget_controller_code" class="form-control" placeholder="Acct. Code" value="<?=$request_row['budget_controller_code']?>">
                    <br>
                    <?php
            //if the budget controller has already signed, display the signature else display the signature pad
                if($request_row['signed_3'] != '')
                {
                   //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
                    echo '<input type="hidden" name="signed_3" value="'.$request_row['signed_3'].'">';
                    echo '<div class="mb-3 kbw-signature">
                    <label for="signed_3">Signature:</label>
                    <img src="../uploads/signatures/'.$request_row['signed_3'].'" alt="signature" class="img-fluid">
                    </div>';
                    //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
                    echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_3">Delete Signature</button>';
                }
                else
                {
                    echo '<div class="mb-3">
                    <div id="sig3" class="kbw-signature"></div>
                    <button id="clear3" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_3" name="signed_3" style="display:none"></textarea>
                    </div>';
                }
            ?>
                </td>
            </tr>
            <!-- University Treasurer -->
            <tr>
                <td>4-UNIVERSITY TREASURER:</td>
                <td>
                    <input type="text" id="university_treasurer_remarks" name="university_treasurer_remarks" class="form-control" placeholder="Remarks" value="<?=$request_row['university_treasurer_remarks']?>">
                    <br>
                    <input type="text" id="university_treasurer_approved" name="university_treasurer_approved" class="form-control" placeholder="Approved By" value="<?=$request_row['university_treasurer_approved']?>">
                    <br>
                    <?php
            //if the university treasurer has already signed, display the signature else display the signature pad
                if($request_row['signed_4'] != '')
                {
                   //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
                    echo '<input type="hidden" name="signed_4" value="'.$request_row['signed_4'].'">';
                    echo '<div class="mb-3 kbw-signature">
                    <label for="signed_4">Signature:</label>
                    <img src="../uploads/signatures/'.$request_row['signed_4'].'" alt="signature" class="img-fluid">
                    </div>';
                    //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
                    echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_4">Delete Signature</button>';
                }
                else
                {
                    echo '<div class="mb-3">
                    <div id="sig4" class="kbw-signature"></div>
                    <button id="clear4" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_4" name="signed_4" style="display:none"></textarea>
                    </div>';
                }
            ?>
                </td>
            </tr>
            <!-- OFFICE OF THE PRESIDENT -->
            <tr>
                <td>5-OFFICE OF THE PRESIDENT (for budget re-alignment only):</td>
                <td>
                    <input type="text" id="office_of_the_president_remarks" name="office_of_the_president_remarks" class="form-control" placeholder="Remarks" value="<?=$request_row['office_of_the_president_remarks']?>">
                    <br>
                    <input type="text" id="office_of_the_president_approved" name="office_of_the_president_approved" class="form-control" placeholder="Approved By" value="<?=$request_row['office_of_the_president_approved']?>">
                    <br>
                    <?php
            //if the office of the president has already signed, display the signature else display the signature pad
                if($request_row['signed_5'] != '')
                {
                   //hidden input to store the signature (DO NOT DELETE IF YOU DO, WHEN UPDATING FORMS, THE SIGNATURE WILL BE OVERWRITTEN TO BLANK)
                    echo '<input type="hidden" name="signed_5" value="'.$request_row['signed_5'].'">';
                    echo '<div class="mb-3 kbw-signature">
                    <label for="signed_5">Signature:</label>
                    <img src="../uploads/signatures/'.$request_row['signed_5'].'" alt="signature" class="img-fluid">
                    </div>';
                    //DELETE BUTTON TO CLEAR SIGNATURE FROM DATABASE
                    echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_5">Delete Signature</button>';
                }
                else
                {
                    echo '<div class="mb-3">
                    <div id="sig5" class="kbw-signature"></div>
                    <button id="clear5" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_5" name="signed_5" style="display:none"></textarea>
                    </div>';
                }
            ?>
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>


      <button type="submit" name="request_update_btn_front" class="btn btn-primary">Update Request Details</button>
    </form>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
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


      addItemButton.addEventListener('click', function () {
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


      itemRows.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove-item')) {
          event.target.closest('.item-row').remove();
          itemNumber--; // Decrement item number
        }
      });

      // Signatures Script (Add var sig per Approval person and button id per approval person)
      var sig1 = $('#sig1').signature({syncField: '#signature64_1', syncFormat:'PNG'});
      $('#clear1').click(function(e){
        e.preventDefault();
        sig1.signature('clear');
        $("#signature64_1").val('');
      });

      var sig2 = $('#sig2').signature({syncField: '#signature64_2', syncFormat:'PNG'});
      $('#clear2').click(function(e){
        e.preventDefault();
        sig2.signature('clear');
        $("#signature64_2").val('');
      });

    var sig3 = $('#sig3').signature({syncField: '#signature64_3', syncFormat:'PNG'});
    $('#clear3').click(function(e){
        e.preventDefault();
        sig3.signature('clear');
        $("#signature64_3").val('');
        });

    var sig4 = $('#sig4').signature({syncField: '#signature64_4', syncFormat:'PNG'});
    $('#clear4').click(function(e){
        e.preventDefault();
        sig4.signature('clear');
        $("#signature64_4").val('');
        });

    var sig5 = $('#sig5').signature({syncField: '#signature64_5', syncFormat:'PNG'});
    $('#clear5').click(function(e){
        e.preventDefault();
        sig5.signature('clear');
        $("#signature64_5").val('');
        });

    var sigRequestor = $('#sigRequestor').signature({syncField: '#signature64_Requestor', syncFormat:'PNG'});
    $('#clearRequestor').click(function(e){
        e.preventDefault();
        sigRequestor.signature('clear');
        $("#signature64_Requestor").val('');
        });

      // Add more signature scripts as needed

    });
  </script>

  <!-- Delete Signature Script -->
<script>
$(document).ready(function(){
    $(".delete-signature-btn").click(function(){
        var signatureField = $(this).data("signature-field");
        // Ask for confirmation before deleting
        if (confirm("Are you sure you want to delete this signature?")) {
            // AJAX call to delete the signature from the database
            $.ajax({
                url: "delete_signature.php",
                type: "POST",
                data: { signatureField: signatureField , request_id: <?=$request_id?>},
                success: function(response) {
                    // Handle success
                    if (response === "success") {
                        // Remove signature from DOM
                        $("input[name='" + signatureField + "']").val("");
                        $("img[src='../uploads/signatures/" + signatureField + "']").remove();
                        $(".delete-signature-btn[data-signature-field='" + signatureField + "']").remove();
                        alert("Signature deleted successfully.");
                        // Reload the page
                        window.location.reload();
                    } else {
                        alert("Error deleting signature: " + response);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        }
    });
});
</script>

<?php
include('includes/footer.php');

?>


