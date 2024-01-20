<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    // Redirect TO LOGIN IF NOT SIGNED IN ALREADY
    header("Location: login.php");
    exit;
}
?>

<!-- HTML CODE FOR MAINTANENCE PAGE -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShopOnline - Maintenence</title>
    <link rel="stylesheet" href="login.css">
  </head>
  <body>
    <header>
      <h1 class="logo">ShopOnline</h1>
      <div class="navbar">
        <a href="./login.php">Home</a><a href="./createListing.php">Listing</a><a  href="./bid.php">Bidding</a
        ><a href="./maintenence.php">Maintenence</a>
        <form action="logout.php" method="post" style="padding:0px;">
          <button class="logoutbtn" style="  padding: 10px 25px;
  background-color: #333;
  color: white;
  border-radius: 15px;
  text-decoration: none;cursor: pointer;" type="submit">Logout</button>
        </form>
      </div>
      <hr />
    </header>
    <main>
      <p class="loginRegText">Process Auction Items And Generate Reports</p>
      <div class="loginbox">
        
        
          <div style="display: flex; margin: 10px 0px; align-items: center">
            <button class="loginBtn" style=' margin: auto; padding: 16px 25px; cursor: pointer;' onClick="alert('The process is completed!')">Process Auction Items</button>
            <button class="resetBtn" style='width: 30%; margin: auto; padding: 16px 25px; cursor: pointer;' onClick="generateReport()">Generate Reports</button>
          </div>
          
          
        </div>
        <div id="reportContainer" style="margin: 25px auto;"></div>

    </main>

    <script>
      // WHEN WE CLICK GENERATE REPORT BUTTON
        function generateReport(){
            fetch('generateReport.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('reportContainer').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
  </body>
</html>
