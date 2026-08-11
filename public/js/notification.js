// filepath: public/js/notification.js
function showNotification(message, isError = false) {
    const modal = document.getElementById("notification-modal");
    const titleEl = document.getElementById("modal-title");
    const messageEl = document.getElementById("modal-message");
    const actionButton = document.getElementById("modal-action-button");
    const closeButton = document.getElementById("modal-close-button");

    if (!modal || !titleEl || !messageEl || !actionButton || !closeButton) {
        console.error("Modal elements not found");
        alert(isError ? `Error: ${message}` : message); // Fallback
        return;
    }

    titleEl.textContent = isError ? "Error: Terjadi kesalahan" : "Berhasil!";
    messageEl.textContent = message;

    actionButton.className = "px-4 py-2 text-sm rounded-lg";
    closeButton.classList.add("hidden");

    if (isError) {
        actionButton.textContent = "Close";
        actionButton.classList.add(
            "text-gray-700",
            "bg-gray-200",
            "hover:bg-gray-300"
        );
        closeButton.classList.remove("hidden");
    } else {
        actionButton.textContent = "OK";
        actionButton.classList.add(
            "text-white",
            "bg-green-500",
            "hover:bg-green-600"
        );
    }

    modal.classList.remove("hidden");

    const closeModal = () => {
        modal.classList.add("hidden");
        actionButton.removeEventListener("click", handleActionButtonClick);
        closeButton.removeEventListener("click", closeModal);
    };

    const handleActionButtonClick = () => {
        if (actionButton.textContent === "OK") {
            // Reload the window when OK button is clicked
            window.location.reload();
        } else {
            closeModal();
        }
    };

    actionButton.addEventListener("click", handleActionButtonClick);
    closeButton.addEventListener("click", closeModal);
}
