@extends('layouts.dashboard')

@section('title', 'Support Staff Dashboard')

@section('content')
<div class="container-content">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="header">Support Staff Dashboard</div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Ticket Insights</h4>
        <div class="card">
            <div class="card-body">
                <div class="visualization-container">
                    <!-- Ticket Overview -->
                    <div class="visualization">
                        <h4 class="chart-title">Ticket Overview</h4>
                        <p class="chart-title">Total Tickets Assigned: {{ $totalTickets }} ( {{ $inProgressTickets }} In Progress / {{ $closedTickets }} Closed )</p>

                        <div class="row justify-content-center">
                            <div class='col-md-6 mt-4'>
                                <div class="breakdown-section">
                                    <h5>Breakdown by Query Type</h5>
                                    <ul>
                                        @foreach ($queryTypeCounts as $type => $count)
                                        <li>{{ $type }}: {{ $count }}</li>
                                        @endforeach
                                    </ul>
                                    <h4 class="chart-title">Query Type Analysis</h4>
                                    <div style="max-width: 400px; margin: auto;">
                                        <canvas id="queryTypeAnalysisChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6 mt-4'>
                                <div class="distribution-section">
                                    <h5>Distribution by Ticket Status</h5>
                                    <ul>
                                        @foreach ($ticketStatusCounts as $status => $count)
                                        <li>{{ $status }}: {{ $count }}</li>
                                        @endforeach
                                    </ul>
                                    <h4 class="chart-title">Ticket Status Analysis</h4>
                                    <div style="max-width: 400px; margin: auto;">
                                        <canvas id="ticketStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Ticket Submitted by Customer Segments (Bar Chart)</h4>
                            <div style="max-width: 400px; height: 280px; margin: auto;">
                                <canvas id="customerSegmentChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Ticket Submitted by Customer Segments (Pie Chart)</h4>
                            <div style="max-width: 400px; height: 280px; margin: auto;">
                                <canvas id="customerSegmentPieChart"></canvas>
                            </div>
                        </div>
                    </div>

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

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Ticket Creation Time Distribution</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="ticketCreationDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Ticket Aging Analysis</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="ticketAgingChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Query Type Analysis Chart
    var queryTypeCtx = document.getElementById('queryTypeAnalysisChart').getContext('2d');
    var queryTypeChart = new Chart(queryTypeCtx, {
        type: 'pie', // You can change the chart type here (e.g., bar, pie, etc.)
        data: {
            labels: {!! json_encode(array_keys($queryTypeCounts)) !!},
            datasets: [{
                data: {!! json_encode(array_values($queryTypeCounts)) !!},
                backgroundColor: ['red', 'green', 'blue', 'orange'], // Customize colors as needed
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Distribution by Query Type'
            }
        }
    });

    // Ticket Status Analysis Chart
    var ticketStatusCtx = document.getElementById('ticketStatusChart').getContext('2d');
    var ticketStatusChart = new Chart(ticketStatusCtx, {
        type: 'bar', // You can change the chart type here (e.g., bar, pie, etc.)
        data: {
            labels: {!! json_encode(array_keys($ticketStatusCounts)) !!},
            datasets: [{
                label: 'Ticket Count',
                data: {!! json_encode(array_values($ticketStatusCounts)) !!},
                backgroundColor: ['red', 'green', 'blue', 'orange', 'purple'], // Customize colors as needed
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Distribution by Ticket Status'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Customer Segment Analysis (bar)
    var segmentCtx = document.getElementById('customerSegmentChart').getContext('2d');
    var segmentChart = new Chart(segmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($customerSegments) !!},
            datasets: [{
                label: 'Ticket Count',
                data: {!! json_encode(array_values($segmentTicketCounts)) !!},
                backgroundColor: ['red', 'green', 'blue', 'orange'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Distribution of Tickets Across Customer Segments'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Customer Segment Analysis (Pie Chart)
    var segmentPieCtx = document.getElementById('customerSegmentPieChart').getContext('2d');
    var segmentPieChart = new Chart(segmentPieCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($customerSegments) !!},
            datasets: [{
                label: 'Ticket Count',
                data: {!! json_encode(array_values($segmentTicketCounts)) !!},
                backgroundColor: ['red', 'green', 'blue', 'orange'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Distribution of Tickets Across Customer Segments (Pie Chart)'
            },
        }
    });

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
                        text: 'Average Response Time'
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
                        text: 'Average Resolution Time'
                    }
                }
            }
        }
    });

    // Time-Based Analysis
    // Construct dataset for ticket creation time distribution
    var ticketCreationDistribution = {!! json_encode($ticketCreationDistribution) !!};
    var labels = Object.keys(ticketCreationDistribution); // Hours as X-axis labels
    var data = Object.values(ticketCreationDistribution); // Ticket counts for each hour

    // Create the chart
    var ticketCreationDistributionCtx = document.getElementById('ticketCreationDistributionChart').getContext('2d');
    var ticketCreationDistributionChart = new Chart(ticketCreationDistributionCtx, {
        type: 'bar', // You can also use 'line' for a line chart
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Tickets',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.7)', // Customize bar color
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Hour of the Day'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Number of Tickets'
                    }
                }
            }
        }
    });

    //Ticket Aging Analysis
    // Construct dataset for ticket aging analysis
    var ageIntervalData = {!! json_encode($ageIntervalData) !!};

    // Create the chart
    var ticketAgingCtx = document.getElementById('ticketAgingChart').getContext('2d');
    var ticketAgingChart = new Chart(ticketAgingCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(ageIntervalData), // Age intervals as X-axis labels
            datasets: [{
                label: 'Ticket Count by Age Interval',
                data: Object.values(ageIntervalData), // Ticket counts for each age interval
                backgroundColor: 'rgba(75, 192, 192, 0.7)', // Customize bar color
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Age Intervals'
                    }
                },
                y: {
                    beginAtZero: true, // Ensure y-axis starts from zero
                    display: true,
                    title: {
                        display: true,
                        text: 'Number of Tickets'
                    }
                }
            }
        }
    });
</script>
@endsection