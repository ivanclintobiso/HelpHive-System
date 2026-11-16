<?php
$pageTitle = "FAQs";
$pageCss = "faqs";

include __DIR__ . "/../components/header.php";
?>

<main>

    <section class="faq-hero">
        <h1 class="faq-hero-title">Frequently Asked Questions (FAQs)</h1>
        <p class="faq-hero-text">
            Find quick answers to common questions about volunteering,
            registering organizations, and using HelpHive.
        </p>
    </section>


    <section class="faq-content">

        <div class="faq-group">
            <p class="faq-category">General</p>

            <div class="faq-item">
                <h2 class="faq-question">What is HelpHive?</h2>
                <p class="faq-answer">
                    HelpHive is a community volunteering platform that connects people
                    with meaningful opportunities to help their communities. Volunteers
                    can discover activities, register for events, and track their
                    volunteer hours.
                </p>
            </div>
        </div>


        <div class="faq-group">
            <p class="faq-category">For Volunteers</p>

            <div class="faq-item">
                <h2 class="faq-question">How do I find volunteer opportunities?</h2>
                <p class="faq-answer">
                    You can browse available opportunities on our Search Opportunities
                    page. Use filters to find activities based on location, cause,
                    duration, and interests.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How do I register for a volunteer activity?</h2>
                <p class="faq-answer">
                    Choose an opportunity that interests you, view its details, and
                    click the Register button. You can check your registered activities
                    through your account.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How can I track my volunteer hours?</h2>
                <p class="faq-answer">
                    Your completed hours appear in your profile after the organizing
                    team verifies your attendance and records your hours.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">What if my volunteer hours are incorrect?</h2>
                <p class="faq-answer">
                    Contact the organization that hosted the activity and request a
                    correction to your recorded hours.
                </p>
            </div>
        </div>


        <div class="faq-group">
            <p class="faq-category">For Organizations</p>

            <div class="faq-item">
                <h2 class="faq-question">How can my organization join HelpHive?</h2>
                <p class="faq-answer">
                    Select Register Organization on the navigation bar and submit your
                    organization's information for review.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How can my organization post a volunteer opportunity?</h2>
                <p class="faq-answer">
                    Once your organization profile is approved, go to My Organization
                    in your profile dropdown to manage your organization and post
                    volunteer opportunities. You can add descriptions, dates, locations,
                    requirements, and other important details.
                </p>
            </div>

            <div class="faq-item">
                <h2 class="faq-question">How do we verify volunteer hours?</h2>
                <p class="faq-answer">
                    After an activity, your organization can confirm attendance and
                    record the hours completed by each volunteer.
                </p>
            </div>
        </div>


        <div class="faq-group">
            <div class="faq-item">
                <h2 class="faq-question">How can I contact HelpHive for assistance?</h2>
                <p class="faq-answer">
                    You can find the Contact Us link in the footer at the bottom of our
                    website. Click it to access our contact information and get
                    assistance with any questions or issues.
                </p>
            </div>
        </div>

    </section>

</main>

<?php include __DIR__ . "/../components/footer.php"; ?>