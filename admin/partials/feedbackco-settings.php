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

    <h2>Feedback Form Shortcode</h2>
<p>Use the shortcode below to embed the feedback form anywhere on your site:</p>
<pre><code>[feedbackco_form]</code></pre>
<button id="feedbackco-copy-shortcode" class="button">Copy Shortcode</button>

<h3>Customization Options</h3>
<p>You can customize the form using the following attributes:</p>
<ul>
    <li><code>title</code>: The title displayed above the form. Default is "Send us your feedback".</li>
    <li><code>button_text</code>: The text displayed on the submit button. Default is "Submit".</li>
    <!-- Add more attributes if implemented -->
</ul>
<p><strong>Example:</strong></p>
<pre><code>[feedbackco_form title="We value your opinion" button_text="Send Feedback"]</code></pre>

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
