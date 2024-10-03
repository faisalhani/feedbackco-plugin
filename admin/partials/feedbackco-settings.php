<div class="wrap">
    <h1>FeedbackCo Settings</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('feedbackco_settings_group');
        do_settings_sections('feedbackco_settings_group');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Enable Feedback Widget</th>
                <td>
                    <input type="checkbox" name="feedbackco_widget_enabled" value="1" <?php checked(1, get_option('feedbackco_widget_enabled'), true); ?> />
                </td>
            </tr>
            <!-- Add more settings as needed -->
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
