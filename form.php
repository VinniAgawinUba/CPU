<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('config/dbcon.php');
include('authentication.php');




?>

 

  <!--Include the jQuery and jQuery UI libraries so that Signatures work-->
  <script type="text/javascript" src="assets/js/jquery.min.js"></script> 
  <link type="text/css" href="assets/css/jquery-ui.css" rel="stylesheet"> 
  <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
  <!-- Bootstrap JavaScript -->
  <script src="assets/js/bootstrap5.bundle.min.js"></script>

  <script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css"></link>

  <!--Signature Styles-->
  <style>
    .kbw-signature { width: 800px; height: 200px; }
    #sig canvas { width: 100% !important; height: auto; }
  </style>


<!--Horizontal Rule Line-->
<hr style="width:80%; margin:auto; color:#00478a; background:#429ef5; height: 3px;">

<div class="container mx-auto p-6" style="background-image: url('assets/images/BG.png'); border-radius:4%;">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 justify-centeritems-center text-white">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT</h1>
    <form action="allcode.php" method="post" enctype="multipart/form-data">
    <!-- Hidden user_name, user_id, user_email -->
    <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
    <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

    

      <fieldset class="mb-4 bg-blue-900 bg-opacity-50 shadow-md rounded p-4">
        <legend class="font-bold text-white">Purchase Request</legend>
        
        
        
      </fieldset>

      <!-- ITEMS -->
      <fieldset class="mb-4 bg-blue-900 bg-opacity-50 text-white shadow-md rounded p-4">
        <legend class="font-bold text-inherit">Items</legend>
        <p class="mb-2 text-inherit">Please include complete specifications/details or attach additional information on items.</p>
        <p class="mb-2 text-inherit">CPU may refuse to receive request without complete specifications or details.</p>
        
        <table class="table-fixed border">
    <thead>
        <tr>
            <th class="px-2 py-2" style="width:60px">ITEM#</th>
            <th class="px-2 py-2" style="width:60px">QTY/UNIT</th>
            <th class="px-2 py-2" style="width:550px">DESCRIPTION</th>
            <th class="px-2 py-2" style="width:550px">JUSTIFICATION</th>
            <th class="px-2 py-2" style="width:80px">REMOVE</th> <!-- Empty header for remove button -->
        </tr>
    </thead>
    <tbody id="itemRows">
        <!-- Dynamically generated rows will be added here -->
    </tbody>
</table>


        <button type="button" class="btn btn-primary btn-add-item bg-blue-600">Add Item</button>
      </fieldset>


      <!-- Requestor Information -->
<fieldset class="mb-4 bg-blue-900 bg-opacity-50 shadow-md rounded p-4">
    <legend class="font-bold text-white">Requestor Information</legend>
    <table class="table-auto">
        <tbody>
            <tr>
                <td class="text-white">Unit/Dept:</td>
                <td class="p-1">
                    <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Unit/Dept">
                </td>
            </tr>
            <tr>
                <td class="text-white">Requested by:</td>
                <td class="p-1">
                    <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name">
                </td>
            </tr>
            
            
            <tr class="text-white bold">
                <td>IPTel#/E-mail Address:</td>
                <td class="px-1">
                    <input type="text" readonly id="iptel_email" name="iptel_email" class="form-control" required value="<?php echo $_SESSION['auth_user']['user_email']; ?>">
                </td>
            </tr>

            <tr class="text-white bold">
                <td>Request File Attatchments:</td>
                <td class="px-1">
                <input type="file" name="request_documents[]" multiple class="form-control">
                </td>
            </tr>
            
        </tbody>
    </table>
</fieldset>





      <button type="submit" name="request_add_btn_front" class="btn btn-primary bg-blue-600">Submit Request</button>
    </form>
  </div>

  <script>
    // jQuery noConflict mode to avoid conflicts with other libraries
    jQuery.noConflict();
    document.addEventListener("DOMContentLoaded", function () {
        const itemRows = document.getElementById('itemRows');
        const addItemButton = document.querySelector('.btn-add-item');
        let itemNumber = 1; // Initialize item number

        addItemButton.addEventListener('click', function () {
            const itemRow = document.createElement('tr');
            itemRow.classList.add('item-row', 'mb-2');
            itemRow.innerHTML = `
                <td class="px-2 py-2" style="width:60px">
                    <!-- ITEM# -->
                    <input type="text" name="item_number[]" class="form-control" style="width:60px" readonly value="${itemNumber}">
                </td>
                <td class="px-2 py-2">
                    <!-- QTY/UNIT -->
                    <input type="number" name="item_qty[]" class="form-control" style="width:60px" required>
                </td>
                <td class="px-2 py-2">
                    <!-- DESCRIPTION -->
                    <textarea name="item_description[]" class="form-control" style="width:550px" required maxlength="500"></textarea>
                </td>
                <td class="px-2 py-2">
                    <!-- JUSTIFICATION -->
                    <textarea type="text" name="item_justification[]" class="form-control" style="width:550px" required maxlength="500"> </textarea>
                </td>
                <td class="px-2 py-2">
                    <!-- Remove Button -->
                    <button type="button" class="btn btn-danger btn-remove-item bg-red-600" style="width:80px">Remove</button>
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

        // Ensure jQuery is loaded before executing jQuery-dependent code
        jQuery(document).ready(function() {
            // Check if the container is properly selected
            console.log(jQuery('#sigRequestor'));

            // SignaturePad initialization
            var sigRequestor = jQuery('#sigRequestor').signature({syncField: '#signature64_Requestor', syncFormat:'PNG'});
            jQuery('#clearRequestor').click(function(e){
                e.preventDefault();
                sigRequestor.signature('clear');
                jQuery("#signature64_Requestor").val('');
            });
        });

        // Custom validation functions
        function validateForm() {
            var itemQtyInputs = document.querySelectorAll('input[name="item_qty[]"]');

            for (var i = 0; i < itemQtyInputs.length; i++) {
                if (!itemQtyInputs[i].value.match(/^\d+$/)) {
                    alert('Quantity must be a number.');
                    return false; // Exit the function and prevent form submission
                }
            }

            return true; // All inputs passed validation, allow form submission
        }

        // Form submission event
        document.querySelector('form').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Enforce character limits programmatically
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


  

<!-- Dont Load Footer PHP (Causes Multiple Jquery js file conflicts), Use Hard coded footer instead -->
<footer class="bg-dark text-white py-5">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-between items-start">
            <div class="w-full md:w-1/2 lg:w-2/5 m-4">
                <h2 class="text-lg font-semibold mb-3">About Xavier University's Central Procurement Unit</h2>
                <p class="py-2">The Central Procurement Unit (CPU) at Xavier University plays a crucial role in managing the acquisition of goods and services for various departments and units within the university. As a central entity responsible for procurement activities, the CPU ensures compliance with regulations, maximizes cost-effectiveness, and maintains transparency in purchasing processes.</p>
            </div>

            <div class="w-full md:w-1/2 lg:w-1/5 m-3">
                <h2 class="text-lg font-semibold mb-3">Help Desk</h2>
                <p class="py-2">For assistance, contact:</p>
                <div class="flex items-center">
                    <span class="text-gray-400 mr-2"><i class="fas fa-envelope"></i></span><p class="py-2 mb-0">ciso@xu.edu.ph</p>
                </div>
                <div class="flex items-center mt-2">
                    <span class="text-gray-400 mr-2"><i class="fas fa-phone-alt"></i></span><p class="py-2 mb-0 text-nowrap">(088) 853-9800</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center pt-3">
            <p class="text-center mb-0">© <?php echo date('Y'); ?> Xavier University - Ateneo de Cagayan Corrales Avenue, Cagayan de Oro City, Philippines.</p>
        </div>
    </div>
</footer>
