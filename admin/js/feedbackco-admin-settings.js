jQuery(document).ready(function($) {
    $('#feedbackco-copy-shortcode').on('click', function() {
        var shortcode = $('#feedbackco-shortcode');
        shortcode.select();
        document.execCommand('copy');
        $(this).text('Copied!');
        setTimeout(function() {
            $('#feedbackco-copy-shortcode').text('Copy Shortcode');
        }, 2000);
    });
});
