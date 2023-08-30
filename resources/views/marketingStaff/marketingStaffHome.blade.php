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
    <div class="row col-md-12 mb-4">
        <form id="updateRfmForm" action="{{ route('marketingStaff.updateRfmScores') }}" method="post">
            @csrf
            <button type="button" class="custom-button" onclick="updateRfmScores()">
                <i id="updateIcon1" class="fas fa-sync"></i> Update Customer RFM Scores
            </button>
        </form>

        <form id="updateCSegmentForm" action="{{ route('marketingStaff.updateCSegment') }}" method="post">
            @csrf
            <button type="submit" class="custom-button" onclick="updateCSegment()">
                <i id="updateIcon2" class="fas fa-sync"></i> Update Customer Segment
            </button>
        </form>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-4 mb-4">
            <a href="{{ route('marketingStaff.customerInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users card-icon"></i> Customer Insights</h5>
                        <p class="card-text">View insights about customer behavior and segments.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('marketingStaff.leadInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-chart-line card-icon"></i> Lead Insights</h5>
                        <p class="card-text">Explore insights about lead activities and engagement.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('marketingStaff.productInsights') }}" class="card-link">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-shopping-cart card-icon"></i> Product Insights</h5>
                        <p class="card-text">Discover insights about product sales performance.</p>
                    </div>
                </div>
            </a>
        </div>
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
</script>
@endsection