<?php
include __DIR__ . "/../access-control/organization-required.php";


function cleanCsvText($text) {
    $firstLetter = substr($text, 0, 1);

    if ($firstLetter == "=" || $firstLetter == "+" || $firstLetter == "-" || $firstLetter == "@") {
        return "'" . $text;
    }

    return $text;
}


$period = getTextFromUrl("period");
$opportunityId = (int) getTextFromUrl("opportunity");

$opportunities = getOrganizationOpportunities($connection, $organization["id"]);
$records = getOrganizationVolunteerRecords($connection, $organization["id"]);

$reportOpportunities = getReportOpportunities($opportunities, $period, $opportunityId);

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=participation-report.csv");

$output = fopen("php://output", "w");

fputcsv($output, ["Opportunity", "Activity Date", "Status", "Registrations", "Completed Participants", "Verified Hours"]);

foreach ($reportOpportunities as $opportunity) {
    $opportunityRecords = getRecordsForOpportunities($records, [$opportunity]);
    $totals = getVolunteerTotals($opportunityRecords);

    fputcsv($output, [
        cleanCsvText($opportunity["title"]),
        $opportunity["activity_date"],
        $opportunity["status"],
        $totals["registrations"],
        $totals["completed"],
        $totals["hours"]
    ]);
}

fclose($output);