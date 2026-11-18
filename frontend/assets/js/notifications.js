var deleteOverlay = document.getElementById("delete-overlay");
var deleteCancel = document.getElementById("delete-cancel");
var deleteConfirm = document.getElementById("delete-confirm");

var formToDelete = null;


function openDeleteBox(form) {
    formToDelete = form;
    deleteOverlay.classList.add("open");
}

function closeDeleteBox() {
    formToDelete = null;
    deleteOverlay.classList.remove("open");
}


document.addEventListener("click", function (event) {
    var deleteButton = event.target.closest(".notification-delete-button");

    if (deleteButton) {
        openDeleteBox(deleteButton.closest("form"));
        return;
    }

    if (event.target == deleteOverlay) {
        closeDeleteBox();
    }
});

deleteCancel.addEventListener("click", closeDeleteBox);

deleteConfirm.addEventListener("click", function () {
    if (formToDelete != null) {
        formToDelete.submit();
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        closeDeleteBox();
    }
});
