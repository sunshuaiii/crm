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

        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ $couponDetails->name }}</div>
                <div class="card-body text-center">
                    <img src="{{ asset('images/icon/coupon-color.png') }}" alt="Coupon Image" class="mb-3" style="width: 40px; height: 40px;">
                    <h5 class="card-title highlight"> RM {{ $couponDetails->discount }} Coupon</h5>

                    <div class="m-4">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('images/icon/points.png') }}" alt="Points Image" class="mr-2" style="width: 20px; height: 20px;">
                            <h5 class="m-2 card-text highlight text-center">
                                You claimed this coupon with {{ $couponDetails->redemption_points }} points
                            </h5>
                            @if($couponDetails->end_date < \Carbon\Carbon::now()) <p class="expired">Expired</p>
                                @else
                                <p>{{ $couponDetails->end_date->diffInDays(\Carbon\Carbon::now()) }} days left</p>
                                @endif
                        </div>
                    </div>

                    <div class="card-text">
                        <p>Terms & Conditions:</p>
                        <p> {{$couponDetails->conditions}} </p>
                    </div>

                    <div class="card-text mt-3 text-center">
                        <h6> Start Date: {{ $couponDetails->start_date->format('d M Y') }} </h6>
                        <h6> End Date: {{ $couponDetails->end_date->format('d M Y') }} </h6>
                        <div class="d-flex mt-4 justify-content-center">
                            {!! $barCode !!}
                        </div>
                        <h5> {{ $couponDetails->code }} </h5>
                    </div>

                    <div class="m-4">
                        <form id="redeemForm" action="{{ route('customer.coupons.redeem', ['couponCode' => $couponDetails->code]) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-primary" onclick="showConfirmation()">Checkout and Redeem This Coupon</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function showConfirmation() {
        if (confirm("Are you sure you want to proceed to checkout and redeem this coupon?")) {
            document.getElementById('redeemForm').submit();
        }
    }
</script>
@endsection