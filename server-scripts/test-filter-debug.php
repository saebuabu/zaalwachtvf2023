<?php
/**
 * Debug filter logic - shows why events are included/excluded
 */

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo "<h1>Filter Debug - Step by Step</h1>";
echo "<pre>";

// Dutch month mapping
$monthsNL = [
    'jan' => 1, 'feb' => 2, 'mrt' => 3, 'apr' => 4,
    'mei' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
    'sep' => 9, 'okt' => 10, 'nov' => 11, 'dec' => 12
];

// Set up date range
$today = new DateTime();
$today->setTime(0, 0, 0);
$nextWeek = (new DateTime())->modify('+7 days');

echo "=== DATE RANGE ===\n";
echo "Today (midnight): " . $today->format('Y-m-d H:i:s') . "\n";
echo "Next week: " . $nextWeek->format('Y-m-d H:i:s') . "\n\n";

// Test parsing specific dates
$testDates = [
    "za 8 nov. - 13:30",
    "za 8 nov. - 16:00",
    "zo 9 nov. - 14:00",
    "ma 11 nov. - 19:00",
];

echo "=== PARSING & FILTER TEST ===\n\n";

foreach ($testDates as $dateString) {
    echo "Testing: '$dateString'\n";

    // Parse
    if (preg_match('/(\d{1,2})\s+(\w{3})\.?\s*-\s*(\d{1,2}):(\d{2})/', $dateString, $matches)) {
        $day = (int)$matches[1];
        $monthStr = strtolower($matches[2]);
        $hour = (int)$matches[3];
        $minute = (int)$matches[4];

        echo "  Parsed: day=$day, month=$monthStr, hour=$hour, minute=$minute\n";

        if (isset($monthsNL[$monthStr])) {
            $month = $monthsNL[$monthStr];
            $referenceYear = date('Y');

            $eventDate = new DateTime();
            $eventDate->setDate($referenceYear, $month, $day);
            $eventDate->setTime($hour, $minute, 0);

            echo "  Initial DateTime: " . $eventDate->format('Y-m-d H:i:s') . "\n";

            // Check if date is more than a month in the past
            $now = new DateTime();
            $oneMonthAgo = (clone $now)->modify('-1 month');

            echo "  One month ago: " . $oneMonthAgo->format('Y-m-d H:i:s') . "\n";

            if ($eventDate < $oneMonthAgo) {
                echo "  Event is > 1 month in past, adding 1 year!\n";
                $eventDate->setDate($referenceYear + 1, $month, $day);
                $eventDate->setTime($hour, $minute, 0);
                echo "  Adjusted DateTime: " . $eventDate->format('Y-m-d H:i:s') . "\n";
            }

            // Filter check
            echo "  Filter check:\n";
            echo "    eventDate >= today? " . ($eventDate >= $today ? "YES" : "NO") . "\n";
            echo "    eventDate <= nextWeek? " . ($eventDate <= $nextWeek ? "YES" : "NO") . "\n";

            $willShow = ($eventDate >= $today && $eventDate <= $nextWeek);
            echo "  RESULT: " . ($willShow ? "✓ WILL SHOW" : "✗ FILTERED OUT") . "\n";

            if (!$willShow) {
                if ($eventDate < $today) {
                    echo "  Reason: Event is before today\n";
                } elseif ($eventDate > $nextWeek) {
                    echo "  Reason: Event is after next week\n";
                }
            }
        } else {
            echo "  ERROR: Month '$monthStr' not recognized\n";
        }
    } else {
        echo "  ERROR: Regex failed to parse\n";
    }

    echo "\n";
}

echo "</pre>";
?>
