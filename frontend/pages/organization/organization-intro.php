<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user_id"])) {
    header("Location: register-organization.php");
    exit;
}

$pageTitle = "Register Organization";
$pageCss = "organization-intro";
$afterLoginPath = "/frontend/pages/organization/register-organization.php";

include __DIR__ . "/../../components/header.php";

$registerLink = BASE_URL . "/frontend/pages/organization/organization-intro.php?auth=login";
$registerClass = "open-login";
?>

<main>

    <section class="intro-hero">

        <div class="intro-card">

            <img src="<?php echo BASE_URL; ?>/frontend/assets/images/bringyourcausetothehive-registerorganizationpage.png"
                 alt="Volunteers helping each other"
                 class="intro-card-image">

            <div class="intro-card-body">
                <h1 class="intro-card-title">Bring your cause to the hive.</h1>

                <p class="intro-card-text">
                    Connect with passionate volunteers and turn your organization's
                    mission into meaningful community action.
                </p>

                <a href="<?php echo $registerLink; ?>" class="btn-outline <?php echo $registerClass; ?>">
                    Register your organization
                    <span class="arrow">&rarr;</span>
                </a>
            </div>

        </div>

    </section>


    <section class="why-section">

        <h2 class="intro-heading">Why join HelpHive?</h2>

        <div class="why-cards">

            <div class="why-card">
                <div class="why-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/findvolunteers-registerorganizationpage-png.png"
                         alt="Find volunteers icon">
                </div>
                <h3>Find Volunteers</h3>
                <p>Connect with people who care about your cause.</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/postoppurtunities-registerorganizationpage-png.png"
                         alt="Post opportunities icon">
                </div>
                <h3>Post Opportunities</h3>
                <p>Share activities and welcome new volunteers.</p>
            </div>

            <div class="why-card">
                <div class="why-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/trackimpact-registerorganizationpage-png.png"
                         alt="Track impact icon">
                </div>
                <h3>Track Impact</h3>
                <p>Manage participation and verified volunteer hours.</p>
            </div>

        </div>

    </section>


    <section class="steps-section">

        <h2 class="intro-heading">Get started in 3 simple steps</h2>

        <div class="steps-list">

            <div class="step">
                <div class="step-number">1</div>
                <div class="step-text">
                    <h3>Create an Account</h3>
                    <p>Sign up or log in to get started.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-text">
                    <h3>Register Your Organization</h3>
                    <p>Submit your organization's information for review.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">
                    <h3>Start Making an Impact</h3>
                    <p>Once approved, publish opportunities and manage volunteer participation.</p>
                </div>
            </div>

        </div>

    </section>


    <section class="community-section">

        <div class="community-box">
            <h2>Your community needs you.</h2>
            <p>Join HelpHive and bring more helping hands to your mission.</p>

            <a href="<?php echo $registerLink; ?>" class="community-button <?php echo $registerClass; ?>">
                Register your Organization
            </a>
        </div>

    </section>

</main>

<?php include __DIR__ . "/../../components/footer.php"; ?>