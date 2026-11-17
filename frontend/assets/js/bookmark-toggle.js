var bookmarkButtons = document.querySelectorAll(".bookmark-button");


function showBookmark(button, isSaved) {
    if (isSaved) {
        button.classList.add("saved");
    } else {
        button.classList.remove("saved");
    }
}


bookmarkButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        var id = button.getAttribute("data-id");
        var url = button.getAttribute("data-url");

        var formData = new FormData();
        formData.append("opportunity_id", id);

        button.disabled = true;

        fetch(url, { method: "POST", body: formData })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                button.disabled = false;

                if (data.status == "login_required") {
                    document.querySelector(".open-login").click();
                }

                if (data.status == "saved") {
                    showBookmark(button, true);
                }

                if (data.status == "removed") {
                    showBookmark(button, false);
                }
            })
            .catch(function () {
                button.disabled = false;
            });
    });
});