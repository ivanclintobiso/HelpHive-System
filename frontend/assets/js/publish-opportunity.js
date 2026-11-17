var imageInput = document.getElementById("image-input");
var uploadTitle = document.getElementById("upload-title");


imageInput.addEventListener("change", function () {
    var file = imageInput.files[0];

    if (!file) {
        uploadTitle.textContent = "Upload an Opportunity Image";
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert("The image must be 2 MB or smaller.");
        imageInput.value = "";
        uploadTitle.textContent = "Upload an Opportunity Image";
        return;
    }

    uploadTitle.textContent = file.name;
});

var popupOverlay = document.getElementById("popup-overlay");
var continueEditingButton = document.getElementById("continue-editing");
var publishedToast = document.getElementById("published-toast");


function closeDraftPopup() {
    popupOverlay.classList.add("closed");
}


if (continueEditingButton) {
    continueEditingButton.addEventListener("click", closeDraftPopup);

    document.addEventListener("keydown", function (event) {
        if (event.key == "Escape") {
            closeDraftPopup();
        }
    });
}


if (publishedToast) {
    setTimeout(function () {
        window.location.href = publishedToast.dataset.redirect;
    }, 3000);
}