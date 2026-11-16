<?php
session_start();

$pageTitle = "Contact Us";
$pageCss = "contact";


function safe($text) {
    return htmlspecialchars($text);
}

function oldValue($old, $name) {
    if (isset($old[$name])) {
        return safe($old[$name]);
    }

    return "";
}

function errorClass($errors, $name) {
    if (isset($errors[$name])) {
        return "input-error";
    }

    return "";
}


$errors = [];
$old = [];

if (isset($_SESSION["contact_errors"])) {
    $errors = $_SESSION["contact_errors"];
    unset($_SESSION["contact_errors"]);
}

if (isset($_SESSION["contact_old"])) {
    $old = $_SESSION["contact_old"];
    unset($_SESSION["contact_old"]);
}

include __DIR__ . "/../components/header.php";
?>

<main class="contact-section">

    <div class="contact-card" id="contact-card">

        <h1 class="contact-title">Send us a message</h1>
        <p class="contact-subtitle">Fill out the form below, and let us know how we can help.</p>

        <hr class="contact-line">

        <form method="post"
              action="<?php echo BASE_URL; ?>/backend/form-actions/contact-action.php"
              id="contact-form">

            <?php if (count($errors) > 0) { ?>
                <div class="form-errors">
                    <ul>
                        <?php foreach ($errors as $error) { ?>
                            <li><?php echo safe($error); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <div class="contact-group">
                <label for="full-name" class="contact-label">Full Name *</label>
                <input type="text"
                       id="full-name"
                       name="full_name"
                       class="contact-input <?php echo errorClass($errors, "full_name"); ?>"
                       placeholder="Enter your full name"
                       value="<?php echo oldValue($old, "full_name"); ?>">
            </div>

            <div class="contact-group">
                <label for="email" class="contact-label">Email Address *</label>
                <input type="text"
                       id="email"
                       name="email"
                       class="contact-input <?php echo errorClass($errors, "email"); ?>"
                       placeholder="Enter your email address"
                       value="<?php echo oldValue($old, "email"); ?>">
            </div>

            <div class="contact-group">
                <label for="subject" class="contact-label">Subject *</label>
                <input type="text"
                       id="subject"
                       name="subject"
                       class="contact-input <?php echo errorClass($errors, "subject"); ?>"
                       placeholder="What is your message about?"
                       value="<?php echo oldValue($old, "subject"); ?>">
            </div>

            <div class="contact-group">
                <label for="message" class="contact-label">Message *</label>
                <textarea id="message"
                          name="message"
                          class="contact-input contact-textarea <?php echo errorClass($errors, "message"); ?>"
                          placeholder="Write your message here..."><?php echo oldValue($old, "message"); ?></textarea>
            </div>

            <button type="submit" class="contact-button" id="contact-button">Send Message</button>

        </form>

    </div>

</main>

<script src="<?php echo BASE_URL; ?>/frontend/assets/js/contact.js"></script>

<?php include __DIR__ . "/../components/footer-simple.php"; ?>