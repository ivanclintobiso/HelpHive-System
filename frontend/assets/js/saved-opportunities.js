var savedButtons = document.querySelectorAll(".saved-button");
var emptyMessage = document.getElementById("empty-saved");


function showEmptyMessageIfNeeded() {
    var cards = document.querySelectorAll(".opportunity-card");

    if (cards.length == 0) {
        emptyMessage.classList.remove("is-hidden");
    }
}


savedButtons.forEach(function (button) {
    button.addEventListener("click", function () {
        var id = button.getAttribute("data-id");
        var url = button.getAttribute("data-url");
        var card = button.closest(".opportunity-card");

        var formData = new FormData();
        formData.append("opportunity_id", id);
        formData.append("mode", "remove");

        button.disabled = true;

        fetch(url, { method: "POST", body: formData })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status == "removed") {
                    card.remove();
                    showEmptyMessageIfNeeded();
                } else {
                    button.disabled = false;
                }
            })
            .catch(function () {
                button.disabled = false;
            });
    });
});