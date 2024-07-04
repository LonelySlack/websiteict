<?php
// Start the session
session_start();

// Include the database connection file
include ('connection.php');

// Function to format dates from yyyy-mm-dd to dd-mm-yyyy
function formatDate($date)
{
    return date('d-m-Y', strtotime($date));
}

// Default to today's date for the report
if (!isset($_GET['date'])) {
    $date = date('Y-m-d'); // Today's date in yyyy-mm-dd format
} else {
    // Validate and sanitize the date input
    $date = $_GET['date'];
    $date = htmlspecialchars(strip_tags(trim($date)));
}

// Fetch orders for the specified date
$sql = "SELECT * FROM receipt r join receiptdetail rd on r.RECEIPT_NUM = rd.RECEIPT_NUM WHERE TRUNC(RECEIPT_DATE) = TO_DATE(:RECEIPT_DATE, 'YYYY-MM-DD')";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ':RECEIPT_DATE', $date);
oci_execute($stmt);

$orders = [];
while ($row = oci_fetch_assoc($stmt)) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Daily Report</title>
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
            <div class="w3-col s2">
                <a href="employee.php" class="w3-button w3-block w3-black">EMPLOYEES</a>
            </div>
            <div class="w3-col s2">
                <a href="item.php" class="w3-button w3-block w3-black">ITEMS</a>
            </div>
            <div class="w3-col s2">
                <a href="branch.php" class="w3-button w3-block w3-black">BRANCH</a>
            </div>
            <div class="w3-col s2">
                <a href="receipt.php" class="w3-button w3-block w3-black">RECEIPT</a>
            </div>
            <div class="w3-col s2">
                <a href="order.php" class="w3-button w3-block w3-black">PLACE ORDER</a>
            </div>
            <div class="w3-col s2">
                <a href="searchorder.php" class="w3-button w3-block w3-black">SEARCH ORDER</a>
            </div>
            <div class="w3-col s2">
                <a href="index.php" class="w3-button w3-block w3-black">BACK TO HOME</a>
            </div>
        </div>
    </div>

    <!-- Header with image -->
    <header class="bgimg w3-display-container w3-grayscale-min" id="home">
    </header>

    <!-- Displaying Daily Report -->
    <div class="w3-sand w3-grayscale w3-large">
        <div class="w3-container" id="dailyreport">
            <div class="w3-content" style="max-width: 700px">
                <h5 class="w3-center w3-padding-48"><span class="w3-tag w3-wide">DAILY REPORT</span></h5>

                <!-- Form for manual date input -->
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <p><input class="w3-input w3-padding-16 w3-border" type="date" required name="date"
                            value="<?php echo htmlspecialchars($date); ?>"></p>
                    <p><button class="w3-button w3-black" type="submit">SHOW DAILY REPORT</button></p>
                </form>

                <!-- Display orders for the selected date -->
                <?php if (count($orders) > 0): ?>
                    <div class="w3-card w3-padding">
                        <h5>Orders for <?php echo formatDate($date); ?></h5>
                        <table class="w3-table w3-striped w3-bordered">
                            <tr class="w3-black">
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Phone Number</th>
                                <th>Item ID</th>
                                <th>Item Quantity</th>
                                <th>PC Number</th>
                                <th>Employee ID</th>
                                <th>Order Date</th>
                            </tr>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['RECEIPT_NUM']); ?></td>
                                    <td><?php echo htmlspecialchars($order['CUST_NAME']); ?></td>
                                    <td><?php echo htmlspecialchars($order['CUST_PHONENUM']); ?></td>
                                    <td><?php echo htmlspecialchars($order['ITEM_ID']); ?></td>
                                    <td><?php echo htmlspecialchars($order['ITEMQTY']); ?></td>
                                    <td><?php echo htmlspecialchars($order['PC_NUM']); ?></td>
                                    <td><?php echo htmlspecialchars($order['EMPLOYEE_ID']); ?></td>
                                    <td><?php echo formatDate($order['RECEIPT_DATE']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No orders found for <?php echo formatDate($date); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-center w3-light-grey w3-padding-48 w3-large">
        <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" title="W3.CSS" target="_blank"
                class="w3-hover-text-green">w3.css</a></p>
    </footer>

</body>

</html>