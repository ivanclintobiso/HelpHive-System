<?php
function getCauseName($cause) {
    $names = [
        "environment" => "Environment",
        "education" => "Education",
        "community" => "Community Support",
        "animal" => "Animal Welfare"
    ];

    if (isset($names[$cause])) {
        return $names[$cause];
    }

    return ucfirst($cause);
}

function getDurationName($duration) {
    $names = [
        "one-day" => "One day",
        "few-days" => "A few days",
        "ongoing" => "Ongoing"
    ];

    if (isset($names[$duration])) {
        return $names[$duration];
    }

    return "Not set";
}

function getGoodForText($goodFor) {
    if ($goodFor == "") {
        return "Everyone";
    }

    $parts = explode(",", $goodFor);
    $cleanParts = [];

    foreach ($parts as $part) {
        $part = trim($part);

        if ($part != "") {
            $cleanParts[] = $part;
        }
    }

    if (count($cleanParts) == 0) {
        return "Everyone";
    }

    return implode(", ", $cleanParts);
}

function getTimeRangeText($startTime, $endTime) {
    if ($startTime == "" || $endTime == "") {
        return "To be announced";
    }

    return date("g:i A", strtotime($startTime)) . " - " . date("g:i A", strtotime($endTime));
}

function getActivityDateText($dateText) {
    if ($dateText == "" || $dateText == null) {
        return "No date";
    }

    return date("D, M j, Y", strtotime($dateText));
}

function getTextLines($text) {
    $lines = explode("\n", str_replace("\r", "", $text));
    $cleanLines = [];

    foreach ($lines as $line) {
        $line = trim($line);
        $line = ltrim($line, "-*\xE2\x80\xA2 ");

        if ($line != "") {
            $cleanLines[] = $line;
        }
    }

    return $cleanLines;
}

function getCauseList($causeAreas) {
    $causes = [];

    foreach (explode(",", $causeAreas) as $cause) {
        $cause = trim($cause);

        if ($cause != "") {
            $causes[] = $cause;
        }
    }

    return $causes;
}

function getLogoUrl($logo) {
    if ($logo == "") {
        return "";
    }

    return BASE_URL . "/frontend/assets/uploads/logos/" . $logo;
}

function getOpportunityImageUrl($image) {
    if ($image == "") {
        return "";
    }

    return BASE_URL . "/frontend/assets/uploads/opportunities/" . $image;
}
