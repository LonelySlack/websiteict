<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to delete
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

// Handle the form submission (delete confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the data
    $sql = "DELETE FROM BRANCH WHERE BRANCH_ID = :branch_id";
    $stmt = oci_parse($conn, $sql);
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
    <title>Delete Branch</title>
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

        .table-container {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .table-container th {
            background-color: #f0f0f0;
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
        }

        .btn-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-group button.primary {
            background-color: #dc3545;
            color: white;
        }

        .btn-group button.primary:hover {
            background-color: #c82333;
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
        <h1>Delete Branch</h1>
        <div class="table-container">
            <table>
                <tr>
                    <th>Branch ID:</th>
                    <td><?php echo $row['BRANCH_ID']; ?></td>
                </tr>
                <tr>
                    <th>Branch Name:</th>
                    <td><?php echo $row['BRANCH_NAME']; ?></td>
                </tr>
                <tr>
                    <th>Branch Address:</th>
                    <td><?php echo $row['BRANCH_ADD']; ?></td>
                </tr>
                <tr>
                    <th>Branch Phone:</th>
                    <td><?php echo $row['BRANCH_PHONENUM']; ?></td>
                </tr>
                <tr>
                    <th>Branch Email:</th>
                    <td><?php echo $row['BRANCH_EMAIL']; ?></td>
                </tr>
            </table>
        </div>
        <form method="post">
            <div class="btn-group">
                <button type="submit" class="primary">Delete</button>
                <a href="branch.php" class="secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>