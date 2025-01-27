document.addEventListener('DOMContentLoaded', function() {
    var toggleLang = document.getElementById('toggle-lang');
    var esElements = document.querySelectorAll('.lang-es');
    var enElements = document.querySelectorAll('.lang-en');
    var toggleTextEn = document.getElementById('toggle-lang-text-en');
    var toggleTextEs = document.getElementById('toggle-lang-text-es');

    // Set initial state based on the toggle switch
    if (toggleLang.checked) {
        esElements.forEach(function(el) {
            el.classList.remove('d-none');
        });
        enElements.forEach(function(el) {
            el.classList.add('d-none');
        });
        toggleTextEn.classList.add('d-none');
        toggleTextEs.classList.remove('d-none');
    } else {
        esElements.forEach(function(el) {
            el.classList.add('d-none');
        });
        enElements.forEach(function(el) {
            el.classList.remove('d-none');
        });
        toggleTextEn.classList.remove('d-none');
        toggleTextEs.classList.add('d-none');
    }

    toggleLang.addEventListener('change', function() {
        if (this.checked) {
            esElements.forEach(function(el) {
                el.classList.remove('d-none');
            });
            enElements.forEach(function(el) {
                el.classList.add('d-none');
            });
            toggleTextEn.classList.add('d-none');
            toggleTextEs.classList.remove('d-none');
        } else {
            esElements.forEach(function(el) {
                el.classList.add('d-none');
            });
            enElements.forEach(function(el) {
                el.classList.remove('d-none');
            });
            toggleTextEn.classList.remove('d-none');
            toggleTextEs.classList.add('d-none');
        }
    });

    document.getElementById('offcanvasProfile').addEventListener('show.bs.offcanvas', function () {
        document.getElementById('offcanvasTab').classList.add('offcanvas-tab-open');
    });

    document.getElementById('offcanvasProfile').addEventListener('hide.bs.offcanvas', function () {
        document.getElementById('offcanvasTab').classList.remove('offcanvas-tab-open');
    });

    // Adjust the close button if the admin bar is present
    if (document.getElementById('wpadminbar')) {
        var closeButton = document.querySelector('.offcanvas-header .btn-close');
        closeButton.style.top = 'calc(1rem + 32px)'; // Adjust the top position
    }
});