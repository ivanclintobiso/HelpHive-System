var confirmOverlay = document.getElementById("confirm-overlay");
var confirmTitle = document.getElementById("confirm-title");
var confirmText = document.getElementById("confirm-text");
var reasonBox = document.getElementById("reason-box");
var reasonLabel = document.getElementById("reason-label");
var reasonInput = document.getElementById("reason-input");
var reasonError = document.getElementById("reason-error");
var confirmYes = document.getElementById("confirm-yes");
var confirmNo = document.getElementById("confirm-no");

var waitingForm = null;
var needsReason = false;


function openConfirm(button) {
    waitingForm = button.closest("form");
    needsReason = button.dataset.reason == "yes";

    confirmTitle.textContent = button.dataset.title;
    confirmText.textContent = button.dataset.message;
    confirmYes.textContent = button.dataset.yes;
    confirmYes.className = "confirm-yes " + button.dataset.style;

    reasonInput.value = "";
    reasonError.textContent = "";
    reasonInput.classList.remove("input-error");

    if (button.dataset.label) {
        reasonLabel.textContent = button.dataset.label;
    } else {
        reasonLabel.textContent = "Reason (the organization will see this)";
    }

    if (needsReason) {
        reasonBox.classList.add("show");
    } else {
        reasonBox.classList.remove("show");
    }

    confirmOverlay.classList.add("open");
}


function closeConfirm() {
    confirmOverlay.classList.remove("open");
    waitingForm = null;
}


function openDetail(popupId) {
    var popup = document.getElementById(popupId);

    if (popup) {
        popup.classList.add("open");
        document.body.classList.add("popup-open");
    }
}


function closeDetails() {
    var popups = document.querySelectorAll(".detail-overlay");

    for (var i = 0; i < popups.length; i++) {
        popups[i].classList.remove("open");
    }

    document.body.classList.remove("popup-open");
}


document.addEventListener("click", function (event) {
    var button = event.target.closest(".confirm-button");

    if (button) {
        openConfirm(button);
        return;
    }

    if (event.target == confirmOverlay) {
        closeConfirm();
        return;
    }

    if (event.target.closest(".close-detail")) {
        closeDetails();
        return;
    }

    if (event.target.classList.contains("detail-overlay")) {
        closeDetails();
        return;
    }

    var opener = event.target.closest("[data-target]");

    if (opener) {
        openDetail(opener.getAttribute("data-target"));
    }
});


confirmNo.addEventListener("click", closeConfirm);


confirmYes.addEventListener("click", function () {
    if (needsReason) {
        var reason = reasonInput.value.trim();

        if (reason == "") {
            reasonError.textContent = "Please write a reason.";
            reasonInput.classList.add("input-error");
            return;
        }

        waitingForm.querySelector(".reason-field").value = reason;
    }

    waitingForm.submit();
});


document.addEventListener("keydown", function (event) {
    if (event.key == "Escape") {
        if (confirmOverlay.classList.contains("open")) {
            closeConfirm();
        } else {
            closeDetails();
        }
    }
});


document.addEventListener("submit", function (event) {
    var form = event.target;

    if (!form.classList.contains("reply-form")) {
        return;
    }

    var replyBox = form.querySelector(".reply-box");
    var replyError = form.querySelector(".reply-error");

    if (replyBox.value.trim() == "") {
        event.preventDefault();
        replyBox.classList.add("input-error");
        replyError.textContent = "Please write your reply first.";
    }
});


document.addEventListener("input", function (event) {
    if (!event.target.classList.contains("reply-box")) {
        return;
    }

    var form = event.target.closest("form");

    event.target.classList.remove("input-error");
    form.querySelector(".reply-error").textContent = "";
});


var searchInput = document.querySelector(".search-box input");
var searchTimer = null;
var searchNumber = 0;


function loadResults() {
    var form = searchInput.closest("form");
    var params = new URLSearchParams(new FormData(form));
    var url = form.getAttribute("action") + "?" + params.toString();

    searchNumber = searchNumber + 1;
    var thisNumber = searchNumber;

    fetch(url)
        .then(function (response) {
            return response.text();
        })
        .then(function (html) {
            if (thisNumber != searchNumber) {
                return;
            }

            var newPage = new DOMParser().parseFromString(html, "text/html");
            var newResults = newPage.getElementById("results");

            if (newResults) {
                document.getElementById("results").innerHTML = newResults.innerHTML;
            }
        });
}


if (searchInput) {
    searchInput.addEventListener("input", function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadResults, 250);
    });
}
