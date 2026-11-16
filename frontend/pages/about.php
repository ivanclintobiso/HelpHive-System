<?php
$pageTitle = "About HelpHive";
$pageCss = "about";

include __DIR__ . "/../components/header.php";

$registerLink = BASE_URL . "/frontend/pages/search.php";
$registerClass = "";

if (!$isLoggedIn) {
    $registerLink = BASE_URL . "/frontend/pages/auth/signup.php";
    $registerClass = "open-signup";
}
?>

<main>

    <section class="about-intro">

        <h1 class="about-title">About HelpHive</h1>

        <p class="about-intro-text">
            We believe that a kinder world begins when people come together to help.
            HelpHive connects caring individuals with organizations and meaningful
            volunteer opportunities that make a difference in local communities.
        </p>

        <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-about-hero.jpg"
             alt="Volunteers planting together"
             class="about-hero-image">

    </section>


    <section class="story-section">

        <div class="story-box">

            <div class="story-block">
                <h2>Our Story</h2>
                <p>
                    HelpHive was created around a simple idea: everyone should have the
                    opportunity to make a difference. Finding ways to help shouldn't be
                    difficult, so we bring volunteers and community organizations together
                    in one place.
                </p>
                <p>
                    Whether you're giving your time, sharing your skills, or organizing a
                    community activity, HelpHive helps turn the desire to help into
                    meaningful action.
                </p>
            </div>

            <div class="story-block">
                <h2>Our Mission</h2>
                <p>
                    To make volunteering more accessible by connecting passionate people
                    with organizations that need helping hands, while supporting meaningful
                    community engagement and positive change.
                </p>
            </div>

            <div class="story-block">
                <h2>Our Vision</h2>
                <p>
                    To build a kinder, more connected world where everyone can contribute
                    to their communities and every act of kindness has the chance to grow.
                </p>
            </div>

        </div>

    </section>


    <section class="what-section">

        <h2 class="what-title">What we do</h2>

        <div class="what-list">

            <div class="what-item">
                <div class="what-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-icon-discover.png"
                         alt="Discover opportunities icon">
                </div>
                <div class="what-text">
                    <h3>Discover Opportunities</h3>
                    <p>Help volunteers find activities that match their interests, skills, and availability.</p>
                </div>
            </div>

            <div class="what-item">
                <div class="what-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-icon-connect.png"
                         alt="Connect icon">
                </div>
                <div class="what-text">
                    <h3>Connect Organizations and Volunteers</h3>
                    <p>Give community organizations a place to share opportunities and welcome people who want to help.</p>
                </div>
            </div>

            <div class="what-item">
                <div class="what-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-icon-recognize.png"
                         alt="Recognize contributions icon">
                </div>
                <div class="what-text">
                    <h3>Recognize Every Contribution</h3>
                    <p>Support the tracking of volunteer participation and verified hours to help organizations recognize their volunteers' efforts.</p>
                </div>
            </div>

        </div>

    </section>


    <section class="values-section">

        <h2 class="values-title">Our Values</h2>

        <div class="values-grid">

            <div class="value-item">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-value-kindness.png"
                     alt="Kindness icon"
                     class="value-icon">
                <h3>Kindness</h3>
                <p>Every act of helping matters.</p>
            </div>

            <div class="value-item">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-value-community.png"
                     alt="Community icon"
                     class="value-icon">
                <h3>Community</h3>
                <p>We achieve more when we work together.</p>
            </div>

            <div class="value-item">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-value-trust.png"
                     alt="Trust icon"
                     class="value-icon">
                <h3>Trust</h3>
                <p>We value honesty and accountability.</p>
            </div>

            <div class="value-item">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/temp-value-impact.png"
                     alt="Impact icon"
                     class="value-icon">
                <h3>Impact</h3>
                <p>Small efforts can inspire lasting change.</p>
            </div>

        </div>

    </section>


    <section class="about-cta-section">

        <div class="about-cta-box">
            <h2>A kinder world begins with you.</h2>
            <p>Find a cause you care about and turn your kindness into action.</p>

            <a href="<?php echo $registerLink; ?>" class="about-cta-button <?php echo $registerClass; ?>">
                Register Now
            </a>
        </div>

    </section>

</main>

<?php include __DIR__ . "/../components/footer.php"; ?>