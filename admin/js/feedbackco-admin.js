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

jQuery(document).ready(function($) {
    // Initialize color picker fields
    $('.feedbackco-color-field').wpColorPicker();

    // Handle tab navigation
    function showTab(tabId) {
        // Remove active class from all tabs
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $('.feedbackco-tab-content').hide();

        // Add active class to selected tab
        $('.nav-tab-wrapper a[href="' + tabId + '"]').addClass('nav-tab-active');
        $(tabId).show();
    }

    // On tab click
    $('.nav-tab-wrapper a').click(function(event) {
        event.preventDefault();
        var selected_tab = $(this).attr('href');

        // Store selected tab in localStorage
        localStorage.setItem('feedbackco_selected_tab', selected_tab);

        showTab(selected_tab);
    });

    // On page load, check if a tab is stored
    var storedTab = localStorage.getItem('feedbackco_selected_tab');
    if (storedTab) {
        showTab(storedTab);
    } else {
        showTab('#feedbackco-tab-widget'); // Default tab
    }

    $('#feedbackco-button-icon').each(function() {
        var $select = $(this);
        var options = $select.find('option');

        options.each(function() {
            var $option = $(this);
            var iconClass = $option.val();
            if (iconClass) {
                $option.text(' ' + $option.text());
                $option.prepend($('<i></i>').addClass(iconClass));
            }
        });
    });

    
});
