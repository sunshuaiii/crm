@extends('layouts.app')

@section('title', 'Checkout Details')

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

        <h3 class="m-4 text-center"> You have earned <span class="highlight">{{ $pointsToCredit}} </span> points with this checkout.</h3>

        <h2><i class="fas fa-receipt"></i> Checkout Details</h2>

        <!-- Display checkout details -->
        <p><i class="fas fa-credit-card"></i> Payment Method: {{ $checkout->payment_method }}</p>
        <p><i class="far fa-calendar-alt"></i> Checkout Date: {{ $checkout->date->setTimezone('Asia/Kuala_Lumpur')->format('d M Y H:i:s') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th><i class="fas fa-box"></i> Product Name</th>
                    <th><i class="fas fa-sort-amount-up"></i> Quantity</th>
                    <th><i class="fas fa-tag"></i> Unit Price</th>
                    <th><i class="fas fa-file-invoice-dollar"></i> Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($checkout->checkoutProducts as $checkoutProduct)
                <tr>
                    <td>{{ $checkoutProduct->product->name }}</td>
                    <td>{{ $checkoutProduct->quantity }}</td>
                    <td>RM {{ $checkoutProduct->product->unit_price }}</td>
                    <td>RM {{ $checkoutProduct->product->unit_price * $checkoutProduct->quantity }}</td>
                </tr>
                @endforeach

                <tr>
                    <td colspan="3" class="text-right">
                        <i class="fas fa-dollar-sign"></i> Total Amount:
                    </td>
                    <td>RM {{ $totalAmount }}</td>
                </tr>

                @if($couponDiscount)
                <tr>
                    <td colspan="3" class="text-right">
                        <i class="fas fa-percent"></i> Coupon Discount:
                    </td>
                    <td>RM {{ $couponDiscount }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">
                        <i class="fas fa-money-bill-wave"></i> Final Amount:
                    </td>
                    <td>RM {{ $finalAmount }}</td>
                </tr>
                @endif

            </tbody>
        </table>

        <div class="row justify-content-center align-items-center mt-3">
            <div class="col-md-6 text-center">
                <a href="{{ route('customer.coupons') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Coupons
                </a>
            </div>
            <div class="col-md-6 text-center">
                <a href="{{ route('customer.checkout.history') }}" class="btn btn-secondary">
                    <i class="fas fa-history"></i> Go to Checkout History
                </a>
            </div>
        </div>

    </div>
</div>
@endsection