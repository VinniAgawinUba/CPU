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

<div class="container mx-auto p-6 bg-yellow-100 shadow-black">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT<a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a></h1>
    <form action="code.php" method="post">
    <!-- Hidden user_name, user_id, user_email -->
    <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
    <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Purchase Request</legend>
        <div class="mb-3">
          <label for="purchase_request_number" class="form-label">PURCHASE REQUEST#:</label>
          <input type="text" id="purchase_request_number" name="purchase_request_number" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">REQUEST TO PURCHASE:</label>
          <div class="form-check">
            <input type="checkbox" id="capex" name="purchase_type[]" value="CAPEX" class="form-check-input">
            <label for="capex" class="form-check-label">CAPEX</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="ict_items" name="purchase_type[]" value="ICT_ITEMS" class="form-check-input">
            <label for="ict_items" class="form-check-label">ICT ITEMS</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="consumables" name="purchase_type[]" value="CONSUMABLES" class="form-check-input">
            <label for="consumables" class="form-check-label">CONSUMABLES</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="services" name="purchase_type[]" value="SERVICES" class="form-check-input">
            <label for="services" class="form-check-label">SERVICES</label>
          </div>
          <!-- Add other checkboxes for purchase types -->
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Items</legend>
        <p class="mb-2">Please include complete specifications/details or attach additional information on items. Use the back of this sheet if needed.</p>
        <p class="mb-2">CPU will refuse to receive request without complete specifications or details.</p>
        <div class="mb-3" id="itemRows">
          <!-- Dynamic item rows will be added here -->
        </div>
        <button type="button" class="btn btn-primary btn-add-item">Add Item</button>
      </fieldset>


      <!-- Requesting Person Information -->
      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Requesting Person Information</legend>
        <p class="italic m-3">Requesting person will be contacted if more information is needed</p>
        <div class="mb-3">
          <label for="printed_name" class="form-label">Requested by:</label>
          <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Printed Name">
        </div>
        
        <!-- Signature Requestor-->
        <div class="mb-3">
          <label for="signatureRequestor">Signature:</label>
          <div id="sigRequestor" class="kbw-signature"></div>
          <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
          <textarea id="signature64_Requestor" name="signed_Requestor" style="display:none"></textarea>
        </div>

        <div class="mb-3">
          <label for="unit_dept_college" class="form-label">Unit/Dept/College:</label>
          <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control">
        </div>
        <div class="mb-3">
          <label for="iptel_email" class="form-label">IPTel#/E-mail Address:</label>
          <input type="text" id="iptel_email" name="iptel_email" class="form-control">
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Remarks by College Dean/Principal</legend>
        <div class="mb-3">
          <textarea id="remarks_dean" name="remarks_dean" class="form-control" rows="4"></textarea>
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Endorsed by: College Dean/Principal</legend>
        <div class="mb-3">
          <input type="text" id="endorsed_by_dean" name="endorsed_by_dean" class="form-control">
        </div>
      </fieldset>

      <!-- Signatures for Approvals -->
      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Approvals</legend>

        <!-- Vice President -->
        <div class="mb-3">
          <label for="vice_president" class="form-label">1-VICE PRESIDENT (CLUSTER):</label>
          <input type="text" id="vice_president_remarks" name="vice_president_remarks" class="form-control" placeholder="Remarks">
          <label for="vice_president" class="form-label">Approved By:</label>
          <input type="text" id="vice_president_approved" name="vice_president_approved" class="form-control">
          
          <!-- Signature -->
          <div class="mb-3">
            <label for="signature1">Signature:</label>
            <div id="sig1"></div>
            <button id="clear1" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_1" name="signed_1" style="display:none"></textarea>
          </div>
        </div>
        
        <!-- Vice President for Administration -->
        <div class="mb-3">
          <label for="vice_president_administration" class="form-label">2-VICE PRESIDENT FOR ADMINISTRATION:</label>
          <input type="text" id="vice_president_administration_remarks" name="vice_president_administration_remarks" class="form-control" placeholder="Remarks">
          <label for="vice_president_administration" class="form-label">Approved By:</label>
          <input type="text" id="vice_president_administration_approved" name="vice_president_administration_approved" class="form-control">
          
          <!-- Signature -->
          <div class="mb-3">
            <label for="signature2">Signature:</label>
            <div id="sig2"></div>
            <button id="clear2" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_2" name="signed_2" style="display:none"></textarea>
          </div>
        </div>

        <!--Budget Controller-->
        <div class="mb-3">
          <label for="budget_controller" class="form-label">3-BUDGET CONTROLLER:</label>
          <input type="text" id="budget_controller_remarks" name="budget_controller_remarks" class="form-control" placeholder="Remarks">
          <label for="budget_controller" class="form-label">Approved By:</label>
          <input type="text" id="budget_controller_approved" name="budget_controller_approved" class="form-control">
          <label for="budget_controller" class="form-label">Acct. Code:</label>
          <input type="text" id="budget_controller_code" name="budget_controller_code" class="form-control" placeholder="Input Acct. Code">
          
          <!-- Signature -->
          <div class="mb-3">
            <label for="signature3">Signature:</label>
            <div id="sig3"></div>
            <button id="clear3" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_3" name="signed_3" style="display:none"></textarea>
          </div>

        <!--University Treasurer-->
        <div class="mb-3">
            <label for="university_treasurer" class="form-label">4-UNIVERSITY TREASURER:</label>
            <input type="text" id="university_treasurer_remarks" name="university_treasurer_remarks" class="form-control" placeholder="Remarks">
            <label for="university_treasurer" class="form-label">Approved By:</label>
            <input type="text" id="university_treasurer_approved" name="university_treasurer_approved" class="form-control">
            
            <!-- Signature -->
            <div class="mb-3">
            <label for="signature4">Signature:</label>
            <div id="sig4"></div>
            <button id="clear4" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_4" name="signed_4" style="display:none"></textarea>
            </div>

        <!--OFFICE OF THE PRESIDENT (for budget re-alignment only) :-->
        <div class="mb-3">
            <label for="office_of_the_president" class="form-label">5-OFFICE OF THE PRESIDENT (for budget re-alignment only) :</label>
            <input type="text" id="office_of_the_president_remarks" name="office_of_the_president_remarks" class="form-control" placeholder="Remarks">
            <label for="office_of_the_president" class="form-label">Approved By:</label>
            <input type="text" id="office_of_the_president_approved" name="office_of_the_president_approved" class="form-control">
            
            <!-- Signature -->
            <div class="mb-3">
            <label for="signature5">Signature:</label>
            <div id="sig5"></div>
            <button id="clear5" class="btn btn-primary">Clear Signature</button>
            <textarea id="signature64_5" name="signed_5" style="display:none"></textarea>
            </div>
        <!-- Add more approval sections as needed -->
        
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

      addItemButton.addEventListener('click', function () {
        const itemRow = document.createElement('div');
        itemRow.classList.add('item-row', 'mb-2');
        itemRow.innerHTML = `
        <label class="form-label">Item:</label>
        <div class="row bg-gray-100">
            <div class="col-md-3">
                <label for="item_qty">Qty/Unit:</label>
                <input type="text" name="item_qty[]" id="item_qty" class="form-control" placeholder="Qty/Unit">
            </div>
            <div class="col-md-3">
                <label for="item_type">Item:</label>
                <textarea name="item_type[]" id="item_type" class="form-control" placeholder="ITEMS -Please include complete specifications/details -CPU will refuse to receive request without complete specifications or details"></textarea>
            </div>
            <div class="col-md-5">
                <label for="item_type">Justification:</label>
                <div class="form-check">
                    <input type="checkbox" id="additional" name="item_justification[]" value="additional" class="form-check-input">
                    <label for="additional" class="form-check-label">Additional</label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="replacement" name="item_justification[]" value="replacement" class="form-check-input">
                    <label for="replacement" class="form-check-label">Replacement</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="new" name="item_justification[]" value="new" class="form-check-input">
                    <label for="new" class="form-check-label">New</label>
                </div>
                <div>
                    <label for="item_reason">Reason for request:</label>
                    <input type="text" name="item_reason[]" id="item_reason" class="form-control mt-1" placeholder="Pls specify needs & reasons" required>
                </div>
                <div>
                    <label for="item_date_condition">Date Purchased & Condition (if replacement):</label>
                    <input type="text" name="item_date_condition[]" id="item_date_condition" class="form-control mt-1" placeholder="Indicate date purchased & condition if replacement">
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-remove-item">Remove</button>
            </div>
        </div>
        `;
        itemRows.appendChild(itemRow);
      });

      itemRows.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove-item')) {
          event.target.closest('.item-row').remove();
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


