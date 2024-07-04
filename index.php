<?php
session_start();

include ('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = oci_parse($conn, "SELECT * FROM employee WHERE FIRST_NAME = :username");
    oci_bind_by_name($stmt, ':username', $username);
    oci_execute($stmt);
    $result = oci_fetch_assoc($stmt);

    if ($result) {
        // Verify the password
        if ($password == $result['LAST_NAME']) { // assuming LAST_NAME contains hashed passwords
            $_SESSION['username'] = $username;
            header("Location: mainpage.php");
            exit;
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    oci_free_statement($stmt);
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>




        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f2f2; /* Initial background color */
    background-image: url('/websiteict/image/loginbg.jpg'); /* Specify the path to your image */
    background-size: cover; /* Ensure the image covers the entire background */
    background-position: center; /* Center the image */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    position: relative;
    overflow: hidden; /* Prevents horizontal scrollbars */
}

.container {
    background-color: black; /* Container background color */
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 420px;
    text-align: center;
    transition: transform 0.3s ease; /* Transform transition */
    z-index: 1; /* Ensure login box stays above background */
    position: relative; /* Ensure stacking context */
    margin-bottom: 20px; /* Space below the login box */
}

.container:hover {
    transform: translateY(-5px);
}

.container h2 {
    color: #ffffff;
    margin-bottom: 20px;
}

.container div {
    margin-bottom: 15px;
}

.container label {
    display: block;
    margin-bottom: 8px;
    color: #666;
    font-size: 14px;
    text-align: left;
}

.container input[type="text"],
.container input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

.container button {
    background-color: #451111;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease; /* Smooth transition for button background color */
}

.container button:hover {
    background-color: #8a3030;
    /* animation: wiggle 0.5s ease infinite; */ /* Remove animation */
}

.error {
    color: red;
    font-size: 14px;
    margin-top: 10px;
    text-align: left;
}

.signup-link {
    margin-top: 15px;
    font-size: 14px;
    color: #666;
}

.signup-link a {
    color: #4CAF50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.signup-link a:hover {
    color: #45a049;
}

.overlay-text {
    position: absolute;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    font-size: 4rem; /* Increased font size */
    font-weight: bold; /* Bold text */
    color: #fcf0f0; /* Text color */
    text-align: center;
    width: 100%;
   
}

@media (max-width: 480px) {
    .container {
        width: 90%; /* Adjusted width for smaller screens */
    }

    .overlay-text {
        font-size: 3rem; /* Smaller font size for smaller screens */
    }
}




    </style>
</head>

<body>
    <div class="container">
        <h2>Login to Your Account</h2>
        <?php if (isset($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
    <div class="overlay-text">
        WELCOME TO TEGUH ENIGMA
    </div>
</body>

</html>











