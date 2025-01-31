document.addEventListener('DOMContentLoaded', function() {
    // Toggle language functionality
    var toggleLang = document.getElementById('toggle-lang');
    if (toggleLang) {
        var esElements = document.querySelectorAll('.lang-es');
        var enElements = document.querySelectorAll('.lang-en');
        var toggleTextEn = document.getElementById('toggle-lang-text-en');
        var toggleTextEs = document.getElementById('toggle-lang-text-es');

        // Function to update the visibility of language elements
        function updateLanguageVisibility() {
            if (toggleLang.checked) {
                esElements.forEach(el => el.classList.remove('d-none'));
                enElements.forEach(el => el.classList.add('d-none'));
                toggleTextEn.classList.add('d-none');
                toggleTextEs.classList.remove('d-none');
            } else {
                esElements.forEach(el => el.classList.add('d-none'));
                enElements.forEach(el => el.classList.remove('d-none'));
                toggleTextEn.classList.remove('d-none');
                toggleTextEs.classList.add('d-none');
            }
        }

        // Set initial state based on the toggle switch
        updateLanguageVisibility();

        // Add event listener to toggle switch
        toggleLang.addEventListener('change', updateLanguageVisibility);
    }

    // Offcanvas profile functionality
    var offcanvasProfile = document.getElementById('offcanvasProfile');
    if (offcanvasProfile) {
        var offcanvasTab = document.getElementById('offcanvasTab');

        // Add event listener for showing the offcanvas
        offcanvasProfile.addEventListener('show.bs.offcanvas', function () {
            offcanvasTab.classList.add('offcanvas-tab-open');
        });

        // Add event listener for hiding the offcanvas
        offcanvasProfile.addEventListener('hide.bs.offcanvas', function () {
            offcanvasTab.classList.remove('offcanvas-tab-open');
        });
    }

    // Adjust the close button if the admin bar is present
    var adminBar = document.getElementById('wpadminbar');
    if (adminBar) {
        var closeButton = document.querySelector('.offcanvas-header .btn-close');
        if (closeButton) {
            closeButton.style.top = 'calc(1rem + 32px)'; // Adjust the top position
        }
    }
});