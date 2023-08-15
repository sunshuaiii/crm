@extends('layouts.app')

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
    ->where('status', 'Claimed')
    ->count() }}</span> coupon(s)</h4>
            </div>
        </div>

        @if ($customerCouponsInfo->count() > 0)
        <div class="col-md-12 mt-1">
            <div class="row justify-content-center align-items-center">
                @foreach($customerCouponsInfo as $coupon)
                <div class="col-md-4 m-5">
                    <div class="card-container">
                        <a href="{{ route('customer.coupons.details', ['couponId' => $coupon->coupon_id, 'customerId' => Auth::user()->id]) }}" class="card-link">
                            <div class="card" style="background:none; border:none; background-image: url('{{ asset('images/icon/coupon-bg.png') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; min-height: 280px; min-width: 350px;">
                                <div class="card-body m-5" style="padding-left:25%">
                                    <h5 class="card-title heading">{{ $coupon->name }}</h5>
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
        </div>

        @else
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8 m-5">
                <div class="text-center">
                    <h5>You currently don't have any coupons. Start collecting points to claim exciting discounts!</h5>
                </div>
            </div>
        </div>
        @endif


        <h2 class="heading"> Available Coupons</h2>


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
                                    <p>Terms & Conditions:</p>
                                    <span class="coupon-conditions">{{ substr($coupon->conditions, 0, 300) }}</span>
                                    <span class="read-more" style="display:none;">{{ substr($coupon->conditions, 0) }}</span>
                                    <a href="#" class="read-more-btn">Read More</a>
                                </div>

                                <form action="{{ route('customer.coupons.claim') }}" method="POST" class="claim-form" data-coupon-id="{{ $coupon->id }}">
                                    @csrf
                                    <input type="hidden" name="coupon_id" value="{{ $coupon->id }}">
                                </form>

                                <div class="mt-3">
                                    <button class="btn btn-primary claim-btn" data-coupon-id="{{ $coupon->id }}">Claim This Coupon</button>
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
</script>

<script>
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
</script>

<!-- <script>
    $(document).ready(function() {
        $('.claim-btn').click(function(e) {
            e.preventDefault();

            var couponId = $(this).data('coupon-id');

            if (confirm('Are you sure you want to claim this coupon?')) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('customer.coupons.claim') }}',
                    data: {
                        coupon_id: couponId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message); // Display success message
                        location.reload(); // Refresh the page
                    },
                    error: function(xhr, status, error) {
                        alert(xhr.responseJSON.message); // Display error message
                    }
                });
            }
        });
    });
</script> -->

@endsection