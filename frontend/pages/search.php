<?php
$pageTitle = "Search Opportunities";
$pageCss = "search";

include __DIR__ . "/../../backend/database-queries/opportunity-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}

function getListFromUrl($name) {
    if (!isset($_GET[$name])) {
        return [];
    }

    if (is_array($_GET[$name])) {
        return $_GET[$name];
    }

    return [$_GET[$name]];
}

function getTextFromUrl($name, $defaultValue) {
    if (!isset($_GET[$name])) {
        return $defaultValue;
    }

    if (is_array($_GET[$name])) {
        return $defaultValue;
    }

    return trim($_GET[$name]);
}

function isChecked($value, $selectedList) {
    if (in_array($value, $selectedList)) {
        return "checked";
    }

    return "";
}

function getChipClass($selectedList) {
    if (count($selectedList) > 0) {
        return "has-selection";
    }

    return "";
}

function makePageLink($pageNumber) {
    $queryData = $_GET;
    $queryData["page"] = $pageNumber;

    return "search.php?" . http_build_query($queryData);
}


$locationTypeOptions = [
    "On-site" => "On-site",
    "Remote" => "Remote"
];

$causeOptions = [
    "environment" => "Environment",
    "animal" => "Animal Welfare",
    "community" => "Community Support",
    "education" => "Education"
];

$goodForOptions = [
    "groups" => "Groups",
    "beginners" => "Beginners",
    "students" => "Students",
    "individuals" => "Individuals"
];

$durationOptions = [
    "one-day" => "One day",
    "ongoing" => "Ongoing"
];

$skillOptions = [
    "Animal Care" => "Animal Care",
    "Communication" => "Communication",
    "Digital Design" => "Digital Design",
    "Physical Work" => "Physical Work",
    "Teaching" => "Teaching"
];

$distanceOptions = [
    "" => "Any distance",
    "5" => "Within 5 km",
    "10" => "Within 10 km",
    "25" => "Within 25 km",
    "50" => "Within 50 km"
];


$keyword = getTextFromUrl("keyword", "");
$location = getTextFromUrl("location", "Cebu, Philippines");
$distance = getTextFromUrl("distance", "");
$fromDate = getTextFromUrl("date", "");

$selectedLocationTypes = getListFromUrl("location_type");
$selectedCauses = getListFromUrl("cause");
$selectedGoodFor = getListFromUrl("good_for");
$selectedDurations = getListFromUrl("duration");
$selectedSkills = getListFromUrl("skill");

$distanceLabel = "Distance";
if ($distance != "" && isset($distanceOptions[$distance])) {
    $distanceLabel = $distanceOptions[$distance];
}

$dateClass = "";
if ($fromDate != "") {
    $dateClass = "has-selection";
}

$filters = [
    "keyword" => $keyword,
    "location" => $location,
    "distance" => $distance,
    "location_types" => $selectedLocationTypes,
    "causes" => $selectedCauses,
    "good_for" => $selectedGoodFor,
    "durations" => $selectedDurations,
    "from_date" => $fromDate,
    "skills" => $selectedSkills
];

$allResults = searchOpportunities($filters);
$totalResults = count($allResults);

$resultsPerPage = 5;
$totalPages = ceil($totalResults / $resultsPerPage);
if ($totalPages < 1) {
    $totalPages = 1;
}

$pageNumber = 1;
if (isset($_GET["page"])) {
    $pageNumber = (int) $_GET["page"];
}
if ($pageNumber < 1) {
    $pageNumber = 1;
}
if ($pageNumber > $totalPages) {
    $pageNumber = $totalPages;
}

$startIndex = ($pageNumber - 1) * $resultsPerPage;
$pageResults = array_slice($allResults, $startIndex, $resultsPerPage);

$countText = $totalResults . " Opportunities";
if ($totalResults == 1) {
    $countText = "1 Opportunity";
}

include __DIR__ . "/../components/header.php";

$savedIds = [];

if ($isLoggedIn) {
    include __DIR__ . "/../../backend/settings/connection-with-test-mode.php";
    include __DIR__ . "/../../backend/database-queries/saved-opportunity-queries.php";

    $savedIds = getSavedOpportunityIds($connection, $_SESSION["user_id"]);
}
?>

<form method="get" action="search.php" class="search-form">

    <div class="search-row">

        <div class="input-box keyword-box">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/search-searchoppurtunitiespage.png" alt="" class="field-icon">
            <input type="text"
                   name="keyword"
                   value="<?php echo safe($keyword); ?>"
                   placeholder="Search by keyword, skill, or interest">
        </div>

        <div class="input-box location-box">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/location-searchoppurtunitiespage.png" alt="" class="field-icon">
            <input type="text"
                   name="location"
                   id="location-input"
                   value="<?php echo safe($location); ?>"
                   placeholder="Enter a location">
            <button type="button" id="clear-location" class="clear-location-button">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="filter-dropdown distance-dropdown">
            <button type="button" class="dropdown-button distance-button <?php if ($distance != "") { echo "has-selection"; } ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/distance-searchoppurtunitiespage.png" alt="" class="field-icon">
                <span><?php echo safe($distanceLabel); ?></span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($distanceOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="radio"
                               name="distance"
                               value="<?php echo $value; ?>"
                               <?php if ($distance == $value) { echo "checked"; } ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <button type="submit" class="search-button">Search</button>

    </div>


    <div class="filter-row">

        <div class="filters-label">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/filters-searchoppurtunitiespage.png" alt="" class="filters-icon">
            <span>Filters</span>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo getChipClass($selectedLocationTypes); ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/locationtype-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Location Type</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($locationTypeOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="checkbox"
                               name="location_type[]"
                               value="<?php echo $value; ?>"
                               <?php echo isChecked($value, $selectedLocationTypes); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo getChipClass($selectedCauses); ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/causeareas-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Cause Areas</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($causeOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="checkbox"
                               name="cause[]"
                               value="<?php echo $value; ?>"
                               <?php echo isChecked($value, $selectedCauses); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo getChipClass($selectedGoodFor); ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/goodfor-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Good For</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($goodForOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="checkbox"
                               name="good_for[]"
                               value="<?php echo $value; ?>"
                               <?php echo isChecked($value, $selectedGoodFor); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo getChipClass($selectedDurations); ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/duration-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Duration</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($durationOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="checkbox"
                               name="duration[]"
                               value="<?php echo $value; ?>"
                               <?php echo isChecked($value, $selectedDurations); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo $dateClass; ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/date-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Date</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <label class="date-option">
                    Show opportunities from:
                    <input type="date" name="date" value="<?php echo safe($fromDate); ?>">
                </label>
            </div>
        </div>

        <div class="filter-dropdown">
            <button type="button" class="dropdown-button <?php echo getChipClass($selectedSkills); ?>">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/skills-searchoppurtunitiespage.png" alt="" class="filter-icon">
                <span>Skills</span>
                <i class="fa-solid fa-chevron-up chevron"></i>
            </button>

            <div class="dropdown-menu">
                <?php foreach ($skillOptions as $value => $label) { ?>
                    <label class="dropdown-option">
                        <input type="checkbox"
                               name="skill[]"
                               value="<?php echo $value; ?>"
                               <?php echo isChecked($value, $selectedSkills); ?>>
                        <?php echo $label; ?>
                    </label>
                <?php } ?>
            </div>
        </div>

        <a href="search.php" class="clear-filters-button" title="Clear all filters">
            <i class="fa-solid fa-xmark"></i>
        </a>

    </div>

</form>


<main class="results-section">

    <p class="result-count"><?php echo $countText; ?></p>

    <?php if ($totalResults == 0) { ?>
        <div class="no-results">
            <h3>No opportunities found</h3>
            <p>Try changing your keyword, location, or filters.</p>
            <a href="search.php" class="clear-link">Clear all filters</a>
        </div>
    <?php } ?>

    <?php foreach ($pageResults as $opportunity) { ?>

        <article class="opportunity-card">

            <div class="card-image">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/<?php echo $opportunity["image"]; ?>"
                     alt="<?php echo safe($opportunity["title"]); ?>">
            </div>

            <div class="card-body">

                <?php
                    $bookmarkClass = "bookmark-button";

                    if (in_array($opportunity["id"], $savedIds)) {
                        $bookmarkClass = "bookmark-button saved";
                    }
                ?>

                <button type="button"
                        class="<?php echo $bookmarkClass; ?>"
                        data-id="<?php echo $opportunity["id"]; ?>"
                        data-url="<?php echo BASE_URL; ?>/backend/form-actions/save-opportunity-action.php"
                        title="Save opportunity">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/saveoppurtunity-searchoppurtunitiespage.png" alt="" class="bookmark-icon">
                </button>

                <h3 class="card-title">
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/opportunity.php?id=<?php echo $opportunity["id"]; ?>">
                        <?php echo safe($opportunity["title"]); ?>
                    </a>
                </h3>

                <p class="card-organization"><?php echo safe($opportunity["organization"]); ?></p>

                <span class="cause-tag cause-<?php echo $opportunity["cause"]; ?>">
                    <?php echo $causeOptions[$opportunity["cause"]]; ?>
                </span>

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
                        <?php echo $durationOptions[$opportunity["duration"]]; ?>
                    </span>

                </div>

            </div>

        </article>

    <?php } ?>


    <?php if ($totalResults > 0) { ?>
        <div class="pagination">

            <?php if ($pageNumber > 1) { ?>
                <a href="<?php echo makePageLink($pageNumber - 1); ?>" class="page-arrow">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php } else { ?>
                <span class="page-arrow disabled">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            <?php } ?>

            <?php for ($number = 1; $number <= $totalPages; $number++) { ?>
                <a href="<?php echo makePageLink($number); ?>"
                   class="page-number <?php if ($number == $pageNumber) { echo "active"; } ?>">
                    <?php echo $number; ?>
                </a>
            <?php } ?>

            <?php if ($pageNumber < $totalPages) { ?>
                <a href="<?php echo makePageLink($pageNumber + 1); ?>" class="page-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php } else { ?>
                <span class="page-arrow disabled">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            <?php } ?>

        </div>
    <?php } ?>

</main>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/search-filter.js"></script>
<script src="<?php echo BASE_URL; ?>/frontend/assets/js/bookmark-toggle.js"></script>

<?php include __DIR__ . "/../components/footer.php"; ?>