<?php
session_start();
include ('connection.php');

$receipt_num = $_SESSION['receipt_num'];

// Fetch receipt details
$sql = "SELECT * FROM receipt WHERE receipt_num = :receipt_num";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':receipt_num', $receipt_num);
$stmt->execute();
$receipt = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch cart items
$sql = "SELECT * FROM receipt_detail WHERE receipt_num = :receipt_num";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':receipt_num', $receipt_num);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Redirect to receipt generator page
    header("Location: generatereceipt.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add relevant meta tags and links here -->
</head>

<body>
    <h2>Checkout</h2>
    <p>Receipt Number: <?php echo $receipt_num; ?></p>
    <p>Customer Name: <?php echo $receipt['customer_name']; ?></p>
    <p>Phone Number: <?php echo $receipt['phone_number']; ?></p>
    <p>PC Number: <?php echo $receipt['pc_number']; ?></p>
    <p>Employee ID: <?php echo $receipt['employee_id']; ?></p>
    <p>Payment Method: <?php echo $receipt['payment_method']; ?></p>

    <h3>Cart Items</h3>
    <ul>
        <?php foreach ($cart_items as $item): ?>
            <li>
                Item ID: <?php echo $item['item_id']; ?>, Quantity: <?php echo $item['item_qty']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="checkout.php">
        <button type="submit">Submit Order</button>
    </form>
</body>

</html>