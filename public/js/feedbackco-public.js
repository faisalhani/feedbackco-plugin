jQuery(document).ready(function($) {

    // Function to initialize the feedback form
    function initFeedbackForm(container) {
        var $container = $(container);
        var $form = $container.find('.feedbackco-form-element');
        var $ratingStarsContainer = $container.find('.feedbackco-rating');
        var selectedRating = 0;

        // Generate star rating
        var stars = '';
        for (var i = 1; i <= 5; i++) {
            stars += '<span class="star" data-rating="' + i + '">&#9733;</span>';
        }
        $ratingStarsContainer.html(stars);

        $ratingStarsContainer.find('.star').on('click', function() {
            selectedRating = $(this).data('rating');
            $ratingStarsContainer.find('.star').removeClass('selected');
            $(this).prevAll().addBack().addClass('selected');
        });

        // Handle form submission
        $form.on('submit', function(e) {
            e.preventDefault();

            if (selectedRating === 0) {
                alert('Please select a rating.');
                return;
            }

            var formData = {
                action: 'feedbackco_submit_feedback',
                nonce: feedbackco_ajax.nonce,
                user_name: $form.find('input[name="user_name"]').val(),
                user_email: $form.find('input[name="user_email"]').val(),
                message: $form.find('textarea[name="message"]').val(),
                rating: selectedRating,
                form_id: $container.data('form-id') // Optional: Capture form ID
            };

            $.post(feedbackco_ajax.ajax_url, formData, function(response) {
                if (response.success) {
                    // Show thank you message
                    $container.find('.feedbackco-form').hide();
                    $container.find('.feedbackco-thank-you').show();
                } else {
                    alert(response.data);
                }
            }).fail(function() {
                alert('An error occurred. Please try again.');
            });
        });

        // Handle close button in the thank you message
        $container.on('click', '.feedbackco-close', function() {
            $container.find('.feedbackco-thank-you').hide();
            $container.find('.feedbackco-form').show();
            $form[0].reset();
            selectedRating = 0;
            $ratingStarsContainer.find('.star').removeClass('selected');
        });

        // Handle cancel button in the form (for the widget)
        $container.on('click', '.feedbackco-cancel', function() {
            $container.hide();
        });
    }

    // Initialize all feedback forms on the page
    $('.feedbackco-form-container').each(function() {
        initFeedbackForm(this);
    });

    // Widget-specific code to toggle visibility
    $('#feedbackco-button').on('click', function() {
        $('#feedbackco-widget .feedbackco-form-container').toggle();
    });

});
