@extends('layouts.dashboard')

@section('title', 'Customer Behaviour Report')

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
    <div class="header">Customer Behaviour Report</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-4">From {{ $startDate }} to {{ $endDate }} </h4>

                <!-- Section 1: Overview -->
                <div class="section">
                    <h2 class="section-header">Customer Overview</h2>
                    <h6 class="section-description">Total number of customers of all times. New customers acquired during the time period.</h6>
                    <p>Total Customers: {{ $reportDatasets['Overview']['Total Customers'] }} </p>
                    <p>New Customers Joined: {{ $reportDatasets['Overview']['New Customers'] }} </p>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">New Customer Joined Timeline</h4>
                            <div style="max-width: 450px; margin: auto;">
                                <canvas id="newCustomersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Customer Segmentation -->
                <div class="section">
                    <h2 class="section-header">Customer Segmentation</h2>
                    <h6 class="section-description">Distribution of all customers among different segments with RFM scores.</h6>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Customer Distribution by RFM Scores and Segments</h4>
                            <div style="max-width: 450px; margin: auto;">
                                <canvas id="customerDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Customer Engagement -->
                <div class="section">
                    <h2 class="section-header">Customer Engagement</h2>
                    <h6 class="section-description">Analyze customer engagement by counting the ticket (customer service request) submitted, and the customer coupons claimed and redeemed by customers during the time period.</h6>
                    <p>Ticket Submitted: {{ $reportDatasets['Customer Engagement']['Ticket Submitted'] }}</p>
                    <p>Coupons Claimed: {{ $reportDatasets['Customer Engagement']['Coupons Claimed'] }}</p>
                    <p>Coupons Redeemed: {{ $reportDatasets['Customer Engagement']['Coupons Redeemed'] }}</p>
                    <h4 class="chart-title">Customer Engagement Timeline</h4>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <div style="max-width: 400px; margin: auto;">
                                <canvas id="customerEngagementChart1"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <div style="max-width: 400px; margin: auto;">
                                <canvas id="customerEngagementChart2"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <div style="max-width: 400px; margin: auto;">
                                <canvas id="customerEngagementChart3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Customer Demographics -->
                <div class="section">
                    <h2 class="section-header">Customer Demographics</h2>
                    <h6 class="section-description">Age and gender distribution of customers acquired during the time period.</h6>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Age Distribution Chart</h4>
                            <div style="max-width: 450px; margin: auto;">
                                <canvas id="ageDistributionChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Gender Distribution Chart</h4>
                            <div style="max-width: 260px; margin: auto;">
                                <canvas id="genderDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Customer Lifetime Value (CLTV) -->
                <div class="section">
                    <h2 class="section-header">Customer Lifetime Value (CLTV)</h2>
                    <h6 class="section-description">Calculate and analyze the CLTV for different customer segments.</h6>
                    <h6 class="section-description-sub">CLTV is calculated as the sum of the revenue generated by each customer within the segment within the time period.</h6>
                    <h6 class="section-description">Identify high-value customers for targeted marketing efforts.</h6>
                    <h6 class="section-description-sub"> High-value customers are those who have made the highest total purchases within the time period.</h6>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">CLTV By Customer Segment</h4>
                            <div style="max-width: 450px; margin: auto;">
                                <canvas id="cltvBySegmentChart"></canvas>
                            </div>
                        </div>
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Top 10 High-Value Customers</h4>
                            <table class="table table-bordered custom-table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Total Purchase</th>
                                        <th>Customer Segment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportDatasets['Customer Lifetime Value (CLTV)']['Top 10 High Value Customers'] as $customer)
                                    <tr>
                                        <td>{{ $customer['customer']->first_name }} {{ $customer['customer']->last_name }}</td>
                                        <td>{{ $customer['customer']->email }}</td>
                                        <td>${{ number_format($customer['totalAmount'], 2) }}</td>
                                        <td>{{ $customer['customer']->c_segment }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Customer Activity Timeline -->
                <div class="section">
                    <h2 class="section-header">Customer Activity Timeline</h2>
                    <h6 class="section-description">Visualize the timelines of customer interactions over time to identify peak engagement periods within the time period.</h6>
                    <h6 class="section-description-sub">The interactions are the ticket submitted, coupons claimed and coupons redeemed customers.</h6>
                    <h4 class="chart-title">Customer Activity Timeline</h4>
                    <div class="justify-content-center">
                        <div style="max-width: 1500px; height: 550px; margin: auto;">
                            <canvas id="activityTimelineChart"></canvas>
                        </div>

                    </div>
                </div>

                <!-- Section 7: Customer Loyalty Trends -->
                <div class="section">
                    <h2 class="section-header">Customer Loyalty Trends</h2>
                    <h6 class="section-description">Analyze Top 50 repeat purchases count customers within the time period.</h6>
                    <h4 class="chart-title">Repeat Purchase Customers</h4>
                    <div class="justify-content-center text-center"> <!-- Center the chart -->
                        <div style="max-width: 1000px; width: 100%; height: 550px; margin: auto;"> <!-- Increase width and center horizontally -->
                            <canvas id="repeatPurchasesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <a href="{{ route('marketingStaff.reportGeneration') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Dashboard
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Get the chart data passed from the controller
    var newCustomersChartData = @json($reportDatasets['Overview']['New Customers Chart Data']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('newCustomersChart').getContext('2d');

    // Create a new line chart
    var newCustomersChart = new Chart(ctx, {
        type: 'line',
        data: newCustomersChartData,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day', // Adjust the time unit as needed
                        displayFormats: {
                            day: 'MMM d', // Adjust the date format as needed
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    function renderCustomerDistributionChart(customerDistributionData) {
        var rfmScores = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; // Assuming these are the column names for RFM scores
        var customerSegments = ['Silver', 'Gold', 'Platinum']; // Sample customer segments

        var data = {
            labels: customerSegments,
            datasets: []
        };

        rfmScores.forEach(function(rfmScore) {
            var scoresData = customerSegments.map(function(segment) {
                return customerDistributionData[rfmScore][segment];
            });

            data.datasets.push({
                label: rfmScore,
                data: scoresData,
                backgroundColor: getRandomColor(),
            });
        });

        var ctx = document.getElementById('customerDistributionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    },
                },
            },
        });
    }

    // Function to generate random colors
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    var customerDistributionData = @json($reportDatasets['Customer Segmentation']['Segment Distribution Chart Data']);

    // Call the chart rendering function
    renderCustomerDistributionChart(customerDistributionData);

    // Get the chart data passed from the controller
    var customerEngagementChartData1 = @json($reportDatasets['Customer Engagement']['Customer Engagement Chart Data 1']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('customerEngagementChart1').getContext('2d');

    // Create a new line chart
    var customerEngagementChart = new Chart(ctx, {
        type: 'line',
        data: customerEngagementChartData1,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day', // Adjust the time unit as needed
                        displayFormats: {
                            day: 'MMM d', // Adjust the date format as needed
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Get the chart data passed from the controller
    var customerEngagementChartData2 = @json($reportDatasets['Customer Engagement']['Customer Engagement Chart Data 2']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('customerEngagementChart2').getContext('2d');

    // Create a new line chart
    var customerEngagementChart2 = new Chart(ctx, {
        type: 'line',
        data: customerEngagementChartData2,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day', // Adjust the time unit as needed
                        displayFormats: {
                            day: 'MMM d', // Adjust the date format as needed
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Get the chart data passed from the controller
    var customerEngagementChartData3 = @json($reportDatasets['Customer Engagement']['Customer Engagement Chart Data 3']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('customerEngagementChart3').getContext('2d');

    // Create a new line chart
    var customerEngagementChart = new Chart(ctx, {
        type: 'line',
        data: customerEngagementChartData3,
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day', // Adjust the time unit as needed
                        displayFormats: {
                            day: 'MMM d', // Adjust the date format as needed
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Get the chart data passed from the controller
    var ageDistributionChartData = @json($reportDatasets['Customer Demographics']['Age Distribution Chart Data']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('ageDistributionChart').getContext('2d');

    // Create a new bar chart
    var ageDistributionChart = new Chart(ctx, {
        type: 'bar',
        data: ageDistributionChartData,
        options: {
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });

    // Get the chart data passed from the controller
    var genderDistributionChartData = @json($reportDatasets['Customer Demographics']['Gender Distribution Chart Data']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('genderDistributionChart').getContext('2d');

    // Create a new pie chart
    var genderDistributionChart = new Chart(ctx, {
        type: 'pie',
        data: genderDistributionChartData,
        options: {
            responsive: true,
        },
    });

    // Get the CLTV by segment data passed from the controller
    var cltvData = @json($reportDatasets['Customer Lifetime Value (CLTV)']['CLTV By Segment Chart Data']);

    // Get the canvas element for the chart
    var ctx = document.getElementById('cltvBySegmentChart').getContext('2d');

    // Extract segment names and CLTV values from the data
    var segments = cltvData.map(item => item.segment);
    var cltvValues = cltvData.map(item => item.cltv);

    // Create a bar chart
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: segments,
            datasets: [{
                label: 'CLTV by Segment',
                data: cltvValues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Adjust color as needed
                borderColor: 'rgba(75, 192, 192, 1)', // Adjust color as needed
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'CLTV'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Segment'
                    }
                }
            }
        }
    });

    // Get the data from PHP and parse it into JavaScript variables
    var activityTimelineData = @json($reportDatasets['Customer Activity Timeline']['Activity Timeline Chart Data']);

    // Extract date labels and interaction counts
    var dates = activityTimelineData.map(function(item) {
        return item[0]; // Date
    });

    var ticketsCount = activityTimelineData.map(function(item) {
        return item[1]; // Tickets Count
    });

    var claimedCouponsCount = activityTimelineData.map(function(item) {
        return item[2]; // Claimed Coupons Count
    });

    var redeemedCouponsCount = activityTimelineData.map(function(item) {
        return item[3]; // Redeemed Coupons Count
    });

    // Create a line chart
    var ctx = document.getElementById('activityTimelineChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates, // X-axis labels (dates)
            datasets: [{
                    label: 'Tickets Count',
                    borderColor: 'blue',
                    data: ticketsCount,
                    fill: false,
                },
                {
                    label: 'Claimed Coupons Count',
                    borderColor: 'green',
                    data: claimedCouponsCount,
                    fill: false,
                },
                {
                    label: 'Redeemed Coupons Count',
                    borderColor: 'red',
                    data: redeemedCouponsCount,
                    fill: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'time', // X-axis type is time
                    time: {
                        unit: 'day', // Display one label per day
                        displayFormats: {
                            day: 'MMM d', // Format for displaying dates (e.g., Jan 1)
                        },
                    },
                    title: {
                        display: true,
                        text: 'Date',
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Count',
                    },
                },
            },
        },
    });

    // Retrieve the data from the server (replace this with your data retrieval method)
    var repeatPurchasesData = @json($reportDatasets['Customer Loyalty Trends']['Repeat Purchases']);

    // Extract labels (customer names) and data (purchase counts) from the JSON data
    var labels = repeatPurchasesData.map(function(item) {
        return item.first_name + ' ' + item.last_name;
    });

    var data = repeatPurchasesData.map(function(item) {
        return item.purchase_count;
    });

    // Get the canvas element
    var ctx = document.getElementById('repeatPurchasesChart').getContext('2d');

    // Create the bar chart
    var repeatPurchasesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Repeat Purchases',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)', // Bar color
                borderColor: 'rgba(75, 192, 192, 1)', // Border color
                borderWidth: 1 // Border width
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Purchase Count'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Hide legend
                }
            }
        }
    });
</script>
@endsection