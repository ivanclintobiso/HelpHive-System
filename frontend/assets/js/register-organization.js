const logoInput = document.getElementById("logo-input");
const uploadTitle = document.getElementById("upload-title");

logoInput.addEventListener("change", function () {
    if (logoInput.files.length > 0) {
        uploadTitle.textContent = logoInput.files[0].name;
    }
});