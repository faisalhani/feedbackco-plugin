<?php
$options = array(
    'position' => get_option('feedbackco_widget_position', 'bottom-right'),
    'button_text' => get_option('feedbackco_button_text', 'Feedback'),
    'button_bg_color' => get_option('feedbackco_button_bg_color', '#0073aa'),
    'button_text_color' => get_option('feedbackco_button_text_color', '#ffffff'),
    'button_icon' => get_option('feedbackco_button_icon', ''),
);

$categories = get_option('feedbackco_feedback_categories', array());


$recaptcha_enabled = get_option('feedbackco_recaptcha_enabled', false);
$recaptcha_site_key = get_option('feedbackco_recaptcha_site_key', '');
?>

<style>
/* Inline styles for the widget */
#feedbackco-widget {
    position: fixed;
    z-index: 9999;
    <?php
    switch ($options['position']) {
        case 'bottom-right':
            echo 'bottom: 20px; right: 20px;';
            break;
        case 'bottom-left':
            echo 'bottom: 20px; left: 20px;';
            break;
        case 'top-right':
            echo 'top: 20px; right: 20px;';
            break;
        case 'top-left':
            echo 'top: 20px; left: 20px;';
            break;
        default:
            echo 'bottom: 20px; right: 20px;';
    }
    ?>
   
}

#feedbackco-button {
    background-color: <?php echo esc_attr($options['button_bg_color']); ?>;
    color: <?php echo esc_attr($options['button_text_color']); ?>;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    /* Additional button styles */
}

#feedbackco-form-container {
    display: none;
    position: absolute;
    top: 50px; /* Adjust this value as needed */
    
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 20px;
    <?php
    if ($options['position'] === 'bottom-right' || $options['position'] === 'bottom-left') {
        echo 'bottom: 50px; top: auto;';
    }
    if ($options['position'] === 'top-right' || $options['position'] === 'top-left') {
        echo 'top: 50px; bottom: auto;';
    }
    if ($options['position'] === 'bottom-right' || $options['position'] === 'top-right') {
        echo 'right: 0; left: auto;';
    }
    if ($options['position'] === 'bottom-left' || $options['position'] === 'top-left') {
        echo 'left: 0; right: auto;';
    }
    ?>

}


/* Additional styles for the form */
</style>




<div id="feedbackco-widget">
<button id="feedbackco-button">
        <?php if (!empty($options['button_icon'])): ?>
            <i class="<?php echo esc_attr($options['button_icon']); ?>"></i>
        <?php endif; ?>
        <?php echo esc_html($options['button_text']); ?>
    </button>

    <div id="feedbackco-form-container">
        <div id="feedbackco-form">
         
            <form id="feedbackco-form-element">
                <div class="feedbackco-field">
                    <label for="feedbackco-name">Name</label>
                    <input type="text" id="feedbackco-name" name="user_name" required>
                </div>
                <div class="feedbackco-field">
                    <label for="feedbackco-email">Email</label>
                    <input type="email" id="feedbackco-email" name="user_email" required>
                </div>
                <div class="feedbackco-field">
                    <label>Rating</label>
                    <div class="feedbackco-rating">
                        <!-- Stars will be generated by JavaScript -->
                    </div>
                </div>

                <?php if (!empty($categories)): ?>
                <div class="feedbackco-field">
                    <label for="feedbackco-category">Category</label>
                    <select id="feedbackco-category" name="category" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo esc_attr($category); ?>"><?php echo esc_html($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div class="feedbackco-field">
                    <label for="feedbackco-message">Message</label>
                    <textarea id="feedbackco-message" name="message" required></textarea>
                </div>


                <div style="display:none;">
        <label for="feedbackco-website">Website</label>
        <input type="text" id="feedbackco-website" name="website" autocomplete="off">
    </div>

    <?php if ($recaptcha_enabled && !empty($recaptcha_site_key)): ?>
    <!-- reCAPTCHA v2 Checkbox -->
    <div class="feedbackco-field">
        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
                <button type="submit" id="feedbackco-submit">Submit</button>
                <button type="button" id="feedbackco-cancel">Cancel</button>
            </form>
        </div>
    </div>
</div>
