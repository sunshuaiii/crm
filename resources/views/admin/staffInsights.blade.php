@extends('layouts.dashboard')

@section('title', 'Staff Insights')

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
    <div class="header">Staff Insights</div>

    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Marketing Staff: {{ $totalMarketingStaff }}</h6>
                <h6>Total Support Staff: {{ $totalSupportStaff }}</h6>
                <h6 class="section-description">Distribution of staff members.</h6>
                <h4 class="chart-title">Summary</h4>
                <div style="max-width: 250px; margin: auto;">
                    <canvas id="staffInsights"></canvas>
                </div>

                <div class='mt-4'>
                    <h4 class="chart-title">Support Staff Insights</h4>
                    <div class="form-group">
                        <label for="selectSupportStaff">Select Support Staff ID:</label>
                        <select id="selectSupportStaff" class="form-control">
                            <option value="">Select an ID</option>
                            <option value="all">All Staff</option> <!-- Add the option to view all staff -->
                            @foreach($supportStaffIds as $id)
                            <option value="{{ $id }}">{{ $id }}</option>
                            @endforeach
                        </select>
                        <button id="btnGetSupportStaffInsights" class="btn btn-primary mt-2">Get Support Staff Insights</button>
                    </div>
                    <div id="supportStaffInsights">
                        <!-- Content from AJAX response will be displayed here -->
                    </div>
                </div>

                <div class='mt-4'>
                    <h4 class="chart-title">Marketing Staff Insights</h4>
                    <div class="form-group">
                        <label for="selectMarketingStaff">Select Marketing Staff ID:</label>
                        <select id="selectMarketingStaff" class="form-control">
                            <option value="">Select an ID</option>
                            <option value="all">All Staff</option> <!-- Add the option to view all staff -->
                            @foreach($marketingStaffIds as $id)
                            <option value="{{ $id }}">{{ $id }}</option>
                            @endforeach
                        </select>
                        <button id="btnGetMarketingStaffInsights" class="btn btn-primary mt-2">Get Marketing Staff Insights</button>
                    </div>
                    <div id="marketingStaffInsights">
                        <!-- Content from AJAX response will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('admin.adminHome') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Dashboard
        </a>
        <a href="{{ route('admin.couponInsights') }}" class="btn btn-primary">
            <i class="fas fa-ticket-alt"></i> Coupon Insights
        </a>
    </div>
</div>

<script>
    // Staff insights chart
    var staffInsights = new Chart(document.getElementById('staffInsights'), {
        type: 'pie',
        data: {
            labels: ['Marketing Staff', 'Support Staff'],
            datasets: [{
                data: [{{ $totalMarketingStaff }}, {{ $totalSupportStaff }}],
                backgroundColor: ['Orange', 'Blue'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    $(document).ready(function() {
        // Handle button click event
        $("#btnGetSupportStaffInsights").click(function() {
            var selectedStaffId = $("#selectSupportStaff").val();
        
            if (selectedStaffId) {
                // Make an AJAX request
                $.ajax({
                    url: "/admin/staffInsights/supportStaff",
                    method: "GET",
                    data: { staffId: selectedStaffId },
                    success: function(response) {
                        // Update the content with the insights
                        $("#supportStaffInsights").html(response);
                    },
                    error: function(error) {
                        console.log("Error:", error);
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        // Handle button click event
        $("#btnGetMarketingStaffInsights").click(function() {
            var selectedStaffId = $("#selectMarketingStaff").val();
        
            if (selectedStaffId) {
                // Make an AJAX request
                $.ajax({
                    url: "/admin/staffInsights/marketingStaff",
                    method: "GET",
                    data: { staffId: selectedStaffId },
                    success: function(response) {
                        // Update the content with the insights
                        $("#marketingStaffInsights").html(response);
                    },
                    error: function(error) {
                        console.log("Error:", error);
                    }
                });
            }
        });
    });
</script>
@endsection