@extends('layouts.dashboard')

@section('title', 'Sales Performance Report')

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
    <div class="header">Sales Performance Report</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-4">From {{ $startDate }} to {{ $endDate }} </h4>

                <!-- Section 1: Overview -->
                <div class="section">
                    <h2 class="section-header">Sales Overview</h2>
                    <h6 class="section-description">Total revenue generated during the time period. Number of checkouts. Average checkout value.</h6>
                    <p>Total Revenue: RM {{ number_format($reportDatasets['Sales Overview']['Total Revenue'], 2) }} </p>
                    <p>Total Orders: {{ $reportDatasets['Sales Overview']['Total Orders'] }} </p>
                    <p>Average Order Value: RM {{ number_format($reportDatasets['Sales Overview']['Average Order Value'], 2) }} </p>
                </div>

                <!-- Section 2: Product Performance -->
                <div class="section">
                    <h2 class="section-header">Product Performance</h2>
                    <h6 class="section-description">Bestselling products during the time period. Revenue contribution by each best selling product. Trends in best selling product sales.</h6>

                    <h4 class="chart-title">Best Selling Products</h4>
                    <div style="width: 100%; height: 600px; margin: auto;">
                        <canvas id="bestsellingProductsChart"></canvas>
                    </div>

                    <h4 class="chart-title">Product Revenue Contribution</h4>
                    <div style="width: 100%; height: 600px; margin: auto;">
                        <canvas id="productRevenueContributionChart"></canvas>
                    </div>

                    <h4 class="chart-title">Product Sales Trend</h4>
                    <div style="width: 100%; height: 600px; margin: auto;">
                        <canvas id="productSalesTrendsChart"></canvas>
                    </div>
                </div>

                <!-- Section 3: Customer Analysis -->
                <div class="section">
                    <h2 class="section-header">Customer Analysis</h2>
                    <h6 class="section-description">High-value customers and their contribution to sales. Repeat customer rate and one-time purchase customer rate.</h6>
                    <p>Repeat Customer Rate: {{ number_format($reportDatasets['Customer Analysis']['Repeat Customer Rate'], 2) }}% </p>
                    <p>One-Time Purchase Customer Rate: {{ 100 - number_format($reportDatasets['Customer Analysis']['Repeat Customer Rate'], 2) }}% </p>
                    <div class="row justify-content-center">
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
                                    @foreach($reportDatasets['Customer Analysis']['High-Value Customers'] as $customer)
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

                        <!-- Repeat Customer Rate Pie Chart -->
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Repeat Customer Rate</h4>
                            <div style="max-width: 300px; margin: auto;">
                                <canvas id="repeatCustomerRateChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Product Affinity Analysis -->
                <div class="section">
                    <h2 class="section-header">Product Affinity Analysis</h2>
                    <h6 class="section-description">Identify top 15 product pairs frequently purchased together to create cross-selling opportunities.</h6>
                    <div class="row justify-content-center">
                        <div class="col-md-12 mt-4">
                            <h4 class="chart-title">Top 15 Frequently Purchased Product Pairs</h4>
                            <div style="max-width: 600px; margin: auto;">
                                @if(count($reportDatasets['Product Affinity Analysis']) > 0)
                                <table class="table table-bordered custom-table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Product 1</th>
                                            <th>Product 2</th>
                                            <th>Times Purchased Together</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $count = 1; @endphp
                                        @foreach($reportDatasets['Product Affinity Analysis'] as $productPair)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $productPair->product_1 }}</td>
                                            <td>{{ $productPair->product_2 }}</td>
                                            <td>{{ $productPair->pair_count }} times</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                <p>No product pairs frequently purchased together found in the specified date range.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Payment Method Preferences -->
                <div class="section">
                    <h2 class="section-header">Payment Method Preferences</h2>
                    <h6 class="section-description">Analyze payment methods preferred by customers and their impact on sales.</h6>
                    @foreach($reportDatasets['Payment Method Preferences'] as $paymentMethod)
                    <p>{{ $paymentMethod->payment_method }} (Used {{ $paymentMethod->payment_count }} times)</p>
                    @endforeach
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">Payment Method Preferences</h4>
                            <div style="max-width: 500px; margin: auto;">
                                <canvas id="paymentMethodChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Sales by Customer Segment -->
                <div class="section">
                    <h2 class="section-header">Sales by Customer Segment</h2>
                    <h6 class="section-description">Analyze how sales are distributed among different customer segments.</h6>
                    <div class="row justify-content-center">
                        <div class='col-md-12 mt-4'>
                            <h4 class="chart-title">Sales by Customer Segment</h4>
                            <div style="max-width: 600px; margin: auto;">
                                <canvas id="salesByCSegmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 7: Customer Segment and Coupon Redemption -->
                <div class="section">
                    <h2 class="section-header">Customer Segment and Coupon Redemption</h2>
                    <h6 class="section-description">Analyze coupon redemption rates across customer segments to tailor coupon offers.</h6>
                    <div class="row justify-content-center">
                        <div class='col-md-12 mt-4'>
                            <h4 class="chart-title">Coupon Redemption</h4>
                            <div style="max-width: 600px; margin: auto;">
                                <canvas id="cSegmentCouponRedemptionChart"></canvas>
                            </div>
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
    // Data for Best Selling Products Chart
    var bestsellingProductsData = @json($reportDatasets['Product Performance']['Bestselling Products']);

    // Extract product names and total quantities
    var productNames = bestsellingProductsData.map(item => item.name);
    var totalQuantities = bestsellingProductsData.map(item => item.total_quantity);

    // Create a bar chart
    var bestsellingProductsChart = new Chart(document.getElementById('bestsellingProductsChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Total Quantity Sold',
                data: totalQuantities,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Quantity Sold'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Product Name'
                    }
                }
            }
        }
    });

    // Data for Product Revenue Contribution Chart
    var productRevenueContributionData = @json($reportDatasets['Product Performance']['Product Revenue Contribution']);

    // Extract product names and total revenue
    var productNames = productRevenueContributionData.map(item => item.name);
    var totalRevenue = productRevenueContributionData.map(item => item.total_revenue);

    // Create a horizontal bar chart
    var productRevenueContributionChart = new Chart(document.getElementById('productRevenueContributionChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Total Revenue',
                data: totalRevenue,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Revenue (RM)'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Product Name'
                    }
                }
            }
        }
    });

    // Data for Product Sales Trend Chart
    var productSalesTrendsData = @json($reportDatasets['Product Performance']['Product Sales Trends']);

    // Extract order dates, product names, and total quantities
    var orderDates = [...new Set(productSalesTrendsData.map(item => item.order_date))];
    var productNames = [...new Set(productSalesTrendsData.map(item => item.name))];

    // Create an array to store data for each product
    var datasets = [];
    productNames.forEach(productName => {
        var productData = productSalesTrendsData.filter(item => item.name === productName);
        var quantities = new Array(orderDates.length).fill(0);
        productData.forEach(item => {
            var index = orderDates.indexOf(item.order_date);
            quantities[index] = item.total_quantity;
        });

        datasets.push({
            label: productName,
            data: quantities,
            borderColor: getRandomColor(),
            fill: false
        });
    });

    // Create a line chart
    var productSalesTrendsChart = new Chart(document.getElementById('productSalesTrendsChart'), {
        type: 'line',
        data: {
            labels: orderDates,
            datasets: datasets
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Order Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Quantity Sold'
                    }
                }
            }
        }
    });

    // Function to generate random colors
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Get the repeat customer rate and customer retention rate from your Blade template
    var repeatCustomerRate = @json($reportDatasets['Customer Analysis']['Repeat Customer Rate']);

    // Create pie chart data
    var pieChartData = {
        labels: ['Repeat Customers', 'One-Time Purchase Customers'],
        datasets: [{
            data: [repeatCustomerRate, 100 - repeatCustomerRate],
            backgroundColor: ['#36A2EB', '#FF6384'],
        }]
    };

    // Create pie chart for Repeat Customer Rate
    var repeatCustomerRateChart = new Chart(document.getElementById('repeatCustomerRateChart'), {
        type: 'pie',
        data: pieChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Repeat Customer Rate',
            },
        },
    });

    // Get the data for payment method preferences from your Laravel controller
    const paymentMethodData = @json($reportDatasets['Payment Method Preferences']);

    // Extract labels (payment methods) and data (payment counts) from the JSON data
    const paymentLabels = paymentMethodData.map(item => item.payment_method);
    const paymentData = paymentMethodData.map(item => item.payment_count);

    // Get the canvas element
    const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');

    // Create the chart
    new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: paymentLabels,
            datasets: [{
                label: 'Payment Method Preferences',
                data: paymentData,
                backgroundColor: 'rgba(75, 192, 192, 0.6)', // Customize the bar color
                borderWidth: 1,
            }],
        },
        options: {
            scales: {
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

    // Get the data for sales by customer segment from your Laravel controller
    const salesData = @json($reportDatasets['Sales by Customer Segment']);

    // Extract labels (customer segments) and data (total sales) from the JSON data
    const segmentLabels = salesData.map(item => item.c_segment);
    const totalSalesData = salesData.map(item => item.total_sales);

    // Get the canvas element
    const segmentSalesctx = document.getElementById('salesByCSegmentChart').getContext('2d');

    // Create the chart
    new Chart(segmentSalesctx, {
        type: 'pie',
        data: {
            labels: segmentLabels,
            datasets: [{
                data: totalSalesData,
                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)'],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
            },
        },
    });

    // Get the data for coupon redemption rates across customer segments from your Laravel controller
    const redemptionData = @json($reportDatasets['Customer Segment and Coupon Redemption']);

    // Define a custom order of customer segments
    const customOrder = ['Silver', 'Gold', 'Platinum'];

    // Sort the redemptionData array based on the custom order
    redemptionData.sort((a, b) => customOrder.indexOf(a.c_segment) - customOrder.indexOf(b.c_segment));


    // Extract customer segments and redemption counts from the JSON data
    const customerSegments = redemptionData.map(item => item.c_segment);
    const redemptionCounts = redemptionData.map(item => item.coupons_redeemed);

    // Get the canvas element
    const redemptionCtx = document.getElementById('cSegmentCouponRedemptionChart').getContext('2d');

    // Create the horizontal bar chart
    new Chart(redemptionCtx, {
        type: 'bar',
        data: {
            labels: customerSegments,
            datasets: [{
                label: 'Coupons Redeemed',
                data: redemptionCounts,
                backgroundColor: 'rgba(75, 192, 192, 0.5)', // Adjust the color as needed
                borderColor: 'rgba(75, 192, 192, 1)', // Adjust the color as needed
                borderWidth: 1,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    position: 'top',
                },
            },
            plugins: {
                legend: {
                    display: false, // You can adjust legend options as needed
                },
            },
        },
    });
</script>
@endsection