var bookmarkButtons = document.querySelectorAll(".bookmark-button");
var savedIds = [];

var storedText = localStorage.getItem("savedOpportunities");
if (storedText) {
    savedIds = JSON.parse(storedText);
}


function showBookmark(button, isSaved) {
    var icon = button.querySelector("i");

    if (isSaved) {
        button.classList.add("saved");
        icon.className = "fa-solid fa-bookmark";
    } else {
        button.classList.remove("saved");
        icon.className = "fa-regular fa-bookmark";
    }
}


bookmarkButtons.forEach(function (button) {
    var id = button.getAttribute("data-id");

    showBookmark(button, savedIds.indexOf(id) !== -1);

    button.addEventListener("click", function () {
        var position = savedIds.indexOf(id);

        if (position === -1) {
            savedIds.push(id);
        } else {
            savedIds.splice(position, 1);
        }

        localStorage.setItem("savedOpportunities", JSON.stringify(savedIds));
        showBookmark(button, savedIds.indexOf(id) !== -1);
    });
});