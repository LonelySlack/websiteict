<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch item data from the database
$sql = "SELECT * FROM item";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Make an Order</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body {
            font-family: 'Inconsolata', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .order-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .order-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .order-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .order-form {
            display: flex;
            flex-direction: column;
        }

        .order-form label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .order-form input,
        .order-form select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .order-form button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .order-form button:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none;
        }

        .item-table-container {
            max-width: 800px;
            margin: 0 auto;
            margin-bottom: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-table th,
        .item-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .item-table th {
            background-color: #f2f2f2;
        }
    </style>
    <script>
        function showPaymentFields() {
            var paymentMethod = document.getElementById('payment_method').value;
            var eWalletFields = document.getElementById('e_wallet_fields');
            var onlineFields = document.getElementById('online_fields');

            if (paymentMethod === 'e_wallet') {
                eWalletFields.classList.remove('hidden');
                onlineFields.classList.add('hidden');
            } else if (paymentMethod === 'online') {
                eWalletFields.classList.add('hidden');
                onlineFields.classList.remove('hidden');
            } else {
                eWalletFields.classList.add('hidden');
                onlineFields.classList.add('hidden');
            }
        }
    </script>
</head>

<body>
    <div class="item-table-container">
        <h2>Available Items</h2>
        <table class="item-table">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_assoc($stmt)) { ?>
                    <tr>
                        <td><?php echo $row['ITEM_ID']; ?></td>
                        <td><?php echo $row['ITEM_NAME']; ?></td>
                        <td><?php echo $row['ITEM_PRICE']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="order-container">
        <div class="order-header">
            <h1>Make an Order</h1>
        </div>
        <form class="order-form" action="submitorder.php" method="POST">
            <label for="cust_name">Name:</label>
            <input type="text" id="cust_name" name="cust_name" required>

            <label for="cust_phonenum">Phone Number:</label>
            <input type="text" id="cust_phonenum" name="cust_phonenum" required>

            <label for="item_id">Item ID:</label>
            <input type="number" id="item_id" name="item_id" required>

            <label for="item_qty">Item Quantity:</label>
            <input type="number" id="item_qty" name="item_qty" required>

            <label for="pc_number">PC Number:</label>
            <input type="text" id="pc_number" name="pc_number">

            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id">

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required onchange="showPaymentFields()">
                <option value="">Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="e_wallet">E-Wallet</option>
                <option value="online">Online</option>
            </select>

            <div id="e_wallet_fields" class="hidden">
                <label for="e_wallet_type">E-Wallet Type:</label>
                <input type="text" id="e_wallet_type" name="e_wallet_type">

                <label for="transaction_id">E-Wallet Transaction ID:</label>
                <input type="text" id="transaction_id" name="transaction_id">
            </div>

            <div id="online_fields" class="hidden">
                <label for="bank_name">Bank Name:</label>
                <input type="text" id="bank_name" name="bank_name">

                <label for="bank_number">Bank Number:</label>
                <input type="text" id="bank_number" name="bank_number">
            </div>

            <button type="submit">Submit Order</button>
        </form>
    </div>
</body>

</html>