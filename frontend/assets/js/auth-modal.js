var overlay = document.getElementById("auth-overlay");
var loginCard = document.getElementById("login-card");
var signupCard = document.getElementById("signup-card");


function removeAuthFromUrl() {
    var url = new URL(window.location.href);
    url.searchParams.delete("auth");
    window.history.replaceState(null, "", url.toString());
}

function openCard(cardName) {
    loginCard.classList.remove("show");
    signupCard.classList.remove("show");

    if (cardName == "login") {
        loginCard.classList.add("show");
    } else {
        signupCard.classList.add("show");
    }

    overlay.classList.add("open");
    document.body.classList.add("auth-open");
}

function closeCard() {
    overlay.classList.remove("open");
    document.body.classList.remove("auth-open");
    removeAuthFromUrl();
}


if (overlay.classList.contains("open")) {
    document.body.classList.add("auth-open");
}


document.addEventListener("click", function (event) {
    if (event.target.closest(".open-login")) {
        event.preventDefault();
        openCard("login");
        return;
    }

    if (event.target.closest(".open-signup")) {
        event.preventDefault();
        openCard("signup");
        return;
    }

    if (event.target.closest(".auth-close")) {
        closeCard();
        return;
    }

    if (event.target == overlay) {
        closeCard();
        return;
    }

    var socialButton = event.target.closest(".social-button");

    if (socialButton) {
        alert(socialButton.dataset.provider + " sign-in is not available yet.");
    }
});


document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        closeCard();
    }
});