function togglePopup(popupId) {
    const popup = document.getElementById(popupId);
    popup.classList.toggle("hidden");
}

function openDeleteModal(deleteModalId) {
    togglePopup(deleteModalId);
}
