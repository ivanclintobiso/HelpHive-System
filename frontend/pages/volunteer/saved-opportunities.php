<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../home.php?auth=login");
    exit;
}

$pageTitle = "Saved Opportunities";
$pageCss = "saved-opportunities";

include __DIR__ . "/../../../backend/database-queries/opportunity-queries.php";
include __DIR__ . "/../../../backend/settings/connection-with-test-mode.php";
include __DIR__ . "/../../../backend/database-queries/saved-opportunity-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}


$causeOptions = [
    "environment" => "Environment",
    "animal" => "Animal Welfare",
    "community" => "Community Support",
    "education" => "Education"
];

$durationOptions = [
    "one-day" => "One day",
    "few-days" => "A few days",
    "ongoing" => "Ongoing"
];

$allOpportunities = getAllOpportunities($connection);
$savedIds = getSavedOpportunityIds($connection, $_SESSION["user_id"]);

$savedOpportunities = [];

foreach ($allOpportunities as $opportunity) {
    if (in_array($opportunity["id"], $savedIds)) {
        $savedOpportunities[] = $opportunity;
    }
}

$emptyClass = "is-hidden";

if (count($savedOpportunities) == 0) {
    $emptyClass = "";
}

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">Saved Opportunities</h1>
        <p class="page-subtitle">
            Keep track of the volunteer opportunities you've saved and revisit them whenever you're ready to make a difference.
        </p>

        <div class="empty-saved <?php echo $emptyClass; ?>" id="empty-saved">
            <h3>No saved opportunities yet</h3>
            <p>Tap the bookmark on any opportunity to save it here.</p>
            <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php" class="browse-link">
                Browse opportunities
            </a>
        </div>

        <?php foreach ($savedOpportunities as $opportunity) { ?>

            <article class="opportunity-card">

                <div class="card-image">
                    <img src="<?php echo $opportunity["image_url"]; ?>"
                         alt="<?php echo safe($opportunity["title"]); ?>">
                </div>

                <div class="card-body">

                    <button type="button"
                            class="saved-button"
                            data-id="<?php echo $opportunity["id"]; ?>"
                            data-url="<?php echo BASE_URL; ?>/backend/form-actions/save-opportunity-action.php"
                            title="Remove from saved">
                        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/saveoppurtunity-searchoppurtunitiespage.png" alt="" class="bookmark-icon">
                    </button>

                    <h3 class="card-title">
                        <a href="<?php echo BASE_URL; ?>/frontend/pages/opportunity.php?id=<?php echo $opportunity["id"]; ?>">
                            <?php echo safe($opportunity["title"]); ?>
                        </a>
                    </h3>

                    <p class="card-organization"><?php echo safe($opportunity["organization"]); ?></p>

                    <?php foreach ($opportunity["causes"] as $cause) { ?>
                        <?php if (isset($causeOptions[$cause])) { ?>
                            <span class="cause-tag cause-<?php echo $cause; ?>">
                                <?php echo $causeOptions[$cause]; ?>
                            </span>
                        <?php } ?>
                    <?php } ?>

                    <hr class="card-line">

                    <p class="card-description"><?php echo safe($opportunity["description"]); ?></p>

                    <div class="card-tags">

                        <?php if ($opportunity["location_type"] == "Remote") { ?>
                            <span class="tag tag-remote">Remote</span>
                        <?php } else { ?>
                            <span class="tag tag-onsite">On-site</span>
                        <?php } ?>

                        <span class="tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/location-searchoppurtunitiespage.png" alt="" class="tag-icon">
                            <?php echo safe($opportunity["city"]); ?>
                        </span>

                        <span class="tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/date-searchoppurtunitiespage.png" alt="" class="tag-icon">
                            <?php echo safe($opportunity["date_text"]); ?>
                        </span>

                        <span class="tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/goodfor-searchoppurtunitiespage.png" alt="" class="tag-icon">
                            Good for <?php echo safe($opportunity["good_for"]); ?>
                        </span>

                        <span class="tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/duration-searchoppurtunitiespage.png" alt="" class="tag-icon">
                            <?php echo safe(isset($durationOptions[$opportunity["duration"]]) ? $durationOptions[$opportunity["duration"]] : "Not set"); ?>
                        </span>

                    </div>

                </div>

            </article>

        <?php } ?>

    </main>

</div>

<?php include __DIR__ . "/../../components/unsave-modal.php"; ?>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/unsave-modal.js"></script>
<script src="<?php echo BASE_URL; ?>/frontend/assets/js/saved-opportunities.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>