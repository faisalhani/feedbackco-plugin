<div class="wrap">
    <h1>Feedback Entries</h1>

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedbackco_entries';

    // Handle bulk delete action
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && !empty($_POST['entry_ids'])) {
        check_admin_referer('feedbackco_bulk_delete', 'feedbackco_nonce');
        $entry_ids = array_map('intval', $_POST['entry_ids']);
        $ids_placeholder = implode(',', array_fill(0, count($entry_ids), '%d'));
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids_placeholder)", $entry_ids));
        echo '<div class="updated notice is-dismissible"><p>Selected entries deleted.</p></div>';
    }

    // Pagination parameters
    $per_page = 10; // Number of entries per page
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Get total number of entries
    $total_entries = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Calculate total pages
    $total_pages = ceil($total_entries / $per_page);

    // Fetch entries for current page
    $entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT %d OFFSET %d", $per_page, $offset));

    ?>

    <form method="post" id="feedbackco-entries-form">
        <?php wp_nonce_field('feedbackco_bulk_delete', 'feedbackco_nonce'); ?>
        <input type="hidden" name="action" value="delete">

        <button type="submit" class="button action" onclick="return confirm('Are you sure you want to delete the selected entries?');">Delete Selected</button>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td id="cb" class="manage-column column-cb check-column">
                        <input type="checkbox" id="feedbackco-select-all">
                    </td>
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
                if ($entries) {
                    foreach ($entries as $entry) {
                        echo '<tr>';
                        echo '<th scope="row" class="check-column">';
                        echo '<input type="checkbox" name="entry_ids[]" value="' . esc_attr($entry->id) . '">';
                        echo '</th>';
                        echo '<td>' . esc_html($entry->id) . '</td>';
                        echo '<td>' . esc_html($entry->user_name) . '</td>';
                        echo '<td>' . esc_html($entry->user_email) . '</td>';
                        echo '<td>' . esc_html($entry->message) . '</td>';
                        echo '<td>' . esc_html($entry->rating) . '</td>';
                        echo '<td>' . esc_html($entry->created_at) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">No feedback entries found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <button type="submit" class="button action" onclick="return confirm('Are you sure you want to delete the selected entries?');">Delete Selected</button>
    </form>

    <?php
    // Pagination links
    if ($total_pages > 1) {
        $page_links = paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $total_pages,
            'current' => $current_page,
            'type' => 'array',
        ));

        if ($page_links) {
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo '<span class="pagination-links">';
            foreach ($page_links as $link) {
                echo $link;
            }
            echo '</span></div></div>';
        }
    }
    ?>

    <form method="post" id="feedbackco-export-form">
        <button type="submit" class="button button-primary">Export as CSV</button>
    </form>

</div>

<script>
    jQuery(document).ready(function($) {
        $('#feedbackco-export-form').on('submit', function(e) {
            e.preventDefault();
            window.location.href = '<?php echo admin_url('admin-ajax.php?action=feedbackco_export_csv&nonce=' . wp_create_nonce('feedbackco_admin_nonce')); ?>';
        });

        // Select/Deselect all checkboxes
        $('#feedbackco-select-all').on('click', function() {
            $('input[name="entry_ids[]"]').prop('checked', this.checked);
        });
    });
</script>

<style>
    .pagination-links a, .pagination-links span {
        padding: 8px 12px;
        background-color: #f1f1f1;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #0073aa;
        margin-right: 5px;
        border-radius: 4px;
    }

    .pagination-links a:hover {
        background-color: #0073aa;
        color: #fff;
    }

    .pagination-links .current {
        background-color: #0073aa;
        color: #fff;
        font-weight: bold;
    }

    .tablenav-pages {
        padding: 10px 0;
    }
</style>