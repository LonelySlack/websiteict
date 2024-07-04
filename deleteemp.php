<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to delete
$employeeId = $_GET['employee_id'];
$sql = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = :employee_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':employee_id', $employeeId);
oci_define_by_name($stmt, 'EMPLOYEE_ID', $row['EMPLOYEE_ID']);
oci_define_by_name($stmt, 'FIRST_NAME', $row['FIRST_NAME']);
oci_define_by_name($stmt, 'LAST_NAME', $row['LAST_NAME']);
oci_define_by_name($stmt, 'EMPPHONENUM', $row['EMPPHONENUM']);
oci_define_by_name($stmt, 'HIRE_DATE', $row['HIRE_DATE']);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

// Handle the form submission (delete confirmation)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the data
    $sql = "DELETE FROM EMPLOYEE WHERE EMPLOYEE_ID = :employee_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':employee_id', $employeeId);
    oci_execute($stmt);

    // Redirect to the main page or display a success message
    header('Location: employee.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee</title>
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
            margin-top: 20px;
            border-collapse: collapse;
        }

        .delete-container th,
        .delete-container td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .delete-container th {
            background-color: #f2f2f2;
        }

        .delete-container form {
            margin-top: 20px;
            text-align: center;
        }

        .delete-container .btn {
            padding: 10px 20px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .delete-container .btn.btn-danger {
            background-color: #dc3545;
        }

        .delete-container .btn.btn-danger:hover {
            background-color: #c82333;
        }

        .delete-container .btn.btn-secondary {
            background-color: #6c757d;
        }

        .delete-container .btn.btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="delete-container">
        <h1>Delete Employee</h1>
        <p>Are you sure you want to delete the following employee?</p>
        <table>
            <tr>
                <th>Employee ID:</th>
                <td><?php echo $row['EMPLOYEE_ID']; ?></td>
            </tr>
            <tr>
                <th>First Name:</th>
                <td><?php echo $row['FIRST_NAME']; ?></td>
            </tr>
            <tr>
                <th>Last Name:</th>
                <td><?php echo $row['LAST_NAME']; ?></td>
            </tr>
            <tr>
                <th>Phone Number:</th>
                <td><?php echo $row['EMPPHONENUM']; ?></td>
            </tr>
            <tr>
                <th>Hire Date:</th>
                <td><?php echo $row['HIRE_DATE']; ?></td>
            </tr>
        </table>
        <form method="post">
            <input type="submit" value="Delete" class="btn btn-danger">
            <a href="employee.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>