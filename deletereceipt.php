<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to delete
if (isset($_GET['receipt_num'])) {
    $receiptNum = $_GET['receipt_num'];
} else {
    // Handle the case where receipt_num is not provided in the URL
    echo "Error: receipt_num not provided.";
    exit;
}

// Fetch the receipt data
$sql = "SELECT * FROM receipt WHERE receipt_num = :receipt_num";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':receipt_num', $receiptNum);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

// Handle the form submission (delete confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start a transaction
    oci_execute(oci_parse($conn, "BEGIN"));

    // Delete from RECEIPTDETAIL
    $sql_receiptdetail = "DELETE FROM RECEIPTDETAIL WHERE receipt_num = :receipt_num";
    $stmt_receiptdetail = oci_parse($conn, $sql_receiptdetail);
    oci_bind_by_name($stmt_receiptdetail, ':receipt_num', $receiptNum);
    oci_execute($stmt_receiptdetail);

    // Delete from E_WALLET
    $sql_ewallet = "DELETE FROM E_WALLET WHERE receipt_num = :receipt_num";
    $stmt_ewallet = oci_parse($conn, $sql_ewallet);
    oci_bind_by_name($stmt_ewallet, ':receipt_num', $receiptNum);
    oci_execute($stmt_ewallet);

    // Delete from ONLINE_PAYMENT
    $sql_online = "DELETE FROM ONLINE_PAYMENT WHERE receipt_num = :receipt_num";
    $stmt_online = oci_parse($conn, $sql_online);
    oci_bind_by_name($stmt_online, ':receipt_num', $receiptNum);
    oci_execute($stmt_online);

    // Delete from RECEIPT
    $sql = "DELETE FROM receipt WHERE receipt_num = :receipt_num";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':receipt_num', $receiptNum);
    oci_execute($stmt);

    // Commit the transaction
    oci_execute(oci_parse($conn, "COMMIT"));

    // Redirect to the main page or display a success message
    header('Location: receipt.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-container {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .table-container th {
            background-color: #f0f0f0;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
        }

        .btn-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-group button.primary {
            background-color: #dc3545;
            color: white;
        }

        .btn-group button.primary:hover {
            background-color: #c82333;
        }

        .btn-group button.secondary {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-group button.secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Delete Receipt</h1>
        <div class="table-container">
            <table>
                <tr>
                    <th>Receipt Number:</th>
                    <td><?php echo htmlspecialchars($row['RECEIPT_NUM']); ?></td>
                </tr>
                <tr>
                    <th>Employee ID:</th>
                    <td><?php echo htmlspecialchars($row['EMPLOYEE_ID']); ?></td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td><?php echo htmlspecialchars($row['RECEIPT_DATE']); ?></td>
                </tr>
                <tr>
                    <th>Name:</th>
                    <td><?php echo htmlspecialchars($row['CUST_NAME']); ?></td>
                </tr>
                <tr>
                    <th>Customer Phone Number:</th>
                    <td><?php echo htmlspecialchars($row['CUST_PHONENUM']); ?></td>
                </tr>
                <tr>
                    <th>PC Number:</th>
                    <td><?php echo htmlspecialchars($row['PC_NUM']); ?></td>
                </tr>
                <tr>
                    <th>Total:</th>
                    <td><?php echo htmlspecialchars($row['TOTAL_RECEIPT']); ?></td>
                </tr>
                <tr>
                    <th>Payment Method:</th>
                    <td><?php echo htmlspecialchars($row['PAYMENT_METHOD']); ?></td>
                </tr>
            </table>
        </div>
        <form method="post">
            <div class="btn-group">
                <button type="submit" class="primary">Delete</button>
                <a href="receipt.php" class="secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>