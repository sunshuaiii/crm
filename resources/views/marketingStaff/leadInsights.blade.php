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
    <div class="header">Lead Insights/div>

    <div class="col-md-12 mb-4">
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

    <div class="text-center">
        <a href="{{ route('marketingStaff.marketingStaffHome') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Go Back
        </a>
    </div>

</div>

<script>

</script>
@endsection