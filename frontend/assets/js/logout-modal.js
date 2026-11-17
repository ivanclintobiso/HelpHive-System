var logoutOverlay = document.getElementById("logout-overlay");
var logoutCancel = document.getElementById("logout-cancel");
var profileMenuBox = document.getElementById("profile-menu");


function openLogoutCard() {
    if (profileMenuBox) {
        profileMenuBox.classList.remove("open");
    }

    logoutOverlay.classList.add("open");
}

function closeLogoutCard() {
    logoutOverlay.classList.remove("open");
}


document.addEventListener("click", function (event) {
    if (event.target.closest(".logout-link")) {
        event.preventDefault();
        openLogoutCard();
        return;
    }

    if (event.target == logoutOverlay) {
        closeLogoutCard();
    }
});


logoutCancel.addEventListener("click", closeLogoutCard);


document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        closeLogoutCard();
    }
});