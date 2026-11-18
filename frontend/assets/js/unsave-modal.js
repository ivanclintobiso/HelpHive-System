var unsaveOverlay = document.getElementById("unsave-overlay");
var unsaveCancel = document.getElementById("unsave-cancel");
var unsaveConfirm = document.getElementById("unsave-confirm");

var buttonToUnsave = null;


function openUnsaveBox(button) {
    buttonToUnsave = button;
    unsaveOverlay.classList.add("open");
}

function closeUnsaveBox() {
    buttonToUnsave = null;
    unsaveOverlay.classList.remove("open");
}


unsaveCancel.addEventListener("click", closeUnsaveBox);

unsaveConfirm.addEventListener("click", function () {
    var button = buttonToUnsave;

    closeUnsaveBox();

    if (button != null) {
        removeSavedOpportunity(button);
    }
});

unsaveOverlay.addEventListener("click", function (event) {
    if (event.target == unsaveOverlay) {
        closeUnsaveBox();
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        closeUnsaveBox();
    }
});
