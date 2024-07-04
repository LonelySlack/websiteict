<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Get the form data
$cust_name = $_POST['cust_name'];
$cust_phonenum = $_POST['cust_phonenum'];
$item_id = $_POST['item_id'];
$item_qty = $_POST['item_qty'];
$payment_method = $_POST['payment_method'];
$e_wallet_type = isset($_POST['e_wallet_type']) ? $_POST['e_wallet_type'] : null;
$transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : null;
$bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : null;
$bank_number = isset($_POST['bank_number']) ? $_POST['bank_number'] : null;
$employee_id = $_POST['employee_id']; // Get employee_id from the form

// Generate a new receipt number using a sequence
$sql_get_seq = "SELECT RECEIPT_NUM_SEQ.NEXTVAL AS NEXTVAL FROM DUAL";
$stmt_get_seq = oci_parse($conn, $sql_get_seq);
oci_execute($stmt_get_seq);
$row = oci_fetch_assoc($stmt_get_seq);
$receipt_num = $row['NEXTVAL'];

// Get the current date
$receipt_date = date("Y-m-d");

// Fetch the item price from the database
$sql_item_price = "SELECT ITEM_PRICE FROM ITEM WHERE ITEM_ID = :item_id";
$stmt_item_price = oci_parse($conn, $sql_item_price);
oci_bind_by_name($stmt_item_price, ':item_id', $item_id);
oci_execute($stmt_item_price);

$item_data = oci_fetch_assoc($stmt_item_price);
if (!$item_data) {
    echo "Item not found.";
    exit;
}

$item_price = $item_data['ITEM_PRICE'];

// Calculate the total receipt amount
$total_receipt = $item_qty * $item_price;

// Insert the receipt into the RECEIPT table
$sql_receipt = "
    INSERT INTO RECEIPT (RECEIPT_NUM, CUST_NAME, CUST_PHONENUM, RECEIPT_DATE, TOTAL_RECEIPT, PAYMENT_METHOD, EMPLOYEE_ID)
    VALUES (:receipt_num, :cust_name, :cust_phonenum, TO_DATE(:receipt_date, 'YYYY-MM-DD'), :total_receipt, :payment_method, :employee_id)";

$stmt_receipt = oci_parse($conn, $sql_receipt);
oci_bind_by_name($stmt_receipt, ':receipt_num', $receipt_num);
oci_bind_by_name($stmt_receipt, ':cust_name', $cust_name);
oci_bind_by_name($stmt_receipt, ':cust_phonenum', $cust_phonenum);
oci_bind_by_name($stmt_receipt, ':receipt_date', $receipt_date);
oci_bind_by_name($stmt_receipt, ':total_receipt', $total_receipt);
oci_bind_by_name($stmt_receipt, ':payment_method', $payment_method);
oci_bind_by_name($stmt_receipt, ':employee_id', $employee_id); // Bind employee_id
oci_execute($stmt_receipt);

// Insert the receipt details into the RECEIPTDETAIL table
$sql_receiptdetail = "
    INSERT INTO RECEIPTDETAIL (RECEIPT_NUM, ITEM_ID, ITEMQTY)
    VALUES (:receipt_num, :item_id, :item_qty)";

$stmt_receiptdetail = oci_parse($conn, $sql_receiptdetail);
oci_bind_by_name($stmt_receiptdetail, ':receipt_num', $receipt_num);
oci_bind_by_name($stmt_receiptdetail, ':item_id', $item_id);
oci_bind_by_name($stmt_receiptdetail, ':item_qty', $item_qty);
oci_execute($stmt_receiptdetail);

// Handle different payment methods
if ($payment_method == 'e_wallet') {
    // Insert into E_WALLET table
    $sql_e_wallet = "
        INSERT INTO E_WALLET (RECEIPT_NUM, EWALLET_NUM, EWALLET_TYPE)
        VALUES (:receipt_num, :transaction_id, :e_wallet_type)";

    $stmt_e_wallet = oci_parse($conn, $sql_e_wallet);
    oci_bind_by_name($stmt_e_wallet, ':receipt_num', $receipt_num);
    oci_bind_by_name($stmt_e_wallet, ':transaction_id', $transaction_id);
    oci_bind_by_name($stmt_e_wallet, ':e_wallet_type', $e_wallet_type);
    oci_execute($stmt_e_wallet);
} elseif ($payment_method == 'online') {
    // Insert into O_LINE table
    $sql_online = "
        INSERT INTO O_LINE (RECEIPT_NUM, BANK_NUM, BANK_NAME)
        VALUES (:receipt_num, :bank_number, :bank_name)";

    $stmt_online = oci_parse($conn, $sql_online);
    oci_bind_by_name($stmt_online, ':receipt_num', $receipt_num);
    oci_bind_by_name($stmt_online, ':bank_number', $bank_number);
    oci_bind_by_name($stmt_online, ':bank_name', $bank_name);
    oci_execute($stmt_online);
}

// Redirect to a confirmation page or back to the order form
header('Location: orderconfirmation.php?receipt_num=' . $receipt_num);
exit;
?>