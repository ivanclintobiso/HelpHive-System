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