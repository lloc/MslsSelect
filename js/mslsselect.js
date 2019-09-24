(function() {
    const selectors = document.querySelectorAll('select.msls_languages')
    const process = function(e) {
        if (e.target.value) {
            window.location = e.target.value
        }
    }
    for (let i = 0; i < selectors.length; i++) {
        selectors[i].addEventListener('change', process)
    }
})();
