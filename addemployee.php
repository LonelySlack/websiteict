<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];
    $newPhoneNum = $_POST['phone_number'];
    $newHireDate = $_POST['hire_date'];
    $newEmpDOB = $_POST['empdob'];
    $newSupervisorID = $_POST['supervisor_id'] !== '' ? $_POST['supervisor_id'] : null;
    $newBranchID = $_POST['branch_id'];

    // Get the next value from the sequence
    $sql = "SELECT emp_num_seq.NEXTVAL AS next_val FROM DUAL";
    $stmt = oci_parse($conn, $sql);
    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        echo "Error executing query: " . $e['message'];
        exit;
    }
    $row = oci_fetch_assoc($stmt);
    $next_seq_value = $row['NEXT_VAL'];

    // Insert the new employee data
    $sql = "INSERT INTO EMPLOYEE (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, EMPPHONENUM, HIRE_DATE, EMPDOB, SUPERVISOR_ID, BRANCH_ID)
            VALUES (:employee_id, :first_name, :last_name, :phone_number, :hire_date, :empdob, :supervisor_id, :branch_id)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':employee_id', $next_seq_value);
    oci_bind_by_name($stmt, ':first_name', $newFirstName);
    oci_bind_by_name($stmt, ':last_name', $newLastName);
    oci_bind_by_name($stmt, ':phone_number', $newPhoneNum);
    oci_bind_by_name($stmt, ':hire_date', $newHireDate);
    oci_bind_by_name($stmt, ':empdob', $newEmpDOB);
    oci_bind_by_name($stmt, ':supervisor_id', $newSupervisorID, -1, OCI_B_INT);
    oci_bind_by_name($stmt, ':branch_id', $newBranchID);
    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        echo "Error executing query: " . $e['message'];
        exit;
    }

    // Redirect to employee.php after successful insertion
    header('Location: employee.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Employee</title>
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

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-container h1 {
            text-align: center;
        }

        .form-container label {
            display: block;
            margin-top: 10px;
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-container input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-container a {
            text-decoration: none;
            color: #007bff;
            display: block;
            text-align: center;
            margin-top: 10px;
        }

        .form-container a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Add Employee</h1>
        <form method="post" action="addemployee.php">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <label for="hire_date">Hire Date:</label>
            <input type="text" id="hire_date" name="hire_date" required>

            <label for="empdob">Date of Birth:</label>
            <input type="text" id="empdob" name="empdob" required>

            <label for="supervisor_id">Supervisor ID:</label>
            <input type="text" id="supervisor_id" name="supervisor_id">

            <label for="branch_id">Branch ID:</label>
            <input type="text" id="branch_id" name="branch_id" required>

            <input type="submit" value="Add" class="btn btn-primary">
            <a href="employee.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>