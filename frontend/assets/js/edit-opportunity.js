var imageInput = document.getElementById("image-input");
var imageTitle = document.getElementById("image-title");


imageInput.addEventListener("change", function () {
    var file = imageInput.files[0];

    if (!file) {
        imageTitle.textContent = "Upload an Opportunity Image";
        return;
    }

    if (file.size > 2 * 1024 * 1024) {
        alert("The image must be 2 MB or smaller.");
        imageInput.value = "";
        imageTitle.textContent = "Upload an Opportunity Image";
        return;
    }

    imageTitle.textContent = file.name;
});