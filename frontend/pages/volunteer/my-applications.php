<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../home.php?auth=login");
    exit;
}

$pageTitle = "My Applications";
$pageCss = "my-applications";

include __DIR__ . "/../../../backend/settings/connection-with-test-mode.php";
include __DIR__ . "/../../../backend/database-queries/registration-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}

function getStatusFromUrl() {
    if (!isset($_GET["status"])) {
        return "all";
    }

    if (is_array($_GET["status"])) {
        return "all";
    }

    return $_GET["status"];
}

function makeStatusLink($status) {
    if ($status == "all") {
        return "my-applications.php";
    }

    return "my-applications.php?status=" . $status;
}


$statusOptions = [
    "all" => "All",
    "pending" => "Pending",
    "approved" => "Approved",
    "declined" => "Declined",
    "cancelled" => "Cancelled"
];

$statusMessages = [
    "approved" => "You're confirmed! Check the activity details before attending.",
    "pending" => "Your application is waiting for the organization to review it.",
    "declined" => "Sorry, the organization could not accept your application this time.",
    "cancelled" => "This opportunity is no longer available, so your application was cancelled."
];

$statusIcons = [
    "approved" => "fa-regular fa-circle-check",
    "pending" => "fa-regular fa-clock",
    "declined" => "fa-regular fa-circle-xmark",
    "cancelled" => "fa-solid fa-ban"
];


$selectedStatus = getStatusFromUrl();

if (!isset($statusOptions[$selectedStatus])) {
    $selectedStatus = "all";
}

$allApplications = getMyApplications($connection, $_SESSION["user_id"]);

$totalCount = count($allApplications);
$pendingCount = 0;
$approvedCount = 0;

foreach ($allApplications as $application) {
    if ($application["status"] == "pending") {
        $pendingCount = $pendingCount + 1;
    }

    if ($application["status"] == "approved") {
        $approvedCount = $approvedCount + 1;
    }
}

$visibleApplications = [];

foreach ($allApplications as $application) {
    if ($selectedStatus == "all" || $application["status"] == $selectedStatus) {
        $visibleApplications[] = $application;
    }
}

$visibleCount = count($visibleApplications);

$countText = $visibleCount . " applications";
if ($visibleCount == 1) {
    $countText = "1 application";
}

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">My Applications</h1>
        <p class="page-subtitle">Keep track of your volunteer registrations and upcoming activities.</p>


        <div class="stat-cards">

            <div class="stat-card">
                <p class="stat-label">Total</p>
                <p class="stat-number"><?php echo $totalCount; ?></p>
                <p class="stat-note">Applications</p>
            </div>

            <div class="stat-card">
                <p class="stat-label">Pending</p>
                <p class="stat-number stat-pending"><?php echo $pendingCount; ?></p>
                <p class="stat-note">Awaiting review</p>
            </div>

            <div class="stat-card">
                <p class="stat-label">Approved</p>
                <p class="stat-number stat-approved"><?php echo $approvedCount; ?></p>
                <p class="stat-note">Confirmed</p>
            </div>

        </div>


        <div class="list-header">
            <h2 class="list-title">Your Applications</h2>
            <p class="list-count"><?php echo $countText; ?></p>
        </div>

        <div class="filter-pills">
            <?php foreach ($statusOptions as $value => $label) { ?>
                <a href="<?php echo makeStatusLink($value); ?>"
                   class="filter-pill <?php if ($selectedStatus == $value) { echo "active"; } ?>">
                    <?php echo $label; ?>
                </a>
            <?php } ?>
        </div>


        <?php if ($totalCount == 0) { ?>
            <div class="no-applications">
                <h3>No applications yet</h3>
                <p>Register for an opportunity and your applications will show up here.</p>
                <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php" class="show-all-link">Browse opportunities</a>
            </div>
        <?php } else if ($visibleCount == 0) { ?>
            <div class="no-applications">
                <h3>No applications found</h3>
                <p>You don't have any applications in this list yet.</p>
                <a href="my-applications.php" class="show-all-link">Show all applications</a>
            </div>
        <?php } ?>


        <?php foreach ($visibleApplications as $application) { ?>

            <article class="application-card">

                <div class="application-image">
                    <img src="<?php echo $application["image_url"]; ?>"
                         alt="<?php echo safe($application["title"]); ?>">
                </div>

                <div class="application-body">

                    <h3 class="application-title"><?php echo safe($application["title"]); ?></h3>
                    <p class="application-organization"><?php echo safe($application["organization"]); ?></p>

                    <div class="status-banner status-<?php echo $application["status"]; ?>">
                        <i class="<?php echo $statusIcons[$application["status"]]; ?>"></i>
                        <strong><?php echo $statusOptions[$application["status"]]; ?></strong>
                        <span><?php echo $statusMessages[$application["status"]]; ?></span>
                    </div>

                    <hr class="application-line">

                    <div class="application-bottom">

                        <div class="application-info">
                            <p>
                                <i class="fa-regular fa-calendar"></i>
                                <?php echo safe($application["schedule_text"]); ?>
                            </p>
                            <p>
                                <i class="fa-solid fa-location-dot"></i>
                                <?php echo safe($application["city"]); ?>
                            </p>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/frontend/pages/opportunity.php?id=<?php echo $application["opportunity_id"]; ?>"
                           class="view-button">
                            View Opportunity
                        </a>

                    </div>

                </div>

            </article>

        <?php } ?>

    </main>

</div>

<?php include __DIR__ . "/../../components/footer.php"; ?>