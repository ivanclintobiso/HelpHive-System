<?php
$authErrors = [];
$authOld = [];
$openCard = "";

if (isset($_SESSION["auth_errors"])) {
    $authErrors = $_SESSION["auth_errors"];
    unset($_SESSION["auth_errors"]);
}

if (isset($_SESSION["auth_old"])) {
    $authOld = $_SESSION["auth_old"];
    unset($_SESSION["auth_old"]);
}

if (isset($_SESSION["auth_mode"])) {
    $openCard = $_SESSION["auth_mode"];
    unset($_SESSION["auth_mode"]);
}

if ($openCard == "" && isset($_GET["auth"])) {
    if ($_GET["auth"] == "login" || $_GET["auth"] == "signup") {
        $openCard = $_GET["auth"];
    }
}

$oldFirstName = "";
if (isset($authOld["first_name"])) {
    $oldFirstName = htmlspecialchars($authOld["first_name"]);
}

$oldLastName = "";
if (isset($authOld["last_name"])) {
    $oldLastName = htmlspecialchars($authOld["last_name"]);
}

$oldEmail = "";
if (isset($authOld["email"])) {
    $oldEmail = htmlspecialchars($authOld["email"]);
}

$oldLocation = "";
if (isset($authOld["location"])) {
    $oldLocation = htmlspecialchars($authOld["location"]);
}

$returnQuery = $_GET;
unset($returnQuery["auth"]);

$returnUrl = $_SERVER["PHP_SELF"];
if (count($returnQuery) > 0) {
    $returnUrl = $returnUrl . "?" . http_build_query($returnQuery);
}

if (isset($afterLoginPath)) {
    $returnUrl = BASE_URL . $afterLoginPath;
}
?>

<div class="auth-overlay <?php if ($openCard != "") { echo "open"; } ?>" id="auth-overlay">

    <div class="auth-card <?php if ($openCard == "login") { echo "show"; } ?>" id="login-card">

        <button type="button" class="auth-close">&times;</button>

        <div class="auth-logo">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/logo-home-page.png" alt="HelpHive logo">
        </div>

        <hr class="auth-line">

        <h2 class="auth-title">Login in or create your account</h2>
        <p class="auth-subtitle">
            Don't have an account?
            <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/signup.php" class="auth-switch open-signup">Sign Up</a>
        </p>

        <button type="button" class="social-button" data-provider="Google">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/google-logo.png" alt="Google logo">
            <span>Continue with Google</span>
        </button>

        <button type="button" class="social-button" data-provider="Facebook">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/fb-logo.png" alt="Facebook logo">
            <span>Continue with Facebook</span>
        </button>

        <hr class="auth-line">

        <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/login-action.php" class="auth-form">

            <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($returnUrl); ?>">

            <?php if ($openCard == "login" && count($authErrors) > 0) { ?>
                <div class="auth-errors">
                    <?php foreach ($authErrors as $error) { ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="auth-field">
                <label for="login-email" class="auth-label">Email Address</label>
                <input type="email"
                       id="login-email"
                       name="email"
                       class="auth-input"
                       placeholder="Enter your email"
                       value="<?php echo $oldEmail; ?>">
            </div>

            <div class="auth-field">
                <label for="login-password" class="auth-label">Password</label>
                <input type="password"
                       id="login-password"
                       name="password"
                       class="auth-input"
                       placeholder="Enter your password">
            </div>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/contact.php" class="forgot-link">Forgot Password?</a>

            <button type="submit" class="auth-submit">Log In</button>

        </form>

        <p class="auth-terms">
            By creating an account, you agree to our
            <a href="<?php echo BASE_URL; ?>/frontend/pages/terms.php">Terms of Use</a>
            and
            <a href="<?php echo BASE_URL; ?>/frontend/pages/privacy.php">Privacy Policy</a>.
        </p>

    </div>


    <div class="auth-card <?php if ($openCard == "signup") { echo "show"; } ?>" id="signup-card">

        <button type="button" class="auth-close">&times;</button>

        <div class="auth-logo">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/logo-home-page.png" alt="HelpHive logo">
        </div>

        <hr class="auth-line">

        <h2 class="auth-title">Sign Up</h2>
        <p class="auth-subtitle">
            Already have an account?
            <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/login.php" class="auth-switch open-login">Log In</a>
        </p>

        <button type="button" class="social-button" data-provider="Google">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/google-logo.png" alt="Google logo">
            <span>Continue with Google</span>
        </button>

        <button type="button" class="social-button" data-provider="Facebook">
            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/fb-logo.png" alt="Facebook logo">
            <span>Continue with Facebook</span>
        </button>

        <hr class="auth-line">

        <form method="post" action="<?php echo BASE_URL; ?>/backend/form-actions/signup-action.php" class="auth-form">

            <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($returnUrl); ?>">

            <?php if ($openCard == "signup" && count($authErrors) > 0) { ?>
                <div class="auth-errors">
                    <?php foreach ($authErrors as $error) { ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="auth-row">
                <div class="auth-field">
                    <label for="signup-first-name" class="auth-label">First Name</label>
                    <input type="text"
                           id="signup-first-name"
                           name="first_name"
                           class="auth-input"
                           placeholder="John"
                           value="<?php echo $oldFirstName; ?>">
                </div>

                <div class="auth-field">
                    <label for="signup-last-name" class="auth-label">Last Name</label>
                    <input type="text"
                           id="signup-last-name"
                           name="last_name"
                           class="auth-input"
                           placeholder="Smith"
                           value="<?php echo $oldLastName; ?>">
                </div>
            </div>

            <div class="auth-field">
                <label for="signup-email" class="auth-label">Email Address</label>
                <input type="email"
                       id="signup-email"
                       name="email"
                       class="auth-input"
                       placeholder="Enter your email"
                       value="<?php echo $oldEmail; ?>">
            </div>

            <div class="auth-field">
                <label for="signup-password" class="auth-label">Password</label>
                <input type="password"
                       id="signup-password"
                       name="password"
                       class="auth-input"
                       placeholder="Enter your password">
            </div>

            <div class="auth-field">
                <label for="signup-location" class="auth-label">Location</label>
                <input type="text"
                       id="signup-location"
                       name="location"
                       class="auth-input"
                       placeholder="Manila, Metro Manila, Philippines"
                       value="<?php echo $oldLocation; ?>">
            </div>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/contact.php" class="forgot-link">Forgot Password?</a>

            <button type="submit" class="auth-submit">Sign Up</button>

        </form>

        <p class="auth-terms">
            By creating an account, you agree to our
            <a href="<?php echo BASE_URL; ?>/frontend/pages/terms.php">Terms of Use</a>
            and
            <a href="<?php echo BASE_URL; ?>/frontend/pages/privacy.php">Privacy Policy</a>.
        </p>

    </div>

</div>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/auth-modal.js"></script>