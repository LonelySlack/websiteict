<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to update
if (isset($_GET['branch_id'])) {
    $branchId = $_GET['branch_id'];
} else {
    // Handle the case where branch_id is not provided in the URL
    echo "Error: branch_id not provided.";
    exit;
}

$sql = "SELECT * FROM BRANCH WHERE BRANCH_ID = :branch_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':branch_id', $branchId);
oci_define_by_name($stmt, 'BRANCH_ID', $row['BRANCH_ID']);
oci_define_by_name($stmt, 'BRANCH_NAME', $row['BRANCH_NAME']);
oci_define_by_name($stmt, 'BRANCH_ADD', $row['BRANCH_ADD']);
oci_define_by_name($stmt, 'BRANCH_PHONENUM', $row['BRANCH_PHONENUM']);
oci_define_by_name($stmt, 'BRANCH_EMAIL', $row['BRANCH_EMAIL']);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newBranchName = $_POST['branch_name'];
    $newBranchAddress = $_POST['branch_address'];
    $newBranchPhone = $_POST['branch_phone'];
    $newBranchEmail = $_POST['branch_email'];

    // Update the data
    $sql = "UPDATE BRANCH
            SET BRANCH_NAME = :branch_name,
                BRANCH_ADD = :branch_address,
                BRANCH_PHONENUM = :branch_phone,
                BRANCH_EMAIL = :branch_email
            WHERE BRANCH_ID = :branch_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':branch_name', $newBranchName);
    oci_bind_by_name($stmt, ':branch_address', $newBranchAddress);
    oci_bind_by_name($stmt, ':branch_phone', $newBranchPhone);
    oci_bind_by_name($stmt, ':branch_email', $newBranchEmail);
    oci_bind_by_name($stmt, ':branch_id', $branchId);
    oci_execute($stmt);

    // Redirect to the main page or display a success message
    header('Location: branch.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Branch</title>
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

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-group button.primary {
            background-color: #007bff;
            color: white;
        }

        .btn-group button.primary:hover {
            background-color: #0056b3;
        }

        .btn-group button.secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-group button.secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Branch</h1>
        <form method="post">
            <div class="form-group">
                <label for="branch_name">Branch Name:</label>
                <input type="text" id="branch_name" name="branch_name" value="<?php echo $row['BRANCH_NAME']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="branch_address">Branch Address:</label>
                <input type="text" id="branch_address" name="branch_address" value="<?php echo $row['BRANCH_ADD']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="branch_phone">Branch Phone:</label>
                <input type="text" id="branch_phone" name="branch_phone" value="<?php echo $row['BRANCH_PHONENUM']; ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="branch_email">Branch Email:</label>
                <input type="email" id="branch_email" name="branch_email" value="<?php echo $row['BRANCH_EMAIL']; ?>"
                    required>
            </div>

            <div class="btn-group">
                <button type="submit" class="primary">Update</button>
                <a href="branch.php" class="secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>