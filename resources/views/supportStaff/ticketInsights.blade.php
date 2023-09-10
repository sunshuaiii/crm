@extends('layouts.dashboard')

@section('title', 'Ticket Insights')

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
    <div class="header">Ticket Insights</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="visualization-container">
                    <!-- Ticket Overview -->
                    <div class="visualization">
                        <h4 class="chart-title">Ticket Overview</h4>
                        <p class="chart-title">Total Tickets Assigned: {{ $totalTickets }} ( {{ $inProgressTickets }} In Progress / {{ $closedTickets }} Closed )</p>

                        <div class="row justify-content-center">
                            <div class='col-md-6 mt-5'>
                                <div class="breakdown-section">
                                    <h5>Breakdown by Query Type</h5>
                                    <ul>
                                        @foreach ($queryTypeCounts as $type => $count)
                                        <li>{{ $type }}: {{ $count }}</li>
                                        @endforeach
                                    </ul>
                                    <h6 class="section-description">Breakdown of tickets by query types.</h6>
                                    <h4 class="chart-title">Query Type Analysis</h4>
                                    <div style="max-width: 400px; margin: auto;">
                                        <canvas id="queryTypeAnalysisChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6 mt-5'>
                                <div class="distribution-section">
                                    <h5>Distribution by Ticket Status</h5>
                                    <ul>
                                        @foreach ($ticketStatusCounts as $status => $count)
                                        <li>{{ $status }}: {{ $count }}</li>
                                        @endforeach
                                    </ul>
                                    <h6 class="section-description">Distribution of ticket statuses.</h6>
                                    <h4 class="chart-title">Ticket Status Analysis</h4>
                                    <div style="max-width: 400px; margin: auto;">
                                        <canvas id="ticketStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Closed ticket rate of all query types.</h6>
                            <h4 class="chart-title">Closed Ticket Rate Analysis</h4>
                            <select id="queryTypeSelect">
                                <option value="all">All Query Types</option>
                                @foreach ($queryTypes as $queryType)
                                    <option value="{{ $queryType }}">{{ $queryType }}</option>
                                @endforeach
                            </select>
                            <div style="max-width: 400px; height: 280px; margin: auto;">
                                <canvas id="closedRateChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Show the number of tickets submitted by customer segments.</h6>
                            <h4 class="chart-title">Ticket Submitted by Customer Segments (Bar Chart)</h4>
                            <div style="max-width: 400px; height: 280px; margin: auto;">
                                <canvas id="customerSegmentChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Show the number of tickets submitted by customer segments.</h6>
                            <h4 class="chart-title">Ticket Submitted by Customer Segments (Pie Chart)</h4>
                            <div style="max-width: 400px; height: 280px; margin: auto;">
                                <canvas id="customerSegmentPieChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Average response time for each query type by all status levels to how the trend of response times over time.</h6>
                            <h6 class="section-description-sub">The response time is the time used in seconds to change the ticket status from “New” to another status.</h6>
                            <h4 class="chart-title">Response Time Analysis</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="responseTimeChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Average resolution time for each query type by all status levels to show the trend of resolution times over time.</h6>
                            <h6 class="section-description-sub">The resolution time is the time used in seconds to change the ticket status to “Closed”.</h6>
                            <h4 class="chart-title">Resolution Time Analysis</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="resolutionTimeChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Analyze the distribution of ticket creation times throughout the day.</h6>
                            <h6 class="section-description-sub">To determine peak hours for ticket submissions.</h6>
                            <h4 class="chart-title">Ticket Creation Time Distribution</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="ticketCreationDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-5'>
                            <h6 class="section-description">Show the distribution of open and pending tickets based on their age (time since creation) with age intervals defined.</h6>
                            <h6 class="section-description-sub">To highlight tickets that have been open or pending for an extended period.</h6>
                            <h4 class="chart-title">Open and Pending Ticket Aging Analysis</h4>
                            <div style="max-width: 600px; height: 300px; margin: auto;">
                                <canvas id="ticketAgingChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('supportStaff.supportStaffHome') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Dashboard
        </a>
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
    var queryTypes = {!! json_encode($queryTypes) !!}; // Query types as X-axis labels
    var data = [];

    // Create the dataset
    for (var i = 0; i < queryTypes.length; i++) {
        data.push(responseTimeData[queryTypes[i]]);
    }

    // Create the chart
    var responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
    var responseTimeChart = new Chart(responseTimeCtx, {
        type: 'line',
        data: {
            labels: queryTypes,
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
    var queryTypes = {!! json_encode($queryTypes) !!}; // Query types as X-axis labels

    // Create data array based on average resolution time for each query type
    var data = queryTypes.map(function (queryType) {
        return resolutionTimeData[queryType];
    });

    // Create the chart
    var resolutionTimeCtx = document.getElementById('resolutionTimeChart').getContext('2d');
    var resolutionTimeChart = new Chart(resolutionTimeCtx, {
        type: 'line',
        data: {
            labels: queryTypes,
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

    console.log(labels);

    // Format the hour labels in 12-hour format (e.g., "4pm")
    labels = labels.map(function (hour) {
        const hourInt = parseInt(hour);
        if (hourInt === 0) {
            return '12am';
        } else if (hourInt < 12) {
            return hourInt + 'am';
        } else if (hourInt === 12) {
            return '12pm';
        } else {
            return (hourInt - 12) + 'pm';
        }
    });

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

    // Closed Rate Analysis
    var closedRateData = {!! json_encode($closedRateData) !!};
    var queryTypes = {!! json_encode($queryTypes) !!};

    // Create the chart
    var closedRateCtx = document.getElementById('closedRateChart').getContext('2d');
    var closedRateChart;

    function updateClosedRateChart(selectedQueryType) {
        var labels = [];
        var data = [];

        if (selectedQueryType === 'all') {
            labels = queryTypes;
            data = Object.values(closedRateData);
        } else {
            labels.push(selectedQueryType);
            data.push(closedRateData[selectedQueryType]);
        }

        if (closedRateChart) {
            closedRateChart.destroy(); // Destroy existing chart if any
        }

        closedRateChart = new Chart(closedRateCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Closed Ticket Rate (%)',
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
                            text: 'Query Type'
                        }
                    },
                    y: {
                        beginAtZero: true, // Ensure y-axis starts from zero
                        display: true,
                        title: {
                            display: true,
                            text: 'Closed Ticket Rate (%)'
                        }
                    }
                }
            }
        });
    }

    // Listen for changes in the query type selection
    var queryTypeSelect = document.getElementById('queryTypeSelect');
    queryTypeSelect.addEventListener('change', function () {
        var selectedQueryType = queryTypeSelect.value;
        updateClosedRateChart(selectedQueryType);
    });

    // Initial chart update based on default selection
    var defaultQueryType = queryTypeSelect.value;
    updateClosedRateChart(defaultQueryType);

</script>
@endsection