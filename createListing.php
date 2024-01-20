<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

// Access the customer details from session variables
$customerId = $_SESSION['customer_id'];
$userEmail = $_SESSION['user_email'];
$userFirstName = $_SESSION['user_first_name'];
$userSurName = $_SESSION['user_sur_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $itemName = $_POST["ItemName"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $startPrice = $_POST["StartPrice"];
    $reservePrice = $_POST["ReservePrice"];
    $buyItNowPrice = $_POST["BuyItNowPrice"];
    $day = $_POST["day"];
    $hour = $_POST["hour"];
    $minute = $_POST["minute"];
    $duration = $day . '-' . $hour . '-' . $minute;
    $currentBidPrice = $startPrice;
    

    // Check if "Other" category is selected and use the provided category name
    if ($category === "Other") {
      $otherCategory = $_POST["OtherCategory"];
      if (!empty($otherCategory)) {
          $category = $otherCategory;
      } else {
          echo "Please provide a custom category for 'Other'.";
          exit;
      }
  }

    if ($startPrice > $reservePrice){
      echo "Reserve price cannot be less than starting price!";
      exit;
    }
    if ($reservePrice > $buyItNowPrice){
      echo "Reserve price can not be more than buy-it-now price!";
      exit;
    }

    $itemNumber = uniqid();

    date_default_timezone_set('Australia/Melbourne');
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i:s");

    // Prepare XML data
    $listingData = "<listing>";
    $listingData .= "<itemNumber>{$itemNumber}</itemNumber>";
    $listingData .= "<customerId>{$customerId}</customerId>";
    $listingData .= "<itemName>{$itemName}</itemName>";
    $listingData .= "<category>{$category}</category>";
    $listingData .= "<description>{$description}</description>"; 
    $listingData .= "<startPrice>{$startPrice}</startPrice>";
    $listingData .= "<reservePrice>{$reservePrice}</reservePrice>";
    $listingData .= "<buyItNowPrice>{$buyItNowPrice}</buyItNowPrice>";
    $listingData .= "<currentbidprice>{$currentBidPrice}</currentbidprice>";
    $listingData .= "<startDate>{$currentDate}</startDate>";
    $listingData .= "<startTime>{$currentTime}</startTime>";
    $listingData .= "<duration>{$duration}</duration>";
    $listingData .= "<status>in_progress</status>";
    $listingData .= "</listing>";

    $file = './auction.xml';
    if (!file_exists($file)) {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><listings></listings>');
    } else {
        $xml = simplexml_load_file($file);
    }

    $listing = $xml->addChild('listing');
    $listing->addChild('itemNumber', $itemNumber);
    $listing->addChild('customerId', $customerId);
    $listing->addChild('itemName', $itemName);
    $listing->addChild('category', $category);
    $listing->addChild('description', $description); 
    $listing->addChild('startPrice', $startPrice);
    $listing->addChild('reservePrice', $reservePrice);
    $listing->addChild('buyItNowPrice', $buyItNowPrice);
    $listing->addChild('currentBidPrice', $currentBidPrice);
    $listing->addChild('startDate', $currentDate);
    $listing->addChild('startTime', $currentTime);
    $listing->addChild('duration', $duration);
    $listing->addChild('status', 'in_progress');
    $xml->asXML($file);

    $confirmationMessage = "Thank you! Your item has been listed in ShopOnline. The item number is {$itemNumber}, and the bidding starts now: {$currentTime} on {$currentDate}.";
}

$categories = [];
$file = './auction.xml';
if (file_exists($file)) {
    $xml = simplexml_load_file($file);
    foreach ($xml->listing as $listing) {
        $category = (string)$listing->category;
        if (!in_array($category, $categories)) {
            $categories[] = $category;
        }
    }
}
?>

<!-- HTML CODE -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ShopOnline</title>
   <link rel="stylesheet" href="createListing.css">
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
      <p class="loginRegText">To create listing enter listing details below.</p>
      <div class="loginbox">
        <p>Seller Details</p>
        <p class="requiredFieldText">All feild are required</p>
        <form action="createListing.php" method="post">
          <div class="formdiv">
            <label for="ItemName">Item Name:</label>
            <input type="text" name="ItemName" id="ItemName" required/>
          </div>
    <div class="formdiv">
      <label for="category">Category:</label>
      <select style="width: 100%; padding: 7px" name="category" id="category">
      <option value="Home Appliances">Home Appliances</option>

        <?php
        foreach ($categories as $cat) {
            echo "<option value=\"$cat\">$cat</option>";
        }
        ?>
                      <option value="Other">Other</option>

      </select>
    </div>
          <div class="formdiv" id="otherCategoryDiv" style="display: none;">
  <label for="OtherCategory">Other Category:</label>
  <input type="text" name="OtherCategory" id="OtherCategory">
</div>

          <div class="formdiv">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required/>
          </div>
          <div class="formdiv">
            <label for="StartPrice">Start Price:</label>
            <input type="number" name="StartPrice" id="StartPrice" required/>
          </div>
          <div class="formdiv">
            <label for="ReservePrice">Reserve Price:</label>
            <input type="number" name="ReservePrice" id="ReservePrice" required/>
          </div>
          <div class="formdiv">
            <label for="BuyItNowPrice">Buy It Now Price:</label>
            <input type="number" name="BuyItNowPrice" id="BuyItNowPrice" required/>
          </div>
          <div class="durationDiv">
              <label class="durationLabel">Duration:</label>
              <div class="durationInputs">
                <label for="day">Day:</label>
                <input type="number" id="day" name="day" min="0" required>

                <label for="hour">Hour:</label>
                <input type="number" id="hour" name="hour" min="0" max="23" required>

                <label for="minute">Minute:</label>
                <input type="number" id="minute" name="minute" min="0" max="59" required>
                </div>
          </div>

          <div>
          <?php
            if (!empty($confirmationMessage)){
              echo '<p style="color: green;">' . $confirmationMessage . '</p>';
            }
          ?>
          </div>

          <div class="formdiv"
            style="
              display: flex;
              margin: 10px 0px;
              align-items: center;
              justify-content: center;
            "
          >
            <button class="loginBtn" type="submit">Listing</button>
            <button class="resetBtn" type="reset">Reset</button>
          </div>
        </form>
      </div>
    </main>

    <script>
  document.getElementById("category").addEventListener("change", function () {
    var otherCategoryDiv = document.getElementById("otherCategoryDiv");
    if (this.value === "Other") {
      otherCategoryDiv.style.display = "block";
    } else {
      otherCategoryDiv.style.display = "none";
    }
  });
</script>

  </body>
</html>
