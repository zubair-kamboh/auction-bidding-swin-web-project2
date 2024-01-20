<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- PHP LOGIC -->
<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}
?>

<!-- HTML CODE -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShopOnline - Bidding</title>
    <link rel="stylesheet" href="bid.css">
  </head>
  <body>
    <header>
      <h1 class="logo">ShopOnline</h1>
      <div class="navbar">
        <a href="./login.php">Home</a><a href="./createListing.php">Listing</a><a href="./bid.php">Bidding</a
        ><a href="./maintenence.php">Maintenence</a>
        <form action="logout.php" method="post" style="width: auto;">
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
      <p class="loginRegText">
        Current auction items are listed below. To place a bid for an item, use
        the Place Bid button, <span style="font-weight: bold">Note: </span> Item
        remaining time and bid prices are updated every 5 seconds.
      </p>
    </main>

<div class="boxes">
<div class="auction-items-container" id="auctionItemsContainer"></div>
</div>

<!-- JAVASCRIPT FUNCTIONS -->
<script>
  function updateAuctionItems() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_auction_items.php", true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        document.getElementById("auctionItemsContainer").innerHTML = xhr.responseText;
      }
    };
    xhr.send();
  }

  setInterval(updateAuctionItems, 5000);

  updateAuctionItems();
</script>
<script>
  function openBidPopup(itemNumber, currentBidPrice) {
    var newBid = prompt("Enter your bid price:");
    if (newBid !== null && !isNaN(newBid) && parseFloat(newBid) > currentBidPrice) {
        // Prepare the data to be sent in the request body
        var formData = new FormData();
        formData.append('itemNumber', itemNumber);
        formData.append('newBid', newBid);

        // Send the bid request to the server using fetch API
        fetch('updateBidPrice.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
                alert("Thank you! Your bid is recorded in ShopOnline.");
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        // Invalid bid price or user canceled
        alert(`Sorry, your bid is not valid. It should be greater than Current Bid Price: ${currentBidPrice}`);
    }
}
  function openBuyItNowPopup(itemNumber) {
        var formData = new FormData();
        formData.append('itemNumber', itemNumber);

        fetch('updateBidPriceAndStatus.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
                alert("Thank you for purchasing this item!");
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } 


</script>
</body>
</html>