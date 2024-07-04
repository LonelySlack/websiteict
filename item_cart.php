<?php
session_start();
include ('connection.php');

$receipt_num = $_SESSION['receipt_num'];

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $item_id = $_POST['item_id'];
    $item_qty = $_POST['item_qty'];

    $sql = "INSERT INTO receipt_detail (receipt_num, item_id, item_qty) VALUES (:receipt_num, :item_id, :item_qty)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':receipt_num', $receipt_num);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->bindParam(':item_qty', $item_qty);
    $stmt->execute();
}

// Handle removing items from the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item'])) {
    $detail_id = $_POST['detail_id'];

    $sql = "DELETE FROM receipt_detail WHERE detail_id = :detail_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':detail_id', $detail_id);
    $stmt->execute();
}

// Fetch current cart items
$sql = "SELECT * FROM receipt_detail WHERE receipt_num = :receipt_num";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':receipt_num', $receipt_num);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add relevant meta tags and links here -->
</head>

<body>
    <h2>Receipt Number: <?php echo $receipt_num; ?></h2>
    <form method="post" action="item_cart.php">
        <label for="item_id">Item ID:</label>
        <input type="number" id="item_id" name="item_id" required>

        <label for="item_qty">Item Quantity:</label>
        <input type="number" id="item_qty" name="item_qty" required>

        <button type="submit" name="add_item">Add Item</button>
    </form>

    <h3>Cart Items</h3>
    <ul>
        <?php foreach ($cart_items as $item): ?>
            <li>
                Item ID: <?php echo $item['item_id']; ?>, Quantity: <?php echo $item['item_qty']; ?>
                <form method="post" action="item_cart.php" style="display:inline;">
                    <input type="hidden" name="detail_id" value="<?php echo $item['detail_id']; ?>">
                    <button type="submit" name="remove_item">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="checkout.php">
        <button type="submit">Proceed to Checkout</button>
    </form>
</body>

</html>