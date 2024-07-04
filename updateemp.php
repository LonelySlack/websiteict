<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Fetch the data you want to update
$employeeId = $_GET['employee_id'];
$sql = "SELECT * FROM EMPLOYEE WHERE EMPLOYEE_ID = :employee_id";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':employee_id', $employeeId);
oci_define_by_name($stmt, 'EMPLOYEE_ID', $row['EMPLOYEE_ID']);
oci_define_by_name($stmt, 'FIRST_NAME', $row['FIRST_NAME']);
oci_define_by_name($stmt, 'LAST_NAME', $row['LAST_NAME']);
oci_define_by_name($stmt, 'EMPPHONENUM', $row['EMPPHONENUM']);
oci_define_by_name($stmt, 'EMPDOB', $row['EMPDOB']);
oci_define_by_name($stmt, 'SUPERVISOR_ID', $row['SUPERVISOR_ID']);
oci_define_by_name($stmt, 'BRANCH_ID', $row['BRANCH_ID']);
oci_define_by_name($stmt, 'HIRE_DATE', $row['HIRE_DATE']);
oci_execute($stmt);
$row = oci_fetch_assoc($stmt);

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];
    $newPhoneNum = $_POST['phone_number'];
    $newDOB = $_POST['dob'];
    $newSupervisorId = $_POST['supervisor_id'];
    $newBranchId = $_POST['branch_id'];
    $newHireDate = $_POST['hire_date'];

    // Update the data
    $sql = "UPDATE EMPLOYEE
            SET FIRST_NAME = :first_name,
                LAST_NAME = :last_name,
                EMPPHONENUM = :phone_number,
                EMPDOB = :dob,
                SUPERVISOR_ID = :supervisor_id,
                BRANCH_ID = :branch_id,
                HIRE_DATE = :hire_date
            WHERE EMPLOYEE_ID = :employee_id";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':first_name', $newFirstName);
    oci_bind_by_name($stmt, ':last_name', $newLastName);
    oci_bind_by_name($stmt, ':phone_number', $newPhoneNum);
    oci_bind_by_name($stmt, ':dob', $newDOB);
    oci_bind_by_name($stmt, ':supervisor_id', $newSupervisorId);
    oci_bind_by_name($stmt, ':branch_id', $newBranchId);
    oci_bind_by_name($stmt, ':hire_date', $newHireDate);
    oci_bind_by_name($stmt, ':employee_id', $employeeId);
    oci_execute($stmt);

    // Redirect to the main page or display a success message
    header('Location: employee.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn {
            margin-right: 10px;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Employee</h1>
        <form method="post">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="<?php echo htmlspecialchars($row['FIRST_NAME']); ?>" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?php echo htmlspecialchars($row['LAST_NAME']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number"
                    value="<?php echo htmlspecialchars($row['EMPPHONENUM']); ?>" required>
            </div>

            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="text" class="form-control" id="dob" name="dob"
                    value="<?php echo htmlspecialchars($row['EMPDOB']); ?>" required>
            </div>

            <div class="form-group">
                <label for="supervisor_id">Supervisor ID:</label>
                <input type="text" class="form-control" id="supervisor_id" name="supervisor_id"
                    value="<?php echo htmlspecialchars($row['SUPERVISOR_ID']); ?>" required>
            </div>

            <div class="form-group">
                <label for="branch_id">Branch ID:</label>
                <input type="text" class="form-control" id="branch_id" name="branch_id"
                    value="<?php echo htmlspecialchars($row['BRANCH_ID']); ?>" required>
            </div>

            <div class="form-group">
                <label for="hire_date">Hire Date:</label>
                <input type="text" class="form-control" id="hire_date" name="hire_date"
                    value="<?php echo htmlspecialchars($row['HIRE_DATE']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="employee.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>