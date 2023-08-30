@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

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
    <div class="header">Admin Dashboard</div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Coupon Insights</h4>
        <div class="card">
            <div class="card-body">
                <h6>Claimed Coupons: {{ $claimedCoupons }} ({{ $totalAvailableCoupons }} Available / {{ $expiredCoupons }} Expired)</h6>
                <h6>Redeemed Coupons: {{ $redeemedCoupons }}</h6>
                <h4 class="chart-title">Summary</h4>
                <div style="max-width: 400px; margin: auto;">
                    <canvas id="couponInsights" width="400" height="200"></canvas>
                </div>
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Redemption Rate by Coupon</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="redemptionRateChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Top 5 Redeemed Coupons</h4>
                        <div style="max-width: 500px; margin: auto;">
                            <canvas id="topRedeemedChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Redemption Points vs. Discount</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="redemptionVsDiscountChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Coupon Status Percentage Distribution </h4>
                        <div style="max-width: 220px; margin: auto;">
                            <canvas id="couponStatusDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 mt-4">
                        <h4 class="chart-title">Coupon Usage Over Time</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="couponUsageOverTimeChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <h4 class="chart-title">Coupon Expiry Analysis</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="expiryAnalysisChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 mt-4">
                        <h4 class="chart-title">Coupon Usage by Customer Segments</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="couponUsageBySegmentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
        <h4 class="sub-header">Staff Insights</h4>
        <div class="card">
            <div class="card-body">
                <h6>Total Marketing Staff: {{ $totalMarketingStaff }}</h6>
                <h6>Total Support Staff: {{ $totalSupportStaff }}</h6>
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
</div>

<script>
    // Coupon insights chart
    var couponInsights = new Chart(document.getElementById('couponInsights'), {
        type: 'bar',
        data: {
            labels: ['Claimed', 'Redeemed'],
            datasets: [{
                label: ['Claimed'],
                data: [{{ $totalAvailableCoupons }}, {{ $redeemedCoupons }}],
                backgroundColor: ['#36A2EB', '#FF5733']
            }, {
                label: 'Expired',
                data: [{{ $expiredCoupons }}],
                backgroundColor: 'red'
            }, {
                label: 'Redeemed',
                backgroundColor: '#FF5733'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                beginAtZero: true
                }
            }
        }
    });

    // Redemption Rate by Coupon chart
    var redemptionRateChart = new Chart(document.getElementById('redemptionRateChart'), {
        type: 'bar',
        data: {
            labels: [@foreach ($redemptionRates as $rate) "{{ $rate->name }}", @endforeach],
            datasets: [{
                label: 'Redeemed',
                data: [@foreach ($redemptionRates as $rate) {{ $rate->redeemed_count }}, @endforeach],
                backgroundColor: '#FF5733',
            }, {
                label: 'Claimed',
                data: [@foreach ($redemptionRates as $rate) {{ $rate->claimed_count }}, @endforeach],
                backgroundColor: '#36A2EB',
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Top Redeemed Coupons chart
    var topRedeemedChart = new Chart(document.getElementById('topRedeemedChart'), {
        type: 'bar',
        data: {
            labels: [@foreach ($topRedeemedCoupons as $coupon) "{{ $coupon->name }}", @endforeach],
            datasets: [{
                label: 'Redeemed Count',
                data: [@foreach ($topRedeemedCoupons as $coupon) {{ $coupon->redeemed_count }}, @endforeach],
                backgroundColor: '#FF5733',
            }]
        },
        options: {
            responsive: true,
            scales: {
            y: {
                beginAtZero: true,
                stepSize: 1, // Show only whole numbers on the y-axis
            },
        },
        }
    });

    // Redemption Points vs. Discount chart
    var redemptionVsDiscountChart = new Chart(document.getElementById('redemptionVsDiscountChart'), {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Redemption Points vs. Discount',
                data: [
                    @foreach ($redemptionVsDiscount as $data)
                        { x: {{ $data->avg_redemption_points }}, y: {{ $data->avg_discount }} },
                    @endforeach
                ],
                backgroundColor: '#36A2EB',
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Redemption Points'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Discount'
                    }
                }
            }
        }
    });

    // Coupon Status Distribution chart
    var couponStatusDistributionChart = new Chart(document.getElementById('couponStatusDistributionChart'), {
        type: 'pie',
        data: {
            labels: ['Claimed', 'Redeemed'],
            datasets: [{
                data: [{{ $claimedPercentage }}, {{ $redeemedPercentage }}],
                backgroundColor: ['#36A2EB', '#FF5733'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Coupon Usage Over Time
    var couponUsageOverTimeData = {
        labels: {!! json_encode($couponUsageOverTime->pluck('date')) !!},
        datasets: [{
            label: 'Coupon Usage',
            data: {!! json_encode($couponUsageOverTime->pluck('coupon_count')) !!},
            borderColor: '#36A2EB',
            fill: false
        }]
    };

    var couponUsageOverTimeChart = new Chart(document.getElementById('couponUsageOverTimeChart'), {
        type: 'line',
        data: couponUsageOverTimeData,
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        timezone: 'Asia/Kuala_Lumpur'
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Coupon Count'
                    }
                }
            }
        }
    });

    // Expiry Analysis
    var expiryAnalysisData = {
        labels: {!! json_encode($expiryAnalysis->pluck('remaining_days')) !!},
        datasets: [{
            label: 'Coupon Distribution',
            data: {!! json_encode($expiryAnalysis->pluck('coupon_count')) !!},
            backgroundColor: '#36A2EB',
        }]
    };

    var expiryAnalysisChart = new Chart(document.getElementById('expiryAnalysisChart'), {
        type: 'bar',
        data: expiryAnalysisData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Remaining Days till Expiry'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Coupon Count'
                    }
                }
            }
        }
    });

    // Coupon Usage by Customer Segments chart
    var couponUsageBySegmentsChart = new Chart(document.getElementById('couponUsageBySegmentsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($segmentLabels) !!}, // Array of segment labels
            datasets: [{
                label: 'Claimed Coupons',
                data: {!! json_encode($claimedCouponsBySegment) !!}, // Array of claimed coupons by segment
                backgroundColor: '#36A2EB'
            }, {
                label: 'Redeemed Coupons',
                data: {!! json_encode($redeemedCouponsBySegment) !!}, // Array of redeemed coupons by segment
                backgroundColor: '#FF5733'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Coupon Count'
                    }
                }
            }
        }
    });

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
                    url: "/admin/getSupportStaffInsights",
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
</script>
@endsection