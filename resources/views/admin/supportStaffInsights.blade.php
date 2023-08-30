<!-- admin/supportStaffInsights.blade.php -->

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .support-staff-insights {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f7f7f7;
            margin-top: 20px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
        }

        .support-staff-insights h4 {
            margin-bottom: 10px;
        }

        .support-staff-insights p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="support-staff-insights">
        <h4 class="chart-title">Ticket Insights</h4>
        <p>Tickets Assigned: {{ $ticketsAssigned }}</p>
        <p>New Tickets: {{ $newTickets }}</p>
        <p>Open Tickets: {{ $openTickets }}</p>
        <p>Pending Tickets: {{ $pendingTickets }}</p>
        <p>Solved Tickets: {{ $solvedTickets }}</p>
        <p>Closed Tickets: {{ $closedTickets }}</p>
        <div class="row justify-content-center">
            <div class='col-md-6 mt-4'>
                <h4 class="chart-title">Response Time Analysis</h4>
                <div style="max-width: 600px; height: 300px; margin: auto;">
                    <canvas id="responseTimeChart"></canvas>
                </div>
            </div>
            <div class='col-md-6 mt-4'>
                <h4 class="chart-title">Resolution Time Analysis</h4>
                <div style="max-width: 600px; height: 300px; margin: auto;">
                    <canvas id="resolutionTimeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
// Response Time Analysis
    // Construct dataset for average response time by query type
    var responseTimeData = {!! json_encode($responseTimeData) !!};

    // Prepare data for the chart
    var labels = {!! json_encode($queryTypes) !!}; // Query types as X-axis labels
    var data = [];

    // Create the dataset
    for (var i = 0; i < labels.length; i++) {
        data.push(responseTimeData[labels[i]]);
    }

    // Create the chart
    var responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
    var responseTimeChart = new Chart(responseTimeCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Average Response Time',
                data: data,
                borderColor: 'blue', // Customize the line color
                fill: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Average Response Time Analysis'
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Query Type'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Average Response Time (Seconds)'
                    },
                    ticks: {
                        // Include units in the tick values
                        callback: function(value) {
                            return value + 's';
                        }
                    }
                }
            }
        }
    });

    // Resolution Time Analysis
    // Construct dataset for average resolution time by query type
    var resolutionTimeData = {!! json_encode($resolutionTimeData) !!};
    var labels = {!! json_encode($queryTypes) !!}; // Query types as X-axis labels

    // Create data array based on average resolution time for each query type
    var data = labels.map(function (queryType) {
        return resolutionTimeData[queryType];
    });

    // Create the chart
    var resolutionTimeCtx = document.getElementById('resolutionTimeChart').getContext('2d');
    var resolutionTimeChart = new Chart(resolutionTimeCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Average Resolution Time',
                data: data,
                borderColor: 'green', // Customize the line color
                fill: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Average Resolution Time Analysis'
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Query Type'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Average Resolution Time (Seconds)'
                    },
                    ticks: {
                        // Include units in the tick values
                        callback: function(value) {
                            return value + 's';
                        }
                    }
                }
            }
        }
    });
</script>
</html>