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
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
  <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css"></link>

  <!--Signature Styles-->
  <style>
    .kbw-signature { width: 800px; height: 200px; }
    #sig canvas { width: 100% !important; height: auto; }
  </style>




<div class="container mx-auto p-6" style="background-image: url('assets/images/BG.png'); border-radius:4%;">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 justify-centeritems-center text-white">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT</h1>
    <form action="allcode.php" method="post">
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
            <tr>
                <td class="text-white">Approved by: (Unit Head)</td>
                <td class="p-1">
                    <input type="text" id="endorsed_by_dean" name="endorsed_by_dean" class="form-control" required placeholder="Unit Head Name">
                </td>
            </tr>
            <tr>
                <td class="text-white">Unit Head Signature:</td>
                <td class="p-1">
                    <div id="sigRequestor" class="kbw-signature"></div>
                    <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
                    <textarea id="signature64_Requestor" name="signed_Requestor" style="display:none"></textarea>
                </td>
            </tr>
            
            <tr class="text-white bold">
                <td>IPTel#/E-mail Address:</td>
                <td class="px-1">
                    <input type="text" id="iptel_email" name="iptel_email" class="form-control" required>
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
                <input type="text" name="item_number[]" class="form-control" style="width:60px" value="${itemNumber}">
            </td>
            <td class="px-2 py-2">
                <!-- QTY/UNIT -->
                <input type="text" name="item_qty[]" class="form-control" style="width:60px" required>
            </td>
            <td class="px-2 py-2">
                <!-- DESCRIPTION -->
                <textarea name="item_description[]" class="form-control" style="width:550px" required></textarea>
            </td>
            <td class="px-2 py-2">
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" style="width:550px" required> </textarea>
            </td>
            <td class="px-2 py-2">
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger btn-remove-item bg-red-600" style="width:80px" required>Remove</button>
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
