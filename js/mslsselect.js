jQuery(document).ready(function ($) {
    $('select.msls_languages').bind('change', function () {
        var url = $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });
});