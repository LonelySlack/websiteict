<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inconsolata">
    <style>
        body {
            font-family: 'Inconsolata', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        .confirmation-header {
            margin-bottom: 20px;
        }

        .confirmation-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .confirmation-message {
            margin-bottom: 20px;
            color: #666;
        }

        .back-button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="confirmation-container">
        <div class="confirmation-header">
            <h1>Order Confirmation</h1>
        </div>
        <div class="confirmation-message">
            <p>Thank you for your order! Your receipt number is <?php echo htmlspecialchars($_GET['receipt_num']); ?>.
            </p>
        </div>
        <button class="back-button" onclick="window.location.href='mainpage.php'">Place Another Order</button>
    </div>
</body>

</html>