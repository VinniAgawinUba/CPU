<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Print Template</title>
    <link rel="stylesheet" href="print-template.css">
</head>
<body>


<!-- Header -->
<div class="prDiv">
    <!-- Purchase Request# (Dynamically fetch from purchase_requests table in database) -->
    <label for="pr_input">Purchase Request#</label>
    <input id="pr_input" type="text" class="pr_input"></input>
</div>
<div class="headerTitle">
    <h1>PURCHASE REQUEST FORM</h1>
</div>

<!-- Item Table -->
<div class="tableDiv">
<table class="tableMain">
    <thead>
        <tr>
            <th>ITEM#</th>
            <th>QTY/UNIT</th>
            <th>DESCRIPTION</th>
            <th>JUSTIFICATION</th>
        </tr>
    </thead>
    <tbody>
        <!-- Dynamically fetch items from items table in database with matching purchase_request_id -->
        <tr>
            <td>1</td>
            <td>1</td>
            <td>Soap</td>
            <td>NEW NANGIANGIANGIANGIANGI Lorem ipsum dolor sit amet consectetur, adipisicing elit. Tempora tempore aliquid voluptate autem repudiandae eveniet quos sapiente, consequuntur unde soluta consequatur perspiciatis molestias aliquam alias nulla excepturi adipisci quod sunt?</td>
        </tr>
        <tr>
            <td>2</td>
            <td>1</td>
            <td>Balls</td>
            <td>New</td>
        </tr>
    </tbody>
</table>
</div>

<!-- Footer (Requestor Information and Signatures)-->
<div class="footer">
    <div class="box1">
        <!--Row 1-->
        <div class="row">
            <div class="col">
                <label for="unit_dept">Unit/Dept:</label>
                <input id="unit_dept" type="text" class="unit_dept input_border_bottom"></input>
            </div>
          
        </div>
        <!--Row 2-->
        <div class="row">
            <div class="col">
                <label for="requested_by">Requested by:</label>
            </div>
            <div class="col">
                <label for="approved_by">Approved by:</label>
            </div>
            <div class="empty">
            <!--Empty div for spacing-->
            </div>
            <div class="empty">
            <!--Empty div for spacing-->
            </div>
            
            
        </div>
        <!--Row 3-->
        <div class="row">
            <div class="col">
                <input id="requestor" type="text" class="requestor input_border_bottom"></input>
            </div>
            <div class="col">
                <input id="approved_by" type="text" class="approved_by input_border_bottom"></input>
            </div>
            <div class="col">
                <input id="cluster_vice_president" type="text" class="cluster_vice_president input_border_bottom"></input>
            </div>
        </div>

        <!--Row 4-->
        <div class="row">
            <div class="col">
                <label for="requestor">Requestor</label>
            </div>
            <div class="col">
                <label for="unit_head">Unit Head</label>
            </div>
            <div class="col">
                <label for="cluster_vice_president">Cluster Vice President</label>
            </div>
        </div>
    </div>

    <div class="box2">
        <!--Row 1-->
        <div class="row">
            <div class="col">
                <label for="finance_office_use">For Finance Office Use</label>
            </div>
        </div>
        <!--Row 2-->
        <div class="row">
            
            <div class="col">
                <label for="account_code">Acct. Code</label>
                <input id="account_code" type="text" class="account_code input_border_bottom"></input>
            </div>
        </div>
        <!--Row 3-->
        <div class="row">
            <div class="col">
                <input id="budget_controller" type="text" class="budget_controller input_border_bottom"></input>
            </div>
            <div class="col">
                <input id="university_treasurer" type="text" class="university_treasurer input_border_bottom"></input>
            </div>
        </div>

        <!--Row 4-->
        <div class="row">
            <div class="col">
                <label for="budget_controller">Budget Controller</label>
            </div>
            <div class="col">
                <label for="university_treasurer">University Treasurer</label>
            </div>
        </div>
    </div>
</div>



    
</body>
</html>