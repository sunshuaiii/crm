@extends('layouts.app')

@section('title', 'Coupons')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
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
        </div>

        <div class="col-md-8 mt-3">
            <div class="d-flex ">
                <img src="{{ asset('images/icon/coupon-color.png') }}" alt="Coupon Image" class="mr-3" style="width: 40px; height: 40px;">
                <h4 class="m-2 text-center">Your coupons | <span class="highlight">{{ \App\Models\CustomerCoupon::where('customer_id', Auth::user()->id)
    ->where('status', 'Claimed')->whereDate('end_date', '>=', now())
    ->count() }}</span> coupon(s) available</h4>
            </div>
        </div>

        <div class="col-md-8 mb-4 mt-3">
            <div class="btn-group" role="group" aria-label="Coupon Filters">
                <button type="button" class="btn btn-primary" id="validCouponsBtn">
                    <i class="fas fa-check-circle"></i> Valid Coupons
                </button>
                <button type="button" class="btn btn-secondary" id="expiredCouponsBtn">
                    <i class="far fa-calendar-times"></i> Expired Coupons
                </button>
            </div>
        </div>

        <div class="col-md-10 m-3 expired coupon-card">
            @if ($expiredCustomerCouponsInfo->count() > 0)
            <div class="row justify-content-center align-items-center">
                @foreach($expiredCustomerCouponsInfo as $coupon)
                <div class="col-md-4">
                    <div class="card-container">
                        @if($coupon->end_date < \Carbon\Carbon::now()) <div class="card" style="background:none; border:none; opacity: 0.5; background-image: url('{{ asset('images/icon/coupon-bg.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 280px; min-width: 350px;">
                            @else
                            <a href="{{ route('customer.coupons.details', ['couponCode' => $coupon->code]) }}" class="card-link">
                                <div class="card" style="background:none; border:none; background-image: url('{{ asset('images/icon/coupon-bg.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 280px; min-width: 350px;">
                                    @endif

                                    <div class="card-body m-5" style="padding-left:25%">
                                        <div class="text-left md-5">
                                            @if($coupon->end_date < \Carbon\Carbon::now()) <p class="expired">Expired</p>
                                                @else
                                                <p>{{ $coupon->end_date->diffInDays(\Carbon\Carbon::now()) }} days left</p>
                                                @endif
                                        </div>
                                        <h4 class="card-title heading">{{ $coupon->name }}</h4>
                                        <br>
                                        <br>
                                        <h5 class="card-text">
                                            Discount: RM {{ $coupon->discount }}<br>
                                            Expiry Date: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                                        </h5>
                                    </div>
                                </div>
                            </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 m-5">
                    <div class="text-center">
                        <h5>You currently don't have any expired coupons. Start collecting points to claim exciting discounts!</h5>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-10 m-3 valid coupon-card">
            @if ($validCustomerCouponsInfo->count() > 0)
            <div class="row justify-content-center align-items-center">
                @foreach($validCustomerCouponsInfo as $coupon)
                <div class="col-md-4">
                    <div class="card-container">
                        @if($coupon->end_date < \Carbon\Carbon::now()) <div class="card" style="background:none; border:none; opacity: 0.5; background-image: url('{{ asset('images/icon/coupon-bg.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 280px; min-width: 350px;">
                            @else
                            <a href="{{ route('customer.coupons.details', ['couponCode' => $coupon->code]) }}" class="card-link">
                                <div class="card" style="background:none; border:none; background-image: url('{{ asset('images/icon/coupon-bg.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 280px; min-width: 350px;">
                                    @endif

                                    <div class="card-body m-5" style="padding-left:25%">
                                        <div class="text-left md-5">
                                            @if($coupon->end_date < \Carbon\Carbon::now()) <p class="expired">Expired</p>
                                                @else
                                                <p>{{ $coupon->end_date->diffInDays(\Carbon\Carbon::now()) }} days left</p>
                                                @endif
                                        </div>
                                        <h4 class="card-title heading">{{ $coupon->name }}</h4>
                                        <br>
                                        <br>
                                        <h5 class="card-text">
                                            Discount: RM {{ $coupon->discount }}<br>
                                            Expiry Date: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                                        </h5>
                                    </div>
                                </div>
                            </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="row justify-content-center align-items-center">
                <div class="col-md-8 m-5">
                    <div class="text-center">
                        <h5>You currently don't have any valid coupons. Start collecting points to claim exciting discounts!</h5>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <h2 class="heading">
            <i class="fas fa-ticket-alt"></i> Available Coupons
        </h2>

        <div class="col-md-8 mt-3">
            <div class="d-flex ">
                <img src="{{ asset('images/icon/points.png') }}" alt="Points Image" class="mr-3 " style="width: 40px; height: 40px;">
                <h4 class="m-2 text-center">Your points | <span class="highlight">{{ Auth::user()->points }}</span> points</h4>
            </div>
        </div>

        <!-- coupon cards -->

        <div class="col-md-9 mt-4">
            <div class="row justify-content-center align-items-center">
                @foreach($allCouponsInfo as $coupon)
                <div class="col-md-4 mb-5">
                    <div class="card-container">
                        <div class="card">
                            <div class="card-body text-center">
                                <img src="{{ asset('images/icon/coupon-color.png') }}" alt="Coupon Image" class="mb-3" style="width: 40px; height: 40px;">
                                <h5 class="card-title highlight"> RM {{ $coupon->discount }} Coupon</h5>

                                <div class="card-text">
                                    <p>
                                        <i class="fas fa-file-alt"></i> Terms & Conditions:
                                    </p>
                                    <span class="coupon-conditions">{{ substr($coupon->conditions, 0, 180) }}</span>
                                    <span class="read-more" style="display:none;">{{ substr($coupon->conditions, 0) }}</span>
                                    <a href="#" class="read-more-btn">Read More</a>
                                </div>

                                <form action="{{ route('customer.coupons.claim') }}" method="POST" class="claim-form" data-coupon-id="{{ $coupon->id }}">
                                    @csrf
                                    <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                </form>

                                <div class="mt-3">
                                    <button class="btn btn-primary claim-btn" data-coupon-id="{{ $coupon->id }}">
                                        <i class="fas fa-gift"></i> Claim This Coupon
                                    </button>
                                </div>

                                <div class="m-4">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('images/icon/points.png') }}" alt="Points Image" class="mr-2" style="width: 20px; height: 20px;">
                                        <h5 class="m-2 card-text highlight text-center">
                                            {{ $coupon->redemption_points }} points
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const readMoreButtons = document.querySelectorAll('.read-more-btn');
        readMoreButtons.forEach(button => {
            button.addEventListener('click', () => {
                const conditions = button.parentElement.querySelector('.coupon-conditions');
                const readMoreContent = button.parentElement.querySelector('.read-more');
                if (readMoreContent.style.display === 'none') {
                    conditions.style.display = 'none';
                    readMoreContent.style.display = 'inline';
                    button.textContent = 'Read Less';
                } else {
                    conditions.style.display = 'inline';
                    readMoreContent.style.display = 'none';
                    button.textContent = 'Read More';
                }
            });
        });

    });

    $(document).ready(function() {
        $('.claim-btn').click(function(e) {
            e.preventDefault();

            var couponId = $(this).data('coupon-id');
            var form = $('.claim-form[data-coupon-id="' + couponId + '"]');

            if (confirm('Are you sure you want to claim this coupon?')) {
                form.submit();
            }
        });
    });

    $(document).ready(function() {
        // Show valid coupon cards by default
        toggleCouponCards('valid');

        $('#validCouponsBtn').click(function() {
            console.log('Valid Coupons button clicked');
            toggleCouponCards('valid');
        });

        $('#expiredCouponsBtn').click(function() {
            console.log('Expired Coupons button clicked');
            toggleCouponCards('expired');
        });

        function toggleCouponCards(filter) {
            $('.coupon-card').each(function() {
                if (filter === 'valid' && !$(this).hasClass('expired')) {
                    $(this).show();
                } else if (filter === 'expired' && $(this).hasClass('expired')) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
</script>
@endsection