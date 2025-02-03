document.addEventListener('DOMContentLoaded', function() {
    initToggleEndDate();
    initHideNativeTaxonomies();
});

/**
 * Initializes the toggle end date functionality.
 */
function initToggleEndDate() {
    const activeCheckbox = document.getElementById('wpcf-active');
    const endDateField = document.getElementById('wpcf-end-date');

    if (activeCheckbox && endDateField) {
        toggleEndDate(activeCheckbox, endDateField); // Initial check
        activeCheckbox.addEventListener('change', function() {
            toggleEndDate(activeCheckbox, endDateField);
        });
    }
}

/**
 * Toggles the end date field based on the active checkbox state.
 * @param {HTMLElement} activeCheckbox - The active checkbox element.
 * @param {HTMLElement} endDateField - The end date field element.
 */
function toggleEndDate(activeCheckbox, endDateField) {
    if (activeCheckbox.checked) {
        endDateField.disabled = true;
        endDateField.value = '';
    } else {
        endDateField.disabled = false;
    }
}

/**
 * Initializes the hide native taxonomies functionality.
 */
function initHideNativeTaxonomies() {
    const postType = document.querySelector('input[name="post_type"]');
    if (postType && postType.value === 'position') {
        hideElementById('categorydiv');
        hideElementById('tagsdiv-post_tag');
    }
}

/**
 * Hides an element by its ID.
 * @param {string} elementId - The ID of the element to hide.
 */
function hideElementById(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = 'none';
    }
}