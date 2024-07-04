<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Initialize receipt details array
$receipt = [];

// Check if receipt number is provided in the query parameter
if (isset($_GET['receipt_num'])) {
    // Sanitize input to prevent SQL injection
    $receipt_num = htmlspecialchars($_GET['receipt_num']);

    // SQL query to fetch receipt details
    $sql = "
    SELECT 
        RD.ITEMQTY,
        R.RECEIPT_NUM,
        R.CUST_NAME,
        R.CUST_PHONENUM,
        I.ITEM_NAME,
        I.ITEM_PRICE,
        E.FIRST_NAME,
        B.BRANCH_NAME,
        B.BRANCH_ADD,
        B.BRANCH_PHONENUM,
        B.BRANCH_EMAIL
    FROM 
        RECEIPTDETAIL RD
    JOIN 
        RECEIPT R
        ON RD.RECEIPT_NUM = R.RECEIPT_NUM
    JOIN 
        ITEM I
        ON RD.ITEM_ID = I.ITEM_ID
    JOIN 
        EMPLOYEE E
        ON R.EMPLOYEE_ID = E.EMPLOYEE_ID
    JOIN 
        BRANCH B
        ON E.BRANCH_ID = B.BRANCH_ID
    WHERE 
        R.RECEIPT_NUM = :receipt_num";

    // Parse and execute the query
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':receipt_num', $receipt_num);
    oci_execute($stmt);

    // Fetch the results
    $rows = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $rows[] = $row;
    }

    // Check if any results were returned
    if (!empty($rows)) {
        // Get the receipt details from the first row (since all rows will have the same receipt details)
        $receipt = $rows[0];
    } else {
        // No receipt found for the given receipt number
        echo "No receipt found for Receipt Number: $receipt_num";
        exit;
    }
} else {
    // Redirect to the page where receipt selection is done, if no receipt number is provided
    header("Location: generatereceipt.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt <?php echo htmlspecialchars($receipt['RECEIPT_NUM']); ?></title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body {
            font-family: 'Inconsolata', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .receipt-section {
            margin-bottom: 20px;
        }

        .receipt-section h2 {
            font-size: 18px;
            color: #333;
            border-bottom: 2px solid #f2f2f2;
            padding-bottom: 5px;
        }

        .receipt-section p {
            margin: 5px 0;
            color: #666;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .receipt-table th {
            background-color: #f9f9f9;
            color: #333;
        }

        .receipt-table td {
            background-color: #fff;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        @media print {
            body {
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                border-radius: 0;
                page-break-inside: avoid;
            }

            .receipt-footer {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>Receipt</h1>
            <p>Receipt Number: <?php echo htmlspecialchars($receipt['RECEIPT_NUM']); ?></p>
            <p>Date: <?php echo date("Y-m-d"); ?></p>
        </div>

        <div class="receipt-details">
            <div class="receipt-section">
                <h2>Customer Information</h2>
                <p>Name: <?php echo htmlspecialchars($receipt['CUST_NAME']); ?></p>
                <p>Phone Number: <?php echo htmlspecialchars($receipt['CUST_PHONENUM']); ?></p>
            </div>

            <div class="receipt-section">
                <h2>Employee Information</h2>
                <p>Employee: <?php echo htmlspecialchars($receipt['FIRST_NAME']); ?></p>
            </div>

            <div class="receipt-section">
                <h2>Branch Information</h2>
                <p>Branch: <?php echo htmlspecialchars($receipt['BRANCH_NAME']); ?></p>
                <p>Address: <?php echo htmlspecialchars($receipt['BRANCH_ADD']); ?></p>
                <p>Phone Number: <?php echo htmlspecialchars($receipt['BRANCH_PHONENUM']); ?></p>
                <p>Email: <?php echo htmlspecialchars($receipt['BRANCH_EMAIL']); ?></p>
            </div>

            <div class="receipt-section">
                <h2>Item Details</h2>
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Item Quantity</th>
                            <th>Item Name</th>
                            <th>Item Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ITEMQTY']); ?></td>
                                <td><?php echo htmlspecialchars($row['ITEM_NAME']); ?></td>
                                <td><?php echo number_format($row['ITEM_PRICE'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="total-amount">
                <?php
                $total = 0;
                foreach ($rows as $row) {
                    $total += $row['ITEMQTY'] * $row['ITEM_PRICE'];
                }
                echo "Total Amount: RM" . number_format($total, 2);
                ?>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>

</html>