<?php
$pageTitle = "Post an Opportunity";
$pageCss = "organization-dashboard";

$pageSubtitle = "Share a volunteering activity and connect with people who care about your cause.";
$emptyHeading = "No organization yet";
$emptyMessage = "You need to register an organization before you can post an opportunity.";

include __DIR__ . "/../../../backend/access-control/organization-required.php";

if ($organization["status"] != "approved") {
    include __DIR__ . "/../../components/organization-not-approved.php";
    exit;
}


$errors = [];

if (isset($_SESSION["opportunity_errors"])) {
    $errors = $_SESSION["opportunity_errors"];
    unset($_SESSION["opportunity_errors"]);
}

$old = [];

if (isset($_SESSION["opportunity_old"])) {
    $old = $_SESSION["opportunity_old"];
    unset($_SESSION["opportunity_old"]);
}

$popup = "";

if (isset($_SESSION["opportunity_popup"])) {
    $popup = $_SESSION["opportunity_popup"];
    unset($_SESSION["opportunity_popup"]);
}

$draftId = 0;
$editId = (int) getTextFromUrl("id");

if ($editId > 0) {
    $draft = getOrganizationOpportunity($connection, $organization["id"], $editId);

    if ($draft != null && ($draft["status"] == "draft" || $draft["status"] == "rejected")) {
        $draftId = $draft["id"];

        if (count($old) == 0) {
            $draftStartTime = "";
            if ($draft["start_time"] != "") {
                $draftStartTime = substr($draft["start_time"], 0, 5);
            }

            $draftEndTime = "";
            if ($draft["end_time"] != "") {
                $draftEndTime = substr($draft["end_time"], 0, 5);
            }

            $draftVolunteers = "";
            if ($draft["volunteers_needed"] > 0) {
                $draftVolunteers = $draft["volunteers_needed"];
            }

            $old = [
                "title" => $draft["title"],
                "cause" => explode(",", $draft["cause"]),
                "short_description" => $draft["short_description"],
                "about" => $draft["about"],
                "what_youll_do" => $draft["what_youll_do"],
                "activity_date" => $draft["activity_date"],
                "location_type" => $draft["location_type"],
                "start_time" => $draftStartTime,
                "end_time" => $draftEndTime,
                "location" => $draft["location"],
                "volunteers_needed" => $draftVolunteers,
                "good_for" => explode(",", $draft["good_for"]),
                "duration" => $draft["duration"],
                "skills" => $draft["skills"],
                "instructions" => $draft["instructions"]
            ];
        }
    }
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

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">Post an Opportunity</h1>
        <p class="page-subtitle">Share a volunteering activity and connect with people who care about your cause.</p>

        <?php if (count($errors) > 0) { ?>
            <div class="error-box">
                <p>Please fix the following:</p>
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo safe($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="<?php echo BASE_URL; ?>/backend/form-actions/publish-opportunity-action.php"
              method="post"
              enctype="multipart/form-data">

            <input type="hidden" name="opportunity_id" value="<?php echo $draftId; ?>">

            <section class="panel form-panel">

                <h2 class="panel-title">Opportunity Details</h2>
                <p class="form-note">Posting as <?php echo safe($organization["organization_name"]); ?></p>

                <div class="form-field">
                    <span class="field-label">Opportunity Image</span>

                    <label class="upload-box" for="image-input">
                        <i class="fa-regular fa-image"></i>
                        <p class="upload-title" id="upload-title">Upload an Opportunity Image</p>
                        <p class="upload-text">Choose an image from your device</p>
                        <p class="upload-hint">JPG, PNG · Recommended: landscape image</p>
                    </label>

                    <input type="file" name="image" id="image-input" accept=".jpg,.jpeg,.png" class="hidden-input">
                </div>

                <div class="form-field">
                    <label class="field-label" for="title">Opportunity Title *</label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="text-input"
                           value="<?php echo getOldText($old, "title"); ?>"
                           placeholder="e.g. Community Cleanup Drive">
                </div>

                <div class="form-field">
                    <span class="field-label">Cause Area *</span>

                    <div class="option-list">
                        <?php foreach ($causeOptions as $value => $label) { ?>
                            <label class="option-row">
                                <input type="checkbox"
                                       name="cause[]"
                                       class="checkbox-input"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "cause", $value); ?>>
                                <?php echo $label; ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="short-description">Short Description *</label>
                    <input type="text"
                           name="short_description"
                           id="short-description"
                           class="text-input"
                           value="<?php echo getOldText($old, "short_description"); ?>"
                           placeholder="A brief summary displayed below the opportunity title on the public listing.">
                </div>

                <div class="form-field">
                    <label class="field-label" for="about">About This Opportunity *</label>
                    <textarea name="about"
                              id="about"
                              class="text-input text-area"
                              placeholder="Explain the purpose of the activity and why it matters."><?php echo getOldText($old, "about"); ?></textarea>
                </div>

                <div class="form-field">
                    <label class="field-label" for="what-youll-do">What You'll Do *</label>
                    <textarea name="what_youll_do"
                              id="what-youll-do"
                              class="text-input text-area"
                              placeholder="List the main tasks volunteers will perform."><?php echo getOldText($old, "what_youll_do"); ?></textarea>
                </div>


                <hr class="form-line">


                <h2 class="panel-title">Date &amp; Location</h2>

                <div class="form-grid">

                    <div class="form-field">
                        <label class="field-label" for="activity-date">Activity Date *</label>
                        <input type="date"
                               name="activity_date"
                               id="activity-date"
                               class="text-input"
                               value="<?php echo getOldText($old, "activity_date"); ?>">
                    </div>

                    <div class="form-field">
                        <span class="field-label">Location Type *</span>

                        <div class="option-list option-list-tight">
                            <?php foreach ($locationTypeOptions as $value => $label) { ?>
                                <label class="option-row">
                                    <input type="radio"
                                           name="location_type"
                                           class="radio-input"
                                           value="<?php echo $value; ?>"
                                           <?php echo getOldChecked($old, "location_type", $value); ?>>
                                    <?php echo $label; ?>
                                </label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="start-time">Start Time *</label>
                        <input type="time"
                               name="start_time"
                               id="start-time"
                               class="text-input"
                               value="<?php echo getOldText($old, "start_time"); ?>">
                    </div>

                    <div class="form-field">
                        <label class="field-label" for="end-time">End Time *</label>
                        <input type="time"
                               name="end_time"
                               id="end-time"
                               class="text-input"
                               value="<?php echo getOldText($old, "end_time"); ?>">
                    </div>

                </div>

                <div class="form-field">
                    <label class="field-label" for="location">Activity Location *</label>
                    <input type="text"
                           name="location"
                           id="location"
                           class="text-input"
                           value="<?php echo getOldText($old, "location"); ?>"
                           placeholder="Enter venue and city">
                </div>

                <p class="form-note form-note-small">
                    For remote activities, provide the online meeting details or explain how volunteers will receive them
                </p>


                <hr class="form-line">


                <h2 class="panel-title">Volunteer Requirements</h2>

                <div class="form-field">
                    <label class="field-label" for="volunteers-needed">Number of Volunteers Needed</label>
                    <input type="number"
                           name="volunteers_needed"
                           id="volunteers-needed"
                           class="text-input"
                           min="1"
                           value="<?php echo getOldText($old, "volunteers_needed"); ?>"
                           placeholder="e.g. 20">
                </div>

                <div class="form-field">
                    <span class="field-label">Good For</span>

                    <div class="option-list">
                        <?php foreach ($goodForOptions as $value => $label) { ?>
                            <label class="option-row">
                                <input type="checkbox"
                                       name="good_for[]"
                                       class="checkbox-input"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "good_for", $value); ?>>
                                <?php echo $label; ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-field">
                    <span class="field-label">Duration</span>

                    <div class="option-list">
                        <?php foreach ($durationOptions as $value => $label) { ?>
                            <label class="option-row">
                                <input type="radio"
                                       name="duration"
                                       class="radio-input"
                                       value="<?php echo $value; ?>"
                                       <?php echo getOldChecked($old, "duration", $value); ?>>
                                <?php echo $label; ?>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-field">
                    <label class="field-label" for="skills">Skills or Requirements</label>
                    <input type="text"
                           name="skills"
                           id="skills"
                           class="text-input"
                           value="<?php echo getOldText($old, "skills"); ?>"
                           placeholder="e.g. No experience required">
                </div>

                <div class="form-field form-field-last">
                    <label class="field-label" for="instructions">Additional Instructions</label>
                    <input type="text"
                           name="instructions"
                           id="instructions"
                           class="text-input"
                           value="<?php echo getOldText($old, "instructions"); ?>"
                           placeholder="What should volunteers bring or prepare?">
                </div>

            </section>

            <div class="button-row">
                <button type="submit" name="action" value="draft" class="pill-button pill-outline">Save as Draft</button>
                <button type="submit" name="action" value="publish" class="pill-button pill-yellow">Publish Opportunity</button>
            </div>

            <p class="form-note form-note-small">* Required fields. Drafts are not visible to volunteers until published.</p>

        </form>

    </main>

</div>

<?php if ($popup == "draft") { ?>
    <div class="popup-overlay" id="popup-overlay">

        <div class="draft-popup">

            <div class="draft-popup-body">

                <span class="popup-icon"><i class="fa-regular fa-circle-check"></i></span>

                <h2 class="popup-title">Draft Saved Successfully!</h2>

                <p class="popup-text">
                    Your volunteer opportunity has been saved as a draft. You can continue editing it or publish it when you're ready.
                </p>

                <hr class="popup-line">

                <div class="popup-buttons">
                    <button type="button" class="popup-button popup-green" id="continue-editing">Continue Editing</button>

                    <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php" class="popup-button popup-outline">
                        Back to My Organization
                    </a>
                </div>

            </div>

            <p class="draft-popup-footer">Your draft will remain private until you publish it.</p>

        </div>

    </div>
    
<?php } ?>

<?php if ($popup == "published") { ?>
    <div class="popup-overlay">

        <div class="toast-popup"
             id="published-toast"
             data-redirect="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php">

            <span class="popup-icon"><i class="fa-regular fa-circle-check"></i></span>

            <h2 class="popup-title">Opportunity published successfully!</h2>

            <p class="popup-text">
                Your volunteer opportunity is now live and available for volunteers to discover.
            </p>

        </div>

    </div>
<?php } ?>

<?php if ($popup == "submitted") { ?>
    <div class="popup-overlay">

        <div class="toast-popup"
             id="published-toast"
             data-redirect="<?php echo BASE_URL; ?>/frontend/pages/organization/my-organization.php">

            <span class="popup-icon"><i class="fa-regular fa-circle-check"></i></span>

            <h2 class="popup-title">Opportunity submitted for review!</h2>

            <p class="popup-text">
                An admin will review it first. You will get a notification once it is approved.
            </p>

        </div>

    </div>
<?php } ?>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/publish-opportunity.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>