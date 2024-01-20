<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<?php
session_start();
$itemNumber = $_POST['itemNumber'];
$newBid = $_POST['newBid'];

// Load the XML file and perform necessary validations
$xml = simplexml_load_file('./auction.xml');

foreach ($xml->listing as $listing) {
    if ($listing->itemNumber == $itemNumber) {
        if ($newBid > floatval($listing->currentBidPrice) && $listing->status != 'sold') {
            $listing->currentBidPrice = $newBid;
            $customerId = $_SESSION['customer_id'];

            $listing->customerId = $_SESSION['customer_id']; 

            $xml->asXML('./auction.xml');

            echo 'success';
        } else {
            echo 'error';
        }
        break;
    }
}

?>