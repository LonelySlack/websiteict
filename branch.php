<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch branch data from the database
$sql = "SELECT *
        FROM branch";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Branch List</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .w3-top {
            z-index: 1;
        }

        /* Styling for the navigation bar */
        .w3-button.w3-black:hover,
        .w3-button.w3-black:focus {
            background-color: #333 !important;
            color: #fff !important;
        }

        /* Styling for the page title */
        .page-title-container {
            text-align: center;
            margin: 20px 0;
        }

        .page-title-container h1 {
            margin-bottom: 20px;
        }

        /* Styling for the content container */
        .content-container {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }

        /* Styling for the table */
        .employee-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .employee-table th,
        .employee-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .employee-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .employee-table tr:hover {
            background-color: #f1f1f1;
        }

        /* Styling for action buttons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.btn-update {
            background-color: #28a745;
            /* Green color */
        }

        .btn.btn-update:hover {
            background-color: #218838;
            /* Darker green on hover */
        }

        .btn.btn-delete {
            background-color: #dc3545;
            /* Red color */
        }

        .btn.btn-delete:hover {
            background-color: #c82333;
        }

        /* Styling for Add Branch button */
        .add-branch-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation bar -->
    <div class="w3-top">
        <div class="w3-row w3-padding w3-black">
            <div class="w3-col s3">
                <a href="mainpage.php" class="w3-button w3-block w3-black" aria-label="Mainpage">Main Page</a>
            </div>
            <div class="w3-col s3">
                <a href="employee.php" class="w3-button w3-block w3-black" aria-label="Employee">Employee</a>
            </div>
            <div class="w3-col s3">
                <a href="item.php" class="w3-button w3-block w3-black" aria-label="Items">Items</a>
            </div>
            <div class="w3-col s3">
                <a href="receipt.php" class="w3-button w3-block w3-black" aria-label="Receipt">Receipt</a>
            </div>
        </div>
    </div>

    <!-- Page title -->
    <div class="page-title-container">
        <h1>Branch List</h1>
    </div>

    <!-- Content container -->
    <div class="content-container">
        <table class="employee-table">
            <thead>
                <tr>
                    <th>Branch ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_assoc($stmt)) { ?>
                    <tr>
                        <td><?php echo $row['BRANCH_ID']; ?></td>
                        <td><?php echo $row['BRANCH_NAME']; ?></td>
                        <td><?php echo $row['BRANCH_ADD']; ?></td>
                        <td><?php echo $row['BRANCH_PHONENUM']; ?></td>
                        <td><?php echo $row['BRANCH_EMAIL']; ?></td>
                        <td>
                            <a href="updatebranch.php?branch_id=<?php echo $row['BRANCH_ID']; ?>"
                                class="btn btn-update">Update</a>
                            <a href="deletebranch.php?branch_id=<?php echo $row['BRANCH_ID']; ?>" class="btn btn-delete"
                                onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Branch button -->
    <div class="add-branch-container">
        <a href="addbranch.php" class="btn btn-primary">Add Branch</a>
    </div>

</body>

</html>