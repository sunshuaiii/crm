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
            <div id="loadingRfm" class="loading-status d-none">
                Updating... Please wait patiently.
            </div>
        </form>

        <form id="updateCSegmentForm" action="{{ route('marketingStaff.updateCSegment') }}" method="post">
            @csrf
            <button type="submit" class="custom-button" onclick="updateCSegment()">
                <i id="updateIcon2" class="fas fa-sync"></i> Update Customer Segment
            </button>
            <div id="loadingCSegment" class="loading-status d-none">
                Updating... Please wait patiently.
            </div>
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
                <div id="loadingCustomerInsights" class="loading-status d-none">
                    Loading... Please wait patiently.
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
                <div id="loadingLeadInsights" class="loading-status d-none">
                    Loading... Please wait patiently.
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
                <div id="loadingProductInsights" class="loading-status d-none">
                    Loading... Please wait patiently.
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    function updateRfmScores() {
        var button = document.getElementById('updateIcon1');
        var loadingRfm = document.getElementById('loadingRfm'); // Get the loading status element
        var form = document.getElementById('updateRfmForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');
        loadingRfm.classList.remove('d-none'); // Show the loading status

        form.submit();
    }

    function updateCSegment() {
        var button = document.getElementById('updateIcon2');
        var loadingCSegment = document.getElementById('loadingCSegment'); // Get the loading status element
        var form = document.getElementById('updateCSegmentForm');

        button.classList.remove('fa-sync');
        button.classList.add('fa-spinner', 'fa-spin');
        loadingCSegment.classList.remove('d-none'); // Show the loading status

        form.submit();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const customerInsightsLink = document.querySelector(".card-link[href*='customerInsights']");
        const loadingCustomerInsights = document.getElementById("loadingCustomerInsights");

        customerInsightsLink.addEventListener("click", function () {
            loadingCustomerInsights.classList.remove("d-none");
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const leadInsightsLink = document.querySelector(".card-link[href*='leadInsights']");
        const loadingLeadInsights = document.getElementById("loadingLeadInsights");

        leadInsightsLink.addEventListener("click", function () {
            loadingLeadInsights.classList.remove("d-none");
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const productInsightsLink = document.querySelector(".card-link[href*='productInsights']");
        const loadingProductInsights = document.getElementById("loadingProductInsights");

        productInsightsLink.addEventListener("click", function() {
            loadingProductInsights.classList.remove("d-none");
        });
    });
</script>
@endsection