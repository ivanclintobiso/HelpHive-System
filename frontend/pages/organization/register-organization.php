<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: organization-intro.php?auth=login");
    exit;
}

$pageTitle = "Register Organization";
$pageCss = "register-organization";


function safe($text) {
    return htmlspecialchars($text);
}

function oldValue($old, $name) {
    if (isset($old[$name])) {
        return safe($old[$name]);
    }

    return "";
}


$errors = [];
$old = [];

if (isset($_SESSION["organization_errors"])) {
    $errors = $_SESSION["organization_errors"];
    unset($_SESSION["organization_errors"]);
}

if (isset($_SESSION["organization_old"])) {
    $old = $_SESSION["organization_old"];
    unset($_SESSION["organization_old"]);
}


$organizationTypes = [
    "Nonprofit Organization",
    "Community Group",
    "School / Educational Institution",
    "Government Organization",
    "Other"
];

$causeOptions = [
    "environment" => "Environment",
    "education" => "Education",
    "community" => "Community Support",
    "animal" => "Animal Welfare"
];

$selectedType = "";
if (isset($old["organization_type"])) {
    $selectedType = $old["organization_type"];
}

$selectedCauses = [];
if (isset($old["cause_areas"])) {
    $selectedCauses = $old["cause_areas"];
}

include __DIR__ . "/../../components/header.php";
?>

<main class="register-section">

    <div class="register-card">

        <h1 class="register-title">Register Your Organization</h1>
        <p class="register-subtitle">
            Bring your organization to HelpHive and connect with volunteers who care about your cause.
        </p>

        <hr class="divider">

        <form method="post"
              action="<?php echo BASE_URL; ?>/backend/form-actions/register-organization-action.php"
              enctype="multipart/form-data">

            <?php if (count($errors) > 0) { ?>
                <div class="form-errors">
                    <ul>
                        <?php foreach ($errors as $error) { ?>
                            <li><?php echo safe($error); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>


            <h2 class="form-section-title">Organization Information</h2>
            <p class="form-section-text">Tell us about your organization and the work you do.</p>

            <div class="form-group">
                <label for="logo-input" class="form-label">Organization Logo</label>

                <label for="logo-input" class="upload-box">
                    <i class="fa-regular fa-image"></i>
                    <span class="upload-title" id="upload-title">Upload an Opportunity Image</span>
                    <span class="upload-hint">Choose an image from your device</span>
                    <span class="upload-hint">JPG, PNG · Recommended: landscape image</span>
                </label>

                <input type="file"
                       id="logo-input"
                       name="logo"
                       class="upload-input"
                       accept=".jpg,.jpeg,.png">
            </div>

            <div class="form-group">
                <label for="organization-name" class="form-label">Organization Name *</label>
                <input type="text"
                       id="organization-name"
                       name="organization_name"
                       class="form-input"
                       placeholder="e.g. Green Future Initiative"
                       value="<?php echo oldValue($old, "organization_name"); ?>"
                       required>
            </div>

            <div class="form-group">
                <p class="form-label">Organization Type *</p>

                <div class="option-list">
                    <?php foreach ($organizationTypes as $type) { ?>
                        <label class="option-item">
                            <input type="radio"
                                   name="organization_type"
                                   value="<?php echo safe($type); ?>"
                                   <?php if ($selectedType == $type) { echo "checked"; } ?>
                                   required>
                            <?php echo safe($type); ?>
                        </label>
                    <?php } ?>
                </div>
            </div>

            <div class="form-group">
                <label for="about" class="form-label">About Your Organization *</label>
                <textarea id="about"
                          name="about"
                          class="form-input form-textarea"
                          placeholder="Describe your mission and how your organization helps the community."
                          required><?php echo oldValue($old, "about"); ?></textarea>
            </div>

            <div class="form-group">
                <p class="form-label">Cause Area *</p>

                <div class="option-list">
                    <?php foreach ($causeOptions as $value => $label) { ?>
                        <label class="option-item">
                            <input type="checkbox"
                                   name="cause_areas[]"
                                   value="<?php echo $value; ?>"
                                   <?php if (in_array($value, $selectedCauses)) { echo "checked"; } ?>>
                            <?php echo $label; ?>
                        </label>
                    <?php } ?>
                </div>
            </div>


            <hr class="divider">

            <h2 class="form-section-title">Location and Contact</h2>
            <p class="form-section-text">Help volunteers learn where you're based and how to reach you.</p>

            <div class="form-group">
                <label for="city-province" class="form-label">City / Province *</label>
                <input type="text"
                       id="city-province"
                       name="city_province"
                       class="form-input"
                       placeholder="e.g. Cebu City, Cebu"
                       value="<?php echo oldValue($old, "city_province"); ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="street-address" class="form-label">Street Address</label>
                <input type="text"
                       id="street-address"
                       name="street_address"
                       class="form-input"
                       placeholder="Street, building, or office address (optional)"
                       value="<?php echo oldValue($old, "street_address"); ?>">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Organization Email *</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-input"
                       placeholder="contact@organization.org"
                       value="<?php echo oldValue($old, "email"); ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="contact-number" class="form-label">Contact Number</label>
                <input type="text"
                       id="contact-number"
                       name="contact_number"
                       class="form-input"
                       placeholder="e.g. 09XX XXX XXXX"
                       value="<?php echo oldValue($old, "contact_number"); ?>">
            </div>

            <div class="form-group">
                <label for="website" class="form-label">Website or Social Media Page</label>
                <input type="text"
                       id="website"
                       name="website"
                       class="form-input"
                       placeholder="https://... (optional)"
                       value="<?php echo oldValue($old, "website"); ?>">
            </div>


            <hr class="divider">

            <h2 class="form-section-title">Organization Representative</h2>
            <p class="form-section-text">Who will manage your organization's HelpHive account?</p>

            <div class="form-group">
                <label for="contact-name" class="form-label">Contact Person's Full Name *</label>
                <input type="text"
                       id="contact-name"
                       name="contact_name"
                       class="form-input"
                       placeholder="Enter full name"
                       value="<?php echo oldValue($old, "contact_name"); ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="position" class="form-label">Position / Role *</label>
                <input type="text"
                       id="position"
                       name="position"
                       class="form-input"
                       placeholder="e.g. Volunteer Coordinator"
                       value="<?php echo oldValue($old, "position"); ?>"
                       required>
            </div>

            <button type="submit" class="submit-button">Submit Organization Registration</button>

            <p class="terms-text">
                By submitting, you agree to HelpHive’s
                <a href="<?php echo BASE_URL; ?>/frontend/pages/terms.php">Terms of Use</a>
                and acknowledge its
                <a href="<?php echo BASE_URL; ?>/frontend/pages/privacy.php">Privacy Policy</a>.
            </p>

        </form>

    </div>

</main>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/register-organization.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>