var passwordRow = document.getElementById("password-row");
var passwordForm = document.getElementById("password-form");
var eyeButtons = document.querySelectorAll(".eye-button");
var successOverlay = document.getElementById("success-overlay");


passwordRow.addEventListener("click", function () {
    passwordRow.classList.toggle("open");
    passwordForm.classList.toggle("open");
});


eyeButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        var input = document.getElementById(button.dataset.target);
        var icon = button.querySelector("i");

        if (input.type == "password") {
            input.type = "text";
            icon.className = "fa-regular fa-eye";
        } else {
            input.type = "password";
            icon.className = "fa-regular fa-eye-slash";
        }
    });
});


if (successOverlay) {
    setTimeout(function () {
        successOverlay.classList.add("hide");
    }, 3000);

    successOverlay.addEventListener("click", function () {
        successOverlay.classList.add("hide");
    });
}