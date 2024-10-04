<div class="wrap">
    <h1>Feedback Analytics</h1>

    <form method="get" id="feedbackco-analytics-filters">
        <input type="hidden" name="page" value="feedbackco-analytics">
        
        <!-- Date Range Filter -->
        <label for="feedbackco-filter-start-date">Start Date:</label>
        <input type="date" name="start_date" id="feedbackco-filter-start-date" value="<?php echo esc_attr($_GET['start_date'] ?? ''); ?>">
        
        <label for="feedbackco-filter-end-date">End Date:</label>
        <input type="date" name="end_date" id="feedbackco-filter-end-date" value="<?php echo esc_attr($_GET['end_date'] ?? ''); ?>">
        
        <!-- Category Filter -->
        <label for="feedbackco-filter-category">Category:</label>
        <select name="category" id="feedbackco-filter-category">
            <option value="">All Categories</option>
            <?php
            $categories = get_option('feedbackco_feedback_categories', array());
            foreach ($categories as $category) {
                $selected = isset($_GET['category']) && $_GET['category'] === $category ? 'selected' : '';
                echo '<option value="' . esc_attr($category) . '" ' . $selected . '>' . esc_html($category) . '</option>';
            }
            ?>
        </select>
        
        <!-- Rating Filter -->
        <label for="feedbackco-filter-rating">Rating:</label>
        <select name="rating" id="feedbackco-filter-rating">
            <option value="">All Ratings</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <?php $selected = isset($_GET['rating']) && $_GET['rating'] == $i ? 'selected' : ''; ?>
                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> Stars</option>
            <?php endfor; ?>
        </select>
        
        <button type="submit" class="button button-primary">Filter</button>
        <button type="button" class="button" id="feedbackco-reset-filters">Reset</button>
    </form>

    <h2>Ratings Distribution</h2>
    <canvas id="feedbackco-chart-ratings" width="400" height="200"></canvas>

    <h2>Submissions Over Time</h2>
    <canvas id="feedbackco-chart-dates" width="400" height="200"></canvas>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Include date adapter for time scale -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<?php
// PHP code to get data for charts
global $wpdb;
$table_name = $wpdb->prefix . 'feedbackco_entries';

// Get filter values
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$rating = isset($_GET['rating']) ? intval($_GET['rating']) : '';

// Build WHERE clause
$where_clauses = [];

if ($start_date) {
    $where_clauses[] = $wpdb->prepare('DATE(created_at) >= %s', $start_date);
}

if ($end_date) {
    $where_clauses[] = $wpdb->prepare('DATE(created_at) <= %s', $end_date);
}

if ($category) {
    $where_clauses[] = $wpdb->prepare('category = %s', $category);
}

if ($rating) {
    $where_clauses[] = $wpdb->prepare('rating = %d', $rating);
}

$where_sql = '';
if (!empty($where_clauses)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where_clauses);
}

// Fetch data for ratings distribution
$results_ratings = $wpdb->get_results("
    SELECT rating, COUNT(*) as count
    FROM $table_name
    $where_sql
    GROUP BY rating
");

$ratings = [];
$counts_ratings = [];

foreach ($results_ratings as $row) {
    $ratings[] = $row->rating;
    $counts_ratings[] = $row->count;
}

// Fetch data for submissions over time
$results_dates = $wpdb->get_results("
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM $table_name
    $where_sql
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");

$dates = [];
$counts_dates = [];

foreach ($results_dates as $row) {
    $dates[] = $row->date;
    $counts_dates[] = $row->count;
}
?>

<script>
jQuery(document).ready(function($) {
    // First Chart: Ratings Distribution
    var ctxRatings = document.getElementById('feedbackco-chart-ratings').getContext('2d');

    var chartRatings = new Chart(ctxRatings, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($ratings); ?>,
            datasets: [{
                label: 'Number of Feedbacks',
                data: <?php echo json_encode($counts_ratings); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Second Chart: Submissions Over Time
    var ctxDates = document.getElementById('feedbackco-chart-dates').getContext('2d');

    var chartDates = new Chart(ctxDates, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Number of Submissions',
                data: <?php echo json_encode($counts_dates); ?>,
                fill: false,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        parser: 'yyyy-MM-dd',
                        unit: 'day',
                        tooltipFormat: 'MMM dd, yyyy'
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Submissions'
                    }
                }
            }
        }});
});

jQuery(document).ready(function($) {
    $('#feedbackco-reset-filters').on('click', function() {
        window.location.href = window.location.pathname + '?page=feedbackco-analytics';
    });
});
</script>
