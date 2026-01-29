document.addEventListener('DOMContentLoaded', function () {
    // Initialize all functionalities
    initSelect2Fields();
    initFilterForm();
    initResetFiltersButton();
    initPrintPositionsButton();
    initToggleDescriptionButtons();
    initCollapseElements();
    initHeaderCollapseTriggers();
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
    const ajaxUrl = (window.JEC_PORTFOLIO && window.JEC_PORTFOLIO.ajaxurl) || window.ajaxurl;
    if (!ajaxUrl) {
        console.error('ajaxurl is not defined.');
        return;
    }
    const url = `${ajaxUrl}?action=filter_positions${params ? '&' + params : ''}`;
    fetch(url)
        .then(response => response.text())
        .then(data => {
            positionsContainer.innerHTML = data;
            initToggleDescriptionButtons();
            initCollapseElements();
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
 * Initializes the print/download PDF button functionality.
 */
function initPrintPositionsButton() {
    let printButtons = document.querySelectorAll('.js-print-positions');
    if (!printButtons.length) {
        const targetContainer = document.querySelector('#filter-form .col-md-12')
            || document.querySelector('#positions-container-fluid')
            || document.querySelector('#position-loop');

        if (targetContainer) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'btn btn-secondary mx-2 btn-download-pdf js-print-positions';
            button.style.cssText = 'display:inline-flex;align-items:center;gap:.35rem;border:1px solid #fff;color:#fff;background:transparent;';
            const downloadLabel = (window.JEC_PORTFOLIO && window.JEC_PORTFOLIO.i18n && window.JEC_PORTFOLIO.i18n.downloadPdf) || 'Download';
            button.innerHTML = `<i class="fa fa-file-pdf btn-icon" aria-hidden="true"></i><span class="btn-text">${downloadLabel}</span>`;
            targetContainer.appendChild(button);
            printButtons = document.querySelectorAll('.js-print-positions');
        }
    }

    if (!printButtons.length) {
        return;
    }

    const getCollapseState = () => Array.from(document.querySelectorAll('.collapse')).map((element) => {
        return {
            element,
            wasShown: element.classList.contains('show'),
        };
    });

    const showAllCollapse = (state) => {
        state.forEach(({ element }) => {
            element.classList.add('show');
        });
    };

    const restoreCollapse = (state) => {
        state.forEach(({ element, wasShown }) => {
            if (!wasShown) {
                element.classList.remove('show');
            }
        });
    };

    printButtons.forEach((printButton) => printButton.addEventListener('click', function (event) {
        const filterForm = document.getElementById('filter-form');
        const ajaxUrl = (window.JEC_PORTFOLIO && window.JEC_PORTFOLIO.ajaxurl) || window.ajaxurl;
        if (!ajaxUrl) {
            console.error('ajaxurl is not defined.');
            return;
        }

        let params = '';
        if (filterForm) {
            params = new URLSearchParams(new FormData(filterForm)).toString();
        }

        const url = `${ajaxUrl}?action=download_positions_pdf${params ? '&' + params : ''}`;
        if (printButton.tagName && printButton.tagName.toLowerCase() === 'a') {
            printButton.setAttribute('href', url);
            return;
        }

        event.preventDefault();
        const pdfWindow = window.open(url, '_blank');
        if (!pdfWindow) {
            window.location.href = url;
        }
    }));
}

/**
 * Initializes the toggle functionality for description elements.
 */
function initToggleDescriptionButtons() {
    const toggleDescriptionButtons = document.querySelectorAll('.toggle-description[data-bs-toggle="collapse"]');
    toggleDescriptionButtons.forEach(toggleDescriptionButton => {
        if (toggleDescriptionButton.dataset.jecInitialized === 'true') {
            return;
        }
        toggleDescriptionButton.dataset.jecInitialized = 'true';
        const targetSelector = toggleDescriptionButton.getAttribute('data-bs-target')
            || toggleDescriptionButton.getAttribute('href')
            || (toggleDescriptionButton.getAttribute('aria-controls')
                ? `#${toggleDescriptionButton.getAttribute('aria-controls')}`
                : null);
        if (!targetSelector) {
            return;
        }
        const target = document.querySelector(targetSelector);
        if (!target) {
            return;
        }
        syncCollapseToggleState(target, target.classList.contains('show'));

    });
}

/**
 * Updates toggle button icons on collapse show/hide events.
 */
function initCollapseElements() {
    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapseElement => {
        if (collapseElement.dataset.jecInitialized === 'true') {
            return;
        }
        collapseElement.dataset.jecInitialized = 'true';
        syncCollapseToggleState(collapseElement, collapseElement.classList.contains('show'));
        collapseElement.addEventListener('show.bs.collapse', () => {
            syncCollapseToggleState(collapseElement, true);
        });
        collapseElement.addEventListener('hidden.bs.collapse', () => {
            syncCollapseToggleState(collapseElement, false);
        });
    });
}

/**
 * Syncs toggle elements with a collapse element state.
 * @param {HTMLElement} collapseElement
 * @param {boolean} isOpen
 */
function syncCollapseToggleState(collapseElement, isOpen) {
    if (!collapseElement || !collapseElement.id) {
        return;
    }
    const selector = `[data-bs-target="#${collapseElement.id}"], [data-jec-collapse-target="#${collapseElement.id}"]`;
    const toggles = document.querySelectorAll(selector);
    toggles.forEach((toggle) => {
        toggle.classList.toggle('is-open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
}

/**
 * Enables collapsing when clicking on position/project headers.
 */
function initHeaderCollapseTriggers() {
    const headers = document.querySelectorAll('[data-jec-collapse-target]');
    headers.forEach((header) => {
        if (header.dataset.jecInitialized === 'true') {
            return;
        }
        header.dataset.jecInitialized = 'true';

        const toggleCollapse = () => {
            const targetSelector = header.getAttribute('data-jec-collapse-target');
            if (!targetSelector) {
                return;
            }
            const target = document.querySelector(targetSelector);
            if (!target || typeof bootstrap === 'undefined') {
                return;
            }
            const instance = bootstrap.Collapse.getOrCreateInstance(target, { toggle: false });
            instance.toggle();
        };

        header.addEventListener('click', (event) => {
            if (event.target.closest('a, button, input, textarea, select, [data-bs-toggle="collapse"]')) {
                return;
            }
            toggleCollapse();
        });

        header.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleCollapse();
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