<?php
$pageTitle = "Edit Opportunity";
$pageCss = "edit-opportunity";

$pageSubtitle = "Update your opportunity details and save your changes.";
$emptyHeading = "No organization yet";
$emptyMessage = "You need to register an organization before you can edit an opportunity.";

include __DIR__ . "/../../../backend/access-control/organization-required.php";

$managePage = BASE_URL . "/frontend/pages/organization/my-organization.php";

$opportunityId = (int) getTextFromUrl("id");
$opportunity = getOrganizationOpportunity($connection, $organization["id"], $opportunityId);

if ($opportunity == null) {
    header("Location: " . $managePage);
    exit;
}

if ($opportunity["status"] == "draft") {
    header("Location: " . BASE_URL . "/frontend/pages/organization/publish-opportunity.php?id=" . $opportunityId);
    exit;
}


$errors = [];

if (isset($_SESSION["edit_opportunity_errors"])) {
    $errors = $_SESSION["edit_opportunity_errors"];
    unset($_SESSION["edit_opportunity_errors"]);
}

$old = [];

if (isset($_SESSION["edit_opportunity_old"])) {
    $old = $_SESSION["edit_opportunity_old"];
    unset($_SESSION["edit_opportunity_old"]);
}

if (count($old) == 0) {
    $savedStartTime = "";
    if ($opportunity["start_time"] != "") {
        $savedStartTime = substr($opportunity["start_time"], 0, 5);
    }

    $savedEndTime = "";
    if ($opportunity["end_time"] != "") {
        $savedEndTime = substr($opportunity["end_time"], 0, 5);
    }

    $savedVolunteers = "";
    if ($opportunity["volunteers_needed"] > 0) {
        $savedVolunteers = $opportunity["volunteers_needed"];
    }

    $old = [
        "title" => $opportunity["title"],
        "cause" => explode(",", $opportunity["cause"]),
        "short_description" => $opportunity["short_description"],
        "about" => $opportunity["about"],
        "what_youll_do" => $opportunity["what_youll_do"],
        "activity_date" => $opportunity["activity_date"],
        "location_type" => $opportunity["location_type"],
        "start_time" => $savedStartTime,
        "end_time" => $savedEndTime,
        "location" => $opportunity["location"],
        "volunteers_needed" => $savedVolunteers,
        "good_for" => explode(",", $opportunity["good_for"]),
        "duration" => $opportunity["duration"],
        "skills" => $opportunity["skills"],
        "instructions" => $opportunity["instructions"]
    ];
}


function getOldText($old, $name) {
    if (!isset($old[$name])) {
        return "";
    }

    if (is_array($old[$name])) {
        return "";
    }

    return htmlspecialchars($old[$name]);
}

function getOldChecked($old, $name, $value) {
    if (!isset($old[$name])) {
        return "";
    }

    if (is_array($old[$name])) {
        if (in_array($value, $old[$name])) {
            return "checked";
        }

        return "";
    }

    if ($old[$name] == $value) {
        return "checked";
    }

    return "";
}


$locationTypeOptions = [
    "On-site" => "On-site",
    "Remote" => "Remote"
];

$goodForOptions = [
    "students" => "Students",
    "beginners" => "Beginners",
    "groups" => "Groups",
    "individuals" => "Individuals"
];

$durationOptions = [
    "one-day" => "One day",
    "few-days" => "A few days",
    "ongoing" => "Ongoing"
];

include __DIR__ . "/../../components/header.php";
?>

<main class="edit-section">

    <div class="edit-container">

        <div class="edit-top">
            <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php" class="edit-back">
                <i class="fa-solid fa-chevron-left"></i>
                Back to My Organization
            </a>

            <span class="edit-label">Opportunity Management</span>
        </div>

        <div class="edit-card">

            <h1 class="edit-title">Edit Opportunity</h1>
            <p class="edit-subtitle">Update your opportunity details and save your changes.</p>

            <hr class="edit-line">

            <?php if (count($errors) > 0) { ?>
                <div class="edit-errors">
                    <p>Please fix the following:</p>
                    <ul>
                        <?php foreach ($errors as $error) { ?>
                            <li><?php echo safe($error); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <form action="<?php echo BASE_URL; ?>/backend/form-actions/edit-opportunity-action.php"
                  method="post"
                  enctype="multipart/form-data">

                <input type="hidden" name="opportunity_id" value="<?php echo $opportunityId; ?>">

                <h2 class="edit-section-title">Opportunity Details</h2>
                <p class="edit-note">Posting as <?php echo safe($organization["organization_name"]); ?></p>

                <div class="edit-field">
                    <span class="edit-field-label">Opportunity Image</span>

                    <label class="edit-upload" for="image-input">
                        <i class="fa-regular fa-image"></i>
                        <span class="edit-upload-title" id="image-title">Upload an Opportunity Image</span>
                        <span class="edit-upload-text">Choose an image from your device</span>
                        <span class="edit-upload-text">JPG, PNG · Recommended: landscape image</span>
                    </label>

                    <input type="file" name="image" id="image-input" accept=".jpg,.jpeg,.png" class="edit-file">
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="title">Opportunity Title *</label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="edit-input"
                           value="<?php echo getOldText($old, "title"); ?>"
                           placeholder="e.g. Community Cleanup Drive">
                </div>

                <div class="edit-field">
                    <span class="edit-field-label">Cause Area *</span>

                    <div class="edit-options">
                        <?php foreach ($causeOptions as $value => $label) { ?>
                            <label class="edit-option">
                                <input type="checkbox"
                                       name="cause[]"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "cause", $value); ?>>
                                <span><?php echo $label; ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="short-description">Short Description *</label>
                    <input type="text"
                           name="short_description"
                           id="short-description"
                           class="edit-input"
                           value="<?php echo getOldText($old, "short_description"); ?>"
                           placeholder="A brief summary displayed below the opportunity title on the public listing.">
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="about">About This Opportunity *</label>
                    <textarea name="about"
                              id="about"
                              class="edit-input edit-textarea"
                              placeholder="Explain the purpose of the activity and why it matters."><?php echo getOldText($old, "about"); ?></textarea>
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="what-youll-do">What You'll Do *</label>
                    <textarea name="what_youll_do"
                              id="what-youll-do"
                              class="edit-input edit-textarea"
                              placeholder="List the main tasks volunteers will perform."><?php echo getOldText($old, "what_youll_do"); ?></textarea>
                </div>


                <hr class="edit-line edit-line-space">


                <h2 class="edit-section-title">Date &amp; Location</h2>

                <div class="edit-grid">

                    <div class="edit-field">
                        <label class="edit-field-label" for="activity-date">Activity Date *</label>
                        <input type="date"
                               name="activity_date"
                               id="activity-date"
                               class="edit-input"
                               value="<?php echo getOldText($old, "activity_date"); ?>">
                    </div>

                    <div class="edit-field">
                        <span class="edit-field-label">Location Type *</span>

                        <div class="edit-options edit-options-tight">
                            <?php foreach ($locationTypeOptions as $value => $label) { ?>
                                <label class="edit-option">
                                    <input type="radio"
                                           name="location_type"
                                           value="<?php echo $value; ?>"
                                           <?php echo getOldChecked($old, "location_type", $value); ?>>
                                    <span><?php echo $label; ?></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="edit-field">
                        <label class="edit-field-label" for="start-time">Start Time *</label>
                        <input type="time"
                               name="start_time"
                               id="start-time"
                               class="edit-input"
                               value="<?php echo getOldText($old, "start_time"); ?>">
                    </div>

                    <div class="edit-field">
                        <label class="edit-field-label" for="end-time">End Time *</label>
                        <input type="time"
                               name="end_time"
                               id="end-time"
                               class="edit-input"
                               value="<?php echo getOldText($old, "end_time"); ?>">
                    </div>

                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="location">Activity Location *</label>
                    <input type="text"
                           name="location"
                           id="location"
                           class="edit-input"
                           value="<?php echo getOldText($old, "location"); ?>"
                           placeholder="Enter venue and city">
                </div>

                <p class="edit-small-note">
                    For remote activities, provide the online meeting details or explain how volunteers will receive them
                </p>


                <hr class="edit-line edit-line-space">


                <h2 class="edit-section-title">Volunteer Requirements</h2>

                <div class="edit-field">
                    <label class="edit-field-label" for="volunteers-needed">Number of Volunteers Needed</label>
                    <input type="number"
                           name="volunteers_needed"
                           id="volunteers-needed"
                           class="edit-input"
                           min="1"
                           value="<?php echo getOldText($old, "volunteers_needed"); ?>"
                           placeholder="e.g. 20">
                </div>

                <div class="edit-field">
                    <span class="edit-field-label">Good For</span>

                    <div class="edit-options">
                        <?php foreach ($goodForOptions as $value => $label) { ?>
                            <label class="edit-option">
                                <input type="checkbox"
                                       name="good_for[]"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "good_for", $value); ?>>
                                <span><?php echo $label; ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="edit-field">
                    <span class="edit-field-label">Duration</span>

                    <div class="edit-options">
                        <?php foreach ($durationOptions as $value => $label) { ?>
                            <label class="edit-option">
                                <input type="radio"
                                       name="duration"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "duration", $value); ?>>
                                <span><?php echo $label; ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="skills">Skills or Requirements</label>
                    <input type="text"
                           name="skills"
                           id="skills"
                           class="edit-input"
                           value="<?php echo getOldText($old, "skills"); ?>"
                           placeholder="e.g. No experience required">
                </div>

                <div class="edit-field">
                    <label class="edit-field-label" for="instructions">Additional Instructions</label>
                    <input type="text"
                           name="instructions"
                           id="instructions"
                           class="edit-input"
                           value="<?php echo getOldText($old, "instructions"); ?>"
                           placeholder="What should volunteers bring or prepare?">
                </div>

                <div class="edit-buttons">
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php" class="edit-button edit-cancel">Cancel</a>
                    <button type="submit" class="edit-button edit-save">Save Changes</button>
                </div>

            </form>

        </div>

    </div>

</main>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/edit-opportunity.js"></script>

<?php include __DIR__ . "/../../components/footer-simple.php"; ?>