jQuery(document).ready(function($) {
    $(".btn-logout").click(function() {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    });
});
