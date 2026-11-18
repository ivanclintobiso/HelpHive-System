var searchForm = document.querySelector(".search-form");
var dropdownButtons = document.querySelectorAll(".dropdown-button");
var dropdownInputs = document.querySelectorAll(".dropdown-menu input");
var keywordInput = document.querySelector("input[name='keyword']");
var locationInput = document.getElementById("location-input");
var clearLocationButton = document.getElementById("clear-location");

var typingTimer = null;
var latestRequest = 0;


function closeAllDropdowns() {
    var openDropdowns = document.querySelectorAll(".filter-dropdown.open");

    openDropdowns.forEach(function (dropdown) {
        dropdown.classList.remove("open");
    });
}


function loadResults() {
    var params = new URLSearchParams(new FormData(searchForm)).toString();

    latestRequest = latestRequest + 1;
    var thisRequest = latestRequest;

    fetch("search.php?" + params)
        .then(function (response) {
            return response.text();
        })
        .then(function (html) {
            if (thisRequest != latestRequest) {
                return;
            }

            var newPage = new DOMParser().parseFromString(html, "text/html");
            var newResults = newPage.querySelector(".results-section");

            document.querySelector(".results-section").innerHTML = newResults.innerHTML;
            window.history.replaceState(null, "", "search.php?" + params);
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


keywordInput.addEventListener("input", function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(loadResults, 300);
});


clearLocationButton.addEventListener("click", function () {
    locationInput.value = "";
    locationInput.focus();
});
