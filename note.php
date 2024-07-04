<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

try {
    // Retrieve the form data
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
    $hire_date = isset($_POST['hire_date']) ? $_POST['hire_date'] : null;
    $empphonenum = isset($_POST['empphonenum']) ? $_POST['empphonenum'] : null;
    $empdob = isset($_POST['empdob']) ? $_POST['empdob'] : null;
    $supervisor_id = isset($_POST['supervisor_id']) ? $_POST['supervisor_id'] : null;
    $branch_id = isset($_POST['branch_id']) ? $_POST['branch_id'] : null;

    // Check for missing data
    if (!$first_name || !$last_name || !$hire_date || !$empphonenum || !$empdob || !$branch_id) {
        throw new Exception("Missing required form data");
    }

    // Get the next value from the emp_num_seq sequence
    $stmt = oci_parse($conn, "SELECT emp_num_seq.NEXTVAL FROM dual");
    oci_execute($stmt);
    $new_emp_num = oci_fetch_array($stmt)[0];

    // Insert the new employee record
    $sql = "INSERT INTO employee (EMPLOYEE_ID, FIRST_NAME, LAST_NAME, HIRE_DATE, EMPPHONENUM, EMPDOB, SUPERVISOR_ID, BRANCH_ID) 
            VALUES (:emp_num, :first_name, :last_name, TO_DATE(:hire_date, 'YYYY-MM-DD'), :empphonenum, TO_DATE(:empdob, 'YYYY-MM-DD'), :supervisor_id, :branch_id)";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':emp_num', $new_emp_num);
    oci_bind_by_name($stmt, ':first_name', $first_name);
    oci_bind_by_name($stmt, ':last_name', $last_name);
    oci_bind_by_name($stmt, ':hire_date', $hire_date);
    oci_bind_by_name($stmt, ':empphonenum', $empphonenum);
    oci_bind_by_name($stmt, ':empdob', $empdob);
    oci_bind_by_name($stmt, ':branch_id', $branch_id);

    // Bind the supervisor_id parameter, using NULL if the value is not provided
    if (!empty($supervisor_id)) {
        oci_bind_by_name($stmt, ':supervisor_id', $supervisor_id);
    } else {
        $supervisor_id = null;
        oci_bind_by_name($stmt, ':supervisor_id', $supervisor_id, -1, SQLT_CHR);
    }

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);  // For oci_execute errors pass the statement handle
        throw new Exception($e['message']);
    }

    // Redirect to the employee.php page
    header("Location: employee.php");
    exit();
} catch (Exception $e) {
    error_log("Error adding employee: " . $e->getMessage());
    echo "Error: " . $e->getMessage(); // Temporarily show the error for debugging
} finally {
    CloseConn($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Employee</title>
</head>

<body>
    <h1>Add Employee</h1>
    <form method="post" action="addemployee.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="hire_date">Hire Date:</label>
        <input type="date" id="hire_date" name="hire_date" required><br><br>

        <label for="empphonenum">Phone Number:</label>
        <input type="tel" id="empphonenum" name="empphonenum" required><br><br>

        <label for="empdob">Date of Birth:</label>
        <input type="date" id="empdob" name="empdob" required><br><br>

        <label for="supervisor_id">Supervisor ID (optional):</label>
        <input type="number" id="supervisor_id" name="supervisor_id"><br><br>

        <label for="branch_id">Branch ID:</label>
        <input type="number" id="branch_id" name="branch_id" required><br><br>

        <input type="submit" value="Add Employee">
    </form>
</body>

</html>