<?php
/**
 * Verkadefabriek Agenda Scraper
 *
 * This script scrapes the Verkadefabriek agenda page and returns event data as JSON.
 * It fetches events from today until next week, handling pagination automatically.
 *
 * Usage: https://itstudy.eu/zaalwacht/agendascraper.php?api_key=YOUR_KEY&language=nl_NL
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration
$BASE_URL = 'https://www.verkadefabriek.nl/agenda';
$MAX_PAGES = 10; // Safety limit to prevent infinite loops

// Simple API key check (optional - match your existing API)
$api_key = isset($_GET['api_key']) ? $_GET['api_key'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : 'nl_NL';

// Date range: today until next week (7 days from now)
$today = new DateTime();
$nextWeek = (new DateTime())->modify('+7 days');

/**
 * Fetch HTML content from URL with proper headers
 */
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

    if ($httpCode !== 200) {
        return false;
    }

    return $html;
}

/**
 * Parse Dutch date string to DateTime object
 * Example: "ma 20 okt. - 13:20" => DateTime object
 */
function parseDutchDate($dateString, $referenceYear = null) {
    if ($referenceYear === null) {
        $referenceYear = date('Y');
    }

    // Dutch month abbreviations
    $monthsNL = [
        'jan' => 1, 'feb' => 2, 'mrt' => 3, 'apr' => 4,
        'mei' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
        'sep' => 9, 'okt' => 10, 'nov' => 11, 'dec' => 12
    ];

    // Extract day, month, and time from string like "ma 20 okt. - 13:20"
    if (preg_match('/(\d{1,2})\s+(\w{3})\.?\s*-\s*(\d{1,2}):(\d{2})/', $dateString, $matches)) {
        $day = (int)$matches[1];
        $monthStr = strtolower($matches[2]);
        $hour = (int)$matches[3];
        $minute = (int)$matches[4];

        if (isset($monthsNL[$monthStr])) {
            $month = $monthsNL[$monthStr];

            try {
                $date = new DateTime();
                $date->setDate($referenceYear, $month, $day);
                $date->setTime($hour, $minute, 0);

                // If the date is in the past (more than a month ago), it's probably next year
                $now = new DateTime();
                if ($date < $now->modify('-1 month')) {
                    $date->setDate($referenceYear + 1, $month, $day);
                    $date->setTime($hour, $minute, 0);
                }

                return $date;
            } catch (Exception $e) {
                return null;
            }
        }
    }

    return null;
}

/**
 * Parse events from HTML content
 */
function parseEvents($html, $todayDate, $nextWeekDate) {
    $events = [];

    // Use DOMDocument to parse HTML
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
    $xpath = new DOMXPath($dom);

    // Find all event links (pattern: <a class="no_decoration" href="/agenda/...">)
    $eventLinks = $xpath->query('//a[contains(@class, "no_decoration")][@href]');

    foreach ($eventLinks as $link) {
        $href = $link->getAttribute('href');

        // Only process agenda event URLs
        if (strpos($href, '/agenda/') !== 0) {
            continue;
        }

        // Get the wrapper div
        $wrapper = $xpath->query('.//div[contains(@class, "article-wrapper")]', $link)->item(0);
        if (!$wrapper) {
            continue;
        }

        // Extract event type (Film, Podium, etc.)
        $eventType = '';
        if (preg_match('/article-wrapper\s+(\w+)/', $wrapper->getAttribute('class'), $matches)) {
            $eventType = $matches[1];
        }

        // Extract date
        $dateElement = $xpath->query('.//span[@class="date"]', $link)->item(0);
        $dateString = $dateElement ? trim($dateElement->textContent) : '';

        // Extract title
        $titleElement = $xpath->query('.//h3[contains(@class, "article-title")]', $link)->item(0);
        $title = $titleElement ? trim($titleElement->textContent) : '';

        // Extract description
        $descElement = $xpath->query('.//div[@class="description"]/p', $link)->item(0);
        $description = $descElement ? trim($descElement->textContent) : '';

        // Extract sold out status
        $ticketStatus = $xpath->query('.//div[@class="ticket-status"]', $link)->item(0);
        $soldOut = '';
        if ($ticketStatus && trim($ticketStatus->textContent) !== '') {
            $statusText = strtolower(trim($ticketStatus->textContent));
            if (strpos($statusText, 'uitverkocht') !== false || strpos($statusText, 'sold out') !== false) {
                $soldOut = 'UITVERKOCHT';
            }
        }

        // Parse the date
        $eventDate = parseDutchDate($dateString);

        // Skip if we couldn't parse the date
        if (!$eventDate) {
            continue;
        }

        // Filter: only events from today until next week
        if ($eventDate < $todayDate || $eventDate > $nextWeekDate) {
            continue;
        }

        // Format the output to match the expected structure
        // Expected: {"tijd": "13:20", "dag": "ma 20 okt", "naam": "...", "type": "Film", "id": "156153", "url": "...", "soldout": ""}
        $dagPart = preg_replace('/\s*-\s*\d{1,2}:\d{2}.*$/', '', $dateString); // Remove time part
        $tijdPart = '';
        if (preg_match('/(\d{1,2}:\d{2})/', $dateString, $matches)) {
            $tijdPart = $matches[1];
        }

        // Extract ID from URL
        $eventId = '';
        if (preg_match('/\/agenda\/[^\/]+-(\d+)$/', $href, $matches)) {
            $eventId = $matches[1];
        }

        $fullUrl = 'https://www.verkadefabriek.nl' . $href;

        $events[] = [
            'tijd' => $tijdPart,
            'dag' => $dagPart,
            'naam' => $title,
            'type' => $eventType,
            'id' => $eventId,
            'url' => $fullUrl,
            'soldout' => $soldOut,
            'description' => $description
        ];
    }

    return $events;
}

/**
 * Check if there's a next page
 */
function hasNextPage($html) {
    return strpos($html, 'rel="next"') !== false;
}

/**
 * Main scraping function
 */
function scrapeAgenda($baseUrl, $maxPages, $today, $nextWeek) {
    $allEvents = [];
    $page = 1;
    $foundEventsInRange = false;

    while ($page <= $maxPages) {
        $url = $baseUrl . ($page > 1 ? '?page=' . $page : '');

        $html = fetchPage($url);
        if ($html === false) {
            break;
        }

        $events = parseEvents($html, $today, $nextWeek);

        if (!empty($events)) {
            $allEvents = array_merge($allEvents, $events);
            $foundEventsInRange = true;
        } else if ($foundEventsInRange) {
            // If we found events before but not anymore, we can stop
            break;
        }

        // Check if there's a next page
        if (!hasNextPage($html)) {
            break;
        }

        $page++;

        // Small delay to be respectful to the server
        usleep(250000); // 250ms
    }

    return $allEvents;
}

// Main execution
try {
    $events = scrapeAgenda($BASE_URL, $MAX_PAGES, $today, $nextWeek);

    // Sort events by date/time
    usort($events, function($a, $b) {
        return strcmp($a['dag'] . ' ' . $a['tijd'], $b['dag'] . ' ' . $b['tijd']);
    });

    echo json_encode($events, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Scraping failed',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
