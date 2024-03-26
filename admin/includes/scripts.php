<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> <!-- Move this line up -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" /> <!-- Move this line up -->


<script src="js/scripts.js"></script>
<script src="js/datatables-simple-demo.js"></script>

        <script>
            $(document).ready( function () {
            $('#myCategory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myCollege').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myProject').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myDepartment').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myFaculty').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPartner').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPost').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myUsers').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myInventory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myRequests').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myProject').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#mySchoolyear').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myStudent').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPurchaseRequests').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myItemHistory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myRequestDetailsHistory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPurchaseRequestsFront').DataTable({
                "order": [[ 0, "desc" ]]
            });

            } );
        </script>

<!-- Summernote JS - CDN Link -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        //$("#summernote").summernote();
        $('#summernote').summernote({
            placeholder: 'Type your Description',
            tabsize: 2,
            height: 200
        });
        $('.dropdown-toggle').dropdown();
    });
</script>

<!-- CSS For Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- JavaScript for Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Initialize Select2 for dropdowns
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<!-- JavaScript for General Button Confirmation (Buttons Should have class of ConfirmButton) -->
<script>
    // Select all elements with the class 'ConfirmButton'
    var ConfirmButton = document.querySelectorAll(".ConfirmButton");
    
    // Iterate over each delete button and attach event listener
    ConfirmButton.forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (confirm("Confirm to Continue")) {
                // Find the closest form and submit it
                this.closest(".deleteForm").submit();
            } else {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<!-- JavaScript for Delete Button Confirmation (Buttons Should have class of deleteButton) -->
<script>
    // Select all elements with the class 'deleteButton'
    var deleteButtons = document.querySelectorAll(".deleteButton");
    
    // Iterate over each delete button and attach event listener
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (confirm("Are you sure you want to delete this document?")) {
                // Find the closest form and submit it
                this.closest(".deleteForm").submit();
            } else {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<!-- JavaScript for Filter Buttons -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const tableRows = document.querySelectorAll('#myPurchaseRequests tbody tr');

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const status = this.getAttribute('data-status');

                tableRows.forEach(function (row) {
                    // If the status is 'all' or the row status matches the button status
                    if (status === 'all' || row.cells[8].textContent.trim() === status  ){
                        row.style.display = '';
                    }
                    
                    else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>


<?php
include ('config/dbcon.php');
// Gets current user email and checks if it is in any of the signed by columns
// Assuming you have a session variable for the current user's email
$current_user_email = $_SESSION['auth_user']['user_email'];

// Escape the email to prevent SQL injection
$current_user_email = $con->real_escape_string($current_user_email);

// Query to fetch purchase requests along with their signed by information for the current user
$sql = "SELECT * FROM purchase_requests WHERE '$current_user_email' IN (signed_1_by, signed_2_by, signed_3_by, signed_4_by, signed_5_by)";
$result = $con->query($sql);

$rows = array();
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
?>

<!-- JavaScript for Show View Filter Button -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-view');
        const tableRows = document.querySelectorAll('#myPurchaseRequests tbody tr');

        // Function to filter table rows based on the selected status
        function filterTableRows(status) {
            tableRows.forEach(function (row) {
                const isSigned = row.cells[8].textContent.trim() !== 'Not Signed';

                // If the status is 'all' or the row is either signed or not signed based on the button clicked
                if (status === 'all' || (status === 'hidden' && isSigned) || (status === 'pending' && !isSigned)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Function to activate the default filter based on the user role
        function activateDefaultFilter(userRole) {
            if (userRole === "2") {
                // For Super Admin (role 2), set the default filter to 'All'
                filterTableRows('all');
                document.querySelector('.filter-view[data-status="all"]').classList.add('active');
            } else if (userRole === "3") {
                // For Department Editor (role 3), set the default filter to 'Pending'
                filterTableRows('pending');
                document.querySelector('.filter-view[data-status="pending"]').classList.add('active');
            } else {
                // For other roles, set the default filter to 'All'
                filterTableRows('all');
                document.querySelector('.filter-view[data-status="all"]').classList.add('active');
            }
        }

        // Activate the default filter based on the user role after the page has loaded
        activateDefaultFilter("<?php echo $_SESSION['auth_role']; ?>");

        // Event listener for filter buttons
        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const status = this.getAttribute('data-status');
                filterTableRows(status);
                // Remove 'active' class from all filter buttons and add it to the clicked button
                filterButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
            });
        });
    });
</script>









</body>
</html>
