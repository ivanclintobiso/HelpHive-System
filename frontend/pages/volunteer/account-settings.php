<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../home.php?auth=login");
    exit;
}

$pageTitle = "Account Settings";
$pageCss = "account-settings";

include __DIR__ . "/../../../backend/settings/connection-with-test-mode.php";
include __DIR__ . "/../../../backend/database-queries/user-queries.php";


function safe($text) {
    return htmlspecialchars($text);
}


$passwordErrors = [];
$passwordSuccess = "";

if (isset($_SESSION["password_errors"])) {
    $passwordErrors = $_SESSION["password_errors"];
    unset($_SESSION["password_errors"]);
}

if (isset($_SESSION["password_success"])) {
    $passwordSuccess = $_SESSION["password_success"];
    unset($_SESSION["password_success"]);
}

$openClass = "";
if (count($passwordErrors) > 0) {
    $openClass = "open";
}

$profile = getUserProfile($connection, $_SESSION["user_id"]);

$emailChecked = "";

if ($profile != null && $profile["email_notifications"] == 1) {
    $emailChecked = "checked";
}

include __DIR__ . "/../../components/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/../../components/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title">Account Settings</h1>
        <p class="page-subtitle">Manage your account preferences.</p>

        <section class="settings-card">

            <button type="button" class="settings-row <?php echo $openClass; ?>" id="password-row">
                <span class="settings-icon">
                    <i class="fa-solid fa-lock"></i>
                </span>

                <span class="settings-text">
                    <strong>Change Password</strong>
                    <span>Update your account password.</span>
                </span>

                <i class="fa-solid fa-chevron-right settings-chevron"></i>
            </button>

            <form method="post"
                  action="<?php echo BASE_URL; ?>/backend/form-actions/change-password-action.php"
                  class="password-form <?php echo $openClass; ?>"
                  id="password-form">

                <?php if (count($passwordErrors) > 0) { ?>
                    <ul class="settings-message settings-error">
                        <?php foreach ($passwordErrors as $error) { ?>
                            <li><?php echo safe($error); ?></li>
                        <?php } ?>
                    </ul>
                <?php } ?>

                <label for="old-password">Old Password *</label>
                <div class="password-box">
                    <input type="password" id="old-password" name="current_password" required>
                    <button type="button" class="eye-button" data-target="old-password">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <label for="new-password">New Password *</label>
                <div class="password-box">
                    <input type="password" id="new-password" name="new_password" minlength="8" required>
                    <button type="button" class="eye-button" data-target="new-password">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <label for="confirm-password">Confirm New Password *</label>
                <div class="password-box">
                    <input type="password" id="confirm-password" name="confirm_password" required>
                    <button type="button" class="eye-button" data-target="confirm-password">
                        <i class="fa-regular fa-eye-slash"></i>
                    </button>
                </div>

                <button type="submit" class="change-password-button">Change Password</button>

            </form>

            <hr class="settings-line">

            <div class="settings-row settings-row-static">
                <span class="settings-icon">
                    <i class="fa-regular fa-bell"></i>
                </span>

                <span class="settings-text">
                    <strong>Email Notifications</strong>
                    <span>Receive important updates by email.</span>
                </span>

                <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/email-notifications-action.php">
                    <label class="switch">
                        <input type="checkbox"
                               name="email_notifications"
                               value="1"
                               onchange="this.form.submit()"
                               <?php echo $emailChecked; ?>>
                        <span class="slider"></span>
                    </label>
                </form>
            </div>

        </section>

    </main>

</div>


<?php if ($passwordSuccess != "") { ?>
    <div class="success-overlay" id="success-overlay">
        <div class="success-card">
            <span class="success-icon"><i class="fa-regular fa-circle-check"></i></span>
            <h2 class="success-title">Password changed successfully!</h2>
            <p class="success-text">Your password has been updated successfully.</p>
        </div>
    </div>
<?php } ?>


<script src="<?php echo BASE_URL; ?>/frontend/assets/js/account-settings.js"></script>

<?php include __DIR__ . "/../../components/footer.php"; ?>