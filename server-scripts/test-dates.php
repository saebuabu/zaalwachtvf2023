<?php
/**
 * Date Debugging Script for Verkadefabriek Scraper
 *
 * This script tests server date/time configuration and event date parsing
 * to diagnose why today's events are not showing up.
 */

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo "<h1>Server Date/Time Diagnostic Tool</h1>";
echo "<pre>";

// 1. Server timezone and date info
echo "=== SERVER CONFIGURATION ===\n";
echo "Current Timezone: " . date_default_timezone_get() . "\n";
echo "Server Date/Time: " . date('Y-m-d H:i:s') . "\n";
echo "Server Timestamp: " . time() . "\n";

// 2. DateTime object tests
echo "\n=== DATETIME OBJECT TESTS ===\n";
$today = new DateTime();
echo "Today (default): " . $today->format('Y-m-d H:i:s') . "\n";

$todayMidnight = new DateTime();
$todayMidnight->setTime(0, 0, 0);
echo "Today (midnight): " . $todayMidnight->format('Y-m-d H:i:s') . "\n";

$nextWeek = (new DateTime())->modify('+7 days');
echo "Next week (+7 days): " . $nextWeek->format('Y-m-d H:i:s') . "\n";

// 3. Test Dutch date parsing
echo "\n=== DUTCH DATE PARSING TESTS ===\n";

function parseDutchDate($dateString, $referenceYear = null) {
    if ($referenceYear === null) {
        $referenceYear = date('Y');
    }

    $monthsNL = [
        'jan' => 1, 'feb' => 2, 'mrt' => 3, 'apr' => 4,
        'mei' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
        'sep' => 9, 'okt' => 10, 'nov' => 11, 'dec' => 12
    ];

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

// Test with sample dates
$testDates = [
    "vr 8 nov. - 14:00",
    "za 9 nov. - 20:30",
    "zo 10 nov. - 15:00",
    "ma 11 nov. - 19:00",
    "di 12 nov. - 21:00"
];

foreach ($testDates as $testDate) {
    $parsed = parseDutchDate($testDate);
    if ($parsed) {
        echo "Input: '$testDate'\n";
        echo "  Parsed: " . $parsed->format('Y-m-d H:i:s') . "\n";

        // Check if it passes the filter
        $willShow = ($parsed >= $todayMidnight && $parsed <= $nextWeek);
        echo "  Will show in app: " . ($willShow ? "YES ✓" : "NO ✗") . "\n";

        // Show why it's filtered
        if (!$willShow) {
            if ($parsed < $todayMidnight) {
                echo "  Reason: Event is before today midnight (" . $todayMidnight->format('Y-m-d H:i:s') . ")\n";
            } elseif ($parsed > $nextWeek) {
                echo "  Reason: Event is after next week (" . $nextWeek->format('Y-m-d H:i:s') . ")\n";
            }
        }
        echo "\n";
    } else {
        echo "Input: '$testDate' - FAILED TO PARSE\n\n";
    }
}

// 4. Test the actual comparison logic
echo "\n=== COMPARISON TESTS ===\n";
$testEvent = parseDutchDate("vr 8 nov. - 14:00");
if ($testEvent) {
    echo "Test Event: " . $testEvent->format('Y-m-d H:i:s') . "\n";
    echo "Today Midnight: " . $todayMidnight->format('Y-m-d H:i:s') . "\n";
    echo "Next Week: " . $nextWeek->format('Y-m-d H:i:s') . "\n\n";

    echo "Comparison Results:\n";
    echo "  testEvent < todayMidnight: " . ($testEvent < $todayMidnight ? "TRUE (filtered out)" : "FALSE") . "\n";
    echo "  testEvent > nextWeek: " . ($testEvent > $nextWeek ? "TRUE (filtered out)" : "FALSE") . "\n";
    echo "  Should show: " . (($testEvent >= $todayMidnight && $testEvent <= $nextWeek) ? "YES" : "NO") . "\n";
}

// 5. PHP and timezone info
echo "\n=== PHP INFO ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "date.timezone ini: " . ini_get('date.timezone') . "\n";

// 6. All available timezones (just to check)
echo "\n=== TIMEZONE TEST (Europe/Amsterdam) ===\n";
date_default_timezone_set('Europe/Amsterdam');
$amsterdamNow = new DateTime();
echo "Amsterdam Time: " . $amsterdamNow->format('Y-m-d H:i:s T') . "\n";

echo "</pre>";
?>
