var contactForm = document.getElementById("contact-form");
var contactCard = document.getElementById("contact-card");
var contactButton = document.getElementById("contact-button");

contactForm.addEventListener("submit", function (event) {
    if (contactForm.dataset.loggedIn != "yes") {
        event.preventDefault();
        openCard("login");
        return;
    }

    var fullName = document.getElementById("full-name").value.trim();
    var email = document.getElementById("email").value.trim();
    var subject = document.getElementById("subject").value.trim();
    var message = document.getElementById("message").value.trim();

    var allFilled = fullName != "" && email != "" && subject != "" && message != "";

    if (!allFilled) {
        return;
    }

    event.preventDefault();

    contactButton.textContent = "Sending...";
    contactCard.classList.add("leaving");

    setTimeout(function () {
        contactForm.submit();
    }, 300);
});