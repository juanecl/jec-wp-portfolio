/**
 * Initializes the toggle functionality for project elements.
 */
document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('.toggle_project');
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-bs-target');
            if (!targetSelector) {
                console.error('No target specified for toggle button:', button);
                return;
            }
            const target = document.querySelector(targetSelector);
            if (!target) {
                console.error('Target element not found for selector:', targetSelector);
                return;
            }
            const bsCollapse = new bootstrap.Collapse(target, { toggle: false });
            if (target.classList.contains('show')) {
                bsCollapse.hide();
                button.classList.replace('fa-minus-circle', 'fa-plus-circle');
            } else {
                bsCollapse.show();
                button.classList.replace('fa-plus-circle', 'fa-minus-circle');
            }
        });
    });

    const collapseElements = document.querySelectorAll('.collapse');
    collapseElements.forEach(collapseElement => {
        collapseElement.addEventListener('show.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-plus-circle', 'fa-minus-circle');
            }
        });
        collapseElement.addEventListener('hide.bs.collapse', () => {
            const button = document.querySelector(`[data-bs-target="#${collapseElement.id}"]`);
            if (button) {
                button.classList.replace('fa-minus-circle', 'fa-plus-circle');
            }
        });
    });

    const toggleDescriptionButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');
    toggleDescriptionButtons.forEach(toggleDescriptionButton => {
        const toggleDescriptionIcon = toggleDescriptionButton.querySelector('.toggle-icon');
        toggleDescriptionButton.addEventListener('click', () => {
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
            const bsCollapse = new bootstrap.Collapse(target, { toggle: false });
            if (target.classList.contains('show')) {
                bsCollapse.hide();
                toggleDescriptionIcon.classList.replace('fa-minus-circle', 'fa-plus-circle');
            } else {
                bsCollapse.show();
                toggleDescriptionIcon.classList.replace('fa-plus-circle', 'fa-minus-circle');
            }
        });
    });
});