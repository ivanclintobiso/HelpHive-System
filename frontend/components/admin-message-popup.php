<?php
if (!isset($returnUrl)) {
    $returnUrl = "dashboard.php";
}

$popupId = "msg-popup-" . $message["id"];
$actionUrl = BASE_URL . "/backend/form-actions/admin-message-action.php";

$isRead = $message["is_read"] == 1;

$statusKey = "unread";

if ($isRead) {
    $statusKey = "read";
}
?>

<div class="detail-overlay" id="<?php echo $popupId; ?>">

    <div class="detail-popup detail-popup-wide">

        <div class="detail-top">
            <button type="button" class="detail-back close-detail">
                <i class="fa-solid fa-chevron-left"></i>
                Back
            </button>

            <span class="detail-label">Message</span>
        </div>

        <div class="detail-card">

            <div class="detail-message-head">
                <div class="person-cell">
                    <img src="<?php echo getProfileImageUrl($message["profile_image"]); ?>"
                         alt="Profile picture"
                         class="person-avatar person-avatar-big">

                    <div>
                        <h2 class="message-title">Message from <?php echo safe($message["full_name"]); ?></h2>
                        <p class="message-subtitle">Received <?php echo formatAdminDate($message["created_at"]); ?></p>
                    </div>
                </div>

                <span class="status-chip <?php echo getStatusColor($statusKey); ?>">
                    <?php echo getStatusLabel($statusKey); ?>
                </span>
            </div>

            <hr class="message-line">

            <div class="message-columns">

                <div class="message-details">

                    <div class="message-info">
                        <p class="message-label">Full Name</p>
                        <p class="message-value"><?php echo safe($message["full_name"]); ?></p>
                    </div>

                    <div class="message-info">
                        <p class="message-label">Email Address</p>
                        <p class="message-value"><?php echo safe($message["email"]); ?></p>
                    </div>

                    <div class="message-info">
                        <p class="message-label">Subject</p>
                        <p class="message-value"><?php echo safe($message["subject"]); ?></p>
                    </div>

                    <div class="message-info">
                        <p class="message-label">Message</p>
                        <p class="message-value message-value-long"><?php echo safe($message["message"]); ?></p>
                    </div>

                </div>

                <form method="post" action="<?php echo $actionUrl; ?>" class="message-reply reply-form">
                    <input type="hidden" name="message_id" value="<?php echo $message["id"]; ?>">
                    <input type="hidden" name="action" value="reply">
                    <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">

                    <label class="message-label">Reply</label>
                    <textarea name="reply_text"
                              class="message-input message-textarea reply-box"
                              placeholder="Write your reply here..."></textarea>
                    <p class="message-error reply-error"></p>

                    <button type="submit" class="message-reply-button">Send Reply</button>
                </form>

            </div>

            <div class="detail-actions">

                <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                    <input type="hidden" name="message_id" value="<?php echo $message["id"]; ?>">
                    <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">

                    <?php if ($isRead) { ?>
                        <input type="hidden" name="action" value="unread">
                        <button type="button"
                                class="detail-button detail-button-outline confirm-button"
                                data-title="Mark as unread?"
                                data-message="<?php echo safe("Mark the message from " . $message["full_name"] . " as unread?"); ?>"
                                data-yes="Mark as Unread"
                                data-style="approve"
                                data-reason="no">Mark as Unread</button>
                    <?php } else { ?>
                        <input type="hidden" name="action" value="read">
                        <button type="button"
                                class="detail-button detail-button-outline confirm-button"
                                data-title="Mark as read?"
                                data-message="<?php echo safe("Mark the message from " . $message["full_name"] . " as read?"); ?>"
                                data-yes="Mark as Read"
                                data-style="approve"
                                data-reason="no">Mark as Read</button>
                    <?php } ?>
                </form>

                <button type="button" class="detail-button detail-button-outline detail-button-small close-detail">Close</button>

            </div>

        </div>

    </div>

</div>
