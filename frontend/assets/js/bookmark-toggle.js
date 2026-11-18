function showBookmark(button, isSaved) {
    if (isSaved) {
        button.classList.add("saved");
    } else {
        button.classList.remove("saved");
    }
}


function removeSavedOpportunity(button) {
    sendBookmarkRequest(button);
}


function sendBookmarkRequest(button) {
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
}


document.addEventListener("click", function (event) {
    var button = event.target.closest(".bookmark-button");

    if (!button) {
        return;
    }

    if (button.classList.contains("saved")) {
        openUnsaveBox(button);
        return;
    }

    sendBookmarkRequest(button);
});
