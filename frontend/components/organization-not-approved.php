<?php
if (!isset($pageTitle)) {
    $pageTitle = "";
}

if (!isset($pageSubtitle)) {
    $pageSubtitle = "";
}

if (!isset($organization)) {
    $organization = [
        "status" => "pending",
        "rejection_reason" => ""
    ];
}

$statusTitle = "Your organization is under review";
$statusText = "An admin is reviewing your organization. You can post opportunities as soon as it is approved.";
$linkText = "Back to My Organization";
$linkUrl = BASE_URL . "/frontend/pages/organization/my-organization.php";

if ($organization["status"] == "declined") {
    $statusTitle = "Your organization was declined";
    $statusText = "You can register again with updated details.";

    if (isset($organization["rejection_reason"]) && $organization["rejection_reason"] != "") {
        $statusText = "Reason: " . $organization["rejection_reason"] . ". You can register again with updated details.";
    }

    $linkText = "Register Organization";
    $linkUrl = BASE_URL . "/frontend/pages/organization/register-organization.php";
}

if ($organization["status"] == "suspended") {
    $statusTitle = "Your organization is suspended";
    $statusText = "You cannot post opportunities right now. Please contact us if you think this is a mistake.";
    $linkText = "Contact Us";
    $linkUrl = BASE_URL . "/frontend/pages/contact.php";
}

include __DIR__ . "/header.php";
?>

<div class="account-layout">

    <?php include __DIR__ . "/sidebar.php"; ?>

    <main class="account-main">

        <h1 class="page-title"><?php echo htmlspecialchars($pageTitle); ?></h1>
        <p class="page-subtitle"><?php echo htmlspecialchars($pageSubtitle); ?></p>

        <div class="empty-state">
            <h3><?php echo htmlspecialchars($statusTitle); ?></h3>
            <p><?php echo htmlspecialchars($statusText); ?></p>
            <a href="<?php echo $linkUrl; ?>" class="empty-link"><?php echo $linkText; ?></a>
        </div>

    </main>

</div>

<?php include __DIR__ . "/footer.php"; ?>