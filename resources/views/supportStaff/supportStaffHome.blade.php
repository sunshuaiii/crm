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
                        <p class="chart-title">Total Tickets: {{ $totalTickets }}</p>

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
                            <h4 class="chart-title">Customer Segment Analysis</h4>
                            <div style="max-width: 400px; height: 250px; margin: auto;">
                                <canvas id="customerSegmentChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Response Time Analysis</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="responseTimeChart"></canvas>
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

    // Customer Segment Analysis
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
</script>
@endsection