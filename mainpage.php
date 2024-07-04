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

// Handle form submission to daily report page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate and sanitize the date input
  $date = $_POST['report_date'];
  $date = htmlspecialchars(strip_tags(trim($date)));

  // Redirect to daily report page with the selected date
  header("Location: dailyreport.php?date=" . urlencode($date));
  exit();
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>W3.CSS Template</title>
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
      <div class="w3-col s3">
        <a href="dailyreport.php" class="w3-button w3-block w3-black">DAILY REPORT</a>
      </div>
    </div>
  </div>


  <!-- Header with image -->
  <header class="bgimg w3-display-container w3-grayscale-min" id="home">
  </header>

  

  <script>
    // Tabbed Menu
    function openMenu(evt, menuName) {
      var i, x, tablinks;
      x = document.getElementsByClassName("menu");
      for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("tablink");
      for (i = 0; i < x.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" w3-dark-grey", "");
      }
      document.getElementById(menuName).style.display = "block";
      evt.currentTarget.firstElementChild.className += " w3-dark-grey";
    }
    document.getElementById("myLink").click();
  </script>

</body>

</html>