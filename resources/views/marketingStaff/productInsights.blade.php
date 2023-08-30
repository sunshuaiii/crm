@extends('layouts.dashboard')

@section('title', 'Product Insights')

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
    <div class="header">Product Insights</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Sales Performance Chart</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="salesPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('marketingStaff.marketingStaffHome') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Go Back
        </a>
    </div>

</div>

<script>
    // Function to render sales performance chart
    function renderSalesPerformanceChart(salesData, productNames) {
        var ctx = document.getElementById('salesPerformanceChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: Object.values(salesData).map(data => data.total_quantity_sold),
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
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
    var salesData = {!! json_encode($salesData) !!};
    var productNames = {!! json_encode($products->pluck('name')) !!};

    // Call the function to render the chart
    renderSalesPerformanceChart(salesData, productNames);
</script>
@endsection