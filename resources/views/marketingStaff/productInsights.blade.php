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
        <h4 class="sub-header">Product Insights</h4>
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