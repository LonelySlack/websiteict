<?php
// Start the session
session_start();

// Include the database connection file
include('connection.php');

// Initialize variables
$branch_name = $branch_address = $branch_phone = $branch_email = '';
$errors = array();

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate branch name
    if (empty(trim($_POST["branch_name"]))) {
        $errors[] = "Please enter branch name.";
    } else {
        $branch_name = trim($_POST["branch_name"]);
    }

    // Validate branch address
    if (empty(trim($_POST["branch_address"]))) {
        $errors[] = "Please enter branch address.";
    } else {
        $branch_address = trim($_POST["branch_address"]);
    }

    // Validate branch phone number
    if (empty(trim($_POST["branch_phone"]))) {
        $errors[] = "Please enter branch phone number.";
    } else {
        $branch_phone = trim($_POST["branch_phone"]);
    }

    // Validate branch email
    if (empty(trim($_POST["branch_email"]))) {
        $errors[] = "Please enter branch email.";
    } else {
        $branch_email = trim($_POST["branch_email"]);
    }

    // If no errors, insert into database
    if (empty($errors)) {
        // Prepare an insert statement
        $sql = "INSERT INTO branch (BRANCH_ID, BRANCH_NAME, BRANCH_ADD, BRANCH_PHONENUM, BRANCH_EMAIL) 
                VALUES (branch_num_seq.nextval, :branch_name, :branch_address, :branch_phone, :branch_email)";

        if ($stmt = oci_parse($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            oci_bind_by_name($stmt, ":branch_name", $branch_name);
            oci_bind_by_name($stmt, ":branch_address", $branch_address);
            oci_bind_by_name($stmt, ":branch_phone", $branch_phone);
            oci_bind_by_name($stmt, ":branch_email", $branch_email);

            // Attempt to execute the prepared statement
            if (oci_execute($stmt)) {
                // Redirect to branch.php upon successful addition
                header("location: branch.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        oci_free_statement($stmt);
    }

    // Close connection
    oci_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Branch</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        /* Basic styling */
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

        /* Styling for navigation bar */
        .w3-button.w3-black:hover,
        .w3-button.w3-black:focus {
            background-color: #333 !important;
            color: #fff !important;
        }

        /* Styling for page title */
        .page-title-container {
            text-align: center;
            margin: 20px 0;
        }

        .page-title-container h1 {
            margin-bottom: 20px;
        }

        /* Styling for form */
        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        .form-container input[type=text],
        .form-container input[type=email] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-container input[type=submit] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-container input[type=submit]:hover {
            background-color: #0056b3;
        }

        /* Container for form */
        .container {
            flex: 1;
            padding: 20px;
            overflow: auto;
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
        <h1>Add Branch</h1>
    </div>

    <!-- Form container -->
    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Branch Name</label>
                <input type="text" name="branch_name" value="<?php echo htmlspecialchars($branch_name); ?>">
            </div>
            <div class="form-group">
                <label>Branch Address</label>
                <input type="text" name="branch_address" value="<?php echo htmlspecialchars($branch_address); ?>">
            </div>
            <div class="form-group">
                <label>Branch Phone Number</label>
                <input type="text" name="branch_phone" value="<?php echo htmlspecialchars($branch_phone); ?>">
            </div>
            <div class="form-group">
                <label>Branch Email</label>
                <input type="email" name="branch_email" value="<?php echo htmlspecialchars($branch_email); ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="Add Branch">
            </div>
        </form>
        <?php
        // Display errors if any
        if (!empty($errors)) {
            echo '<div>';
            foreach ($errors as $error) {
                echo '<p style="color:red;">' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>
    </div>

</body>

</html>
