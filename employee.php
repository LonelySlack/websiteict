<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch employee data from the database
$sql = "SELECT *
        FROM EMPLOYEE
        ";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee List</title>
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

        /* Styling for the employee table */
        .content-container {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }

        .employee-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            margin-top: 20px;
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
        }

        .btn.btn-delete {
            background-color: #dc3545;
            /* Red color */
        }

        .btn.btn-delete:hover {
            background-color: #c82333;
        }

        /* Styling for Add Employee button */
        .add-employee-container {
            text-align: center;
            margin: 20px 0;
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
                <a href="item.php" class="w3-button w3-block w3-black" aria-label="Items">Items</a>
            </div>
            <div class="w3-col s3">
                <a href="branch.php" class="w3-button w3-block w3-black" aria-label="Branch">Branch</a>
            </div>
            <div class="w3-col s3">
                <a href="receipt.php" class="w3-button w3-block w3-black" aria-label="Receipt">Receipt</a>
            </div>
        </div>
    </div>

    <!-- Page title -->
    <div class="page-title-container">
        <h1>Employee List</h1>
    </div>

    <!-- Employee table -->
    <div class="content-container">
        <table class="employee-table">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Hire Date</th>
                    <th>Phone Number</th>
                    <th>Employee DOB</th>
                    <th>Supervisor ID</th>
                    <th>Branch ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = oci_fetch_assoc($stmt)) { ?>
                    <tr>
                        <td><?php echo $row['EMPLOYEE_ID']; ?></td>
                        <td><?php echo $row['FIRST_NAME']; ?></td>
                        <td><?php echo $row['LAST_NAME']; ?></td>
                        <td><?php echo $row['HIRE_DATE']; ?></td>
                        <td><?php echo $row['EMPPHONENUM']; ?></td>
                        <td><?php echo $row['EMPDOB']; ?></td>
                        <td><?php echo $row['SUPERVISOR_ID']; ?></td>
                        <td><?php echo $row['BRANCH_ID']; ?></td>
                        <td>
                            <button type="button" class="btn btn-update"
                                onclick="window.location.href='updateemp.php?employee_id=<?php echo $row['EMPLOYEE_ID']; ?>'">Update</button>
                            <button type="button" class="btn btn-delete"
                                onclick="if(confirm('Are you sure you want to delete this record?')) { window.location.href='deleteemp.php?employee_id=<?php echo $row['EMPLOYEE_ID']; ?>' }">Delete</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add Employee button -->
    <div class="add-employee-container">
        <a href="addemployee.php" class="btn btn-primary">Add Employee</a>
    </div>

</body>

</html>