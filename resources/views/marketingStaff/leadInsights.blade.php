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
                        <h4 class="chart-title">Lead Overview</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-center"> <!-- Added this div -->
                                    <table class="table table-bordered table-sm">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Number of Activities</th>
                                                <th>Number of Feedback</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activityFrequencies as $lead)
                                            <tr>
                                                <td>{{ $lead->id }}</td>
                                                <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                                <td>{{ $lead->email }}</td>
                                                <td>{{ $lead->activity_count }}</td>
                                                <td>{{ $lead->feedback_count }}</td>
                                                <td>{{ $lead->status }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Lead Activity Analysis</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="activityChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Lead Gender Distribution</h4>
                        <div style="max-width: 300px; margin: auto;">
                            <canvas id="genderDistributionChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Lead Engagement Trends</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="engagementChart"></canvas>
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
        var activitiesCounts = @json($activitiesCounts);

        var ctx = document.getElementById('activityChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(activitiesCounts),
                datasets: [{
                    label: 'Activity Counts',
                    data: Object.values(activitiesCounts),
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

    document.addEventListener("DOMContentLoaded", function() {
        var genderCounts = @json($genderCounts);

        var ctx = document.getElementById('genderDistributionChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: genderCounts.map(data => data.gender),
                datasets: [{
                    data: genderCounts.map(data => data.count),
                    backgroundColor: ['pink', 'blue', 'gray'], // Adjust colors as needed
                }],
            },
            options: {
                responsive: true,
            },
        });
    });

    function renderEngagementChart(months, activityCounts, feedbackCounts) {
        var ctx = document.getElementById('engagementChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Activity Count',
                        data: activityCounts,
                        borderColor: 'blue',
                        fill: false,
                    },
                    {
                        label: 'Feedback Count',
                        data: feedbackCounts,
                        borderColor: 'green',
                        fill: false,
                    },
                ],
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month',
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
    }

    // Call the function to render the chart
    renderEngagementChart(@json($months), @json($activityCounts), @json($feedbackCounts));
</script>
@endsection