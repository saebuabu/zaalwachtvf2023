<?php
/**
 * Live Scraper Test - Shows exactly what the scraper finds
 */

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo "<h1>Live Scraper Diagnostic</h1>";
echo "<pre>";

$BASE_URL = 'https://www.verkadefabriek.nl/agenda';

// Fetch the first page
function fetchPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ($httpCode === 200) ? $html : false;
}

echo "=== FETCHING VERKADEFABRIEK AGENDA ===\n";
echo "URL: $BASE_URL\n\n";

$html = fetchPage($BASE_URL);

if (!$html) {
    echo "ERROR: Could not fetch page!\n";
    exit;
}

echo "Page fetched successfully (" . strlen($html) . " bytes)\n\n";

// Parse events
echo "=== PARSING EVENTS ===\n";

$dom = new DOMDocument();
@$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
$xpath = new DOMXPath($dom);

$eventLinks = $xpath->query('//a[contains(@class, "no_decoration")][@href]');

echo "Found " . $eventLinks->length . " event links\n\n";

$eventCount = 0;
foreach ($eventLinks as $link) {
    $href = $link->getAttribute('href');

    if (strpos($href, '/agenda/') !== 0) {
        continue;
    }

    $eventCount++;

    // Get date
    $dateElement = $xpath->query('.//span[@class="date"]', $link)->item(0);
    $dateString = $dateElement ? trim($dateElement->textContent) : 'NO DATE';

    // Get title
    $titleElement = $xpath->query('.//h3[contains(@class, "article-title")]', $link)->item(0);
    $title = $titleElement ? trim($titleElement->textContent) : 'NO TITLE';

    echo "Event #$eventCount:\n";
    echo "  Date String: '$dateString'\n";
    echo "  Title: $title\n";
    echo "  URL: $href\n";

    // Try to parse the date
    if (preg_match('/(\d{1,2})\s+(\w{3})\.?\s*-\s*(\d{1,2}):(\d{2})/', $dateString, $matches)) {
        echo "  Regex Match: YES\n";
        echo "    Day: {$matches[1]}\n";
        echo "    Month: {$matches[2]}\n";
        echo "    Hour: {$matches[3]}\n";
        echo "    Minute: {$matches[4]}\n";
    } else {
        echo "  Regex Match: FAILED - Date format not recognized!\n";
    }

    echo "\n";

    // Show first 10 events only to avoid overwhelming output
    if ($eventCount >= 10) {
        echo "... (showing first 10 events only)\n";
        break;
    }
}

if ($eventCount === 0) {
    echo "WARNING: No agenda events found on page!\n";
    echo "\nSearching for any date patterns in HTML...\n";

    // Look for any date-like patterns
    if (preg_match_all('/\d{1,2}\s+\w{3}\.?\s*-\s*\d{1,2}:\d{2}/', $html, $matches)) {
        echo "Found " . count($matches[0]) . " date patterns:\n";
        foreach (array_slice($matches[0], 0, 5) as $pattern) {
            echo "  - $pattern\n";
        }
    } else {
        echo "No date patterns found in HTML.\n";
    }
}

echo "\n=== DATE FILTER TEST ===\n";
$today = new DateTime();
$today->setTime(0, 0, 0);
$nextWeek = (new DateTime())->modify('+7 days');

echo "Today (midnight): " . $today->format('Y-m-d H:i:s') . "\n";
echo "Next week: " . $nextWeek->format('Y-m-d H:i:s') . "\n";

echo "</pre>";
?>
