<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../home.php?auth=login");
    exit;
}

$pageTitle = "My Profile";
$pageCss = "my-profile";

include __DIR__ . "/../../../backend/settings/connection-with-test-mode.php";
include __DIR__ . "/../../../backend/database-queries/user-queries.php";
include __DIR__ . "/../../../backend/database-queries/organization-queries.php";
include __DIR__ . "/../../../backend/database-queries/registration-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}

function makeHoursText($hours) {
    $number = round($hours, 1);

    if ($number == 1) {
        return "1 hr";
    }

    return $number . " hrs";
}

function makeVerifiedHoursText($hours) {
    $number = round($hours, 1);

    if ($number == 1) {
        return "1 verified hour";
    }

    return $number . " verified hours";
}


$errors = [];
$old = [];
$successMessage = "";

if (isset($_SESSION["profile_errors"])) {
    $errors = $_SESSION["profile_errors"];
    unset($_SESSION["profile_errors"]);
}

if (isset($_SESSION["profile_old"])) {
    $old = $_SESSION["profile_old"];
    unset($_SESSION["profile_old"]);
}

if (isset($_SESSION["profile_success"])) {
    $successMessage = $_SESSION["profile_success"];
    unset($_SESSION["profile_success"]);
}


$profile = getUserProfile($connection, $_SESSION["user_id"]);

if ($profile == null) {
    header("Location: " . BASE_URL . "/backend/form-actions/logout-action.php");
    exit;
}

$fullName = trim($profile["first_name"] . " " . $profile["last_name"]);

$values = [
    "full_name" => $fullName,
    "email" => $profile["email"],
    "phone" => $profile["phone"],
    "location" => $profile["location"],
    "about_me" => $profile["about_me"],
    "causes" => $profile["causes"],
    "skills" => $profile["skills"]
];

$editingClass = "";

if (count($errors) > 0) {
    $values = $old;
    $editingClass = "editing";
}

$organization = getMyOrganization($connection, $_SESSION["user_id"]);

$roleText = "Volunteer Member";

if ($organization != null) {
    $roleText = "Volunteer Member | Organization Owner";
}

$memberSince = date("F Y", strtotime($profile["created_at"]));

$history = getMyVolunteerHistory($connection, $_SESSION["user_id"]);

$totalHours = 0;

foreach ($history as $item) {
    $totalHours = $totalHours + $item["hours"];
}

$completedCount = count($history);

$avatarName = "profile1-defaultrandomprofile.png";

if (!empty($profile["profile_image"])) {
    $avatarName = $profile["profile_image"];
}

$avatarFile = __DIR__ . "/../../assets/images/" . $avatarName;

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your personal information and tell organizations a little about yourself.</p>

        <?php if ($successMessage != "") { ?>
            <p class="profile-message profile-success"><?php echo safe($successMessage); ?></p>
        <?php } ?>

        <?php if (count($errors) > 0) { ?>
            <div class="profile-message profile-error">
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo safe($error); ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>


        <form method="post"
              action="<?php echo BASE_URL; ?>/backend/form-actions/update-profile-action.php"
              enctype="multipart/form-data"
              class="profile-form <?php echo $editingClass; ?>"
              id="profile-form">

            <section class="profile-card">

                <div class="profile-top">

                    <div class="avatar-box">

                        <div class="profile-avatar">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/<?php echo $avatarName; ?>"
                                 alt="Profile picture"
                                 id="avatar-preview">
                        </div>

                        <label for="profile-photo-input" class="avatar-pencil">
                            <i class="fa-solid fa-pencil"></i>
                        </label>

                        <input type="file"
                               name="profile_photo"
                               id="profile-photo-input"
                               class="photo-input"
                               accept="image/*">

                    </div>

                    <div class="profile-info">
                        <h2 class="profile-name">
                            <?php echo safe($fullName); ?>
                            <i class="fa-solid fa-pencil name-pencil" id="name-pencil"></i>
                        </h2>
                        <p class="profile-role"><?php echo $roleText; ?></p>
                        <p class="profile-since">Member since <?php echo $memberSince; ?></p>
                    </div>

                    <button type="button" class="outline-button edit-trigger">Edit profile</button>

                </div>

                <hr class="card-line">

                <div class="profile-stats">

                    <div class="stat">
                        <span class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></span>
                        <div>
                            <p class="stat-label">Verified Volunteer Hours</p>
                            <p class="stat-value"><?php echo makeHoursText($totalHours); ?></p>
                        </div>
                    </div>

                    <div class="stat">
                        <span class="stat-icon"><i class="fa-solid fa-user-check"></i></span>
                        <div>
                            <p class="stat-label">Activities Completed</p>
                            <p class="stat-value"><?php echo $completedCount; ?></p>
                        </div>
                    </div>

                </div>

            </section>


            <section class="profile-card">

                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                    <button type="button" class="outline-button edit-trigger">Edit</button>
                </div>

                <div class="profile-grid">

                    <div class="form-field">
                        <label for="full-name" class="field-label">Full Name</label>
                        <div class="field-box">
                            <input type="text"
                                   id="full-name"
                                   name="full_name"
                                   class="field-input"
                                   placeholder="Enter your full name"
                                   value="<?php echo safe($values["full_name"]); ?>"
                                   required
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="email" class="field-label">Email Address</label>
                        <div class="field-box">
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="field-input"
                                   placeholder="Enter your email"
                                   value="<?php echo safe($values["email"]); ?>"
                                   required
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="phone" class="field-label">Phone Number</label>
                        <div class="field-box">
                            <input type="text"
                                   id="phone"
                                   name="phone"
                                   class="field-input"
                                   placeholder="+63 9XX XXX XXXX"
                                   value="<?php echo safe($values["phone"]); ?>"
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="location" class="field-label">Location</label>
                        <div class="field-box">
                            <input type="text"
                                   id="location"
                                   name="location"
                                   class="field-input"
                                   placeholder="e.g. Cebu City, Cebu"
                                   value="<?php echo safe($values["location"]); ?>"
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                </div>

                <hr class="card-line">

                <h2 class="card-title">About Me</h2>
                <p class="card-note">Introduce yourself and share why you enjoy volunteering.</p>

                <div class="form-field">
                    <label for="about-me" class="field-label">About Me</label>
                    <div class="field-box">
                        <input type="text"
                               id="about-me"
                               name="about_me"
                               class="field-input"
                               maxlength="255"
                               placeholder="Tell organizations a little about yourself"
                               value="<?php echo safe($values["about_me"]); ?>"
                               readonly>
                        <i class="fa-solid fa-pencil field-pencil"></i>
                    </div>
                </div>

                <hr class="card-line">

                <h2 class="card-title">Interests &amp; Skills</h2>

                <div class="profile-grid">

                    <div class="form-field">
                        <label for="causes" class="field-label">Causes I'm interested in</label>
                        <div class="field-box">
                            <input type="text"
                                   id="causes"
                                   name="causes"
                                   class="field-input"
                                   maxlength="255"
                                   placeholder="e.g. Environment, Education"
                                   value="<?php echo safe($values["causes"]); ?>"
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="skills" class="field-label">My skills</label>
                        <div class="field-box">
                            <input type="text"
                                   id="skills"
                                   name="skills"
                                   class="field-input"
                                   maxlength="255"
                                   placeholder="e.g. Communication, Teamwork"
                                   value="<?php echo safe($values["skills"]); ?>"
                                   readonly>
                            <i class="fa-solid fa-pencil field-pencil"></i>
                        </div>
                    </div>

                </div>

                <div class="save-bar">
                    <button type="submit" class="save-button">Save Changes</button>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/volunteer/my-profile.php" class="cancel-button">Cancel</a>
                </div>

            </section>

        </form>


        <section class="profile-card">

            <h2 class="card-title">Volunteering History</h2>
            <p class="card-note">A quick look at your completed activities and verified hours.</p>

            <?php if (count($history) == 0) { ?>
                <div class="empty-state">
                    <h3>No volunteering history yet</h3>
                    <p>Completed activities with verified hours will show up here.</p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php" class="empty-link">Browse opportunities</a>
                </div>
            <?php } ?>

            <?php foreach ($history as $item) { ?>

                <div class="history-row">

                    <span class="history-icon"><i class="fa-regular fa-circle-check"></i></span>

                    <div class="history-text">
                        <h3><?php echo safe($item["title"]); ?></h3>
                        <p>Completed &middot; <?php echo makeVerifiedHoursText($item["hours"]); ?></p>
                    </div>

                    <span class="history-chip">Completed</span>

                </div>

            <?php } ?>

            <?php if (count($history) > 0) { ?>
                <p class="history-footnote">
                    Only completed activities with organizer-verified hours should count toward the total.
                </p>
            <?php } ?>

        </section>

    </main>

</div>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/my-profile.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>