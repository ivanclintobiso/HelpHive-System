<?php
include_once __DIR__ . "/admin-popup-helpers.php";

if (!isset($returnUrl)) {
    $returnUrl = "dashboard.php";
}

$popupId = "opp-popup-" . $opportunity["id"];
$opportunityStatus = $opportunity["status"];
$actionUrl = BASE_URL . "/backend/form-actions/admin-opportunity-action.php";
$imagesUrl = BASE_URL . "/frontend/assets/images/";

$imageUrl = getOpportunityImageUrl($opportunity["image"]);
$whatYoullDoLines = getTextLines($opportunity["what_youll_do"]);

$skillsText = $opportunity["skills"];

if ($skillsText == "") {
    $skillsText = "None";
}

$dateAndTime = getActivityDateText($opportunity["activity_date"]) . " &middot; " . getTimeRangeText($opportunity["start_time"], $opportunity["end_time"]);
?>

<div class="detail-overlay" id="<?php echo $popupId; ?>">

    <div class="detail-popup">

        <div class="detail-top">
            <button type="button" class="detail-back close-detail">
                <i class="fa-solid fa-chevron-left"></i>
                Back
            </button>

            <span class="detail-label">Opportunity Details</span>
        </div>


        <div class="detail-opp-card detail-opp-card-top">

            <?php if ($imageUrl != "") { ?>
                <img src="<?php echo $imageUrl; ?>" alt="Opportunity image" class="detail-opp-image">
            <?php } else { ?>
                <span class="detail-opp-image detail-opp-image-empty"></span>
            <?php } ?>

            <div class="detail-opp-body">
                <h2 class="detail-opp-title"><?php echo safe($opportunity["title"]); ?></h2>
                <p class="detail-opp-link"><?php echo safe($opportunity["organization_name"]); ?></p>

                <span class="detail-cause detail-cause-<?php echo safe($opportunity["cause"]); ?>"><?php echo safe(getCauseName($opportunity["cause"])); ?></span>

                <hr class="detail-line">

                <p class="detail-opp-description"><?php echo safe($opportunity["short_description"]); ?></p>

                <div class="detail-tag-row">
                    <span class="detail-tag detail-tag-green"><?php echo safe($opportunity["location_type"]); ?></span>

                    <span class="detail-tag">
                        <img src="<?php echo $imagesUrl; ?>location-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                        <?php echo safe($opportunity["location"]); ?>
                    </span>

                    <span class="detail-tag">
                        <img src="<?php echo $imagesUrl; ?>date-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                        <?php echo $dateAndTime; ?>
                    </span>

                    <span class="detail-tag">
                        <img src="<?php echo $imagesUrl; ?>goodfor-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                        Good for <?php echo safe(getGoodForText($opportunity["good_for"])); ?>
                    </span>

                    <span class="detail-tag">
                        <img src="<?php echo $imagesUrl; ?>duration-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                        <?php echo getDurationName($opportunity["duration"]); ?>
                    </span>
                </div>
            </div>

        </div>


        <div class="detail-card detail-opp-details">

            <div class="detail-facts">

                <div class="detail-fact">
                    <span class="detail-fact-icon"><img src="<?php echo $imagesUrl; ?>date-searchoppurtunitiespage.png" alt=""></span>
                    <div>
                        <p class="detail-info-label">Time</p>
                        <p class="detail-info-value"><?php echo getActivityDateText($opportunity["activity_date"]); ?></p>
                        <p class="detail-info-value"><?php echo getTimeRangeText($opportunity["start_time"], $opportunity["end_time"]); ?></p>
                    </div>
                </div>

                <div class="detail-fact">
                    <span class="detail-fact-icon"><img src="<?php echo $imagesUrl; ?>duration-searchoppurtunitiespage.png" alt=""></span>
                    <div>
                        <p class="detail-info-label">Duration</p>
                        <p class="detail-info-value"><?php echo getDurationName($opportunity["duration"]); ?></p>
                    </div>
                </div>

                <div class="detail-fact">
                    <span class="detail-fact-icon"><img src="<?php echo $imagesUrl; ?>skills-searchoppurtunitiespage.png" alt=""></span>
                    <div>
                        <p class="detail-info-label">Skills needed</p>
                        <p class="detail-info-value"><?php echo safe($skillsText); ?></p>
                    </div>
                </div>

                <div class="detail-fact">
                    <span class="detail-fact-icon"><img src="<?php echo $imagesUrl; ?>goodfor-searchoppurtunitiespage.png" alt=""></span>
                    <div>
                        <p class="detail-info-label">Suitable for</p>
                        <p class="detail-info-value"><?php echo safe(getGoodForText($opportunity["good_for"])); ?></p>
                    </div>
                </div>

            </div>

            <hr class="detail-line">

            <h3 class="detail-card-title">About this opportunity</h3>
            <p class="detail-text"><?php echo safe($opportunity["about"]); ?></p>

            <h3 class="detail-card-title detail-space-top">What you'll do</h3>

            <ul class="detail-list">
                <?php foreach ($whatYoullDoLines as $line) { ?>
                    <li><?php echo safe($line); ?></li>
                <?php } ?>
            </ul>


            <div class="detail-actions detail-actions-wide">

                <?php if ($opportunityStatus == "pending_review") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="opportunity_id" value="<?php echo $opportunity["id"]; ?>">
                        <input type="hidden" name="action" value="approve">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-green confirm-button"
                                data-title="Approve opportunity?"
                                data-message="<?php echo safe("Approve " . $opportunity["title"] . "? Volunteers will be able to see it."); ?>"
                                data-yes="Approve"
                                data-style="approve"
                                data-reason="no">Approve</button>
                    </form>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form detail-form-small">
                        <input type="hidden" name="opportunity_id" value="<?php echo $opportunity["id"]; ?>">
                        <input type="hidden" name="action" value="reject">
                        <input type="hidden" name="reason" value="" class="reason-field">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-outline confirm-button"
                                data-title="Reject opportunity?"
                                data-message="<?php echo safe("Reject " . $opportunity["title"] . "? Please write the reason below."); ?>"
                                data-yes="Reject"
                                data-style="danger"
                                data-reason="yes">Reject</button>
                    </form>

                <?php } else if ($opportunityStatus == "published") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="opportunity_id" value="<?php echo $opportunity["id"]; ?>">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="reason" value="" class="reason-field">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-red confirm-button"
                                data-title="Remove opportunity?"
                                data-message="<?php echo safe("Remove " . $opportunity["title"] . "? Please write the reason below."); ?>"
                                data-label="Reason (the organization will see this in their notifications)"
                                data-yes="Remove"
                                data-style="danger"
                                data-reason="yes">Remove</button>
                    </form>

                <?php } else if ($opportunityStatus == "removed") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="opportunity_id" value="<?php echo $opportunity["id"]; ?>">
                        <input type="hidden" name="action" value="restore">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-green confirm-button"
                                data-title="Restore opportunity?"
                                data-message="<?php echo safe("Restore " . $opportunity["title"] . "? Volunteers will be able to see it again."); ?>"
                                data-yes="Restore"
                                data-style="approve"
                                data-reason="no">Restore</button>
                    </form>

                <?php } ?>

                <button type="button" class="detail-button detail-button-outline detail-button-small close-detail">Close</button>

            </div>

        </div>

    </div>

</div>
