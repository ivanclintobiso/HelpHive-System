<?php
$pageTitle = "Home";
$pageCss = "home";
include __DIR__ . "/../components/header.php";
?>

<main>

    <section class="hero">
        <div class="hero-card">
            <p class="hero-welcome">Welcome to HelpHive!</p>

            <h1 class="hero-title">
                Be a little buzz of hope in a world that needs more kindness.
            </h1>

            <p class="hero-text">
                Discover opportunities to give back, support local organizations,
                and make a positive impact where it matters most. HelpHive connects
                volunteers with meaningful ways to serve their communities because
                even the smallest contribution can inspire great change.
            </p>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php" class="btn-outline">
                View all opportunities
                <span class="arrow">&rarr;</span>
            </a>
        </div>
    </section>


    <section class="join-section">
        <h2 class="section-title">
            Find your place in the <span class="highlight">Hive</span> and make a difference
        </h2>

        <div class="join-cards">

            <a href="<?php echo BASE_URL; ?>/frontend/pages/auth/signup.php" class="join-card open-signup">
                <span class="join-card-arrow">&#8599;</span>

                <div class="join-card-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/jointhehive-homeicon.png" alt="Join the Hive icon">
                </div>

                <h3>Join the Hive</h3>
                <p>
                    Become part of a caring community where people share their
                    time and talents to make a difference.
                </p>
            </a>

            <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php" class="join-card">
                <span class="join-card-arrow">&#8599;</span>

                <div class="join-card-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/volunteeropuurtunities-homeicon.png" alt="Volunteer opportunities icon">
                </div>

                <h3>Volunteer Opportunities</h3>
                <p>
                    Find meaningful volunteer activities that match your interests
                    and help make your community a better place.
                </p>
            </a>

                <a href="<?php echo BASE_URL; ?>/frontend/pages/organization/organization-intro.php" class="join-card">
                <span class="join-card-arrow">&#8599;</span>

                <div class="join-card-icon">
                    <img src="<?php echo BASE_URL; ?>/frontend/assets/images/registeryourorganization-homeicon.png" alt="Register your organization icon">
                </div>

                <h3>Register Your Organization</h3>
                <p>
                    Share volunteer opportunities and connect with people who want
                    to help bring your community projects to life.
                </p>
            </a>

        </div>
    </section>


    <section class="causes-section">
        <h2 class="section-title">Causes you can support</h2>
        <p class="causes-subtitle">
            Everyone has something to give. Find a cause close to your heart
            and make a difference, one good deed at a time.
        </p>

        <div class="cause-cards">

            <div class="cause-card">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/environmentnatureoriginal-homepage.png"
                     alt="Volunteers planting a tree"
                     class="cause-image">

                <div class="cause-body">
                    <h3>Environment &amp; Nature</h3>
                    <p>
                        Help protect our planet through tree planting, cleanups,
                        and environmental activities.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php?cause=environment" class="btn-outline">
                        Explore Cause
                        <span class="arrow">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="cause-card">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/educationyouth-homepage.png.png"
                     alt="Volunteer teaching children"
                     class="cause-image">

                <div class="cause-body">
                    <h3>Education &amp; Youth</h3>
                    <p>
                        Inspire young minds by tutoring, reading together,
                        and supporting learning programs.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php?cause=education" class="btn-outline">
                        Explore Cause
                        <span class="arrow">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="cause-card">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/communitysupport-homepage.png.png"
                     alt="Volunteers serving food"
                     class="cause-image">

                <div class="cause-body">
                    <h3>Community Support</h3>
                    <p>
                        Support families and neighbors through food drives,
                        donation programs, and outreach.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php?cause=community" class="btn-outline">
                        Explore Cause
                        <span class="arrow">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="cause-card">
                <img src="<?php echo BASE_URL; ?>/frontend/assets/images/animalwelfare-homepage.png.png"
                     alt="Volunteer feeding rescued dogs"
                     class="cause-image">

                <div class="cause-body">
                    <h3>Animal Welfare</h3>
                    <p>
                        Give animals a little extra love by helping at shelters
                        and supporting animal care.
                    </p>
                    <a href="<?php echo BASE_URL; ?>/frontend/pages/search.php?cause=animal" class="btn-outline">
                        Explore Cause
                        <span class="arrow">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </section>


    <section class="cta-section">
        <div class="cta-box">
            <h2>A kinder world begins with you.</h2>
            <p>Find a cause you care about and turn your kindness into action.</p>
            <a href="<?php echo BASE_URL; ?>/frontend/pages/about.php" class="btn-white">
                Learn About HelpHive
            </a>
        </div>
    </section>

</main>

<?php include __DIR__ . "/../components/footer.php"; ?>