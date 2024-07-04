<?php
session_start();
include ('connection.php');

// Check if the receipt number is set in the session
if (!isset($_SESSION['receipt_num'])) {
    // Redirect to main page if the receipt number is not set
    header("Location: mainpage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cust_name = $_POST['cust_name'];
    $cust_phonenum = $_POST['cust_phonenum'];
    $pc_number = $_POST['pc_number'];
    $employee_id = $_POST['employee_id'];
    $payment_method = $_POST['payment_method'];
    $receipt_num = $_SESSION['receipt_num'];

    // Update the receipt with the order details
    $sql = "UPDATE receipt SET 
            customer_name = :cust_name, 
            phone_number = :cust_phonenum,
            pc_number = :pc_number,
            employee_id = :employee_id,
            payment_method = :payment_method
            WHERE receipt_num = :receipt_num";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':cust_name', $cust_name);
    $stmt->bindParam(':cust_phonenum', $cust_phonenum);
    $stmt->bindParam(':pc_number', $pc_number);
    $stmt->bindParam(':employee_id', $employee_id);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':receipt_num', $receipt_num);
    $stmt->execute();

    // Redirect to item cart
    header("Location: item_cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body,
        html {
            height: 100%;
            font-family: "Inconsolata", sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <label for="cust_name">Name:</label>
            <input type="text" id="cust_name" name="cust_name" required>

            <label for="cust_phonenum">Phone Number:</label>
            <input type="text" id="cust_phonenum" name="cust_phonenum" required>

            <label for="pc_number">PC Number:</label>
            <input type="text" id="pc_number" name="pc_number">

            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id">

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="cash">Cash</option>
                <option value="e_wallet">E-Wallet</option>
                <option value="online">Online</option>
            </select>

            <button type="submit">Next</button>
        </form>
    </div>
</body>

</html>