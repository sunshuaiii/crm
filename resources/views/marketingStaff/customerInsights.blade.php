@extends('layouts.dashboard')

@section('title', 'Customer Insights')

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
    <div class="header">Customer Insights</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Top Customers by Purchase Amount</h4>
                        <table class="table table-sm custom-table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Total Purchase Amount</th>
                                    <th>Member Since</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                    <td>RM {{ number_format($customer->total_purchase_amount, 2) }}</td>
                                    <td>@if ($customer->created_at)
                                            {{ \Carbon\Carbon::parse($customer->created_at)->timezone('Asia/Kuala_Lumpur')->format('Y-m-d') }}
                                        @else
                                            NA
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Growth Over Time</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="customerGrowthChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Distribution by RFM Scores and Segments</h4>
                        <div style="max-width: 450px; margin: auto;">
                            <canvas id="customerDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Segment Distribution</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="customerSegmentChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class='col-md-4 mt-4'>
                        <h4 class="chart-title">Customer Churn Analysis</h4>
                        <div style="max-width: 300px; margin: auto;">
                            <canvas id="churnChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-4 mt-4'>
                        <h4 class="chart-title">Coupon Redemption Effectiveness</h4>
                        <div style="max-width: 300px; margin: auto;">
                            <canvas id="couponRedemptionChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-4 mt-4'>
                        <h4 class="chart-title">Customer Service Query Types</h4>
                        <div style="max-width: 300px; margin: auto;">
                            <canvas id="interactionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Lifetime Value (CLTV) Analysis</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="cltvChart"></canvas>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('marketingStaff.marketingStaffHome') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Dashboard
        </a>
        <a href="{{ route('marketingStaff.leadInsights') }}" class="btn btn-primary">
            <i class="fas fa-chart-line"></i> Lead Insights
        </a>
    </div>

</div>

<script>
    function renderCustomerSegmentChart(segmentLabels, countData, segmentColors) {
        var ctx = document.getElementById('customerSegmentChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: segmentLabels,
                datasets: [{
                    label: 'Customer Count',
                    data: countData,
                    backgroundColor: segmentColors,
                    borderWidth: 1,
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1,
                    },
                },
            },
        });
    }

    var silverCounts = {{ $silverCustomerCounts }};
    var goldCounts = {{ $goldCustomerCounts }};
    var platinumCounts = {{ $platinumCustomerCounts }};

    var cSegmentLabels = ['Silver', 'Gold', 'Platinum'];
    var countData = [silverCounts, goldCounts, platinumCounts];
    
    // Define colors for each segment
    var segmentColors = ['#FFC107', '#28A745', '#007BFF'];

    // Call the function to render the chart
    renderCustomerSegmentChart(cSegmentLabels, countData, segmentColors);

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
    var customerDistributionData = {!! json_encode($customerDistribution) !!};

    // Call the chart rendering function
    renderCustomerDistributionChart(customerDistributionData);

    function renderCustomerGrowthChart(customerGrowthData) {
        var dates = customerGrowthData.map(entry => entry.date);
        var newCustomers = customerGrowthData.map(entry => entry.new_customers);

        var ctx = document.getElementById('customerGrowthChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'New Customers',
                    data: newCustomers,
                    borderColor: 'blue',
                    fill: false,
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'month', // Adjust the time unit as needed
                            displayFormats: {
                                month: 'MMM yyyy',
                            },
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
                            text: 'Number of New Customers'
                        }
                    }
                }
            }
        });
    }
    var customerGrowthData = {!! json_encode($customerGrowth) !!};

    // Call the chart rendering function
    renderCustomerGrowthChart(customerGrowthData);

    function renderChurnChart(churnData) {
        var ctx = document.getElementById('churnChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Churned Customers', 'Active Customers'],
                datasets: [{
                    data: [churnData.churned, churnData.active],
                    backgroundColor: ['red', 'green'],
                }]
            },
            options: {
                responsive: true,
            }
        });
    }
    var churnData = {!! json_encode($churnData) !!};

    // Call the chart rendering function
    renderChurnChart(churnData);

    function renderCouponRedemptionChart(couponRedemptionData) {
        var ctx = document.getElementById('couponRedemptionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Redeemed Coupons', 'Unused Coupons'],
                datasets: [{
                    data: [couponRedemptionData.redeemed, couponRedemptionData.distributed - couponRedemptionData.redeemed],
                    backgroundColor: ['#36A2EB', '#FFCE56'],
                }],
            },
        });
    }
    var couponRedemptionData = {!! json_encode($couponRedemptionData) !!};

    // Call the chart rendering function
    renderCouponRedemptionChart(couponRedemptionData);

    // Query Type analysis
    function renderInteractionChart(interactionData) {
        var ctx = document.getElementById('interactionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(interactionData),
                datasets: [{
                    data: Object.values(interactionData),
                    backgroundColor: ['#36A2EB', 'orange', '#FF5733', 'purple'],
                    // label: ['Feedback', 'Query', 'Complaint', 'Issue'],
                }],
            },
        });
    }
    var interactionData = {!! json_encode($interactionData) !!};

    // Call the chart rendering function
    renderInteractionChart(interactionData);

    // function renderCLTVChart(cltvData) {
    //     var ctx = document.getElementById('cltvChart').getContext('2d');
    //     var chart = new Chart(ctx, {
    //         type: 'bar',
    //         data: {
    //             labels: {!! json_encode(array_keys($cltvData)) !!},
    //             datasets: [{
    //                 label: 'CLTV',
    //                 data: {!! json_encode(array_values($cltvData)) !!},
    //                 backgroundColor: ['#36A2EB', 'orange', '#FF5733'], // You can set different colors for each segment
    //             }],
    //         },
    //         options: {
    //             scales: {
    //                 y: {
    //                     beginAtZero: true,
    //                 },
    //             },
    //         },
    //     });
    // }

    // var cltvData = {!! json_encode($cltvData) !!};
    // renderCLTVChart(cltvData);
</script>
@endsection