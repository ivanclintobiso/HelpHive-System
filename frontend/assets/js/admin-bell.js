var bellMenu = document.getElementById("bell-menu");
var bellButton = document.getElementById("bell-button");


bellButton.addEventListener("click", function () {
    bellMenu.classList.toggle("open");
});

document.addEventListener("click", function (event) {
    if (!bellMenu.contains(event.target)) {
        bellMenu.classList.remove("open");
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        bellMenu.classList.remove("open");
    }
});
