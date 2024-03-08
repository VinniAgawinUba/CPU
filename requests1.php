<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xavier University Procurement Request Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mt-8 mb-4">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT</h1>
    <form action="/submit_request" method="post">
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

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Requesting Person Information</legend>
        <p class="italic m-3">Requesting person will be contacted if more information is needed</p>
        <div class="mb-3">
          <label for="printed_name" class="form-label">Requested by:</label>
          <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Printed Name & Signature">
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

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Approvals</legend>
        <div class="mb-3">
          <label for="vice_president" class="form-label">1-VICE PRESIDENT (CLUSTER):</label>
          <input type="text" id="vice_president_remarks" name="vice_president_remarks" class="form-control" placeholder="Remarks">
          <label for="vice_president" class="form-label">Approved By:</label>
          <input type="text" id="vice_president_approved" name="vice_president_approved" class="form-control">
        </div>
        
        <div class="mb-3">
          <label for="vice_president_administration" class="form-label">2-VICE PRESIDENT FOR ADMINISTRATION:</label>
          <input type="text" id="vice_president_administration" name="vice_president_administration" class="form-control" placeholder="Remarks">
          <label for="vice_president_administration" class="form-label">Approved By:</label>
          <input type="text" id="vice_president_administration_approved" name="vice_president_administration_approved" class="form-control">
        </div>
        <div class="mb-3">
          <label for="budget_controller" class="form-label">3-BUDGET CONTROLLER:</label>
          <input type="text" id="budget_controller" name="budget_controller" class="form-control" placeholder="Remarks">
          <label for="budget_controller" class="form-label">Approved By:</label>
          <input type="text" id="budget_controller_approved" name="budget_controller_approved" class="form-control">
        </div>
        <div class="mb-3">
          <label for="university_treasurer" class="form-label">4-UNIVERSITY TREASURER:</label>
          <input type="text" id="university_treasurer" name="university_treasurer" class="form-control" placeholder="Remarks">
          <label for="university_treasurer" class="form-label">Approved By:</label>
          <input type="text" id="university_treasurer_approved" name="university_treasurer_approved" class="form-control">
        </div>
        <div class="mb-3">
          <label for="office_of_president" class="form-label">OFFICE OF THE PRESIDENT (for budget re-alignment only):</label>
          <input type="text" id="office_of_president" name="office_of_president" class="form-control" placeholder="Remarks">
          <label for="office_of_president" class="form-label">Approved By:</label>
          <input type="text" id="office_of_president_approved" name="office_of_president_approved" class="form-control">
        </div>
      </fieldset>

      <button type="submit" class="btn btn-primary">Submit Request</button>
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
          <label class="form-label">Item #:</label>
          <div class="row">
            <div class="col">
              <input type="text" name="item_qty[]" class="form-control" placeholder="Qty/Unit">
            </div>
            <div class="col">
              <input type="text" name="item_type[]" class="form-control" placeholder="ITEMS">
            </div>
            <div class="col">
              <div class="form-check">
                <input type="radio" id="additional" name="item_justification[]" value="additional" class="form-check-input">
                <label for="additional" class="form-check-label">Additional</label>
              </div>
              <div class="form-check">
                <input type="radio" id="replacement" name="item_justification[]" value="replacement" class="form-check-input">
                <label for="replacement" class="form-check-label">Replacement</label>
              </div>
              <div class="form-check">
                <input type="radio" id="new" name="item_justification[]" value="new" class="form-check-input">
                <label for="new" class="form-check-label">New</label>
              </div>
              <div>
                <input type="text" name="item_date_condition[]" class="form-control mt-1" placeholder="Indicate date purchased & condition if replacement">
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
    });
  </script>

</body>
</html>
