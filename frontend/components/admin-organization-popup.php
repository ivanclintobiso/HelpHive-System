<?php
include_once __DIR__ . "/admin-popup-helpers.php";

if (!isset($allOpportunities)) {
    $allOpportunities = [];
}

if (!isset($returnUrl)) {
    $returnUrl = "dashboard.php";
}

$popupId = "org-popup-" . $organization["id"];
$organizationStatus = $organization["status"];
$actionUrl = BASE_URL . "/backend/form-actions/admin-organization-action.php";

$logoUrl = getLogoUrl($organization["logo"]);
$firstLetter = strtoupper(substr($organization["organization_name"], 0, 1));

$organizationOpportunities = [];

foreach ($allOpportunities as $item) {
    if ($item["owner_id"] == $organization["user_id"] && $item["status"] == "published") {
        $organizationOpportunities[] = $item;
    }
}

$opportunityCountText = count($organizationOpportunities) . " opportunities";

if (count($organizationOpportunities) == 1) {
    $opportunityCountText = "1 opportunity";
}

$statusChipText = "Verified Organization";
$statusChipClass = "detail-chip-green";
$statusChipIcon = "fa-circle-check";

if ($organizationStatus == "pending") {
    $statusChipText = "Pending Review";
    $statusChipClass = "detail-chip-yellow";
    $statusChipIcon = "fa-hourglass-half";
}

if ($organizationStatus == "suspended") {
    $statusChipText = "Suspended";
    $statusChipClass = "detail-chip-red";
    $statusChipIcon = "fa-ban";
}

if ($organizationStatus == "declined") {
    $statusChipText = "Declined";
    $statusChipClass = "detail-chip-red";
    $statusChipIcon = "fa-ban";
}
?>

<div class="detail-overlay" id="<?php echo $popupId; ?>">

    <div class="detail-popup">

        <div class="detail-top">
            <button type="button" class="detail-back close-detail">
                <i class="fa-solid fa-chevron-left"></i>
                Back
            </button>

            <span class="detail-label">Organization Profile</span>
        </div>


        <div class="detail-card detail-profile">

            <div class="detail-profile-row">

                <?php if ($logoUrl != "") { ?>
                    <img src="<?php echo $logoUrl; ?>" alt="Organization logo" class="detail-logo">
                <?php } else { ?>
                    <span class="detail-logo detail-logo-empty"><?php echo safe($firstLetter); ?></span>
                <?php } ?>

                <div class="detail-profile-text">
                    <h2 class="detail-name"><?php echo safe($organization["organization_name"]); ?></h2>

                    <p class="detail-meta">
                        <?php echo safe($organization["organization_type"]); ?> &middot; <?php echo safe($organization["city_province"]); ?>
                    </p>

                    <p class="detail-joined">
                        <i class="fa-regular fa-calendar"></i>
                        Joined <?php echo date("F Y", strtotime($organization["created_at"])); ?>
                    </p>
                </div>

                <span class="detail-chip <?php echo $statusChipClass; ?>">
                    <i class="fa-solid <?php echo $statusChipIcon; ?>"></i>
                    <?php echo $statusChipText; ?>
                </span>

            </div>


            <div class="detail-actions">

                <?php if ($organizationStatus == "pending") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="organization_id" value="<?php echo $organization["id"]; ?>">
                        <input type="hidden" name="action" value="approve">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-green confirm-button"
                                data-title="Approve organization?"
                                data-message="<?php echo safe("Approve " . $organization["organization_name"] . "? It will be able to post opportunities."); ?>"
                                data-yes="Approve"
                                data-style="approve"
                                data-reason="no">Approve</button>
                    </form>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form detail-form-small">
                        <input type="hidden" name="organization_id" value="<?php echo $organization["id"]; ?>">
                        <input type="hidden" name="action" value="reject">
                        <input type="hidden" name="reason" value="" class="reason-field">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-outline confirm-button"
                                data-title="Reject organization?"
                                data-message="<?php echo safe("Reject " . $organization["organization_name"] . "? Please write the reason below."); ?>"
                                data-yes="Reject"
                                data-style="danger"
                                data-reason="yes">Reject</button>
                    </form>

                <?php } else if ($organizationStatus == "approved") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="organization_id" value="<?php echo $organization["id"]; ?>">
                        <input type="hidden" name="action" value="suspend">
                        <input type="hidden" name="reason" value="" class="reason-field">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-red confirm-button"
                                data-title="Suspend organization?"
                                data-message="<?php echo safe("Suspend " . $organization["organization_name"] . "? Please write the reason below."); ?>"
                                data-label="Reason (the organization will see this in their notifications)"
                                data-yes="Suspend"
                                data-style="danger"
                                data-reason="yes">Suspend</button>
                    </form>

                <?php } else if ($organizationStatus == "suspended") { ?>

                    <form method="post" action="<?php echo $actionUrl; ?>" class="detail-form">
                        <input type="hidden" name="organization_id" value="<?php echo $organization["id"]; ?>">
                        <input type="hidden" name="action" value="reactivate">
                        <input type="hidden" name="return_url" value="<?php echo safe($returnUrl); ?>">
                        <button type="button"
                                class="detail-button detail-button-green confirm-button"
                                data-title="Reactivate organization?"
                                data-message="<?php echo safe("Reactivate " . $organization["organization_name"] . "? It will be able to post opportunities again."); ?>"
                                data-yes="Reactivate"
                                data-style="approve"
                                data-reason="no">Reactivate</button>
                    </form>

                <?php } ?>

                <button type="button" class="detail-button detail-button-outline detail-button-small close-detail">Close</button>

            </div>

        </div>


        <div class="detail-two-columns">

            <div class="detail-card">
                <h3 class="detail-card-title">About Us</h3>

                <p class="detail-text"><?php echo safe($organization["about"]); ?></p>

                <hr class="detail-line">

                <h4 class="detail-small-title">Cause Areas</h4>

                <div class="detail-tag-row">
                    <?php foreach (getCauseList($organization["cause_areas"]) as $cause) { ?>
                        <span class="detail-cause detail-cause-<?php echo safe($cause); ?>"><?php echo safe(getCauseName($cause)); ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="detail-card">
                <h3 class="detail-card-title">Organization Information</h3>

                <div class="detail-info-row">
                    <span class="detail-info-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <div>
                        <p class="detail-info-label">Location</p>
                        <p class="detail-info-value"><?php echo safe($organization["city_province"]); ?></p>
                    </div>
                </div>

                <div class="detail-info-row">
                    <span class="detail-info-icon"><i class="fa-regular fa-envelope"></i></span>
                    <div>
                        <p class="detail-info-label">Email</p>
                        <p class="detail-info-value"><?php echo safe($organization["email"]); ?></p>
                    </div>
                </div>

                <div class="detail-info-row">
                    <span class="detail-info-icon"><i class="fa-solid fa-phone"></i></span>
                    <div>
                        <p class="detail-info-label">Contact</p>
                        <p class="detail-info-value"><?php echo safe($organization["contact_number"]); ?></p>
                    </div>
                </div>

                <div class="detail-info-row">
                    <span class="detail-info-icon"><i class="fa-solid fa-globe"></i></span>
                    <div>
                        <p class="detail-info-label">Media</p>
                        <p class="detail-info-value"><?php echo safe($organization["website"]); ?></p>
                    </div>
                </div>
            </div>

        </div>


        <div class="detail-section-head">
            <h3 class="detail-section-title">Volunteer Opportunities</h3>
            <span class="detail-count"><?php echo $opportunityCountText; ?></span>
        </div>

        <?php if (count($organizationOpportunities) == 0) { ?>
            <div class="detail-card">
                <p class="detail-text">This organization has no published opportunities yet.</p>
            </div>
        <?php } ?>

        <?php foreach ($organizationOpportunities as $item) { ?>

            <?php $itemImageUrl = getOpportunityImageUrl($item["image"]); ?>

            <div class="detail-opp-card">

                <?php if ($itemImageUrl != "") { ?>
                    <img src="<?php echo $itemImageUrl; ?>" alt="Opportunity image" class="detail-opp-image">
                <?php } else { ?>
                    <span class="detail-opp-image detail-opp-image-empty"></span>
                <?php } ?>

                <div class="detail-opp-body">
                    <h4 class="detail-opp-title"><?php echo safe($item["title"]); ?></h4>
                    <p class="detail-opp-org"><?php echo safe($item["organization_name"]); ?></p>

                    <span class="detail-cause detail-cause-<?php echo safe($item["cause"]); ?>"><?php echo safe(getCauseName($item["cause"])); ?></span>

                    <hr class="detail-line">

                    <p class="detail-opp-description"><?php echo safe($item["short_description"]); ?></p>

                    <div class="detail-tag-row">
                        <span class="detail-tag detail-tag-green"><?php echo safe($item["location_type"]); ?></span>

                        <span class="detail-tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/location-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                            <?php echo safe($item["location"]); ?>
                        </span>

                        <span class="detail-tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/date-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                            <?php echo getActivityDateText($item["activity_date"]); ?>
                        </span>

                        <span class="detail-tag">
                            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/duration-searchoppurtunitiespage.png" alt="" class="detail-tag-icon">
                            <?php echo getDurationName($item["duration"]); ?>
                        </span>
                    </div>
                </div>

            </div>

        <?php } ?>

    </div>

</div>
