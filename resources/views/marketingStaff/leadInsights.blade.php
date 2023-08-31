@extends('layouts.dashboard')

@section('title', 'Lead Insights')

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
    <div class="header">Lead Insights</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Lead Activity Analysis</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Feedback Sentiment Analysis</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="sentimentChart"></canvas>
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
    document.addEventListener("DOMContentLoaded", function() {
        var activityCounts = @json($activityCounts);

        var ctx = document.getElementById('activityChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(activityCounts),
                datasets: [{
                    label: 'Activity Counts',
                    data: Object.values(activityCounts),
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
    });


</script>
@endsection