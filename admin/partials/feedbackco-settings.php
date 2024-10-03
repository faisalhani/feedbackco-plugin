<div class="wrap">
    <h1>FeedbackCo Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('feedbackco_settings_group');
        do_settings_sections('feedbackco_settings_group');
        ?>
        <table class="form-table">
            <!-- Existing settings -->
            <tr valign="top">
                <th scope="row">Enable Feedback Widget</th>
                <td>
                    <input type="checkbox" name="feedbackco_widget_enabled" value="1" <?php checked(1, get_option('feedbackco_widget_enabled'), true); ?> />
                </td>
            </tr>

            <!-- New settings -->
            <tr valign="top">
                <th scope="row">Widget Position</th>
                <td>
                    <select name="feedbackco_widget_position">
                        <?php $position = get_option('feedbackco_widget_position', 'bottom-right'); ?>
                        <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>>Bottom Right</option>
                        <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>>Bottom Left</option>
                        <option value="top-right" <?php selected($position, 'top-right'); ?>>Top Right</option>
                        <option value="top-left" <?php selected($position, 'top-left'); ?>>Top Left</option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Button Text</th>
                <td>
                    <input type="text" name="feedbackco_button_text" value="<?php echo esc_attr(get_option('feedbackco_button_text', 'Feedback')); ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Button Background Color</th>
                <td>
                    <input type="text" name="feedbackco_button_bg_color" value="<?php echo esc_attr(get_option('feedbackco_button_bg_color', '#0073aa')); ?>" class="feedbackco-color-field" data-default-color="#0073aa" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Button Text Color</th>
                <td>
                    <input type="text" name="feedbackco_button_text_color" value="<?php echo esc_attr(get_option('feedbackco_button_text_color', '#ffffff')); ?>" class="feedbackco-color-field" data-default-color="#ffffff" />
                </td>
            </tr>
            <!-- Add more settings fields as needed -->
        </table>
        <?php submit_button(); ?>
    </form>
</div>


<script>
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
</script>
