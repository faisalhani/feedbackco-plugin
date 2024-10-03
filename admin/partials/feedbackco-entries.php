<div class="wrap">
    <h1>Feedback Entries</h1>
    <form method="post" id="feedbackco-export-form">
        <button type="submit" class="button button-primary">Export as CSV</button>
    </form>
    <br>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Name</th>
                <th width="20%">Email</th>
                <th width="35%">Message</th>
                <th width="10%">Rating</th>
                <th width="10%">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'feedbackco_entries';
            $entries = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
            if ($entries) {
                foreach ($entries as $entry) {
                    echo '<tr>';
                    echo '<td>' . esc_html($entry->id) . '</td>';
                    echo '<td>' . esc_html($entry->user_name) . '</td>';
                    echo '<td>' . esc_html($entry->user_email) . '</td>';
                    echo '<td>' . esc_html($entry->message) . '</td>';
                    echo '<td>' . esc_html($entry->rating) . '</td>';
                    echo '<td>' . esc_html($entry->created_at) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No feedback entries found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#feedbackco-export-form').on('submit', function(e) {
            e.preventDefault();
            window.location.href = '<?php echo admin_url('admin-ajax.php?action=feedbackco_export_csv&nonce=' . wp_create_nonce('feedbackco_admin_nonce')); ?>';
        });
    });
</script>
