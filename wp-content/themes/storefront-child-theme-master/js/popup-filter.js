document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggle-popup');
    const closeButton = document.getElementById('close-popup');
    const popup = document.getElementById('secondary');
    const body = document.body;

    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            if (popup.classList.contains('visible')) {
                popup.classList.remove('visible');
                body.classList.remove('no-scroll');
            } else {
                popup.classList.add('visible');
                body.classList.add('no-scroll');
            }
        });
    }

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            popup.classList.remove('visible');
            body.classList.remove('no-scroll');
        });
    }
});