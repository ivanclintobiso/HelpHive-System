<?php
include __DIR__ . "/../access-control/admin-required.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: " . BASE_URL . "/frontend/pages/admin/dashboard.php");
    exit;
}

$messageId = (int) getPostText("message_id");
$action = getPostText("action");

$messages = getMessagesForAdmin($connection);
$message = findRowById($messages, $messageId);

if ($message == null) {
    finishAdminAction("That message was not found.", true);
}

if ($action == "reply") {
    $replyText = getPostText("reply_text");

    if ($replyText == "") {
        finishAdminAction("Please write your reply first.", true);
    }

    finishAdminAction("Reply box is ready. Sending replies to users will be added soon.", false);
}

if ($action == "read") {
    updateMessageRead($connection, $messageId, 1);

    finishAdminAction("Message marked as read.", false);
}

if ($action == "unread") {
    updateMessageRead($connection, $messageId, 0);

    finishAdminAction("Message marked as unread.", false);
}

finishAdminAction("Unknown action.", true);