document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionalities
    initToggleLanguage();
    initOffcanvasProfile();
    adjustCloseButtonForAdminBar();
});

/**
 * Initializes the toggle language functionality.
 */
function initToggleLanguage() {
    const toggleLang = document.getElementById('toggle-lang');
    if (!toggleLang) return;

    const esElements = document.querySelectorAll('.lang-es');
    const enElements = document.querySelectorAll('.lang-en');
    const toggleTextEn = document.getElementById('toggle-lang-text-en');
    const toggleTextEs = document.getElementById('toggle-lang-text-es');

    /**
     * Updates the visibility of language elements.
     */
    function updateLanguageVisibility() {
        const isChecked = toggleLang.checked;
        enElements.forEach(el => el.classList.toggle('d-none', !isChecked));
        esElements.forEach(el => el.classList.toggle('d-none', isChecked));
        toggleTextEs.classList.toggle('d-none', isChecked);
        toggleTextEn.classList.toggle('d-none', !isChecked);
    }

    // Set initial state based on the toggle switch
    updateLanguageVisibility();

    // Add event listener to toggle switch
    toggleLang.addEventListener('change', updateLanguageVisibility);
}

/**
 * Initializes the offcanvas profile functionality.
 */
function initOffcanvasProfile() {
    const offcanvasProfile = document.getElementById('offcanvasProfile');
    if (!offcanvasProfile) return;

    const offcanvasTab = document.getElementById('offcanvasTab');

    // Add event listener for showing the offcanvas
    offcanvasProfile.addEventListener('show.bs.offcanvas', function () {
        offcanvasTab.classList.add('offcanvas-tab-open');
    });

    // Add event listener for hiding the offcanvas
    offcanvasProfile.addEventListener('hide.bs.offcanvas', function () {
        offcanvasTab.classList.remove('offcanvas-tab-open');
    });
}

/**
 * Adjusts the close button position if the admin bar is present.
 */
function adjustCloseButtonForAdminBar() {
    const adminBar = document.getElementById('wpadminbar');
    if (!adminBar) return;

    const closeButton = document.querySelector('.offcanvas-header .btn-close');
    if (closeButton) {
        closeButton.style.top = 'calc(1rem + 32px)'; // Adjust the top position
    }
}