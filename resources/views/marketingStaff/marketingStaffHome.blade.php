@extends('layouts.dashboard')

@section('title', 'Marketing Staff Dashboard')

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
    <div class="header">Marketing Staff Dashboard</div>

    <!-- Add buttons to trigger update -->
    <div class="row col-md-12">
        <div class="col-md-3">
            <form id="updateRfmForm" action="{{ route('marketingStaff.updateRfmScores') }}" method="post">
                @csrf
                <button type="button" class="btn btn-primary" onclick="updateRfmScores()">
                    <i id="updateIcon1" class="fas fa-sync"></i> Update RFM Scores
                </button>
            </form>
        </div>
        <div class="mb-4">
            <form id="updateCSegmentForm" action="{{ route('marketingStaff.updateCSegment') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="updateCSegment()">
                    <i id="updateIcon2" class="fas fa-sync"></i> Update Customer Segment
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Customer Insights</h4>
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Top Customers by Purchase Amount</h4>
                        <table class="table table-sm custom-table">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Total Purchase Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                    <td>RM {{ number_format($customer->total_purchase_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Distribution by RFM Scores and Segments</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="customerDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Growth Over Time</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="customerGrowthChart"></canvas>
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

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Lead Insights</h4>
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Customer Distribution by RFM Scores and Segments</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="customerDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 text-center mt-4">
        <a href="{{ route('marketingStaff.productInsights') }}" class="btn btn-primary">Show Product Insights</a>
    </div>

</div>

<script>
    function updateRfmScores() {
        var button = document.getElementById('updateIcon1');
        var form = document.getElementById('updateRfmForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');

        form.submit();
    }

    function updateCSegment() {
        var button = document.getElementById('updateIcon2');
        var form = document.getElementById('updateCSegmentForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');

        form.submit();
    }

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