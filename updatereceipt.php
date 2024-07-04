<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Initialize variables
$receiptNum = isset($_GET['receipt_num']) ? $_GET['receipt_num'] : '';
$row = [];
$receiptDetails = [];

// Fetch the receipt and its details
if (!empty($receiptNum)) {
    // Fetch receipt data
    $sql = "SELECT * FROM receipt WHERE RECEIPT_NUM = :receipt_num";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':receipt_num', $receiptNum);
    oci_execute($stmt);
    $row = oci_fetch_assoc($stmt);

    // Fetch receipt details
    $sql_details = "SELECT * FROM receiptdetail WHERE RECEIPT_NUM = :receipt_num";
    $stmt_details = oci_parse($conn, $sql_details);
    oci_bind_by_name($stmt_details, ':receipt_num', $receiptNum);
    oci_execute($stmt_details);
    while ($detail = oci_fetch_assoc($stmt_details)) {
        $receiptDetails[] = $detail;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update receipt information
    $newCustName = $_POST['cust_name'];
    $newCustPhoneNum = $_POST['cust_phonenum'];
    $newPcNum = isset($_POST['pc_num']) ? $_POST['pc_num'] : null;
    $newTotalReceipt = $_POST['total_receipt'];
    $newPaymentMethod = $_POST['payment_method'];
    $newReceiptDate = $_POST['receipt_date'];
    $newEmployeeId = $_POST['employee_id'];

    // Update receipt query
    $sql_update_receipt = "UPDATE receipt
            SET CUST_NAME = :cust_name,
                CUST_PHONENUM = :cust_phonenum,
                PC_NUM = :pc_num,
                TOTAL_RECEIPT = :total_receipt,
                PAYMENT_METHOD = :payment_method,
                RECEIPT_DATE = TO_DATE(:receipt_date, 'YYYY-MM-DD'),
                EMPLOYEE_ID = :employee_id
            WHERE RECEIPT_NUM = :receipt_num";
    $stmt_update_receipt = oci_parse($conn, $sql_update_receipt);
    oci_bind_by_name($stmt_update_receipt, ':cust_name', $newCustName);
    oci_bind_by_name($stmt_update_receipt, ':cust_phonenum', $newCustPhoneNum);
    oci_bind_by_name($stmt_update_receipt, ':pc_num', $newPcNum);
    oci_bind_by_name($stmt_update_receipt, ':total_receipt', $newTotalReceipt);
    oci_bind_by_name($stmt_update_receipt, ':payment_method', $newPaymentMethod);
    oci_bind_by_name($stmt_update_receipt, ':receipt_date', $newReceiptDate);
    oci_bind_by_name($stmt_update_receipt, ':employee_id', $newEmployeeId);
    oci_bind_by_name($stmt_update_receipt, ':receipt_num', $receiptNum);
    oci_execute($stmt_update_receipt);

    // Update receipt details
    $item_ids = $_POST['item_id'];
    $item_qtys = $_POST['item_qty'];

    // Loop through items and update receipt detail
    foreach ($item_ids as $key => $item_id) {
        $item_qty = $item_qtys[$key];

        // Update receipt detail query
        $sql_update_detail = "UPDATE receiptdetail
                              SET ITEMQTY = :item_qty
                              WHERE RECEIPT_NUM = :receipt_num
                              AND ITEM_ID = :item_id";
        $stmt_update_detail = oci_parse($conn, $sql_update_detail);
        oci_bind_by_name($stmt_update_detail, ':item_qty', $item_qty);
        oci_bind_by_name($stmt_update_detail, ':receipt_num', $receiptNum);
        oci_bind_by_name($stmt_update_detail, ':item_id', $item_id);
        oci_execute($stmt_update_detail);
    }

    // Redirect to receipt page after update
    header('Location: receipt.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Receipt</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4">Update Receipt</h1>
        <form method="post" class="needs-validation" novalidate>
            <div class="form-group">
                <label for="cust_name">Customer Name:</label>
                <input type="text" class="form-control" id="cust_name" name="cust_name"
                    value="<?php echo htmlspecialchars($row['CUST_NAME'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    Please enter customer name.
                </div>
            </div>

            <div class="form-group">
                <label for="cust_phonenum">Customer Phone Number:</label>
                <input type="text" class="form-control" id="cust_phonenum" name="cust_phonenum"
                    value="<?php echo htmlspecialchars($row['CUST_PHONENUM'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    Please enter customer phone number.
                </div>
            </div>

            <div class="form-group">
                <label for="pc_num">PC Number:</label>
                <input type="text" class="form-control" id="pc_num" name="pc_num"
                    value="<?php echo htmlspecialchars($row['PC_NUM'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="total_receipt">Total:</label>
                <input type="text" class="form-control" id="total_receipt" name="total_receipt"
                    value="<?php echo htmlspecialchars($row['TOTAL_RECEIPT'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    Please enter total receipt amount.
                </div>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <input type="text" class="form-control" id="payment_method" name="payment_method"
                    value="<?php echo htmlspecialchars($row['PAYMENT_METHOD'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    Please enter payment method.
                </div>
            </div>

            <div class="form-group">
                <label for="receipt_date">Receipt Date:</label>
                <input type="date" class="form-control" id="receipt_date" name="receipt_date"
                    value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($row['RECEIPT_DATE'] ?? ''))); ?>"
                    required>
                <div class="invalid-feedback">
                    Please enter receipt date.
                </div>
            </div>

            <div class="form-group">
                <label for="employee_id">Employee ID:</label>
                <input type="text" class="form-control" id="employee_id" name="employee_id"
                    value="<?php echo htmlspecialchars($row['EMPLOYEE_ID'] ?? ''); ?>" required>
                <div class="invalid-feedback">
                    Please enter employee ID.
                </div>
            </div>

            <div class="form-group">
                <label for="item_details">Receipt Details:</label>
                <?php foreach ($receiptDetails as $key => $detail): ?>
                    <div class="form-row">
                        <div class="col">
                            <input type="text" class="form-control" name="item_id[]"
                                value="<?php echo htmlspecialchars($detail['ITEM_ID'] ?? ''); ?>" readonly>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" name="item_qty[]"
                                value="<?php echo htmlspecialchars($detail['ITEMQTY'] ?? ''); ?>" required>
                            <div class="invalid-feedback">
                                Please enter item quantity.
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <input type="hidden" name="receipt_num" value="<?php echo htmlspecialchars($receiptNum); ?>">

            <button type="submit" class="btn btn-primary mr-2">Update</button>
            <a href="receipt.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-CnnvI/09F6EtxwC0Rxk4pY0uFz+2ysJkxxj1YHzceVRkOeFZWhjP07vPDjTsaN3N"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6pG5ZpNY5p3hpuuI5h7kRdQVYLz4chvo"
        crossorigin="anonymous"></script>

    <script>
        // Client-side form validation using Bootstrap classes
        (function () {
            'use strict';
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation');
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</body>

</html>