@extends('layouts.dashboard')

@section('title', 'Report Generation')

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
    <div class="header">Report Generation</div>

    <form action="{{ route('marketingStaff.generateReport') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="startDate">Start Date: *</label>
            <input type="date" name="startDate" id="startDate" class="form-control" required max="{{ now()->toDateString() }}">
            @error('startDate')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <label for="endDate">End Date: *</label>
            <input type="date" name="endDate" id="endDate" class="form-control" required max="{{ now()->toDateString() }}">
            @error('endDate')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <label>Select Report Type: *</label>
        <div class="form-group text-center">
            <div class="report-type-container">
                <!-- Add the hidden input field to store the selected report type -->
                <input type="hidden" name="selectedReportType" id="selectedReportType" value="" required>
                @error('selectedReportType')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <label class="report-card">
                    <input type="radio" name="reportType" value="customerBehaviour" required>
                    <div class="card-content">
                        <h4>Customer Behaviour</h4>
                        <p>Generate a report on customer behavior and interactions.</p>
                    </div>
                </label>
                <label class="report-card">
                    <input type="radio" name="reportType" value="salesPerformance" required>
                    <div class="card-content">
                        <h4>Sales Performance</h4>
                        <p>Generate a report on product sales performance.</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Additional options based on report type can be added here -->

        <div class="text-center">
            <button type="button" class="btn btn-primary" id="generateReportButton">
                <i class="fas fa-download"></i> Generate Report
            </button>
        </div>
    </form>
</div>

<!-- Add this JavaScript code at the bottom of your reportGeneration.blade.php -->
<script>
    // Set the default end date to today
    document.getElementById('endDate').value = new Date().toISOString().split('T')[0];

    // Calculate the date 6 months ago
    var oneMonthAgo = new Date();
    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 9);

    // Format the date as YYYY-MM-DD (ISO format)
    var formattedDate = oneMonthAgo.toISOString().split('T')[0];

    // Set the default start date input value
    document.getElementById('startDate').value = formattedDate;


    // Get all the report cards
    const reportCards = document.querySelectorAll('.report-card');

    // Attach a click event listener to each report card
    reportCards.forEach(card => {
        card.addEventListener('click', () => {
            // Remove the 'selected' class from all report cards
            reportCards.forEach(card => card.classList.remove('selected'));

            // Add the 'selected' class to the clicked card
            card.classList.add('selected');

            // Set the value of the hidden input field to the selected report type
            const selectedReportType = card.querySelector('input[type="radio"]').value;
            document.querySelector('#selectedReportType').value = selectedReportType;
        });
    });

    // Attach a click event listener to the Generate Report button
    document.getElementById('generateReportButton').addEventListener('click', function() {
        // Check if all required fields are filled
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const reportType = document.querySelector('input[name="reportType"]:checked');

        // Display a confirmation pop-up
        if (confirm('Are you sure you want to generate the report?')) {
            // Start the spinning icon and disable the button
            toggleLoadingIcon(true);

            // Submit the form if the user confirms
            document.querySelector('form').submit();
        }
    });

    function toggleLoadingIcon(startLoading) {
        var button = $("#generateReportButton");
        var icon = button.find("i");

        if (startLoading) {
            icon.removeClass("fa-download");
            icon.addClass("fas fa-spinner fa-spin");
            button.prop("disabled", true); // Disable the button while loading
        } else {
            icon.removeClass("fas fa-spinner fa-spin");
            icon.addClass("fa-download");
            button.prop("disabled", false); // Enable the button when loading is done
        }
    }
</script>
@endsection