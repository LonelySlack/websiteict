<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Initialize variables
$receipt_num = "";
$result = "";

// Check if form submitted with receipt number
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $receipt_num = $_POST['receipt_num'];

    // Query to retrieve order details based on receipt number
    $sql = "SELECT * FROM RECEIPT WHERE RECEIPT_NUM = :receipt_num";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':receipt_num', $receipt_num);
    oci_execute($stmt);

    // Fetch result
    $result = oci_fetch_assoc($stmt);

    if (!$result) {
        $result = "Order not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Order</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body,
        html {
            height: 100%;
            font-family: "Inconsolata", sans-serif;
        }

        .bgimg {
            background-position: center;
            background-size: cover;
            background-image: url("/websiteict/image/mainpagebg.png");
            min-height: 100%;
        }

        .menu {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Links (sit on top) -->
    <div class="w3-top">
        <div class="w3-row w3-padding w3-black">
            <div class="w3-col s3">
                <a href="employee.php" class="w3-button w3-block w3-black">EMPLOYEES</a>
            </div>
            <div class="w3-col s3">
                <a href="item.php" class="w3-button w3-block w3-black">ITEMS</a>
            </div>
            <div class="w3-col s3">
                <a href="branch.php" class="w3-button w3-block w3-black">BRANCH</a>
            </div>
            <div class="w3-col s3">
                <a href="receipt.php" class="w3-button w3-block w3-black">RECEIPT</a>
            </div>
            <div class="w3-col s3">
                <a href="order.php" class="w3-button w3-block w3-black">PLACE ORDER</a>
            </div>
            <div class="w3-col s3">
                <a href="searchorder.php" class="w3-button w3-block w3-black">SEARCH ORDER</a>
            </div>
        </div>
    </div>

    <!-- Header with image -->
    <header class="bgimg w3-display-container w3-grayscale-min" id="home">
    </header>

    <!-- Main content -->
    <div class="w3-sand w3-grayscale w3-large">

        <!-- Search Order Container -->
        <div class="w3-container" id="searchorder">
            <div class="w3-content" style="max-width:700px">
                <h5 class="w3-center w3-padding-64"><span class="w3-tag w3-wide">SEARCH ORDER</span></h5>

                <!-- Search Form -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Receipt Number" required
                            name="receipt_num" value="<?php echo $receipt_num; ?>"></p>
                    <p><button class="w3-button w3-black" type="submit">SEARCH</button></p>
                </form>

                <!-- Display Result -->
                <?php if ($result): ?>
                    <?php if (is_array($result)): ?>
                        <div class="w3-panel w3-leftbar w3-light-grey">
                            <p><strong>Receipt Number:</strong> <?php echo htmlspecialchars($result['RECEIPT_NUM']); ?></p>
                            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($result['CUST_NAME']); ?></p>
                            <p><strong>Customer Phone Number:</strong> <?php echo htmlspecialchars($result['CUST_PHONENUM']); ?>
                            </p>
                            <p><strong>Receipt Date:</strong> <?php echo htmlspecialchars($result['RECEIPT_DATE']); ?></p>
                            <p><strong>Total Receipt Amount:</strong> <?php echo htmlspecialchars($result['TOTAL_RECEIPT']); ?>
                            </p>
                            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($result['PAYMENT_METHOD']); ?></p>
                            <!-- Add more fields as needed -->
                        </div>
                    <?php else: ?>
                        <p><?php echo htmlspecialchars($result); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- End page content -->
    </div>

    <!-- Footer -->
    <footer class="w3-center w3-light-grey w3-padding-48 w3-large">
        <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank"
                class="w3-hover-text-green">w3.css</a></p>
    </footer>

</body>

</html>