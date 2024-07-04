<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_type = $_POST['item_type'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];

    // SQL statement to fetch the next value from the sequence and insert new item into database
    $sql_insert = "INSERT INTO item (ITEM_ID, ITEM_TYPE, ITEM_NAME, ITEM_PRICE) 
                   VALUES (item_id_seq.nextval, :item_type, :item_name, :item_price)";

    // Prepare the SQL statement
    $stmt_insert = oci_parse($conn, $sql_insert);

    // Bind parameters
    oci_bind_by_name($stmt_insert, ':item_type', $item_type);
    oci_bind_by_name($stmt_insert, ':item_name', $item_name);
    oci_bind_by_name($stmt_insert, ':item_price', $item_price);

    // Execute the SQL statement
    $success = oci_execute($stmt_insert);

    if ($success) {
        // Redirect to items list page after successful insertion
        header("Location: item.php");
        exit;
    } else {
        // Handle errors if insertion fails
        $error_message = "Failed to add item.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Item</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        /* Add some basic styling */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            text-align: center;
        }

        .form-container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }

        .form-container input[type=text],
        .form-container input[type=number] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        .form-container input[type=submit] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container input[type=submit]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Add Item</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="item_type">Item Type:</label><br>
                <input type="text" id="item_type" name="item_type" required><br>

                <label for="item_name">Item Name:</label><br>
                <input type="text" id="item_name" name="item_name" required><br>

                <label for="item_price">Item Price:</label><br>
                <input type="number" id="item_price" name="item_price" step="0.01" required><br>

                <input type="submit" value="Submit">
            </form>
            <?php if (isset($error_message)) { ?>
                <div class="error"><?php echo $error_message; ?></div>
            <?php } ?>
        </div>
    </div>
</body>

</html>