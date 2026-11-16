var searchForm = document.querySelector(".search-form");
var dropdownButtons = document.querySelectorAll(".dropdown-button");
var dropdownInputs = document.querySelectorAll(".dropdown-menu input");
var locationInput = document.getElementById("location-input");
var clearLocationButton = document.getElementById("clear-location");


function closeAllDropdowns() {
    var openDropdowns = document.querySelectorAll(".filter-dropdown.open");

    openDropdowns.forEach(function (dropdown) {
        dropdown.classList.remove("open");
    });
}


dropdownButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        var dropdown = button.parentElement;
        var wasOpen = dropdown.classList.contains("open");

        closeAllDropdowns();

        if (!wasOpen) {
            dropdown.classList.add("open");
        }
    });
});


document.addEventListener("click", function (event) {
    if (!event.target.closest(".filter-dropdown")) {
        closeAllDropdowns();
    }
});


dropdownInputs.forEach(function (input) {
    input.addEventListener("change", function () {
        searchForm.submit();
    });
});


clearLocationButton.addEventListener("click", function () {
    locationInput.value = "";
    locationInput.focus();
});