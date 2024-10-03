<div class="wrap">
    <h1>Feedback Analytics</h1>

    <h2>Ratings Distribution</h2>
    <canvas id="feedbackco-chart-ratings" width="400" height="200"></canvas>

    <h2>Submissions Over Time</h2>
    <canvas id="feedbackco-chart-dates" width="400" height="200"></canvas>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Include date adapter for time scale -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
jQuery(document).ready(function($) {
    // First Chart: Ratings Distribution
    var ctxRatings = document.getElementById('feedbackco-chart-ratings').getContext('2d');

    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedbackco_entries';

    // Fetch data for ratings distribution
    $results_ratings = $wpdb->get_results("SELECT rating, COUNT(*) as count FROM $table_name GROUP BY rating");

    $ratings = [];
    $counts_ratings = [];

    foreach ($results_ratings as $row) {
        $ratings[] = $row->rating;
        $counts_ratings[] = $row->count;
    }
    ?>

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
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Second Chart: Submissions Over Time
    var ctxDates = document.getElementById('feedbackco-chart-dates').getContext('2d');

    <?php
    // Fetch data grouped by date
    $results_dates = $wpdb->get_results("
        SELECT DATE(created_at) as date, COUNT(*) as count
        FROM $table_name
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
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
