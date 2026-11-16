var profileMenu = document.getElementById("profile-menu");
var profileButton = document.getElementById("profile-button");

profileButton.addEventListener("click", function () {
    profileMenu.classList.toggle("open");
});

document.addEventListener("click", function (event) {
    if (!profileMenu.contains(event.target)) {
        profileMenu.classList.remove("open");
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        profileMenu.classList.remove("open");
    }
});