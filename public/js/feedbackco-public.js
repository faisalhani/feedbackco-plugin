jQuery(document).ready(function($) {
    // Toggle feedback form visibility
    $('#feedbackco-button').on('click', function() {
        $('#feedbackco-form-container').toggle();
    });

    $('#feedbackco-cancel').on('click', function() {
        $('#feedbackco-form-container').hide();
    });

    // Generate star rating
    var stars = '';
    for (var i = 1; i <= 5; i++) {
        stars += '<span class="star" data-rating="' + i + '">&#9733;</span>';
    }
    $('#feedbackco-rating').html(stars);

    var selectedRating = 0;
    $('#feedbackco-rating .star').on('click', function() {
        selectedRating = $(this).data('rating');
        $('#feedbackco-rating .star').removeClass('selected');
        $(this).prevAll().addBack().addClass('selected');
    });

    // Handle form submission
    $('#feedbackco-form-element').on('submit', function(e) {
        e.preventDefault();

        if (selectedRating === 0) {
            alert('Please select a rating.');
            return;
        }

        var formData = {
            action: 'feedbackco_submit_feedback',
            nonce: feedbackco_ajax.nonce,
            user_name: $('#feedbackco-name').val(),
            user_email: $('#feedbackco-email').val(),
            message: $('#feedbackco-message').val(),
            rating: selectedRating
        };

        $.post(feedbackco_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                // Display thank you message inside the widget
                $('#feedbackco-form').html('<div class="feedbackco-thank-you"><h3>Thank you for your feedback!</h3><p>We appreciate your input.</p><button id="feedbackco-close">Close</button></div>');

                // Add event listener for the close button
                $('#feedbackco-close').on('click', function() {
                    $('#feedbackco-form-container').hide();
                });
            } else {
                alert(response.data);
            }
        }).fail(function() {
            alert('An error occurred. Please try again.');
        });
    });
});

