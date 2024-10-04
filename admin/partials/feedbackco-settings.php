<div class="wrap">
    <h1>FeedbackCo Settings</h1>

    <h2 class="nav-tab-wrapper">
        <a href="#feedbackco-tab-widget" class="nav-tab nav-tab-active">Widget Settings</a>
        <a href="#feedbackco-tab-categories" class="nav-tab">Category Settings</a>
        <a href="#feedbackco-tab-shortcode" class="nav-tab">Shortcode Settings</a>
    </h2>

    <div id="feedbackco-tabs">
        <!-- Widget Settings Tab -->
        <div id="feedbackco-tab-widget" class="feedbackco-tab-content">
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
    <th scope="row">Button Icon</th>
    <td>
        <select name="feedbackco_button_icon" id="feedbackco-button-icon">
            <option value="">No Icon</option>
            <?php
            // Define a list of icons to choose from
            $icons = array(
                'fas fa-comment' => 'Comment',
                'fas fa-comments' => 'Comments',
                'fas fa-envelope' => 'Envelope',
                'fas fa-paper-plane' => 'Paper Plane',
                'fas fa-smile' => 'Smile',
                'fas fa-thumbs-up' => 'Thumbs Up',
                // Add more icons as needed
            );
            $selected_icon = get_option('feedbackco_button_icon', '');
            foreach ($icons as $icon_class => $icon_name) {
                $selected = selected($selected_icon, $icon_class, false);
                echo '<option value="' . esc_attr($icon_class) . '" ' . $selected . ' data-icon="' . esc_attr($icon_class) . '">' . esc_html($icon_name) . '</option>';
            }
            ?>
        </select>
        <p class="description">Choose an icon to display on the feedback button.</p>
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

        <!-- Category Settings Tab -->
        <div id="feedbackco-tab-categories" class="feedbackco-tab-content" style="display:none;">
            <form method="post" action="">
                <?php wp_nonce_field('feedbackco_add_category_action', 'feedbackco_add_category_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Add New Category</th>
                        <td>
                            <input type="text" name="feedbackco_new_category" value="" />
                            <input type="submit" name="add_category" class="button button-secondary" value="Add Category">
                        </td>
                    </tr>
                </table>
            </form>

            <h3>Existing Categories</h3>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $categories = get_option('feedbackco_feedback_categories', array());
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                            echo '<tr>';
                            echo '<td>' . esc_html($category) . '</td>';
                            echo '<td>';
                            echo '<form method="post" action="">';
                            wp_nonce_field('feedbackco_delete_category_action', 'feedbackco_delete_category_nonce');
                            echo '<input type="hidden" name="category_to_delete" value="' . esc_attr($category) . '">';
                            echo '<input type="submit" name="delete_category" class="button button-delete" value="Delete">';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2">No categories defined.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <!-- Shortcode Settings Tab -->
        <div id="feedbackco-tab-shortcode" class="feedbackco-tab-content" style="display:none;">
    <h2>Shortcode Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields('feedbackco_shortcode_settings_group');
        do_settings_sections('feedbackco_shortcode_settings_group');
        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Shortcode Display Mode</th>
                <td>
                    <select name="feedbackco_shortcode_display_mode">
                        <option value="inline" <?php selected(get_option('feedbackco_shortcode_display_mode'), 'inline'); ?>>Inline</option>
                    </select>
                    <p class="description">Shortcode will only display inline on your page.</p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <h3>Generated Shortcode</h3>
    <p>Copy the following shortcode to display the form:</p>
    <textarea readonly id="feedbackco-shortcode" style="width: 100%;"><?php echo '[feedbackco_form]'; ?></textarea>
    <button id="feedbackco-copy-shortcode" class="button">Copy Shortcode</button>
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

    </div>
</div>
