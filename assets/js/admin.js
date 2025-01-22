/**
 * Document ready event listener.
 */
document.addEventListener('DOMContentLoaded', function() {
    /**
     * Toggle the end date field based on the active checkbox state.
     */
    function toggleEndDate() {
        const activeCheckbox = document.getElementById('wpcf-active');
        const endDateField = document.getElementById('wpcf-end-date');

        if (activeCheckbox && endDateField) {
            if (activeCheckbox.checked) {
                endDateField.disabled = true;
                endDateField.value = '';
            } else {
                endDateField.disabled = false;
            }
        }
    }

    // Initialize the toggle state and add event listener for the active checkbox.
    const activeCheckbox = document.getElementById('wpcf-active');
    if (activeCheckbox) {
        toggleEndDate(); // Initial check
        activeCheckbox.addEventListener('change', toggleEndDate);
    }

    /**
     * Hide native WordPress categories and tags for the 'position' post type.
     */
    function hideNativeTaxonomies() {
        const postType = document.querySelector('input[name="post_type"]');
        if (postType && postType.value === 'position') {
            const categoriesBox = document.getElementById('categorydiv');
            const tagsBox = document.getElementById('tagsdiv-post_tag');
            if (categoriesBox) {
                categoriesBox.style.display = 'none';
            }
            if (tagsBox) {
                tagsBox.style.display = 'none';
            }
        }
    }

    // Hide native taxonomies on page load.
    hideNativeTaxonomies();
});