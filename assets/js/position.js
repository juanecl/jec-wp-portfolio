document.addEventListener('DOMContentLoaded', function () {
    // Initialize all functionalities
    initSelect2Fields();
    initFilterForm();
    initResetFiltersButton();
    initToggleDescriptionButtons();
    initCollapseElements();
    initBadgeClickListeners();
});

/**
 * Initializes select2 for knowledge and skills fields if they exist.
 */
function initSelect2Fields() {
    const knowledgeSelect = jQuery('#knowledge');
    const skillsSelect = jQuery('#skills');

    if (knowledgeSelect.length) {
        knowledgeSelect.select2({
            theme: "bootstrap-5",
        });
    }

    if (skillsSelect.length) {
        skillsSelect.select2({
            theme: "bootstrap-5",
        });
    }
}

/**
 * Initializes the filter form functionality.
 */
function initFilterForm() {
    const filterForm = document.getElementById('filter-form');
    const positionsContainer = document.getElementById('positions-container-fluid');
    const knowledgeSelect = jQuery('#knowledge');
    const skillsSelect = jQuery('#skills');

    if (knowledgeSelect.length && skillsSelect.length) {
        knowledgeSelect.add(skillsSelect).on('change', function () {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData).toString();
            if (params) {
                fetchPositions(params, positionsContainer);
            }
        });
    }
}

/**
 * Fetches positions based on the provided parameters.
 * @param {string} params - The query parameters for the AJAX request.
 * @param {HTMLElement} positionsContainer - The container to update with the fetched positions.
 */
function fetchPositions(params, positionsContainer) {
    const url = `${ajaxurl}?action=filter_positions${params ? '&' + params : ''}`;
    fetch(url)
        .then(response => response.text())
        .then(data => {
            positionsContainer.innerHTML = data;
        });
}

/**
 * Initializes the reset filters button functionality.
 */
function initResetFiltersButton() {
    const resetFiltersButton = document.getElementById('reset-filters');
    const filterForm = document.getElementById('filter-form');
    const knowledgeSelect = jQuery('#knowledge');
    const skillsSelect = jQuery('#skills');

    if (resetFiltersButton) {
        resetFiltersButton.addEventListener('click', function () {
            filterForm.reset();
            if (knowledgeSelect.length) {
                knowledgeSelect.val(null).trigger('change');
            }
            if (skillsSelect.length) {
                skillsSelect.val(null).trigger('change');
            }
            fetchPositions('', document.getElementById('positions-container-fluid'));
        });
    }
}

/**
 * Initializes the toggle functionality for description elements.
 */
function initToggleDescriptionButtons() {
    const toggleDescriptionButtons = document.querySelectorAll('.toggle-description[data-bs-toggle="collapse"]');
    toggleDescriptionButtons.forEach(toggleDescriptionButton => {
        const toggleDescriptionIcon = toggleDescriptionButton.querySelector('.toggle-icon');
        const targetSelector = toggleDescriptionButton.getAttribute('href');
        if (!targetSelector) {
            console.error('No target specified for toggle description button:', toggleDescriptionButton);
            return;
        }
        const target = document.querySelector(targetSelector);
        if (!target) {
            console.error('Target element not found for selector:', targetSelector);
            return;
        }

        target.addEventListener('show.bs.collapse', () => {
            toggleDescriptionIcon.classList.replace('fa-plus-circle', 'fa-minus-circle');
        });
        target.addEventListener('hide.bs.collapse', () => {
            toggleDescriptionIcon.classList.replace('fa-minus-circle', 'fa-plus-circle');
        });

        const cardHeader = toggleDescriptionButton.closest('.card-header');
        if (cardHeader) {
            cardHeader.addEventListener('click', function (event) {
                if (!event.target.closest('.toggle-description')) {
                    toggleDescriptionButton.click();
                }
            });
        }
    });
}

/**
 * Updates toggle button icons on collapse show/hide events.
 */
function initCollapseElements() {
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapseElement => {
        collapseElement.addEventListener('show.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-plus-circle', 'fa-minus-circle');
            }
        });
        collapseElement.addEventListener('hidden.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-minus-circle', 'fa-plus-circle');
            }
        });
    });
}

/**
 * Adds click event listeners to knowledge and skills badges.
 */
function initBadgeClickListeners() {
    jQuery(document).on('click', '.knowledge-badge', function () {
        const term = jQuery(this).data('term');
        const knowledgeSelect = jQuery('#knowledge');
        const selectedTerms = knowledgeSelect.val();
        if (!selectedTerms.includes(term)) {
            selectedTerms.push(term);
            knowledgeSelect.val(selectedTerms).trigger('change');
        }
    });

    jQuery(document).on('click', '.skills-badge', function () {
        const term = jQuery(this).data('term');
        const skillsSelect = jQuery('#skills');
        const selectedTerms = skillsSelect.val();
        if (!selectedTerms.includes(term)) {
            selectedTerms.push(term);
            skillsSelect.val(selectedTerms).trigger('change');
        }
    });
}