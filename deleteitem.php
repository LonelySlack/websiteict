<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to delete
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

// Handle the form submission (delete confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the data
    $sql = "DELETE FROM ITEM WHERE ITEM_ID = :item_id";
    $stmt = oci_parse($conn, $sql);
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
    <title>Delete Item</title>
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

        .delete-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .delete-container h1 {
            text-align: center;
        }

        .delete-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .delete-container table th,
        .delete-container table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .delete-container table th {
            background-color: #f2f2f2;
        }

        .delete-container form {
            margin-top: 20px;
            text-align: center;
        }

        .delete-container input[type="submit"],
        .delete-container a {
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

        .delete-container input[type="submit"]:hover,
        .delete-container a:hover {
            background-color: #dc3545;
        }

        .delete-container a {
            background-color: #6c757d;
            margin-left: 10px;
        }

        .delete-container a.btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="delete-container">
        <h1>Delete Item</h1>
        <p>Are you sure you want to delete the following item?</p>
        <table>
            <tr>
                <th>Item ID:</th>
                <td><?php echo $row['ITEM_ID']; ?></td>
            </tr>
            <tr>
                <th>Item Type:</th>
                <td><?php echo $row['ITEM_TYPE']; ?></td>
            </tr>
            <tr>
                <th>Item Name:</th>
                <td><?php echo $row['ITEM_NAME']; ?></td>
            </tr>
            <tr>
                <th>Item Price:</th>
                <td><?php echo $row['ITEM_PRICE']; ?></td>
            </tr>
        </table>
        <form method="post">
            <input type="submit" value="Delete" class="btn btn-danger">
            <a href="item.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>