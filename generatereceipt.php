<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch receipt data from the database
$sql = "SELECT * FROM receipt";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generate Receipt</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body {
            font-family: 'Inconsolata', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .w3-top {
            z-index: 1;
        }

        .page-title-container {
            text-align: center;
            margin: 20px 0;
        }

        .page-title-container h1 {
            margin-bottom: 20px;
        }

        .container {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .receipt-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .receipt-table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 8px 16px;
            font-size: 14px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .page-title-container h1 {
                font-size: 24px;
            }

            .btn {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="w3-top">
        <div class="w3-row w3-padding w3-black">
            <div class="w3-col s3">
                <a href="mainpage.php" class="w3-button w3-block w3-black">Main Page</a>
            </div>
            <div class="w3-col s3">
                <a href="item.php" class="w3-button w3-block w3-black">Items</a>
            </div>
            <div class="w3-col s3">
                <a href="branch.php" class="w3-button w3-block w3-black">Branch</a>
            </div>
            <div class="w3-col s3">
                <a href="employee.php" class="w3-button w3-block w3-black">Employees</a>
            </div>
        </div>
    </div>

    <div class="page-title-container">
        <h1>Select a Receipt to Print</h1>
    </div>

    <div class="container">
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Receipt Number</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_assoc($stmt)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['RECEIPT_NUM']); ?></td>
                        <td><?php echo htmlspecialchars($row['CUST_NAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['RECEIPT_DATE']); ?></td>
                        <td>
                            <button class="btn"
                                onclick="window.location.href='printreceipt.php?receipt_num=<?php echo htmlspecialchars($row['RECEIPT_NUM']); ?>'">Print</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>