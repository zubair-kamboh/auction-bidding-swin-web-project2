<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- THIS PHP CODE RUNS WHEN USER CLICKS ON GENERATE REPORT BUTTON -->
<?php
$file = './auction.xml';
if (file_exists($file)){
    $xml = simplexml_load_file('./auction.xml');
}

$soldItemsCount = 0;
$failedItemsCount = 0;
$revenue = 0;

$soldItems = [];
$failedItems = [];

if (!file_exists('./auction.xml')) {
    echo "No listings at the moment!";
} else {
    $xml = simplexml_load_file('./auction.xml');

    foreach ($xml->listing as $listing) {
        if ($listing->status == 'sold') {
            // Compute revenue for sold items (3% of sold price)
            $revenue += 0.03 * floatval($listing->currentBidPrice);
            $soldItemsCount++;
            $soldItems[] = $listing;
        } elseif ($listing->status == 'expired') {
            // Compute revenue for failed items (1% of reserved price)
            $revenue += 0.01 * floatval($listing->reservePrice);
            $failedItemsCount++;
            $failedItems[] = $listing;
        }
    }

    // Display table and revenue
    echo '<table border="1" style="margin: auto; font-family: Arial, sans-serif; border-collapse: collapse; width: 100%;">';
    echo '<tr><th style="padding: 8px;">Item Number</th><th style="padding: 8px;">Customer ID</th><th style="padding: 8px;">Item Name</th><th style="padding: 8px;">Category</th><th style="padding: 8px;">Start Price</th><th style="padding: 8px;">Reserve Price</th><th style="padding: 8px;">Current Bid Price</th><th style="padding: 8px;">Start Date</th><th>Start Time</th><th style="padding: 8px;">Status</th><th style="padding: 8px;">Revenue</th></tr>';
    
    foreach ($soldItems as $listing) {
        $itemRevenue = 0.03 * floatval($listing->currentBidPrice);
        echo '<tr>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->itemNumber) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->customerId) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->itemName) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->category) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startPrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->reservePrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->currentBidPrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startDate) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startTime) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">sold</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . number_format($itemRevenue, 2) . '</td>';
        echo '</tr>';
    }

    foreach ($failedItems as $listing) {
        $itemRevenue = 0.01 * floatval($listing->reservePrice);
        echo '<tr>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->itemNumber) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->customerId) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->itemName) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->category) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startPrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->reservePrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->currentBidPrice) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startDate) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($listing->startTime) . '</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">failed</td>';
        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . number_format($itemRevenue, 2) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    echo '<p style="text-align: center; margin: 8px 0px; background: #333; color: #fff; padding: 8px;">Total Sold Items: ' . $soldItemsCount . '</p>';
    echo '<p style="text-align: center; margin: 8px 0px; background: #333; color: #fff; padding: 8px;">Total Failed Items: ' . $failedItemsCount . '</p>';
    echo '<p style="text-align: center; margin: 8px 0px; background: #333; color: #fff; padding: 8px;">Total Revenue: ' . $revenue . '</p>';

    $updatedXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><listings></listings>');
    foreach ($xml->listing as $listing) {
        if ($listing->status != 'sold' && $listing->status != 'expired') {
            $updatedListing = $updatedXml->addChild('listing');
            $updatedListing->addChild('itemNumber', $listing->itemNumber);
            $updatedListing->addChild('customerId', $listing->customerId);
            $updatedListing->addChild('itemName', $listing->itemName);
            $updatedListing->addChild('category', $listing->category);
            $updatedListing->addChild('description', $listing->description);
            $updatedListing->addChild('startPrice', $listing->startPrice);
            $updatedListing->addChild('reservePrice', $listing->reservePrice);
            $updatedListing->addChild('buyItNowPrice', $listing->buyItNowPrice);
            $updatedListing->addChild('currentBidPrice', $listing->currentBidPrice);
            $updatedListing->addChild('startDate', $listing->startDate);
            $updatedListing->addChild('startTime', $listing->startTime);
            $updatedListing->addChild('duration', $listing->duration);
            $updatedListing->addChild('status', $listing->status);
        }
    }
    $updatedXml->asXML('./auction.xml');
}
?>
