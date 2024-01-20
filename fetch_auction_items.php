<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- PHP LOGIC - TO FETCH ALL ITEMS -->
<?php
if (!file_exists('./auction.xml')) {
echo "No listings at the moment!";
} else {
    $xml = simplexml_load_file('./auction.xml');
foreach ($xml->listing as $listing) {
      $result = calculateTimeLeft($listing->startDate, $listing->startTime, $listing->duration, $listing->itemNumber);

      echo '<div class="box" style="margin-bottom: 20px;">';
      echo '<div class="container">';
      echo '<p class="p1">Item No:</p>';
      echo '<p class="p2">' . htmlspecialchars($listing->itemNumber) . '</p>';
      echo '</div>';
  
      echo '<div class="container">';
      echo '<p class="p1">Item Name:</p>';
      echo '<p class="p2">' . htmlspecialchars($listing->itemName) . '</p>';
      echo '</div>';
  
      echo '<div class="container">';
      echo '<p class="p1">Category:</p>';
      echo '<p class="p2">' . htmlspecialchars($listing->category) . '</p>';
      echo '</div>';
  
      echo '<div class="container">';
      echo '<p class="p1">Description:</p>';
      echo '<p class="p2">' . htmlspecialchars(substr($listing->description, 0, 30)) . '...</p>';
      echo '</div>';
  
      echo '<div class="container">';
      echo '<p class="p1">Buy-it-Now Price:</p>';
      echo '<p class="p2">' . htmlspecialchars($listing->buyItNowPrice) . '</p>';
      echo '</div>';
  
      echo '<div class="container">';
      echo '<p class="p1">Bid Price:</p>';
      echo '<p class="p2">' . htmlspecialchars($listing->currentBidPrice) . '</p>';
      echo '</div>';

      if ($listing->status != 'sold'){
        echo '<div class="newContainer">';
        echo $result['remainingSeconds'] !== 0 ? $result['timeLeft'] . 'remaining!' : '<p style="color: red;">Bid Ended!</p>';
        echo '<div style="display: flex; margin: 10px 0px; align-items: center">';
        echo $result['remainingSeconds'] !== 0 ? '
        <button class="loginBtn" style="cursor: pointer;" onclick="openBidPopup(\'' . htmlspecialchars($listing->itemNumber) . '\', \'' . htmlspecialchars($listing->currentBidPrice) . '\')">Place Bid</button>
        <button class="resetBtn" style="cursor: pointer;" onclick="openBuyItNowPopup(\'' . htmlspecialchars($listing->itemNumber) . '\')">Buy It Now</button>
        ' : '<p style="color: red;">This Item has Expired!</p>'; 
      }else{
        echo "<div class='newContainer'><p style='color: green;'>This item has been sold!</p></div>";
      }
      echo '</div>';
      echo '</div>';
      echo '</div>';
}
}

function calculateTimeLeft($startDate, $startTime, $duration, $itemNumber) {
date_default_timezone_set('Australia/Melbourne');

list($days, $hours, $minutes) = explode('-', $duration);

// time in seconds
$totalSeconds = ($days * 24 * 60 * 60) + ($hours * 60 * 60) + ($minutes * 60);

$currentDateTime = new DateTime();
$startDateTime = new DateTime($startDate . ' ' . $startTime);
$targetEndTime = clone $startDateTime;
$targetEndTime->modify("+$days days");
$targetEndTime->modify("+$hours hours");
$targetEndTime->modify("+$minutes minutes");

$timeLeft = $currentDateTime->diff($targetEndTime);

 // Calculate remaining seconds
 $remainingSeconds = $targetEndTime->getTimestamp() - $currentDateTime->getTimestamp();

if ($remainingSeconds <= 0) {
    $xml = simplexml_load_file('./auction.xml');
    foreach ($xml->listing as $listing) {
        if ($listing->itemNumber == strval($itemNumber) && $listing->status != 'expired') {
            $listing->status = 'expired';
            break;
        }
    }
    $xml->asXML('./auction.xml');
    $remainingSeconds = 0;
}

$timeLeftFormatted = '';
if ($timeLeft->d > 0) {
    $timeLeftFormatted .= $timeLeft->d . ' days ';
}
if ($timeLeft->h > 0) {
    $timeLeftFormatted .= $timeLeft->h . ' hours ';
}
if ($timeLeft->i > 0) {
    $timeLeftFormatted .= $timeLeft->i . ' minutes ';
}
if ($timeLeft->s > 0) {
    $timeLeftFormatted .= $timeLeft->s . ' seconds ';
}

return array('timeLeft' => $timeLeftFormatted, 'remainingSeconds' => $remainingSeconds);
}
?>