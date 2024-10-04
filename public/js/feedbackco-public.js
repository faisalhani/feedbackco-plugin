jQuery(document).ready(function($) {
    // Toggle feedback form visibility for widget
    $('#feedbackco-button').on('click', function() {
        $('#feedbackco-form-container').toggle();
    });

    // Close the form when the cancel button is clicked
    $('.feedbackco-cancel').on('click', function() {
        $('#feedbackco-form-container').hide();
    });

    // Initialize the feedback form
    function initFeedbackForm(container, formId, formClass) {
        var $container = $(container);
        var $form = $container.find(formId);
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

            // Get category value if the field exists
            var categoryField = $form.find('select[name="category"]');
            var categoryValue = '';
            if (categoryField.length > 0) {
                categoryValue = categoryField.val();
            }

            // // Get reCAPTCHA response if enabled
            // var recaptchaResponse = '';
            // if (typeof grecaptcha !== 'undefined') {
            //     recaptchaResponse = grecaptcha.getResponse();
            // }

            var formData = {
                action: 'feedbackco_submit_feedback',
                nonce: feedbackco_ajax.nonce,
                user_name: $form.find('input[name="user_name"]').val(),
                user_email: $form.find('input[name="user_email"]').val(),
                message: $form.find('textarea[name="message"]').val(),
                rating: selectedRating,
                category: categoryValue
            };

            $.post(feedbackco_ajax.ajax_url, formData, function(response) {
                if (response.success) {
                    // Show thank you message in the correct container
                    $container.find(formClass).html('<div class="feedbackco-thank-you"><h3>Thank you for your feedback!</h3><p>We appreciate your input.</p><button class="feedbackco-close">Close</button></div>');

                    // Close the form when the close button is clicked
                    $container.find('.feedbackco-close').on('click', function() {
                        $container.hide(); // Close the form after showing the thank-you message
                    });
                } else {
                    alert(response.data);
                }
            }).fail(function() {
                alert('An error occurred. Please try again.');
            });
        });
    }

    // Initialize the widget form with unique class
    initFeedbackForm('#feedbackco-widget', '#feedbackco-form-element-widget', '.feedbackco-form-widget');

    // Initialize the shortcode form with unique class
    if ($('#feedbackco-shortcode-form').length) {
        initFeedbackForm('#feedbackco-shortcode-form', '#feedbackco-form-element-shortcode', '.feedbackco-form-shortcode');
    }
});
