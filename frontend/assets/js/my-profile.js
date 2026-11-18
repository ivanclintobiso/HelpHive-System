var profileForm = document.getElementById("profile-form");
var profileInputs = document.querySelectorAll(".field-input");
var editButtons = document.querySelectorAll(".edit-trigger");
var namePencil = document.getElementById("name-pencil");
var fullNameInput = document.getElementById("full-name");


function startEditing() {
    profileForm.classList.add("editing");

    profileInputs.forEach(function (input) {
        input.readOnly = false;
    });
}


editButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        startEditing();
        fullNameInput.focus();
    });
});


namePencil.addEventListener("click", function () {
    startEditing();
    fullNameInput.focus();
});


if (profileForm.classList.contains("editing")) {
    startEditing();
}

var photoInput = document.getElementById("profile-photo-input");
var avatarPreview = document.getElementById("avatar-preview");


photoInput.addEventListener("change", function () {
    var file = photoInput.files[0];

    if (!file) {
        return;
    }

    if (file.type.indexOf("image/") != 0) {
        alert("Please choose an image file only.");
        photoInput.value = "";
        return;
    }

    var reader = new FileReader();

    reader.onload = function (event) {
        avatarPreview.src = event.target.result;
    };

    reader.readAsDataURL(file);
});