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
    

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Purchase Request</legend>
        <div class="mb-3">
          <label for="purchase_request_number" class="form-label">PURCHASE REQUEST#:</label>
          <input type="text" id="purchase_request_number" name="purchase_request_number" class="form-control" required>

          <!-- Hidden user_name, user_id, user_email -->
    <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
    <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">
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
            <th style="width:80px">REMOVE</th> <!-- Empty header for remove button -->
        </tr>
    </thead>
    <tbody id="itemRows">
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
                    <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Unit/Dept">
                </td>
            </tr>
            <tr>
                <td>Requested by:</td>
                <td>
                    <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name">
                </td>
            </tr>
            <tr>
                <td>Approved by: (Unit Head)</td>
                <td>
                    <input type="text" id="endorsed_by_dean" name="endorsed_by_dean" class="form-control" required placeholder="Unit Head Name">
                </td>
            </tr>
            <tr>
                <td>Unit Head Signature:</td>
                <td>
                    <div id="sigRequestor" class="kbw-signature"></div>
                    <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_Requestor" name="signed_Requestor" style="display:none"></textarea>
                </td>
            </tr>
            
            <tr>
                <td>IPTel#/E-mail Address:</td>
                <td>
                    <input type="text" id="iptel_email" name="iptel_email" class="form-control" required>
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
                    <input type="text" id="vice_president_remarks" name="vice_president_remarks" class="form-control" placeholder="Remarks">
                    <br>
                    <input type="text" id="vice_president_approved" name="vice_president_approved" class="form-control" placeholder="Approved By">
                    <br>
                    <div id="sig1" class="mb-3"></div>
                    <button id="clear1" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_1" name="signed_1" style="display:none"></textarea>
                </td>
            </tr>
            <!-- Vice President for Administration -->
            <tr>
                <td>2-VICE PRESIDENT FOR ADMINISTRATION:</td>
                <td>
                    <input type="text" id="vice_president_administration_remarks" name="vice_president_administration_remarks" class="form-control" placeholder="Remarks">
                    <br>
                    <input type="text" id="vice_president_administration_approved" name="vice_president_administration_approved" class="form-control" placeholder="Approved By">
                    <br>
                    <div id="sig2" class="mb-3"></div>
                    <button id="clear2" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_2" name="signed_2" style="display:none"></textarea>
                </td>
            </tr>
            <!-- Budget Controller -->
            <tr>
                <td>3-BUDGET CONTROLLER:</td>
                <td>
                    <input type="text" id="budget_controller_remarks" name="budget_controller_remarks" class="form-control" placeholder="Remarks">
                    <br>
                    <input type="text" id="budget_controller_approved" name="budget_controller_approved" class="form-control" placeholder="Approved By">
                    <br>
                    <input type="text" id="budget_controller_code" name="budget_controller_code" class="form-control" placeholder="Acct. Code">
                    <br>
                    <div id="sig3" class="mb-3"></div>
                    <button id="clear3" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_3" name="signed_3" style="display:none"></textarea>
                </td>
            </tr>
            <!-- University Treasurer -->
            <tr>
                <td>4-UNIVERSITY TREASURER:</td>
                <td>
                    <input type="text" id="university_treasurer_remarks" name="university_treasurer_remarks" class="form-control" placeholder="Remarks">
                    <br>
                    <input type="text" id="university_treasurer_approved" name="university_treasurer_approved" class="form-control" placeholder="Approved By">
                    <br>
                    <div id="sig4" class="mb-3"></div>
                    <button id="clear4" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_4" name="signed_4" style="display:none"></textarea>
                </td>
            </tr>
            <!-- OFFICE OF THE PRESIDENT -->
            <tr>
                <td>5-OFFICE OF THE PRESIDENT (for budget re-alignment only):</td>
                <td>
                    <input type="text" id="office_of_the_president_remarks" name="office_of_the_president_remarks" class="form-control" placeholder="Remarks">
                    <br>
                    <input type="text" id="office_of_the_president_approved" name="office_of_the_president_approved" class="form-control" placeholder="Approved By">
                    <br>
                    <div id="sig5" class="mb-3"></div>
                    <button id="clear5" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_5" name="signed_5" style="display:none"></textarea>
                </td>
            </tr>
        </tbody>
    </table>
</fieldset>


      <button type="submit" name="request_add_btn_front" class="btn btn-primary">Submit Request</button>
    </form>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const itemRows = document.getElementById('itemRows');
      const addItemButton = document.querySelector('.btn-add-item');
      let itemNumber = 1; // Initialize item number

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
                <textarea name="item_description[]" class="form-control" style="width:550px" required></textarea>
            </td>
            <td>
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" style="width:550px" required> </textarea>
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

<?php
include('includes/footer.php');

?>


