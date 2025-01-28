/**
 * Initializes the functionality for the position elements.
 */
jQuery(document).ready(function ($) {
    // Cache DOM elements
    const filterForm = document.getElementById('filter-form');
    const positionsContainer = document.getElementById('positions-container-fluid');
    const resetFiltersButton = document.getElementById('reset-filters');
    const knowledgeSelect = $('#knowledge');
    const skillsSelect = $('#skills');

    // Initialize select2 for knowledge and skills fields
    knowledgeSelect.select2({
        theme: "bootstrap-5",
    });
    skillsSelect.select2({
        theme: "bootstrap-5",
    });

    /**
     * Fetch positions based on the provided parameters.
     * @param {string} params - The query parameters for the AJAX request.
     */
    function fetchPositions(params) {
        const url = `${ajaxurl}?action=filter_positions${params ? '&' + params : ''}`;
        fetch(url)
            .then(response => response.text())
            .then(data => {
                positionsContainer.innerHTML = data;
            });
    }

    // Event listener for changes in knowledge and skills select2 fields
    knowledgeSelect.add(skillsSelect).on('change', function () {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData).toString();
        if (params) {
            fetchPositions(params);
        }
    });

    // Event listener for the reset filters button
    resetFiltersButton.addEventListener('click', function () {
        // Reset the form and select2 fields
        filterForm.reset();
        knowledgeSelect.val(null).trigger('change');
        skillsSelect.val(null).trigger('change');

        // Fetch all positions
        fetchPositions('');
    });
    // Initialize toggle functionality for description elements
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
    });
    // Update toggle button icons on collapse show/hide events
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapseElement => {
        collapseElement.addEventListener('show.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-plus-circle', 'fa-minus-circle');
            }
        });
        collapseElement.addEventListener('hidden.bs.collapse', () => { // Use 'hidden.bs.collapse' instead of 'hide.bs.collapse'
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-minus-circle', 'fa-plus-circle');
            }
        });
    });

    // Add click event listeners to knowledge and skills badges
    $(document).on('click', '.knowledge-badge', function () {
        const term = $(this).data('term');
        const knowledgeSelect = $('#knowledge');
        const selectedTerms = knowledgeSelect.val();
        if (!selectedTerms.includes(term)) {
            selectedTerms.push(term);
            knowledgeSelect.val(selectedTerms).trigger('change');
        }
    });

    $(document).on('click', '.skills-badge', function () {
        const term = $(this).data('term');
        const skillsSelect = $('#skills');
        const selectedTerms = skillsSelect.val();
        if (!selectedTerms.includes(term)) {
            selectedTerms.push(term);
            skillsSelect.val(selectedTerms).trigger('change');
        }
    });
});