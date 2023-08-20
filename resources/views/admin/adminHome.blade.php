@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-content">
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
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="redemptionRateChart"></canvas>
                        </div>
                    </div>
                    <div class='col-md-6 mt-4'>
                        <h4 class="chart-title">Top Redeemed Coupons</h4>
                        <div style="max-width: 400px; margin: auto;">
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
                        <h4 class="chart-title">Coupon Status Distribution</h4>
                        <div style="max-width: 400px; margin: auto;">
                            <canvas id="couponStatusDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6 mt-4">
                    <h4 class="chart-title">Coupon Usage Over Time</h4>
                    <div style="max-width: 400px; margin: auto;">
                        <canvas id="couponUsageOverTimeChart"></canvas>
                    </div>
                </div> -->
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
                <div style="max-width: 400px; margin: auto;">
                    <canvas id="staffInsights"></canvas>
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
                label: 'Available',
                data: [{{ $totalAvailableCoupons }}],
                backgroundColor: '#36A2EB'
            }, {
                label: 'Expired',
                data: [{{ $expiredCoupons }}],
                backgroundColor: 'red'
            }, {
                label: 'Redeemed',
                data: [{{ $redeemedCoupons }}],
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
                data: [{{ $claimedCoupons }}, {{ $redeemedCoupons }}],
                backgroundColor: ['#FF5733', '#36A2EB'],
            }]
        },
        options: {
            responsive: true,
        }
    });

    // Coupon Usage Over Time
    // var couponUsageOverTimeData = {
    //     labels: {!! json_encode($couponUsageOverTime->pluck('date')) !!},
    //     datasets: [{
    //         label: 'Coupon Usage',
    //         data: {!! json_encode($couponUsageOverTime->pluck('coupon_count')) !!},
    //         borderColor: '#36A2EB',
    //         fill: false
    //     }]
    // };

    // var couponUsageOverTimeChart = new Chart(document.getElementById('couponUsageOverTimeChart'), {
    //     type: 'line',
    //     data: couponUsageOverTimeData,
    //     options: {
    //         responsive: true,
    //         scales: {
    //             x: {
    //                 type: 'time',
    //                 time: {
    //                     unit: 'day'
    //                 },
    //                 title: {
    //                     display: true,
    //                     text: 'Date'
    //                 }
    //             },
    //             y: {
    //                 title: {
    //                     display: true,
    //                     text: 'Coupon Count'
    //                 }
    //             }
    //         }
    //     }
    // });

    // Staff insights chart
    var staffInsights = new Chart(document.getElementById('staffInsights'), {
        type: 'pie',
        data: {
            labels: ['Marketing Staff', 'Support Staff'],
            datasets: [{
                data: [{{ $totalMarketingStaff }}, {{ $totalSupportStaff }}],
                backgroundColor: ['#FF5733', '#36A2EB'],
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
@endsection
