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
                    <p>Total Revenue: {{ $reportDatasets['Sales Overview']['Total Revenue'] }} </p>
                    <p>Total Orders: {{ $reportDatasets['Sales Overview']['Total Orders'] }} </p>
                    <p>Average Order Value: {{ $reportDatasets['Sales Overview']['Average Order Value'] }} </p>
                </div>

                <!-- Section 1: Product Performance -->
                <div class="section">
                    <h2 class="section-header">Product Performance</h2>
                    <h6 class="section-description">Total revenue generated during the time period. Number of checkouts. Average checkout value.</h6>
                    <p>Total Revenue: {{ $reportDatasets['Sales Overview']['Total Revenue'] }} </p>
                    <p>Total Orders: {{ $reportDatasets['Sales Overview']['Total Orders'] }} </p>
                    <p>Average Order Value: {{ $reportDatasets['Sales Overview']['Average Order Value'] }} </p>
                    <div class="row justify-content-center">
                        <div class='col-md-6 mt-4'>
                            <h4 class="chart-title">New Customer Joined Timeline</h4>
                            <div style="max-width: 450px; margin: auto;">
                                <canvas id="newCustomersChart"></canvas>
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
@endsection