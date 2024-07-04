<?php
// Start the session
session_start();

// Include the database connection file
include('connection.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $receipt_date = $_POST['receipt_date'];
    $cust_name = $_POST['cust_name'];
    $cust_phonenum = $_POST['cust_phonenum'];
    $pc_num = isset($_POST['pc_num']) ? $_POST['pc_num'] : null;
    $total_receipt = $_POST['total_receipt'];
    $payment_method = $_POST['payment_method'];

    // Prepare and execute the SQL query to insert the new receipt
    $sql = "INSERT INTO receipt (RECEIPT_NUM, RECEIPT_DATE, CUST_NAME, CUST_PHONENUM, PC_NUM, TOTAL_RECEIPT, PAYMENT_METHOD)
            VALUES (receipt_num_seq.NEXTVAL, :receipt_date, :cust_name, :cust_phonenum, :pc_num, :total_receipt, :payment_method)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':receipt_date', $receipt_date);
    oci_bind_by_name($stmt, ':cust_name', $cust_name);
    oci_bind_by_name($stmt, ':cust_phonenum', $cust_phonenum);
    oci_bind_by_name($stmt, ':pc_num', $pc_num);
    oci_bind_by_name($stmt, ':total_receipt', $total_receipt);
    oci_bind_by_name($stmt, ':payment_method', $payment_method);
    oci_execute($stmt);

    // Redirect the user back to the receipt list page
    header("Location: receipt.php");
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Receipt</title>
    <!-- Add any necessary CSS or other HTML elements -->
</head>
<body>
    <div class="container">
        <h1>Add Receipt</h1>
        <form method="post" action="addreceipt.php">
            <div class="form-group">
                <label for="receipt_date">Receipt Date:</label>
                <input type="date" class="form-control" id="receipt_date" name="receipt_date" required>
            </div>
            <div class="form-group">
                <label for="cust_name">Customer Name:</label>
                <input type="text" class="form-control" id="cust_name" name="cust_name" required>
            </div>
            <div class="form-group">
                <label for="cust_phonenum">Customer Phone Number:</label>
                <input type="text" class="form-control" id="cust_phonenum" name="cust_phonenum" required>
            </div>
            <div class="form-group">
                <label for="pc_num">PC Number:</label>
                <input type="text" class="form-control" id="pc_num" name="pc_num">
            </div>
            <div class="form-group">
                <label for="total_receipt">Total Receipt:</label>
                <input type="number" class="form-control" id="total_receipt" name="total_receipt" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <input type="text" class="form-control" id="payment_method" name="payment_method" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>
