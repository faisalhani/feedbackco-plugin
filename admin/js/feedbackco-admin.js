/* feedbackco-admin.js */

jQuery(document).ready(function($) {
    // Handle CSV export button click
    $('#feedbackco-export-form').on('submit', function(e) {
        e.preventDefault();

        // Build the URL for the AJAX request
        var exportUrl = feedbackco_admin_ajax.ajax_url + '?action=feedbackco_export_csv&nonce=' + feedbackco_admin_ajax.nonce;
        window.location.href = exportUrl;
    });
});

jQuery(document).ready(function($) {
    $('.feedbackco-color-field').wpColorPicker();
});
