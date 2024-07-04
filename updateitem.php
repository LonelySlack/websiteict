<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to update
if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];
} else {
    // Handle the case where item_id is not provided in the URL
    echo "Error: item_id not provided.";
    exit;
}

$sql = "SELECT * FROM ITEM WHERE ITEM_ID = :item_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':item_id', $itemId);
oci_define_by_name($stmt, 'ITEM_ID', $row['ITEM_ID']);
oci_define_by_name($stmt, 'ITEM_TYPE', $row['ITEM_TYPE']);
oci_define_by_name($stmt, 'ITEM_NAME', $row['ITEM_NAME']);
oci_define_by_name($stmt, 'ITEM_PRICE', $row['ITEM_PRICE']);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newItemType = $_POST['item_type'];
    $newItemName = $_POST['item_name'];
    $newItemPrice = $_POST['item_price'];

    // Update the data
    $sql = "UPDATE ITEM
            SET ITEM_TYPE = :item_type,
                ITEM_NAME = :item_name,
                ITEM_PRICE = :item_price
            WHERE ITEM_ID = :item_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':item_type', $newItemType);
    oci_bind_by_name($stmt, ':item_name', $newItemName);
    oci_bind_by_name($stmt, ':item_price', $newItemPrice);
    oci_bind_by_name($stmt, ':item_id', $itemId);
    oci_execute($stmt);

    // Redirect to the main page or display a success message
    header('Location: item.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .update-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .update-container h1 {
            text-align: center;
        }

        .update-container form {
            margin-top: 20px;
        }

        .update-container label {
            display: block;
            margin-top: 10px;
        }

        .update-container input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .update-container input[type="submit"],
        .update-container a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .update-container input[type="submit"]:hover,
        .update-container a:hover {
            background-color: #007bff;
        }

        .update-container a {
            background-color: #6c757d;
            margin-left: 10px;
        }

        .update-container a.btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="update-container">
        <h1>Update Item</h1>
        <form method="post">
            <label for="item_type">Item Type:</label>
            <input type="text" id="item_type" name="item_type"
                value="<?php echo htmlspecialchars($row['ITEM_TYPE']); ?>" required>

            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name"
                value="<?php echo htmlspecialchars($row['ITEM_NAME']); ?>" required>

            <label for="item_price">Item Price:</label>
            <input type="text" id="item_price" name="item_price"
                value="<?php echo htmlspecialchars($row['ITEM_PRICE']); ?>" required>

            <input type="submit" value="Update" class="btn btn-primary">
            <a href="item.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>