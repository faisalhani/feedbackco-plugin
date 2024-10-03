jQuery(document).ready(function($) {
    // Toggle feedback form visibility
    $('#feedbackco-button').on('click', function() {
        $('#feedbackco-form-container').toggle();
    });

    // Close the form when the cancel button is clicked
    $('.feedbackco-cancel').on('click', function() {
        $('#feedbackco-form-container').hide();
    });

    // Initialize the feedback form
    function initFeedbackForm(container) {
        var $container = $(container);
        var $form = $container.find('#feedbackco-form-element');
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
                    // Show thank you message
                    $container.find('#feedbackco-form').html('<div class="feedbackco-thank-you"><h3>Thank you for your feedback!</h3><p>We appreciate your input.</p><button class="feedbackco-close">Close</button></div>');

                    // Close the form when the close button is clicked
                    $container.find('.feedbackco-close').on('click', function() {
                        $('#feedbackco-form-container').hide();
                    });
                } else {
                    alert(response.data);
                }
            }).fail(function() {
                alert('An error occurred. Please try again.');
            });
        });
    }

    // Initialize the widget form
    initFeedbackForm('#feedbackco-widget');
});
